<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PDF */

$this->title = Yii::t('messages', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Customers Info'), 'url' => ['guest/guestinfo', 'lang' => Yii::$app->language]];
?>
<div class="pdf-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
