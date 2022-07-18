<?php
namespace verbb\shortcut\migrations;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

class Install extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        $this->createTables();
        $this->createIndexes();
        $this->addForeignKeys();

        return true;
    }

    public function safeDown(): bool
    {
        $this->removeTables();

        return true;
    }

    public function createTables(): void
    {
        $this->createTable('{{%shortcut_shortcuts}}', [
            'id' => $this->primaryKey(),
            'siteId' => $this->integer()->notNull(),
            'elementId' => $this->integer()->null()->defaultValue(null),
            'elementType' => $this->string()->null()->defaultValue(null),
            'code' => $this->string()->notNull()->defaultValue(''),
            'url' => $this->string(400)->notNull()->defaultValue(''),
            'urlHash' => $this->string(400)->notNull()->defaultValue(''),
            'hits' => $this->integer()->notNull()->defaultValue(0),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);
    }

    public function createIndexes(): void
    {
        $this->createIndex(null, '{{%shortcut_shortcuts}}', 'code', true);
        $this->createIndex(null, '{{%shortcut_shortcuts}}', 'urlHash', true);
    }

    public function addForeignKeys(): void
    {
        $this->addForeignKey(null, '{{%shortcut_shortcuts}}', 'siteId', '{{%sites}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, '{{%shortcut_shortcuts}}', 'elementId', '{{%elements}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function removeTables(): void
    {
        $this->dropTableIfExists('{{%shortcut_shortcuts}}');
    }
}
