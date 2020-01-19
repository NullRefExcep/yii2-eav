<?php

namespace nullref\eav\models\value_proxy;

use nullref\eav\models\Value;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model is proxy for real ActiveRecord
 *
 *
 * @property mixed $value
 *
 * @property Attribute $attributeModel
 * @property Attribute $attributeModelRelation
 */
class SingleValueProxy extends ValueProxy
{
    /**
     * @var Value|null
     */
    protected $_valueModel = null;

    /**
     * @param $query
     * @param $entityTable
     * @param bool $table
     */
    public function patchEntityQuery($query, $entityTable, $table = false)
    {
        $this->getValueModel()->addJoin($query, $entityTable, $table);
        $this->getValueModel()->addWhere($query, $table);
    }


    /**
     * @return bool|void
     */
    public function save()
    {
        if (!$this->getValueModel()->save()) {
            throw new Exception("Can't save value. \nError: " . print_r($this->getValueModel()->errors));
        }
    }

    /**
     * @param $entityId
     * @return ValueProxy
     */
    public function get($entityId)
    {
        $self = parent::get($entityId);
        $this->loadValue();
        return $self;
    }

    public function getValue()
    {
        return $this->getValueModel()->value;
    }

    public function setValue($value)
    {
        $this->getValueModel()->value = $value;
        return $this;
    }

    protected function getValueModel()
    {
        if ($this->_valueModel == null) {
            $this->_valueModel = $this->createValueInstance($this->_entityId);
        }
        return $this->_valueModel;
    }

    protected function loadValue()
    {
        $query = $this->getDefaultValueInstance()::find();
        $valueModel = $query->andWhere(['attribute_id' => $this->_attributeModel->id, 'entity_id' => $this->_entityId])->one();
        $this->_valueModel = $valueModel ? $valueModel : $this->createValueInstance();
    }
}
