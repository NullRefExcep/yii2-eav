<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2017 NRE
 */


namespace nullref\eav\widgets;


use nullref\eav\components\TypesManager;
use yii\base\Widget;
use yii\widgets\ActiveForm;

class Attributes extends Widget
{
    /** @var  ActiveForm */
    public $form;

    /** @var */
    public $model;

    /** @var string */
    public $itemWrapClass = 'col-md-6';

    /**
     * To override default model attributes
     * @var array
     */
    public $attributes = null;

    /**
     * @return string
     */
    public function run()
    {
        $fields = [];
        $attributes = $this->model->eav->getAttributesConfig();
        if (is_array($this->attributes)) {
            $attributes = $this->attributes;
        }
        foreach ($attributes as $attribute => $config) {
            $inputClass = TypesManager::get()->getType($config['type'])->getInputClass();
            /** @var AttributeInput $widget */
            $widget = new $inputClass([
                'config' => $config,
                'form' => $this->form,
                'model' => $this->model,
                'attribute' => $attribute,
            ]);
            $fields[] = $widget->run();
        }

        return $this->render('attributes', [
            'fields' => $fields,
            'itemWrapClass' => $this->itemWrapClass,
        ]);
    }
}
