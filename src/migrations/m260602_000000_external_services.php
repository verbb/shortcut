<?php
namespace verbb\shortcut\migrations;

use verbb\shortcut\models\Settings;

use craft\db\Migration;

class m260602_000000_external_services extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        if (!$this->db->columnExists('{{%shortcut_shortcuts}}', 'provider')) {
            $this->addColumn('{{%shortcut_shortcuts}}', 'provider', $this->string()->notNull()->defaultValue(Settings::PROVIDER_LOCAL)->after('code'));
        }

        if (!$this->db->columnExists('{{%shortcut_shortcuts}}', 'externalUrl')) {
            $this->addColumn('{{%shortcut_shortcuts}}', 'externalUrl', $this->text()->after('url'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        if ($this->db->columnExists('{{%shortcut_shortcuts}}', 'externalUrl')) {
            $this->dropColumn('{{%shortcut_shortcuts}}', 'externalUrl');
        }

        if ($this->db->columnExists('{{%shortcut_shortcuts}}', 'provider')) {
            $this->dropColumn('{{%shortcut_shortcuts}}', 'provider');
        }

        return true;
    }
}
