<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\helpers;


use nullref\eav\models\Attribute;
use nullref\eav\models\Entity;
use nullref\eav\widgets\ActiveRangeInputGroup;
use kartik\select2\Select2;
use mcms\xeditable\XEditableColumn;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

class Grid
{
    /**
     * @param Model $searchModel
     * @param string $property
     * @return array
     */
    public static function getGridColumns(Model $searchModel, $property = 'eav')
    {
        /** @var Entity $entity */
        $entity = $searchModel->$property;

        return self::getGridColumnsInternal($entity, $searchModel);
    }

    /**
     * @param Entity $entity
     * @param null $searchModel
     * @return array
     */
    public static function getGridColumnsInternal(Entity $entity, $searchModel = null)
    {
        $result = [];

        foreach ($entity->getAttributesConfig() as $code => $item) {
            if (!$item['config']['show_in_grid']) {
                continue;
            }
            $column = [
                'attribute' => $code,
            ];
            if (isset($item['config']['editable']) && $item['config']['editable']) {
                $column = self::getEditableConfig($column, $item);
            }

            $column = self::addFilter($column, $item, $searchModel);
            $column = self::addValue($column, $item);
            $result[$code] = $column;
        }

        return $result;
    }

    public static function addValue($column, $item)
    {
        if (isset($item['items'])) {
            $column['value'] = function ($model) use ($column, $item) {
                if ($model->{$column['attribute']}) {
                    return $item['items'][$model->{$column['attribute']}];
                }
                return '';
            };
        }
        if ($item['type'] === 'image') {

            $column['format'] = 'raw';
            $column['value'] = function ($model) use ($column, $item) {
                $url = $model->{$column['attribute']};
                \newerton\fancybox\FancyBox::widget([
                    'target' => 'a[rel=fancybox]',
                    'helpers' => true,
                    'mouse' => true,
                    'config' => [
                        'maxWidth' => '90%',
                        'maxHeight' => '90%',
                        'playSpeed' => 7000,
                        'padding' => 0,
                        'fitToView' => false,
                        'width' => '70%',
                        'height' => '70%',
                        'autoSize' => false,
                        'closeClick' => false,
                        'openEffect' => 'elastic',
                        'closeEffect' => 'elastic',
                        'prevEffect' => 'elastic',
                        'nextEffect' => 'elastic',
                        'closeBtn' => false,
                        'openOpacity' => true,
                        'helpers' => [
                            'title' => ['type' => 'float'],
                            'buttons' => [],
                            'thumbs' => ['width' => 68, 'height' => 50],
                            'overlay' => [
                                'css' => [
                                    'background' => 'rgba(0, 0, 0, 0.8)'
                                ]
                            ]
                        ],
                    ]
                ]);
                if ($url) {
                    return Html::a(Html::img($url, ['width' => 50]), $url, ['rel' => 'fancybox']);
                }
                return '';
            };
        }
        if ($item['type'] === 'url') {
            $column['format'] = 'raw';
            $column['value'] = function ($model) use ($column, $item) {
                $url = $model->{$column['attribute']};
                if ($url) {
                    return Html::a(StringHelper::truncate($url, 20), $url, ['target' => '_blank']);
                }
                return '';
            };
        }
        return $column;
    }

    public static function addFilter($column, $item, $searchModel)
    {
        if ($searchModel) {
            if (isset($item['items'])) {
                $column['filter'] = Select2::widget([
                    'data' => $item['items'],
                    'attribute' => $column['attribute'],
                    'options' => ['placeholder' => ' '],
                    'model' => $searchModel,
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
            }
            $column['headerOptions'] = [
                'style' => 'min-width: 100px',
            ];
            if (in_array($item['type'], [Attribute::TYPE_INT, Attribute::TYPE_DECIMAL])) {

                $column['filter'] = ActiveRangeInputGroup::widget([
                    'attributeFrom' => $column['attribute'] . '[from]',
                    'attributeTo' => $column['attribute'] . '[to]',
                    'model' => $searchModel,
                    'options' => [
                        'style' => 'min-width: 100px',
                    ],
                ]);
            }
        }
        return $column;
    }

    protected static function getEditableConfig($column, $item)
    {
        $column['class'] = XEditableColumn::class;
        $column['editable'] = [];
        $column['url'] = Url::to(['editable']);
        $column['format'] = 'raw';

        $column['dataType'] = 'text';

        if (isset($item['items'])) {
            $column['dataType'] = 'select';
            $column['editable'] = [
                'source' => $item['items']
            ];
        }
        return $column;
    }
}