<?php
namespace verbb\shortcut\models;

use verbb\shortcut\Shortcut;

use Craft;
use craft\helpers\UrlHelper;
use craft\base\Model;

use yii\base\Exception;
use yii\web\Response;

class Shortcut extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var integer|null
     */
    public $id = null;

    /**
     * @var string
     */
    public $url = '';

    /**
     * @var string
     */
    public $urlHash = '';

    /**
     * @var string
     */
    public $code = '';

    /**
     * @var integer
     */
    public $elementId;

    /**
     * @var string
     */
    public $elementType;

    /**
     * @var integer
     */
    public $siteId;

    /**
     * @var integer
     */
    public $hits = 0;


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
        $settings = Shortcut::$plugin->getSettings();
        $urlSegment = $settings->hideUrlSegment ? '' : ($settings->urlSegment ?: 's');
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
