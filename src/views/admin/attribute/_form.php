<?php

use nullref\eav\models\Attribute;
use nullref\eav\models\attribute\Set;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model nullref\eav\models\Attribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attribute-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true]) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'type')->dropDownList(Attribute::getTypesMap()) ?>

        <?= $form->field($model, 'set_id')->dropDownList(Set::getMap()) ?>
    <?php else: ?>
        <?= $form->field($model, 'config[show_in_grid]')
            ->checkbox([], false)
            ->label(Yii::t('eav', 'Show in grid')) ?>
        <?= $form->field($model, 'config[read_only]')
            ->checkbox([], false)
            ->label(Yii::t('eav', 'Read only')) ?>
        <?= $form->field($model, 'config[editable]')
            ->checkbox([], false)
            ->label(Yii::t('eav', 'Editable')) ?>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('eav', 'Create') : Yii::t('eav', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
