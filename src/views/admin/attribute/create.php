<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model nullref\eav\models\Attribute */

$this->title = Yii::t('eav', 'Create Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('eav', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('eav', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
