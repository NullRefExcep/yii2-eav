<?php


namespace nullref\eav\helpers;

use nullref\eav\models\Entity;

class Attributes
{
    public static function getWithEnabledConfig($attributes, $configProperty)
    {
        $result = [];
        foreach ($attributes as $attribute => $attributeConfig) {
            if (isset($attributeConfig['config'])
                && isset($attributeConfig['config'][$configProperty])
                && $attributeConfig['config'][$configProperty]
            ) {
                $result[$attribute] = $attributeConfig;
            }
        }
        return $result;
    }

    /**
     * @param $model
     * @param null|array $attributeList
     * @param string $eavProperty
     * @return array
     */
    public static function getLabelValuePairs($model, $attributeList = null, $eavProperty = 'eav')
    {
        $attributeList = $attributeList ?? $model->{$eavProperty}->getAttributesConfig();
        $attributes = [];
        foreach ($attributeList as $attr => $config) {
            $value = $model->{$attr};
            if (is_array($value)) {
                $items = isset($config['items']) ? array_map(function ($value) use ($config) {
                    return $config['items'][$value];
                }, $value) : $value;
                $value = implode(', ', $items);
            } else {
                $value = isset($config['items']) ? ($config['items'][$value] ?? '') : $value;
            }
            $attributes[] = [
                'label' => $config['name'],
                'value' => $value,
            ];
        }
        return $attributes;
    }
}