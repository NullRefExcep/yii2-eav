<?php


namespace nullref\eav\features;


use nullref\eav\events\BuildGridColumnConfigEvent;
use nullref\eav\Module;
use nullref\eav\types\Type;
use yii\base\Event;

class Select2
{
    public static function setup(Module $module)
    {
        Event::on(Type::class, Type::EVENT_AFTER_BUILD_GRID_COLUMN_CONFIG,
            function (BuildGridColumnConfigEvent $event) {
                $config = $event->attributeConfig;
                $column = $event->column;

                if ($event->searchModel) {
                    if (isset($attributeConfig['items'])) {
                        $column['filter'] = \kartik\select2\Select2::widget([
                            'data' => $attributeConfig['items'],
                            'attribute' => $column['attribute'],
                            'options' => ['placeholder' => ' '],
                            'model' => $searchModel,
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]);
                        $event->column = $column;
                    }
                }

            });
    }
}