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
 *
 * @property int $siteId Site ID
 */
class ShortcutRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    const TABLE_NAME = '{{%shortcut_shortcuts}}';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return static::TABLE_NAME;
    }
}
