<?php

namespace superbig\shortcut\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Query;
use craft\elements\Asset;
use craft\elements\Entry;
use craft\elements\User;
use craft\models\Site;
use superbig\shortcut\records\ShortcutRecord;
use superbig\shortcut\Shortcut;

/**
 * m180909_202355_CraftUpgrade migration.
 */
class m180909_202355_CraftUpgrade extends Install
{
    public $sites   = [];
    public $siteMap = [];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $result = parent::safeUp();

        $oldTable = Craft::$app->db->schema->getTableSchema('{{%shortcut}}');

        // Convert
        if ($oldTable) {
            $newTable    = ShortcutRecord::TABLE_NAME;
            $this->sites = Craft::$app->getSites()->getAllSites();
            $siteMap     = [];
            $transaction = Craft::$app->getDb()->beginTransaction();

            foreach ($this->sites as $site) {
                /** @var $site Site */
                $this->siteMap[ $site->handle ] = $site->id;
            }

            $query = (new Query())
                ->from('{{%shortcut}}')
                ->select('*')
                ->limit(null)
                ->all();

            try {
                foreach ($query as $row) {

                    $data = $this->mapRecord($row);

                    (new Query())
                        ->createCommand()
                        ->insert($newTable, $data)
                        ->execute();
                }

                $this->dropTableIfExists('{{%shortcut}}');

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }

        }

        return $result;
    }

    public function mapRecord($data = [])
    {
        $elementMap = [
            'Entry' => Entry::class,
            'Asset' => Asset::class,
            'User'  => User::class,
        ];
        $newData    = [];
        $removeKeys = [
            'id',
        ];
        $keys       = [
            'locale' => 'siteId',
        ];
        $transforms = [
            'siteId'      => function($value) {
                $siteId = $this->siteMap[ $value ] ?? $this->sites[0];

                return $siteId;
            },
            'elementType' => function($value) use ($elementMap) {
                return $elementMap[ $value ] ?? $value;
            },
            'code'        => function($value) {
                return Shortcut::$plugin->shortcutService->getUniqueKey($value);
            },
        ];

        foreach ($data as $key => $value) {
            $key             = $keys[ $key ] ?? $key;
            $newData[ $key ] = $value;
            $transform       = $transforms[ $key ] ?? null;

            if ($transform && \is_callable($transform)) {
                $newData[ $key ] = $transform($value);
            }
        }

        foreach ($removeKeys as $key) {
            unset($newData[ $key ]);
        }

        return $newData;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $result = parent::safeDown();

        return $result;
    }
}
