<?php

namespace nullref\eav\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%eav_entity_value_int}}".
 *
 * @property int $id
 * @property int $attribute_id
 * @property int $entity_id
 * @property mixed $value
 *
 * @property Attribute $attributeModel
 * @property Attribute $attributeModelRelation
 */
class Value extends ActiveRecord
{
    const SCENARIO_SEARCH = 'search';

    const JOIN_TABLE_PREFIX = 'eav_value_';

    /** @var Attribute */
    protected $_attributeModel;

    /**
     * @inheritdoc
     * @return ValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ValueQuery(get_called_class());
    }

    /**
     * @param ActiveQuery $query
     * @param $entityTable
     * @param string|bool $table
     * @return ActiveQuery
     */
    public function addJoin($query, $entityTable, $table = false)
    {
        $table = $table ? $table : self::JOIN_TABLE_PREFIX . $this->attributeModel->name;
        return $query->innerJoin([$table => static::tableName()], "$entityTable.id = $table.entity_id");
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_entity_value_other}}';
    }

    /**
     * @param ActiveQuery $query
     * @param string|bool $table
     */
    public function addWhere($query, $table = false)
    {
        $table = $table ? $table : self::JOIN_TABLE_PREFIX . $this->attributeModel->name;
        $query->andWhere(["$table.value" => $this->value]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'entity_id'], 'required'],
            [['attribute_id', 'entity_id'], 'integer'],
            ['value', 'safe'],
            ['value', 'required', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_SEARCH => ['value'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('eav', 'ID'),
            'attribute_id' => Yii::t('eav', 'Attribute ID'),
            'entity_id' => Yii::t('eav', 'Entity ID'),
            'value' => Yii::t('eav', 'Value'),
        ];
    }

    /**
     * @param $value
     */
    public function setAttributeModel($value)
    {
        $this->_attributeModel = $value;
    }

    /**
     * @return Attribute
     */
    public function getAttributeModel()
    {
        if (!isset($this->_attributeModel)) {
            $this->_attributeModel = $this->attributeModelRelation;
        }
        return $this->_attributeModel;
    }

    /**
     * @return ActiveQuery
     */
    public function getAttributeModelRelation()
    {
        return $this->hasOne(Attribute::class, ['id' => 'attribute_id']);
    }
}
