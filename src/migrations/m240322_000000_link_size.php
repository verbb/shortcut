<?php
namespace verbb\shortcut\migrations;

use craft\db\Migration;

class m240322_000000_link_size extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        $this->dropIndexIfExists('{{%shortcut_shortcuts}}', 'urlHash', true);

        $this->alterColumn('{{%shortcut_shortcuts}}', 'elementId', $this->integer());
        $this->alterColumn('{{%shortcut_shortcuts}}', 'elementType', $this->string());
        $this->alterColumn('{{%shortcut_shortcuts}}', 'url', $this->text());
        $this->alterColumn('{{%shortcut_shortcuts}}', 'urlHash', $this->text());

        return true;
    }

    public function safeDown(): bool
    {
        echo "m240322_000000_link_size cannot be reverted.\n";
        return false;
    }

}
