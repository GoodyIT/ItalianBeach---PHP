<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Price */

$this->title = Yii::t('messages', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Settings'), 'url' => ['update', 'id'=>1, 'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'propertytitle',
            'footertitle',
            'phonenumber',
            'email',
        ],
    ]) ?>

</div>
