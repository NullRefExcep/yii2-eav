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

/**
 * Entity behavior that allow to use dynamic attributes in model instance
 *
 * To use Entity behavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use nullref\eav\behaviors\Entity;
 * use nullref\eav\models\Entity as EntityModel;
 *
 * public function behaviors()
 * {
 *     return [
 *          'eav' => [
 *              'class' => Entity::class,
 *                  'entity' => function () {
 *                      return new EntityModel([
 *                      'sets' => [
 *                          Set::findOne(['code' => 'catalog']),
 *                      ],
 *                  ]);
 *              },
 *          ],
 *     ];
 * }
 *
 * @package nullref\eav\behaviors
 */
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
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function afterFind()
    {
        $this->entityModel->find();
    }

    /**
     * @throws \yii\db\Exception
     */
    public function afterSave()
    {
        $this->entityModel->save();
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterDelete()
    {
        $this->entityModel->delete();
    }

    /**
     * @param \yii\base\Component|ActiveRecord $owner
     * @throws \yii\base\InvalidConfigException
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
     * @throws \yii\base\UnknownPropertyException
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