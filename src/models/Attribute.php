<?php

namespace nullref\eav\models;

use nullref\eav\models\attribute\Option;
use nullref\eav\models\attribute\OptionQuery;
use nullref\eav\models\attribute\Set;
use nullref\eav\models\value\DecimalValue;
use nullref\eav\models\value\ImageValue;
use nullref\eav\models\value\IntegerValue;
use nullref\eav\models\value\OptionValue;
use nullref\eav\models\value\StringValue;
use nullref\eav\models\value\TextValue;
use nullref\eav\models\value\UrlValue;
use nullref\useful\behaviors\JsonBehavior;
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
    /** Types */
    const TYPE_INT = 'int';
    const TYPE_OPTION = 'option';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_STRING = 'string';
    const TYPE_IMAGE = 'image';
    const TYPE_URL = 'url';
    const TYPE_TEXT = 'text';
    const TYPE_JSON = 'json';

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
        return new AttributeQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getTypesMap()
    {
        return [
            self::TYPE_OPTION => Yii::t('eav', 'Option'),
            self::TYPE_INT => Yii::t('eav', 'Integer'),
            self::TYPE_DECIMAL => Yii::t('eav', 'Decimal'),
            self::TYPE_STRING => Yii::t('eav', 'String'),
            self::TYPE_IMAGE => Yii::t('eav', 'Image'),
            self::TYPE_URL => Yii::t('eav', 'Url'),
            self::TYPE_TEXT => Yii::t('eav', 'Text'),
        ];
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
     * @return OptionQuery|\yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Option::class, ['attribute_id' => 'id']);
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
     * @return bool
     */
    public function hasOptions()
    {
        return $this->type == self::TYPE_OPTION;
    }

    /**
     * @return string
     */
    public function getValueClass()
    {
        switch ($this->type) {
            case self::TYPE_INT:
                return IntegerValue::class;
            case self::TYPE_OPTION:
                return OptionValue::class;
            case self::TYPE_DECIMAL:
                return DecimalValue::class;
            case self::TYPE_STRING:
                return StringValue::class;
            case self::TYPE_IMAGE:
                return ImageValue::class;
            case self::TYPE_URL:
                return UrlValue::class;
            case self::TYPE_TEXT:
                return TextValue::class;
            default:
                return Value::class;
        }
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
}
