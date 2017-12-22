<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\helpers;


use nullref\eav\models\Attribute;
use nullref\eav\models\attribute\Option;
use nullref\eav\models\attribute\Set;

class Migration
{
    /**
     * @param $code
     * @param $name
     * @param $attributes
     * @return bool
     */
    public static function createSet($code, $name, $attributes)
    {
        $set = new Set();
        $set->code = $code;
        $set->name = $name;
        $result = $set->save();

        if ($result) {
            foreach ($attributes as $attribute) {
                $result &= self::createAttribute($set->id, $attribute);
            }
        }

        return $result;
    }

    /**
     * @param $setId
     * @param $config
     * @return bool
     */
    public static function createAttribute($setId, $config)
    {
        $attribute = new Attribute();
        $attribute->setAttributes($config['attributes']);
        $attribute->set_id = $setId;
        if (isset($config['config']) && is_array($config['config'])) {
            $attribute->config = array_merge([
                'show_in_grid' => false,
                'read_only' => false,
            ], $config['config']);
        }
        $result = $attribute->save();

        if ($result) {
            if (array_key_exists('options', $config)) {
                foreach ($config['options'] as $option) {
                    $result &= self::createOption($attribute->id, $option);
                }
            }
        }

        return $result;
    }

    /**
     * @param $attributeId
     * @param array $config
     * @return bool
     */
    public static function createOption($attributeId, $config)
    {
        $option = new Option();
        $option->setAttributes($config);
        $option->attribute_id = $attributeId;

        return $option->save();
    }

}