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
            'search_value_is_number' => ['value', 'each', 'rule' => ['number'], 'on' => self::SCENARIO_SEARCH],
            'search_value_isset' => ['value', 'anyIsset', 'on' => self::SCENARIO_SEARCH],
        ]);
    }

    /**
     * @param ActiveQuery $query
     * @param string|bool $table
     */
    public function addWhere($query, $table = false)
    {
        $table = $table ? $table : self::JOIN_TABLE_PREFIX . $this->attributeModel->code;

        $needAddCondition = false;
        foreach (['from' => '>=', 'to' => '<='] as $key => $operator) {
            if (isset($this->value[$key]) && $this->value[$key] !== '') {
                $query->andFilterWhere([$operator, "$table.value", floatval($this->value[$key])]);
                $needAddCondition = true;
            }
        }
        if ($needAddCondition) {
            $this->addAttributeCondition($query, $table);
        }
    }
}