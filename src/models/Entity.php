<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\models;


use nullref\eav\models\attribute\Set;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Exception;

class Entity extends Model
{
    /** @var Set[] */
    public $sets = [];

    /** @var bool */
    public $identicalValueCompare = false;

    /** @var array */
    public $_attributeModels = [];

    /** @var ActiveRecord */
    protected $owner;

    /**
     * @var
     */
    protected $_attributesConfig = [];
    protected $_attributes = [];

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
            $attr['items'] = $attribute->getOptionsMap();
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
                $valueClass = $attribute->getValueClass();

                /** @var ValueQuery $query */
                $query = $valueClass::find();

                $valueModel = $query->andWhere(['attribute_id' => $attribute->id, 'entity_id' => $id])->one();
                if ($valueModel) {
                    $valueModel->delete();
                }
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
        $valueClass = $attr->getValueClass();

        /** @var ValueQuery $query */
        $query = $valueClass::find();

        $valueModel = $query->andWhere(['attribute_id' => $attr->id, 'entity_id' => $id])->one();
        if ($valueModel == null) {
            /** @var Value $valueModel */
            $valueModel = new $valueClass();
            $valueModel->attribute_id = $attr->id;
            $valueModel->entity_id = $id;
        }
        $valueModel->value = $value;
        if ($valueModel->isNewRecord || $valueModel->isAttributeChanged('value', $this->identicalValueCompare)) {
            $valueModel->save();
        }
        TagDependency::invalidate(Yii::$app->cache, $valueModel->getCacheKey());
    }

    /**
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
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
     * @return mixed
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     */
    protected function getValue($attrCode, $owner)
    {
        $attr = $this->getAttributeModel($attrCode);

        $id = $owner->primaryKey;
        $valueClass = $attr->getValueClass();

        /** @var Connection $db */
        $db = $valueClass::getDb();
        return $db->cache(function () use ($valueClass, $attr, $id) {
            /** @var ValueQuery $query */
            $query = $valueClass::find();
            return $query->select('value')->andWhere(['attribute_id' => $attr->id, 'entity_id' => $id])->scalar();
        }, null, new TagDependency(['tags' => 'eav.value:' . $attr->id . '-' . $id]));

    }

    /**
     * @return array|null
     */
    public function getAttributesConfig()
    {
        return $this->_attributesConfig;
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
}