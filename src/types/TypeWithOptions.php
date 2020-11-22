<?php


namespace nullref\eav\types;


class TypeWithOptions extends Type
{
    /**
     * @return bool
     */
    public function hasOptions()
    {
        return true;
    }

    /**
     * @param $rawValue
     * @param $attributeConfig
     * @return mixed|string
     */
    public function getDisplayFunction($attributeCode, $attributeConfig)
    {
        return function ($model) use ($attributeCode, $attributeConfig) {
            $value = $model->{$attributeCode};
            if (!is_array($value)) {
                $value = [$value];
            }
            $items = array_map(function ($value) use ($attributeConfig) {
                return $attributeConfig['items'][$value];
            }, array_filter($value));
            return implode(', ', $items);
        };
    }
}