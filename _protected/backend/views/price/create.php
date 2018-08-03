<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Price */

$this->title = Yii::t('messages', 'Create Service Type', [
    'modelClass' => 'Price',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Create');
?>
<div class="price-create card">
<div class="card-header">

    <h2 class="f-500"><?= Html::encode($this->title) ?></h2>
</div>
<div class="card-body card-padding">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
