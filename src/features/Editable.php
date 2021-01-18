<?php


namespace nullref\eav\features;


use nullref\eav\events\BuildGridColumnConfigEvent;
use nullref\eav\features\editable\XEditableColumn;
use nullref\eav\Module;
use nullref\eav\types\Type;
use Yii;
use yii\base\Event;
use yii\helpers\Url;

class Editable
{
    public static function setup(Module $module)
    {

        $module->registerAttributesConfigProperty('editable', function ($activeField) {
            return $activeField
                ->checkbox([], false)
                ->label(Yii::t('eav', 'Editable'));
        });

        Event::on(Type::class, Type::EVENT_AFTER_BUILD_GRID_COLUMN_CONFIG,
            function (BuildGridColumnConfigEvent $event) {
                $config = $event->attributeConfig;
                $column = $event->column;
                if (isset($config['config']['editable']) && $config['config']['editable']) {
                    $column['class'] = XEditableColumn::class;
                    $column['editable'] = [];
                    $column['url'] = Url::to(['editable']);
                    $column['format'] = 'raw';

                    $column['dataType'] = 'text';

                    if (isset($config['items'])) {
                        $column['dataType'] = 'select';
                        $column['editable'] = [
                            'source' => $config['items']
                        ];
                    }
                }

                $event->column = $column;
            });
    }
}