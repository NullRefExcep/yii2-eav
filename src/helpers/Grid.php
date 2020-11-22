<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\helpers;


use kartik\select2\Select2;
use mcms\xeditable\XEditableColumn;
use nullref\core\widgets\ActiveRangeInputGroup;
use nullref\eav\models\Entity;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

class Grid
{
    /**
     * @param Model $searchModel
     * @param string $property
     * @return array
     * @throws \Exception
     */
    public static function getGridColumns(Model $searchModel, $property = 'eav')
    {
        /** @var Entity $entity */
        $entity = $searchModel->$property;

        return self::getGridColumnsInternal($entity, $searchModel);
    }

    /**
     * @param Entity $entity
     * @param null $searchModel
     * @return array
     * @throws \Exception
     */
    public static function getGridColumnsInternal(Entity $entity, $searchModel = null)
    {
        $result = [];

        foreach ($entity->getAttributesConfig() as $code => $item) {
            if (!$item['config']['show_in_grid']) {
                continue;
            }
            $attributeConfig = $entity->getAttributesConfig()[$code];
            $type = Helper::getTypesManager()->getType($attributeConfig['type']);
            $result[$code] = $type->getGridColumnConfig($code, $attributeConfig, $searchModel);
        }

        return $result;
    }
}
