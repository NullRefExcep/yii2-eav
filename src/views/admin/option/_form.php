<?php

use nullref\eav\models\Attribute;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model nullref\eav\models\attribute\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->attribute_id): ?>
        <?= $form->field($model, 'attribute_id', [
            'options' => ['class' => 'empty-class'],
            'errorOptions' => ['class' => 'empty-class'],
        ])->hiddenInput() ?>

        <?= $form->field($model, 'attributeRecord[name]')->textInput(['readonly' => true])->label(false) ?>
    <?php else: ?>
        <?= $form->field($model, 'attribute_id')->dropDownList(Attribute::find()
            ->andWhere(['type' => \nullref\eav\models\Types::TYPE_OPTION])->getMap()) ?>
    <?php endif ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'value')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('eav', 'Create') : Yii::t('eav', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
