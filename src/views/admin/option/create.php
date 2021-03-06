<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model nullref\eav\models\attribute\Option */

$this->title = Yii::t('eav', 'Create Option');
$this->params['breadcrumbs'][] = ['label' => Yii::t('eav', 'Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('eav', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
        <?php if ($model->attribute_id): ?>
            <?= Html::a(Yii::t('eav', 'Attribute'), ['/eav/admin/attribute/view', 'id' => $model->attribute_id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
