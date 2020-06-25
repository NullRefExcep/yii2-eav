<?php

namespace nullref\eav\components;

use nullref\eav\models\Type;
use nullref\eav\Module;
use yii\base\Component;
use yii\base\Exception;

/**
 * EAV types manager
 * @package nullref\eav\components
 */
class TypesManager extends Component
{
    /**
     * @var Type[]
     */
    protected $_types = [];
    protected $_map = [];

    /**
     * @param Type $type
     */
    public function registerType($type)
    {
        $this->_types[$type->getName()] = $type;
        $this->_map[$type->getName()] = $type->getLabel();
    }

    /**
     * @param $type
     * @return Type
     * @throws Exception
     */
    public function getType($type)
    {
        if (isset($this->_types[$type])) {
            return $this->_types[$type];
        }
        throw new Exception("Can't find type '$type'");
    }

    /**
     * @return array
     */
    public function getTypesMap()
    {
        return $this->_map;
    }

    /**
     * @return self
     */
    public static function get()
    {
        return \Yii::$app->getModule(Module::MODULE_ID)->getTypesManager();
    }
}