<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Price */

$this->title = Yii::t('messages', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-view card card-padding">

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
