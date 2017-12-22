<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\components;


use yii\base\Component;
use yii\base\Exception;

class EntityManager extends Component
{
    public $entities = [];

    public function getEntity($id)
    {
        if (!array_key_exists($id, $this->entities)) {
            throw new Exception("Can't find entity config for '{$id}'");
        }
        return $this->entities[$id];
    }
}