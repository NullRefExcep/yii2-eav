<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2020 NRE
 */


namespace nullref\eav\helpers;


use nullref\eav\models\Entity;

class DetailViewAttributes
{
    /**
     * @deprecated use \nullref\eav\helpers\Attributes::getLabelValuePairs instead
     * @param Entity $eavModel
     * @return array
     */
    public static function get(Entity $eavModel)
    {
        $attributes = [];
        foreach ($eavModel->getAttributesConfig() as $attr => $config) {
            $value = $eavModel->{$attr};
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
