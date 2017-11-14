<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut\models;

use craft\helpers\UrlHelper;
use craft\services\Elements;
use yii\web\Response;
use superbig\shortcut\Shortcut;

use Craft;
use craft\base\Model;
use yii\base\Exception;

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */
class ShortcutModel extends Model
{
    // Public Properties
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

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ 'code', 'string' ],
            [ 'url', 'string' ],
            [ 'urlHash', 'string' ],
            [ 'elementId', 'integer' ],
            //[ 'someAttribute', 'default', 'value' => 'Some Default' ],
        ];
    }

    public function getUrl ()
    {
        $settings     = Shortcut::$plugin->getSettings();
        $urlSegment   = $settings->hideUrlSegment ? '' : ($settings->urlSegment ?: 's');
        $customDomain = $settings->customDomain;

        if ( !empty($customDomain) ) {
            return rtrim($customDomain, '/') . '/' . $this->code;
        }

        return UrlHelper::siteUrl($urlSegment . '/' . $this->code);
    }

    public function getRealUrl ()
    {
        if ( !$this->elementId ) {
            return $this->url;
        }
        else {
            $element = Craft::$app->elements->getElementById($this->elementId, $this->elementType, $this->siteId);

            if ( !$element ) {
                throw new Exception(Craft::t('shortcut', 'Could not find the url for element {
                    id}', [ 'id' => $this->elementId ]));
            }

            return $element->getUrl();
        }
    }

    /**
     * @return Response
     */
    public function redirect ()
    {
        return Craft::$app->getResponse()->redirect($this->url);
    }
}
