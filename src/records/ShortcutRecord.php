<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut\records;

use superbig\shortcut\Shortcut;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */
class ShortcutRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shortcut_shortcuts}}';
    }
}
