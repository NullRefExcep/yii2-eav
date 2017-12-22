<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\models\value;


use nullref\eav\models\Value;
use yii\db\ActiveQuery;

class OptionValue extends Value
{
    public static function tableName()
    {
        return '{{%eav_entity_value_int}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['value', 'number'],
        ]);
    }

    /**
     * @param ActiveQuery $query
     * @param string|bool $table
     */
    public function addWhere($query, $table = false)
    {
        $table = $table ? $table : self::JOIN_TABLE_PREFIX . $this->attributeModel->name;
        $query->andWhere(['=', "$table.value", $this->value]);
    }
}