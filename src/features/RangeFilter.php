<?php


namespace nullref\eav\features;


use nullref\core\widgets\ActiveRangeInputGroup;
use nullref\eav\events\BuildGridColumnConfigEvent;
use nullref\eav\Module;
use nullref\eav\types\Type;
use yii\base\Event;

class RangeFilter
{
    public static function setup(Module $module)
    {

        $module->registerAttributesConfigProperty('range_filter', function ($activeField) {
            return $activeField
                ->checkbox([], false)
                ->label(\Yii::t('eav', 'Range Filter'));
        });

        Event::on(Type::class, Type::EVENT_AFTER_BUILD_GRID_COLUMN_CONFIG,
            function (BuildGridColumnConfigEvent $event) {
                $config = $event->attributeConfig;
                $column = $event->column;

                if ($event->searchModel) {
                    if (isset($config['config']['range_filter']) && $config['config']['range_filter']) {
                        $column['filter'] = ActiveRangeInputGroup::widget([
                            'attributeFrom' => $column['attribute'] . '[from]',
                            'attributeTo' => $column['attribute'] . '[to]',
                            'model' => $searchModel,
                            'options' => [
                                'style' => 'min-width: 100px',
                            ],
                        ]);
                        $event->column = $column;
                    }
                }
            });
    }
}