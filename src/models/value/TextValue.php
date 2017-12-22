<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\models\value;


class TextValue extends StringValue
{
    public static function tableName()
    {
        return '{{%eav_entity_value_text}}';
    }
}