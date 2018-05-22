<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\widgets;


use yii\base\Widget;
use yii\widgets\ActiveForm;

class Attributes extends Widget
{
    /** @var  ActiveForm */
    public $form;

    /** @var  */
    public $model;

    /** @var string  */
    public $itemWrapClass = 'col-md-6';

    /**
     * @return string
     */
    public function run()
    {
        $fields = [];
        foreach ($this->model->eav->getAttributesConfig() as $attribute => $config) {
            $field = $this->form->field($this->model, $attribute);
            $widget = new Attribute([
                'config' => $config,
                'field' => $field,
            ]);
            $fields[] = $widget->run();
        }

        return $this->render('attributes', [
            'fields' => $fields,
            'itemWrapClass' => $this->itemWrapClass,
        ]);
    }
}