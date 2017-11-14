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
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $urlSegment = '';

    /**
     * @var string
     */
    public $hideUrlSegment = false;

    /**
     * @var string
     */
    public $customDomain = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ 'urlSegment', 'string' ],
            [ 'hideUrlSegment', 'bool' ],
            [ 'customDomain', 'string' ],
        ];
    }
}
