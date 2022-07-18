<?php
namespace verbb\shortcut\records;

use craft\db\ActiveRecord;

class Shortcut extends ActiveRecord
{
    // Static Methods
    // =========================================================================

    public static function tableName(): string
    {
        return '{{%shortcut_shortcuts}}';
    }
}
