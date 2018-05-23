<?php

namespace nullref\eav\migrations;

use nullref\core\traits\MigrationTrait;
use yii\db\Migration;

class m170520_151321_eav__create_tables extends Migration
{
    use MigrationTrait;

    public function safeUp()
    {
        if (!$this->tableExist('{{%eav_attribute_set}}')) {
            $this->createTable('{{%eav_attribute_set}}', [
                'id' => $this->primaryKey(),
                'code' => $this->string(),
                'name' => $this->string()->unique(),
            ]);

            $this->createTable('{{%eav_attribute}}', [
                'id' => $this->primaryKey(),
                'name' => $this->string(),
                'code' => $this->string(),
                'config' => $this->text(),
                'sort_order' => $this->integer(),
                'type' => $this->string(),
                'set_id' => $this->integer(),
            ]);

            $this->createTable('{{%eav_attribute_option}}', [
                'id' => $this->primaryKey(),
                'attribute_id' => $this->integer(),
                'sort_order' => $this->integer(),
                'value' => $this->string(),
            ]);

            $this->createTable('{{%eav_entity_value_int}}', [
                'id' => $this->primaryKey(),
                'attribute_id' => $this->integer(),
                'entity_id' => $this->integer(),
                'value' => $this->integer(),
            ]);

            $this->createTable('{{%eav_entity_value_decimal}}', [
                'id' => $this->primaryKey(),
                'attribute_id' => $this->integer(),
                'entity_id' => $this->integer(),
                'value' => $this->decimal(12, 4),
            ]);

            $this->createTable('{{%eav_entity_value_string}}', [
                'id' => $this->primaryKey(),
                'attribute_id' => $this->integer(),
                'entity_id' => $this->integer(),
                'value' => $this->string(),
            ]);

            $this->createTable('{{%eav_entity_value_text}}', [
                'id' => $this->primaryKey(),
                'attribute_id' => $this->integer(),
                'entity_id' => $this->integer(),
                'value' => $this->text(),
            ]);

            $this->createTable('{{%eav_entity_value_other}}', [
                'id' => $this->primaryKey(),
                'attribute_id' => $this->integer(),
                'entity_id' => $this->integer(),
                'value' => $this->text(),
            ]);
        }
    }

    public function safeDown()
    {
        $this->dropTable('{{%eav_entity_value_other}}');
        $this->dropTable('{{%eav_entity_value_text}}');
        $this->dropTable('{{%eav_entity_value_string}}');
        $this->dropTable('{{%eav_entity_value_decimal}}');
        $this->dropTable('{{%eav_entity_value_int}}');
        $this->dropTable('{{%eav_attribute_option}}');
        $this->dropTable('{{%eav_attribute}}');
        $this->dropTable('{{%eav_attribute_set}}');
    }
}
