<?php


namespace nullref\eav\helpers;

class Attributes
{
    public static function getWithEnabledConfig($attributes, $configProperty)
    {
        $result = [];
        foreach ($attributes as $attribute => $attributeConfig) {
            if (isset($attributeConfig['config'])
                && isset($attributeConfig['config'][$configProperty])
                && $attributeConfig['config'][$configProperty]
            )
                $result[$attribute] = $attributeConfig;
        }
        return $result;
    }
}