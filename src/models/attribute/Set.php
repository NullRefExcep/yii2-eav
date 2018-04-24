<?php

namespace nullref\eav\models\attribute;

use nullref\eav\models\Attribute;
use nullref\useful\traits\Mappable;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%eav_attribute_set}}".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @property Attribute[] $attributeList
 */
class Set extends ActiveRecord
{
    use Mappable;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute_set}}';
    }

    /**
     * @inheritdoc
     * @return SetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SetQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeList()
    {
        return $this->hasMany(Attribute::class, ['set_id' => 'id'])->indexBy('code')->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('eav', 'ID'),
            'code' => Yii::t('eav', 'Code'),
            'name' => Yii::t('eav', 'Name'),
        ];
    }
}
