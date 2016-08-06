<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Price */

$this->title = $model->rowid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Prices'), 'url' => ['index', 'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-view card">
<div class="card-header">
    <p>
        <?= Html::a(Yii::t('messages', 'Entire list'), ['index', 'lang' => Yii::$app->language], ['class' => 'btn btn-primary']) ?>
       
    </p>
</div>
<div class="card-body card-padding">
    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'rowid',
            'servicetype.servicename',
            'mainprice',
            'tax',
            'supplement',
            'maxguests',
        ],
    ]) ?>
</div>
</div>
