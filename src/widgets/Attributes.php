<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\widgets;


use nullref\eav\models\Attribute as AttributeModel;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class Attributes extends Widget
{
    /** @var  ActiveForm */
    public $form;

    public $model;

    public $itemWrapClass = 'col-md-6';

    public function run()
    {
        $fields = [];
        foreach ($this->model->eav->getAttributesConfig() as $attribute => $config) {
            $field = $this->form->field($this->model, $attribute);

            $type = ArrayHelper::remove($config, 'type');

            switch ($type) {
                case AttributeModel::TYPE_OPTION:
                    $fields[] = $field->dropDownList(ArrayHelper::remove($config, 'items', []),
                        ArrayHelper::remove($config, 'options', []));
                    break;
                case AttributeModel::TYPE_INT:
                case AttributeModel::TYPE_DECIMAL:
                    $fields[] = $field->textInput(ArrayHelper::remove($config, 'options', []));
                    break;
                case AttributeModel::TYPE_STRING:
                case AttributeModel::TYPE_IMAGE:
                case AttributeModel::TYPE_URL:
                    $fields[] = $field->textInput(ArrayHelper::remove($config, 'options', []));
                    break;
                case AttributeModel::TYPE_TEXT:
                    $fields[] = $field->textarea(ArrayHelper::remove($config, 'options', []));
                    break;
                default:
                    $fields[] = "$type is not supported";
            }
        }

        return $this->render('attributes', [
            'fields' => $fields,
            'itemWrapClass' => $this->itemWrapClass,
        ]);
    }
}