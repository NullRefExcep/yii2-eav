<?php

use nullref\eav\models\Attribute;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel nullref\eav\models\attribute\OptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('eav', 'Options');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-index">

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?= Html::encode($this->title) ?>
        </h1>
    </div>
</div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('eav', 'Create Option'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'attribute_id',
                'filter' => Attribute::find()->withOptions()->getMap(),
                'format' => 'attribute',
            ],
            'sort_order',
            'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
