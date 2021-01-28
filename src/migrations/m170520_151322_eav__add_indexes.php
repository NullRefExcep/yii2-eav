<?php

use nullref\core\traits\MigrationTrait;
use yii\db\Migration;

class m170520_151322_eav__add_indexes extends Migration
{
    public $valueTables = [
        'int',
        'decimal',
        'string',
        'text',
        'other',
    ];

    public function safeUp()
    {
        foreach ($this->generateIndexes() as $index) {
            $this->createIndex($index[0], $index[1], $index[2]);
        }
    }

    protected function generateIndexes()
    {
        $indexes = [];
        foreach ($this->valueTables as $table) {
            $tableName = "{{%eav_entity_value_$table}}";
            foreach (['attribute_id', 'entity_id'] as $column) {
                $indexName = "eav_entity_value_${table}_${column}_idx";
                $indexes[] = [$indexName, $tableName, [$column]];
            }
        }

        $tableName = "{{%eav_attribute_option}}";
        foreach (['attribute_id', 'sort_order'] as $column) {
            $indexName = "eav_attribute_option_${column}_idx";
            $indexes[] = [$indexName, $tableName, [$column]];
        }

        $tableName = "{{%eav_attribute}}";
        foreach (['code', 'sort_order', 'type', 'set_id'] as $column) {
            $indexName = "eav_attribute_${column}_idx";
            $indexes[] = [$indexName, $tableName, [$column]];
        }

        return $indexes;
    }

    public function safeDown()
    {
        foreach ($this->generateIndexes() as $index) {
            $this->dropIndex($index[0], $index[1]);
        }
    }
}
