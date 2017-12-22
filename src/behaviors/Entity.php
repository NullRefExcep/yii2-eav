<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\behaviors;


use nullref\eav\models\Entity as EntityModel;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\validators\Validator;

class Entity extends Behavior
{
    /** @var string */
    public $entity;

    /** @var EntityModel */
    protected $entityModel;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->entityModel->find();
    }

    /**
     *
     */
    public function afterSave()
    {
        $this->entityModel->save();
    }

    /**
     *
     */
    public function afterDelete()
    {
        $this->entityModel->delete();
    }

    /**
     * @param ActiveRecord $owner
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->entityModel = EntityModel::create($this->entity, $owner);

        $validators = $owner->getValidators();
        $attributes = $this->entityModel->attributes();
        $validators->append(Validator::createValidator('safe', $owner, $attributes));
    }

    /**
     * @param string $name
     * @return EntityModel|mixed
     */
    public function __get($name)
    {
        if ($name === 'eav') {
            return $this->entityModel;
        }

        if ($this->entityModel->canGetProperty($name)) {
            return $this->entityModel->$name;
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     */
    public function __set($name, $value)
    {
        if ($this->entityModel->canSetProperty($name)) {
            $this->entityModel->$name = $value;
            return;
        }
        parent::__set($name, $value);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if ($name === 'eav') {
            return true;
        }
        if ($this->entityModel->canGetProperty($name, $checkVars)) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if ($this->entityModel->canGetProperty($name, $checkVars)) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

}