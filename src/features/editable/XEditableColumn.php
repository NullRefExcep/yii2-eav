<?php


namespace nullref\eav\features\editable;


use mcms\xeditable\XEditableColumn as BaseXEditableColumn;
use yii\helpers\ArrayHelper;

class XEditableColumn extends BaseXEditableColumn
{

    /**
     * @inheritdoc
     */
    protected function getDataCellContent($model, $key, $index)
    {
        if (empty($this->url)) {
            $this->url = \Yii::$app->urlManager->createUrl($_SERVER['REQUEST_URI']);
        }

        if (empty($this->value)) {
            $value = ArrayHelper::getValue($model, $this->attribute);
        } else {
            $value = call_user_func($this->value, $model, $index, $this);
        }

        $var = $model->{$this->attribute};
        if (is_array($var)) {
            $var = implode(',', $var);
        }
        $value = '<a href="#" data-name="' . $this->attribute . '" data-value="' . $var . '"  class="editable" data-type="' . $this->dataType . '" data-pk="' . $model->{$this->pk} . '" data-url="' . $this->url . '" data-title="' . $this->dataTitle . '">' . $value . '</a>';

        return $value;
    }
}