<?php
/**
 * Shortcut plugin for Craft CMS 3.x
 *
 * Simple URL shortening
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\shortcut\migrations;

use superbig\shortcut\Shortcut;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Superbig
 * @package   Shortcut
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp ()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;

        if ( $this->createTables() ) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown ()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables ()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%shortcut_shortcuts}}');
        if ( $tableSchema === null ) {
            $tablesCreated = true;
            $this->createTable(
                '{{%shortcut_shortcuts}}',
                [
                    'id'          => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid'         => $this->uid(),
                    'siteId'      => $this->integer()->notNull(),

                    'elementId'   => $this->integer()->null()->defaultValue(null),
                    'elementType' => $this->string()->null()->defaultValue(null),
                    'code'        => $this->string()->notNull()->defaultValue(''),
                    'url'         => $this->string(400)->notNull()->defaultValue(''),
                    'urlHash'     => $this->string(400)->notNull()->defaultValue(''),
                    'hits'        => $this->integer()->notNull()->defaultValue(0),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes ()
    {
        $this->createIndex(
            $this->db->getIndexName(
                '{{%shortcut_shortcuts}}',
                'code',
                true
            ),
            '{{%shortcut_shortcuts}}',
            'code',
            true
        );

        $this->createIndex(
            $this->db->getIndexName(
                '{{%shortcut_shortcuts}}',
                'urlHash',
                true
            ),
            '{{%shortcut_shortcuts}}',
            'urlHash',
            true
        );

        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * @return void
     */
    protected function addForeignKeys ()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%shortcut_shortcuts}}', 'siteId'),
            '{{%shortcut_shortcuts}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%shortcut_shortcuts}}', 'elementId'),
            '{{%shortcut_shortcuts}}',
            'elementId',
            '{{%elements}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    protected function insertDefaultData ()
    {
    }

    /**
     * @return void
     */
    protected function removeTables ()
    {
        $this->dropTableIfExists('{{%shortcut_shortcuts}}');
    }
}
