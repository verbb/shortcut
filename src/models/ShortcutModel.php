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
     * @var string
     */
    public $elementId;

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
        $urlSegment   = Craft::$app->getConfig()->get('hideUrlSegment', 'shortcut') ? '' : (craft()->config->get('urlSegment', 'shortcut') ?: 's');
        $customDomain = craft()->config->get('customDomain', 'shortcut');

        if ( !empty($customDomain) ) {
            return rtrim($customDomain, '/') . '/' . $this->code;
        }

        return UrlHelper::getSiteUrl($urlSegment . '/' . $this->code);
    }

    public function getRealUrl ()
    {
        if ( !$this->elementId ) {
            return $this->url;
        }
        else {
            $element = Elements::getElementById($this->elementId, $this->elementType, $this->siteId);

            if ( !$element ) {
                throw new Exception(Craft::t('Could not find the url for element {id}', [ 'id' => $this->elementId ]));
            }

            return $element->getUrl();
        }
    }

    /**
     * @return $this
     */
    public function redirect ()
    {
        return Craft::$app->getResponse()->redirect($this->url);
    }
}
