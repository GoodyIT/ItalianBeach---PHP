<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Price */

$this->title = Yii::t('messages', 'Update {modelClass}: ', [
    'modelClass' => 'Price',
]) . " Row " . $model->rowid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Update');
?>
<div class="price-update card">
<div class="card-header">

    <h1><?= Html::encode($this->title) . " (" . Yii::$app->params['servicetype'][$model->servicetype_Id]  .")" ?></h1>
</div>
<div class="card-body card-padding">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
