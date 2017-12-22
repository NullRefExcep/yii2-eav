<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\behaviors;


use nullref\eav\models\Attribute;
use nullref\eav\models\attribute\Set;
use Yii;
use yii\base\Behavior;

class Formatter extends Behavior
{
    /**
     * @param $setId
     * @return string
     */
    public function asSet($setId)
    {
        $model = Set::findOne($setId);
        if ($model) {
            return $model->name;
        }
        return Yii::t('eav', 'N\A');
    }

    /**
     * @param $type
     * @return string
     */
    public function asAttributeType($type)
    {
        return isset(Attribute::getTypesMap()[$type]) ? Attribute::getTypesMap()[$type] : Yii::t('eav', 'N\A');
    }

}