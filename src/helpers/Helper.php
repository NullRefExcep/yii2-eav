<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\helpers;


use nullref\eav\Module;
use Yii;

class Helper
{
    /**
     * @return \nullref\eav\Module
     */
    public static function getModule()
    {
        return Yii::$app->getModule(Module::MODULE_ID);
    }
}