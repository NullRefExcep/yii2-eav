<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\models\value;


use yii\db\ActiveQuery;

class DecimalValue extends IntegerValue
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%eav_entity_value_decimal}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'value' => ['value', 'filter', 'filter' => 'floatval', 'on' => self::SCENARIO_DEFAULT],
            'search_value' => ['value', 'each', 'rule' => ['number'], 'on' => 'search'],
        ]);
    }

    /**
     * @param ActiveQuery $query
     * @param string|bool $table
     */
    public function addWhere($query, $table = false)
    {
        $table = $table ? $table : self::JOIN_TABLE_PREFIX . $this->attributeModel->code;

        foreach (['from' => '>=', 'to' => '<='] as $key => $operator) {
            if (isset($this->value[$key]) && $this->value[$key] !== '') {
                $query->andFilterWhere([$operator, "$table.value", floatval($this->value[$key])]);
            }
        }
    }
}