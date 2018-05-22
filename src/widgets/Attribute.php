<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\widgets;


use nullref\eav\models\Attribute as AttributeModel;
use unclead\multipleinput\MultipleInput;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

class Attribute extends Widget
{
    /** @var mixed */
    public $config;

    /** @var ActiveField */
    public $field;

    /**
     * @return $this|string
     */
    public function run()
    {
        $config = $this->config;

        $field = $this->field;

        $type = ArrayHelper::remove($config, 'type');

        switch ($type) {
            case AttributeModel::TYPE_OPTION:
                return $field->dropDownList(ArrayHelper::remove($config, 'items', []),
                    ArrayHelper::remove($config, 'options', ['prompt' => '']))->label($config['name']);
                break;
            case AttributeModel::TYPE_INT:
            case AttributeModel::TYPE_DECIMAL:
                return $field->textInput(ArrayHelper::remove($config, 'options', []))->label($config['name']);
                break;
            case AttributeModel::TYPE_STRING:
            case AttributeModel::TYPE_IMAGE:
            case AttributeModel::TYPE_URL:
                return $field->textInput(ArrayHelper::remove($config, 'options', []))->label($config['name']);
                break;
            case AttributeModel::TYPE_TEXT:
                return $field->textarea(ArrayHelper::remove($config, 'options', []))->label($config['name']);
                break;
            case AttributeModel::TYPE_JSON:
                return $field->widget(ArrayHelper::remove($config, 'widget_class', MultipleInput::class),
                    ArrayHelper::remove($config, 'widget_options', []))->label($config['name']);
                break;
            default:
                return "$type is not supported";
        }
    }


}