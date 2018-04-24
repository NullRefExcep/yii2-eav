<?php

namespace nullref\eav\models\attribute;

use nullref\eav\models\Attribute;
use nullref\useful\traits\Mappable;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%eav_attribute_option}}".
 *
 * @property int $id
 * @property int $attribute_id
 * @property int $sort_order
 * @property string $value
 *
 *
 * @property Attribute $attributeRecord
 */
class Option extends ActiveRecord
{
    use Mappable;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute_option}}';
    }

    /**
     * @inheritdoc
     * @return OptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OptionQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeRecord()
    {
        return $this->hasOne(Attribute::class, ['id' => 'attribute_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order', 'attribute_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('eav', 'ID'),
            'attribute_id' => Yii::t('eav', 'Attribute'),
            'sort_order' => Yii::t('eav', 'Sort Order'),
            'value' => Yii::t('eav', 'Value'),
        ];
    }
}
