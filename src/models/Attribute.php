<?php

namespace nullref\eav\models;

use nullref\eav\components\Manager;
use nullref\eav\models\attribute\Option;
use nullref\eav\models\attribute\OptionQuery;
use nullref\eav\models\attribute\Set;
use nullref\eav\models\value\DecimalValue;
use nullref\eav\models\value\ImageValue;
use nullref\eav\models\value\IntegerValue;
use nullref\eav\models\value\JsonValue;
use nullref\eav\models\value\OptionValue;
use nullref\eav\models\value\StringValue;
use nullref\eav\models\value\TextValue;
use nullref\eav\models\value\UrlValue;
use nullref\eav\widgets\inputs\DefaultInput;
use nullref\eav\widgets\inputs\OptionInput;
use nullref\eav\widgets\inputs\TextInput;
use nullref\useful\behaviors\JsonBehavior;
use nullref\useful\traits\Mappable;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%eav_attribute}}".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|array $config
 * @property string $type
 * @property int $set_id
 *
 * @property Set $set
 * @property Option[] $options
 */
class Attribute extends ActiveRecord
{
    use Mappable;

    protected $_optionsMap = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attribute}}';
    }

    /**
     * @inheritdoc
     * @return AttributeQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new AttributeQuery(get_called_class());
        return $query->alias('attribute');
    }


    /**
     * @return array
     */
    public static function getTypesMap()
    {
        return Manager::get()->getTypesMap();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSet()
    {
        return $this->hasOne(Set::class, ['id' => 'set_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['set_id', 'sort_order'], 'integer'],
            [['name', 'code', 'type'], 'string', 'max' => 255],
            [['name', 'code', 'type', 'set_id'], 'required'],
            [['config'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'json' => [
                'class' => JsonBehavior::class,
                'fields' => ['config'],
                'default' => [
                    'show_in_grid' => false,
                    'read_only' => false,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('eav', 'ID'),
            'name' => Yii::t('eav', 'Name'),
            'code' => Yii::t('eav', 'Code'),
            'config' => Yii::t('eav', 'Config'),
            'type' => Yii::t('eav', 'Type'),
            'set_id' => Yii::t('eav', 'Set'),
        ];
    }

    /**
     * @return array|null
     */
    public function getOptionsMap()
    {
        if ($this->_optionsMap === null) {
            $this->_optionsMap = $this->getOptions()->indexBy('id')->select('value')->column();
        }
        return $this->_optionsMap;
    }

    /**
     * @return \yii\db\ActiveQuery | OptionQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Option::class, ['attribute_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        return Manager::get()->getType($this->type)->hasOptions();
    }

    /**
     * @return Value
     */
    public function createValue()
    {
        $class = $this->getValueClass();

        /** @var Value $model */
        $model = new $class;

        $model->attribute_id = $this->id;
        $model->attributeModel = $this;

        return $model;
    }

    /**
     * @return ValueQuery
     */
    public function createValueQuery()
    {
        $class = $this->getValueClass();

        /** @var ValueQuery $query */
        $query = call_user_func([$class, 'find']);

        $query->andWhere(['attribute_id' => $this->id]);

        return $query;
    }

    /**
     * @return string
     */
    public function getValueClass()
    {
        return Manager::get()->getType($this->type)->getValueClass();
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $valueClass = $this->getValueClass();

        $valueClass::deleteAll(['attribute_id' => $this->id]);

        return parent::beforeDelete();
    }
}
