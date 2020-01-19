<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model nullref\eav\models\attribute\Option */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('eav', 'Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-view">

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
        <?= Html::a(Yii::t('eav', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('eav', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('eav', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'attribute_id',
            'sort_order',
            'value',
        ],
    ]) ?>

</div>
