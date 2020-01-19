<?php

namespace nullref\eav\models\value_proxy;

use nullref\eav\models\Attribute;
use nullref\eav\models\Value;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model is proxy for real ActiveRecord
 *
 * @property mixed $value
 *
 * @property Attribute $attributeModel
 * @property Attribute $attributeModelRelation
 */
abstract class ValueProxy extends Model
{
    /** @var mixed */
    protected $_entityId;
    /** @var Attribute */
    protected $_attributeModel;
    /** @var string */
    protected $_valueClass;
    /** @var Value|null */
    protected $_defaultValueInstance;

    /**
     * ValueProxy constructor.
     * Initalize based on concrete value class
     * and attribute model
     *
     * @param $valueClass
     * @param $attributeModel
     * @param array $config
     */
    public function __construct($valueClass, $attributeModel, $config = [])
    {
        $this->_valueClass = $valueClass;
        $this->_attributeModel = $attributeModel;
        parent::__construct($config);
    }

    /**
     * @param $query
     * @param $entityTable
     * @param bool $table
     */
    public function patchEntityQuery($query, $entityTable, $table = false)
    {
        $this->addJoin($query, $entityTable, $table);
        $this->addWhere($query, $entityTable);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = $this->getDefaultValueInstance()->rules();
        return array_reduce($rules, function ($acc, $rule) {
            if (count($rule) > 0) {
                if ($rule[0] == 'value') {
                    $acc[] = $rule;
                } elseif (in_array('value', $rule[0])) {
                    $acc[] = array_merge(['value'], array_slice($rule, 1));
                }
            }
            return $acc;
        }, []);
    }

    /**
     * @return mixed|Value|null
     * @throws InvalidConfigException
     */
    public function getDefaultValueInstance()
    {
        if ($this->_defaultValueInstance == null) {
            $this->_defaultValueInstance = $this->createValueInstance();
        }
        return $this->_defaultValueInstance;
    }

    /**
     * @param null $entityId
     * @return Value
     * @throws InvalidConfigException
     */
    protected function createValueInstance()
    {
        $instance = new $this->_valueClass();
        if ($instance instanceof Value) {
            $instance->attribute_id = $this->_attributeModel->id;
            $instance->attributeModel = $this->_attributeModel;
            if ($this->_entityId != null) {
                $instance->entity_id = $this->_entityId;
            }
        } else {
            throw new InvalidConfigException("$this->_valueClass is not a subclass of " . Value::class);
        }
        return $instance;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return $this->getDefaultValueInstance()->scenarios();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->getDefaultValueInstance()->attributeLabels();
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return 'eav.value:' . $this->_attributeModel->id . '-' . $this->_entityId;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId($entityId)
    {
        $this->_entityId = $entityId;
    }

    /**
     * Proxy save logic to concrete value model(s)
     */
    public abstract function save();

    /**
     * Get instance with values for particular entity
     *
     * @param $entityId
     * @return $this
     */
    public function get($entityId)
    {
        $this->_entityId = $entityId;
        return $this;
    }

    /**
     * Proxy delete logic to concrete value model(s)
     */
    public function delete()
    {
        if ($this->_entityId) {
            $this->getDefaultValueInstance()::deleteAll(
                ['attribute_id' => $this->_attributeModel->id, 'entity_id' => $this->_entityId]
            );
        }
    }

    /**
     * Inline validator for array values for search scenario
     *
     * @param $attribute
     * @see DecimalValue, IntegerValue
     */
    public function anyIsset($attribute)
    {
        if (!count(array_filter($this->$attribute))) {
            $this->addError($attribute, 'Empty');
        }

    }

    /**
     * Get value from concrete value model(s)
     * @return mixed
     */
    public abstract function getValue();

    /**
     * @return mixed
     */
    public abstract function setValue($value);
}
