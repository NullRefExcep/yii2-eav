<?php


namespace nullref\eav\events;


use yii\base\Event;

class BuildGridColumnConfigEvent extends Event
{
    public $column;
    public $code;
    public $attributeConfig;
    public $searchModel = null;
}