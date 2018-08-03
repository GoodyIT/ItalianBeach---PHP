<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = $model->Id;
$this->params['breadcrumbs'][] = $model->firstname . ' ' . $model->lastname;
?>
<div class="guest-view card">
<div class="card-body card-padding">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'firstname',
            'lastname',
            'email:email',
            'country',
            'phonenumber',
            'address',
            'recurringcount'
        ],
    ]) ?>
</div>
</div>
