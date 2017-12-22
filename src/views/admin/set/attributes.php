<?php

use nullref\eav\models\Attribute;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model nullref\eav\models\attribute\Set */
/* @var $searchModel nullref\eav\models\AttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('eav', 'Attributes of set: {set}', ['set' => $model->name]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('eav', 'Create Attribute'), ['admin/attribute/create', 'set_id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'code',
            [
                'attribute' => 'type',
                'filter' => Attribute::getTypesMap(),
                'format' => 'attributeType',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'admin/attribute',
            ],
        ],
    ]); ?>

</div>
