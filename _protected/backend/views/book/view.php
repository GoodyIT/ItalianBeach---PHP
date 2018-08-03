<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Book */

$this->title = $model->sunshadeseat;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Books'), 'url' => ['bookinfo/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sunshadeseat',
            'checkin',
            'servicetype',
            /*'attributes' => [
                    'label'  =>
                    'value'  => function ($data) {
                        return "sdf";
                    },
                    'filter' => '',
                ],*/
            'price',
            'mainprice',
            'tax',
            'supplement',
            'guests',
            'bookstate',
        ],
    ]) ?>

</div>
