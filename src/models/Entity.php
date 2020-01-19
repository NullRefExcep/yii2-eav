<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\models;


use nullref\eav\models\attribute\Set;
use nullref\eav\models\value_proxy\ValueProxy;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Entity model implements all needed stuff to operate with dynamic attributes
 *
 * @package nullref\eav\models
 */
class Entity extends Model
{
    /** @var Set[] */
    public $sets = [];

    /** @var bool */
    public $identicalValueCompare = false;

    /** @var Attribute[] */
    public $_attributeModels = [];

    /** @var ActiveRecord */
    protected $owner;

    /** @var bool */
    public $enableCache = true;

    protected $_attributesConfig = [];
    protected $_attributes = [];

    /**
     * Static field for caching sets of attribute models
     */
    protected static $_attributeSetCache = [];

    /**
     * @param $entity
     * @param $owner
     * @return object
     * @throws InvalidConfigException
     */
    public static function create($entity, $owner)
    {
        $model = Yii::createObject($entity);
        $model->owner = $owner;

        return $model;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        foreach ($this->sets as $set) {
            if (isset(self::$_attributeSetCache[$set->id])) {
                $attributeList = self::$_attributeSetCache[$set->id];
            } else {
                if (!($set instanceof Set)) {
                    throw new InvalidConfigException('Entity set should be instance of ' . Set::class);
                }
                $attributeList = $set->attributeList;
                self::$_attributeSetCache[$set->id] = $attributeList;
            }
            foreach ($attributeList as $code => $attribute) {
                if (isset($this->_attributesConfig[$code])) {
                    throw new InvalidConfigException('Attribute code should be unique');
                }
                $this->_attributesConfig[$code] = $this->getAttributeConfig($attribute);
                $this->_attributes[$code] = null;
                $this->_attributeModels[$code] = $attribute;
            }
        }
    }

    /**
     * @param Attribute $attribute
     * @return array
     */
    protected function getAttributeConfig($attribute)
    {
        $attr = $attribute->attributes;
        if ($attribute->hasOptions()) {
            $attr['items'] = $attribute->getOptionsMap() ?? [];
        }
        return $attr;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return self::canGetProperty($name, true, true);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @param bool $checkBehaviors
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (array_search($name, $this->attributes()) !== false) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars, $checkBehaviors);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return array_keys($this->_attributes);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @param bool $checkBehaviors
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if ($this->canGetProperty($name, $checkVars, $checkBehaviors)) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars, $checkBehaviors);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'attributes') {
            return $this->_attributes;
        }
        return $this->_attributes[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name === 'attributes') {
            $this->_attributes = $value;
        }
        $this->_attributes[$name] = $value;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete()
    {
        $id = $this->owner->primaryKey;

        foreach ($this->sets as $set) {
            foreach ($set->attributeList as $attribute) {

                $valueModel = $attribute->createValue();

                $valueModel->get($id)->delete();
            }
        }
    }

    /**
     * @throws Exception
     */
    public function save()
    {
        foreach ($this->_attributes as $code => $value) {
            $this->setValue($code, $this->owner, $value);
        }
    }

    /**
     * @param $attrCode
     * @param $owner
     * @param $value
     * @throws Exception
     */
    protected function setValue($attrCode, $owner, $value)
    {
        $attr = $this->getAttributeModel($attrCode);

        $id = $owner->primaryKey;

        $valueModel = $attr->createValue();

        $valueModel->get($id)->setValue($value)->save();

        if ($this->enableCache) {
            Yii::$app->cache->set($valueModel->getCacheKey(), $value);
        }
    }

    /**
     * @param $name
     * @return Attribute
     * @throws Exception
     */
    public function getAttributeModel($name)
    {
        if (!isset($this->_attributeModels[$name])) {
            throw new Exception("Can't get attribute $name");
        }
        return $this->_attributeModels[$name];
    }

    /**
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function find()
    {
        $list = [];
        foreach ($this->_attributes as $code => $v) {
            $list[$code] = $this->getValue($code, $this->owner);
        }
        $this->_attributes = $list;
    }

    /**
     * @param $attrCode
     * @param $owner
     * @return mixed|null
     * @throws Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getValue($attrCode, $owner)
    {
        $attr = $this->getAttributeModel($attrCode);

        $defaultValueModel = $attr->createValue();
        $id = $owner->primaryKey;

        if ($this->enableCache) {
            $cacheKey = $defaultValueModel->getCacheKey();

            $value = Yii::$app->cache->get($cacheKey);

            if ($value === false) {

                $value = $this->getValueInternal($defaultValueModel, $id);

                if ($value !== false) {
                    Yii::$app->cache->set($cacheKey, $value);
                }
            }

            return $value;
        }

        return $this->getValueInternal($defaultValueModel, $id);
    }

    /**
     * @param ValueProxy $valueProxy
     * @param Attribute $attribute
     * @param $id
     * @return mixed|null
     */
    protected function getValueInternal(ValueProxy $valueProxy, $id)
    {
        return $valueProxy->get($id)->getValue();
    }

    /**
     * @return array|null
     */
    public function getAttributesConfig()
    {
        return $this->_attributesConfig;
    }
}
