<?php
namespace verbb\shortcut\services;

use verbb\shortcut\Shortcut as ShortcutPlugin;
use verbb\shortcut\models\Settings;
use verbb\shortcut\models\Shortcut;
use verbb\shortcut\records\Shortcut as ShortcutRecord;
use verbb\shortcut\shorteners\BitlyShortener;
use verbb\shortcut\shorteners\IsGdShortener;
use verbb\shortcut\shorteners\RebrandlyShortener;
use verbb\shortcut\shorteners\ShortenerInterface;
use verbb\shortcut\shorteners\ShortIoShortener;
use verbb\shortcut\shorteners\TinyUrlShortener;

use Craft;
use craft\base\Component;
use craft\base\ElementInterface;
use craft\db\Query;
use craft\helpers\Db;
use craft\helpers\StringHelper;

use yii\base\Exception;

class Service extends Component
{
    // Public Methods
    // =========================================================================

    public function get(array $options = []): ?Shortcut
    {
        $shortcut = null;

        if (isset($options['element'])) {
            $element = $options['element'];

            // Check if we have one
            $shortcut = $this->getByElementId($element->id, $element->siteId, $this->_currentProvider());

            // If not, create one
            if (!$shortcut) {
                $shortcut = $this->create($options);
            }
        }

        if (isset($options['url'])) {
            $url = $options['url'];

            // Check if we have one
            $shortcut = $this->getByUrl($url, $this->_currentProvider());

            // If not, create one
            if (!$shortcut) {
                $shortcut = $this->create($options);
            }
        }

        return $shortcut;
    }

    public function create(array $options = []): Shortcut
    {
        $model = new Shortcut();
        $model->provider = $this->_currentProvider();

        if (isset($options['element'])) {
            $element = $options['element'];
            $url = $element->getUrl();

            $model->elementId = $element->id;
            $model->elementType = get_class($element);
            $model->siteId = $element->siteId;
            $model->url = $url;
            $model->urlHash = $this->_hashForUrl($url, $model->elementId, $model->siteId, $model->provider);
        }

        if (isset($options['url'])) {
            $url = $options['url'];
            $model->url = $url;
            $model->siteId = Craft::$app->getSites()->currentSite->id;
            $model->urlHash = $this->_hashForUrl($url, null, null, $model->provider);
        }

        $this->saveShortcut($model);

        return $model;
    }

