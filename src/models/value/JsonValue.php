<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\eav\models\value;


use nullref\eav\models\Value;
use nullref\useful\behaviors\JsonBehavior;

class JsonValue extends TextValue
{
    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(Value::rules(), [
            ['value', 'safe'],
        ]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'json' => [
                'class' => JsonBehavior::class,
                'fields' => ['value'],
            ],
        ];
    }
}