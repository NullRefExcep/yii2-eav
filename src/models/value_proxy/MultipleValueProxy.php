<?php

namespace nullref\eav\models\value_proxy;

use nullref\eav\models\Value;

/**
 * This is the model is proxy for real ActiveRecord
 *
 * @property int $attribute_id
 * @property int $entity_id
 * @property mixed $value
 *
 * @property Attribute $attributeModel
 * @property Attribute $attributeModelRelation
 */
class MultipleValueProxy extends ValueProxy
{
    /**
     * @var Value[]
     */
    protected $_valueModels = [];

    protected $_values = [];
    protected $_oldValues = [];

    /**
     * @param $query
     * @param $entityTable
     * @param bool $table
     */
    public function patchEntityQuery($query, $entityTable, $table = false)
    {
        $this->getDefaultValueInstance()->addJoin($query, $entityTable, $table);
        foreach ($this->_values as $value) {
            $model = $this->createValueInstance();
            $model->value = $value;
            $model->addWhere($query, $table);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = $this->getDefaultValueInstance()->rules();
        $rules = array_reduce($rules, function ($acc, $rule) {
            if (count($rule) > 0) {
                if ($rule[0] == 'value' || in_array('value', $rule[0])) {
                    $config = array_slice($rule, 1);
                    $newRule = ['value', 'each'];
                    if (isset($config['on'])) {
                        $newRule['on'] = $config['on'];
                        unset($config['on']);
                    }
                    $newRule['rule'] = $config;
                    $acc[] = $newRule;
                }
            }
            return $acc;
        }, []);

        return $rules;
    }

    public function beforeValidate()
    {
        if (!is_array($this->_values)) {
            $this->_values = [$this->_values];
        }
        return parent::beforeValidate();
    }


    /**
     * @return bool|void
     */
    public function save()
    {
        // Calculate diffs to know what records should be created and deleted
        $valuesToCreate = array_diff($this->_values, $this->_oldValues);
        $valuesToDelete = array_diff($this->_oldValues, $this->_values);
        $deletedValueModels = [];
        $existingValueModels = [];

        // Split existing records into deleted and existing
        if (!empty($valuesToDelete)) {
            foreach ($this->_valueModels as $valueModel) {
                if (in_array($valueModel->value, $valuesToDelete)) {
                    $deletedValueModels[] = $valueModel;
                } else {
                    $existingValueModels[] = $valueModel;
                }
            }
        }

        $self = $this;
        // Create models for new values
        $createdValueModels = array_map(function ($value) use ($self) {
            $model = $self->createValueInstance();
            $model->value = $value;
            return $model;
        }, $valuesToCreate);

        if (!empty($valuesToDelete) || !empty($valuesToCreate)) {

            $transaction = $this->getDefaultValueInstance()::getDb()->beginTransaction();

            if (!empty($deletedValueModels)) {
                foreach ($deletedValueModels as $item) {
                    $item->delete();
                }
                foreach ($createdValueModels as $item) {
                    $item->save();
                }
            }
            $transaction->commit();
            $this->_valueModels = array_merge($createdValueModels, $existingValueModels);
            $this->_oldValues = $this->_values;
        }

    }

    /**
     * @param $entityId
     * @return ValueProxy
     * @throws \yii\base\InvalidConfigException
     */
    public function get($entityId)
    {
        $self = parent::get($entityId);
        $this->loadModels($entityId);
        return $self;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function loadModels()
    {
        $this->_valueModels = $this->getDefaultValueInstance()::find()
            ->andWhere(['attribute_id' => $this->_attributeModel->id, 'entity_id' => $this->_entityId])->all();

        $this->_values = array_map(function (Value $value) {
            return $value->value;
        }, $this->_valueModels);
        $this->_oldValues = $this->_values;
    }

    /**
     * @return array|mixed
     */
    public function getValue()
    {
        return $this->_values;
    }

    /**
     * @param $value
     * @return $this|mixed
     */
    public function setValue($value = [])
    {
        if (empty($value)) {
            $value = [];
        }
        $this->_values = $value;
        return $this;
    }
}