    public function getById($id = null): ?Shortcut
    {
        $record = ShortcutRecord::findById($id);

        if ($record) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    public function getByCode($code = null): ?Shortcut
    {
        $record = ShortcutRecord::findOne(['code' => $code]);

        if ($record) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    public function getByUrl($url = null, ?string $provider = null): ?Shortcut
    {
        $provider ??= $this->_currentProvider();
        $hash = $this->_hashForUrl($url, null, null, $provider);

        $record = ShortcutRecord::findOne(['urlHash' => $hash]);

        if ($record) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    public function getByElementId($id = null, $siteId = null, ?string $provider = null): ?Shortcut
    {
        $provider ??= $this->_currentProvider();

        $record = ShortcutRecord::findOne(['elementId' => $id, 'siteId' => $siteId, 'provider' => $provider]);

        if ($record) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    public function increaseHits(Shortcut $shortcut): void
    {
        ++$shortcut->hits;

        $this->saveShortcut($shortcut);
    }

    public function saveShortcut(Shortcut $shortcut): void
    {
        $isNew = !$shortcut->id;

        if ($shortcut->validate()) {
            if (!$isNew) {
                $record = ShortcutRecord::findOne($shortcut->id);

                if (!$record) {
                    throw new Exception('No shortcut record with ID ' . $shortcut->id . ' was found.');
                }
            } else {
                $record = new ShortcutRecord();
            }

            // Ensure a code is set, if not already
            if (empty($shortcut->code)) {
                $shortcut->code = $this->getUniqueKey();
            }

            $this->_prepareExternalUrl($shortcut);

            $record->url = $shortcut->url;
            $record->urlHash = $shortcut->urlHash;
            $record->code = $shortcut->code;
            $record->provider = $shortcut->provider;
            $record->externalUrl = $shortcut->externalUrl;
            $record->siteId = $shortcut->siteId;
            $record->hits = $shortcut->hits;
            $record->elementId = $shortcut->elementId;
            $record->elementType = $shortcut->elementType;

            $record->save();
        }
    }

    public function deleteShortcut(Shortcut $shortcut): void
    {
        Db::delete('{{%shortcut_shortcuts}}', [
            'id' => $shortcut->id,
        ]);
    }

    public function onSaveElement(ElementInterface $element): void
    {
        $provider = $this->_currentProvider();
        $shortcut = $this->getByElementId($element->id, $element->siteId, $provider);

        // Check if we should update the url
        if ($shortcut && $element->getUrl() !== $shortcut->url) {
            $shortcut->url = $element->getUrl();
            $shortcut->urlHash = $this->_hashForUrl($shortcut->url, $shortcut->elementId, $shortcut->siteId, $provider);
            $shortcut->externalUrl = '';

            $this->saveShortcut($shortcut);
        }
    }

    public function onDeleteElement(ElementInterface $element): void
    {
        $shortcut = $this->getByElementId($element->id, $element->siteId);

        if ($shortcut && $element->getUrl() === $shortcut->url) {
            $this->deleteShortcut($shortcut);
        }
    }

    public function on404(): void
    {
        $code = Craft::$app->getRequest()->getSegment(1);
        $shortcut = $this->getByCode($code);

        if ($shortcut) {
            $this->increaseHits($shortcut);

            Craft::$app->getResponse()->redirect($shortcut->getRealUrl());

            Craft::$app->end();
        }
    }

    public function getUniqueKey($code = null)
    {
        $settings = ShortcutPlugin::$plugin->getSettings();

        $unique = false;

        if (!$code) {
            $code = StringHelper::randomString($settings->hashLength);
        }

        while (!$unique) {
            $check = (new Query())
                ->from('{{%shortcut_shortcuts}}')
                ->where([
                    'code' => $code,
                ])
                ->exists();

            if (!$check) {
                $unique = true;
            } else {
                $code = StringHelper::randomString($settings->hashLength);
            }
        }

        return $code;
    }


    // Private Methods
    // =========================================================================

    private function _hashForUrl($url = null, $elementId = null, $siteId = null, ?string $provider = null): string
    {
        // Use all parts of info to generate a unique key
        $parts = [$url, $elementId, $siteId];

        if ($provider && $provider !== Settings::PROVIDER_LOCAL) {
            $parts[] = $provider;
        }

        $parts = implode('-', array_filter($parts));

        return md5($parts);
    }

    private function _populateShortcut(ShortcutRecord $record): Shortcut
    {
        $model = new Shortcut();
        $model->id = $record->id;
        $model->siteId = $record->siteId;
        $model->elementId = $record->elementId;
        $model->elementType = $record->elementType;
        $model->hits = $record->hits;
        $model->url = $record->url ?? '';
        $model->urlHash = $record->urlHash ?? '';
        $model->code = $record->code;
        $model->provider = $record->provider;
        $model->externalUrl = $record->externalUrl ?? '';

        return $model;
    }

    private function _currentProvider(): string
    {
        return ShortcutPlugin::$plugin->getSettings()->provider ?: Settings::PROVIDER_LOCAL;
    }

    private function _prepareExternalUrl(Shortcut $shortcut): void
    {
        if ($shortcut->provider === Settings::PROVIDER_LOCAL || $shortcut->externalUrl) {
            return;
        }

        $shortcut->externalUrl = $this->_createShortener($shortcut->provider)->shorten($shortcut->getRealUrl());
    }

    private function _createShortener(string $provider): ShortenerInterface
    {
        $settings = ShortcutPlugin::$plugin->getSettings();

        return match ($provider) {
            Settings::PROVIDER_BITLY => new BitlyShortener($settings),
            Settings::PROVIDER_TINYURL => new TinyUrlShortener(),
            Settings::PROVIDER_ISGD => new IsGdShortener(),
            Settings::PROVIDER_REBRANDLY => new RebrandlyShortener($settings),
            Settings::PROVIDER_SHORTIO => new ShortIoShortener($settings),
            default => throw new Exception(Craft::t('shortcut', 'Unknown shortcut provider "{provider}".', ['provider' => $provider])),
        };
    }
}
