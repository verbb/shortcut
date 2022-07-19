<?php
namespace verbb\shortcut\migrations;

use verbb\shortcut\Shortcut;
use verbb\shortcut\records\Shortcut as ShortcutRecord;

use Craft;
use craft\db\Query;
use craft\elements\Asset;
use craft\elements\Entry;
use craft\elements\User;
use craft\models\Site;

use function is_callable;
use Throwable;

class m180909_202355_CraftUpgrade extends Install
{
    // Properties
    // =========================================================================

    public array $sites = [];
    public array $siteMap = [];


    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        $result = parent::safeUp();

        $oldTable = Craft::$app->getDb()->schema->getTableSchema('{{%shortcut}}');

        // Convert
        if ($oldTable) {
            $newTable = '{{%shortcut_shortcuts}}';
            $this->sites = Craft::$app->getSites()->getAllSites();
            $transaction = Craft::$app->getDb()->beginTransaction();

            foreach ($this->sites as $site) {
                /** @var $site Site */
                $this->siteMap[$site->handle] = $site->id;
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
            } catch (Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $result;
    }

    public function mapRecord($data = []): array
    {
        $elementMap = [
            'Entry' => Entry::class,
            'Asset' => Asset::class,
            'User' => User::class,
        ];

        $newData = [];
        $removeKeys = [
            'id',
        ];

        $keys = [
            'locale' => 'siteId',
        ];

        $transforms = [
            'siteId' => function($value) {
                return $this->siteMap[$value] ?? $this->sites[0];
            },
            'elementType' => function($value) use ($elementMap) {
                return $elementMap[$value] ?? $value;
            },
            'code' => function($value) {
                return Shortcut::$plugin->getService()->getUniqueKey($value);
            },
        ];

        foreach ($data as $key => $value) {
            $key = $keys[$key] ?? $key;
            $newData[$key] = $value;
            $transform = $transforms[$key] ?? null;

            if ($transform && is_callable($transform)) {
                $newData[$key] = $transform($value);
            }
        }

        foreach ($removeKeys as $key) {
            unset($newData[$key]);
        }

        return $newData;
    }

}
