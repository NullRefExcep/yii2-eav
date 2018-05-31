<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model nullref\eav\models\Attribute */
/* @var $optionSearchModel nullref\eav\models\attribute\OptionSearch */
/* @var $optionsDataProvider \yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('eav', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-view">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('eav', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
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
            'name',
            'code',
            'type:attributeType',
            'set_id:set',
        ],
    ]) ?>

    <?php if ($model->hasOptions()): ?>

        <p>
            <?= Html::a(Yii::t('eav', 'Create Option'), ['/eav/admin/option/create', 'attribute_id' => $model->id], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $optionsDataProvider,
            'filterModel' => $optionSearchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'sort_order',
                'value',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'controller' => '/eav/admin/option',
                ],
            ],
        ]); ?>
    <?php endif ?>

</div>
