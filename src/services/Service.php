<?php
namespace verbb\shortcut\services;

use verbb\shortcut\models\Shortcut;
use verbb\shortcut\records\Shortcut as ShortcutRecord;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\db\Query;
use craft\helpers\StringHelper;

use yii\base\ExitException;
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
            $shortcut = $this->getByElementId($element->id, $element->siteId);

            // If not, create one
            if (!$shortcut) {
                $shortcut = $this->create($options);
            }
        }

        if (isset($options['url'])) {
            $url = $options['url'];

            // Check if we have one
            $shortcut = $this->getByUrl($url);

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

        if (isset($options['element'])) {
            $element = $options['element'];
            $url = $element->getUrl();

            $model->elementId = $element->id;
            $model->elementType = get_class($element);
            $model->siteId = $element->siteId;
            $model->url = $url;
            $model->urlHash = $this->_hashForUrl($url);
        }

        if (isset($options['url'])) {
            $url = $options['url'];
            $model->url = $url;
            $model->siteId = Craft::$app->getSites()->currentSite->id;
            $model->urlHash = $this->_hashForUrl($url);
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

    public function getByUrl($url = null): ?Shortcut
    {
        $hash = $this->_hashForUrl($url);

        $record = ShortcutRecord::findOne(['urlHash' => $hash]);

        if ($record) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    public function getByElementId($id = null, $siteId = null): ?Shortcut
    {
        $record = ShortcutRecord::findOne(['elementId' => $id, 'siteId' => $siteId]);

        if ($record) {
            return $this->_populateShortcut($record);
        }

        return null;
    }

    public function increaseHits(Shortcut $shortcut): void
    {
        $shortcut->hits = $shortcut->hits + 1;

        $this->saveShortcut($shortcut);
    }

    public function saveShortcut(Shortcut &$shortcut): void
    {
        $isNew = !$shortcut->id;

        if ($shortcut->validate()) {
            if (!$isNew) {
                $record = ShortcutRecord::findOne($shortcut->id);

                if (!$record) {
                    throw new \Exception('No shortcut record with ID ' . $shortcut->id . ' was found.');
                }
            } else {
                $record = new ShortcutRecord();
            }

            $record->url = $shortcut->url;
            $record->urlHash = $shortcut->urlHash;
            $record->code = $shortcut->code;
            $record->siteId = $shortcut->siteId;
            $record->hits = $shortcut->hits;
            $record->elementId = $shortcut->elementId;
            $record->elementType = $shortcut->elementType;

            if ($record->save() && empty($record->code)) {
                $record->code = $this->getUniqueKey();

                if ($record->save()) {
                    $shortcut->code = $record->code;
                }
            }
        }
    }

    public function onSaveElement(Element $element): void
    {
        $shortcut = $this->getByElementId($element->id, $element->siteId);

        // Check if we should update the url
        if ($shortcut && $element->getUrl() !== $shortcut->url) {
            $shortcut->url = $element->getUrl();

            $this->saveShortcut($shortcut);
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
        $unique = false;

        if (!$code) {
            $code = StringHelper::randomString(12);
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
                $code = StringHelper::randomString(12);
            }
        }

        return $code;
    }


    // Private Methods
    // =========================================================================

    private function _hashForUrl($url = null): string
    {
        return md5($url);
    }

    private function _populateShortcut(ShortcutRecord $record): Shortcut
    {
        $model = new Shortcut();
        $model->id = $record->id;
        $model->siteId = $record->siteId;
        $model->elementId = $record->elementId;
        $model->elementType = $record->elementType;
        $model->hits = $record->hits;
        $model->url = $record->url;
        $model->urlHash = $record->urlHash;
        $model->code = $record->code;

        return $model;
    }
}
