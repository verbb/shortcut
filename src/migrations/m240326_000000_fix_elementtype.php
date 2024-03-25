<?php
namespace verbb\shortcut\migrations;

use craft\db\Migration;

class m240326_000000_fix_elementtype extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        // Fix due to `m240322_000000_link_size` typo in case some people have already migrated
        $this->alterColumn('{{%shortcut_shortcuts}}', 'elementType', $this->string());

        return true;
    }

    public function safeDown(): bool
    {
        echo "m240326_000000_fix_elementtype cannot be reverted.\n";
        return false;
    }

}
