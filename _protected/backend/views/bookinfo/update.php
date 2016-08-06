<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Bookinfo */

$this->title = Yii::t('messages', 'Update {modelClass}: ', [
    'modelClass' => 'Bookinfo',
]) . $model->seat;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Bookinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Update');
?>
<div class="bookinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
