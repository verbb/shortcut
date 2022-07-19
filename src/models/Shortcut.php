<?php
namespace verbb\shortcut\models;

use verbb\shortcut\Shortcut as ShortcutPlugin;

use Craft;
use craft\helpers\UrlHelper;
use craft\base\Model;

use yii\base\Exception;
use yii\web\Response;

class Shortcut extends Model
{
    // Properties
    // =========================================================================

    public ?int $id = null;
    public string $url = '';
    public string $urlHash = '';
    public string $code = '';
    public ?int $elementId= null;
    public ?string $elementType= null;
    public ?int $siteId= null;
    public int $hits = 0;


    // Public Methods
    // =========================================================================

    public function defineRules(): array
    {
        $rules = parent::defineRules();

        $rules[] = [['code', 'url', 'urlHash'], 'string'];
        $rules[] = [['elementId'], 'integer'];

        return $rules;
    }

    public function getUrl(): string
    {
        $settings = ShortcutPlugin::$plugin->getSettings();
        $urlSegment = $settings->hideUrlSegment ? '' : $settings->urlSegment;
        $customDomain = $settings->customDomain;

        if (!empty($customDomain)) {
            return rtrim($customDomain, '/') . '/' . $this->code;
        }

        return UrlHelper::siteUrl($urlSegment . '/' . $this->code);
    }

    public function getRealUrl(): ?string
    {
        if (!$this->elementId) {
            return $this->url;
        }

        $element = Craft::$app->elements->getElementById($this->elementId, $this->elementType, $this->siteId);

        if (!$element) {
            throw new Exception(Craft::t('shortcut', 'Could not find the url for element {id}', ['id' => $this->elementId]));
        }

        return $element->getUrl();
    }

    public function redirect(): Response
    {
        return Craft::$app->getResponse()->redirect($this->url);
    }
}
