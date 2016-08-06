<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\Pricesearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('messages', 'Prices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-index">

    <h1>

    <?= Html::encode($this->title) ?>

    <span class="pull-right">
        <?= Html::a(Yii::t('messages', 'Create Price Service'), ['create', 'lang' => Yii::$app->language], ['class' => 'btn btn-success']) ?>
    </span>         

    </h1>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,        
        'columns' => [
        //    ['class' => 'yii\grid\SerialColumn'],
            'rowid',
            'servicetype.servicename',
            'mainprice',
            'tax',
            'supplement',
            'maxguests',            
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            $url =  Url::to(['price/update', 'id'=>$model->Id, 'lang' => Yii::$app->language]);
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        },
                        'view' => function ($url, $model) {
                            $url =  Url::to(['price/view', 'id'=>$model->Id, 'lang' => Yii::$app->language]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        },
                        'delete' => function ($url, $model, $key) {
                            $url .= '?lang='.Yii::$app->language;
                            return Html::a('', $url,
                            ['title'=>Yii::t('messages', 'Delete Price Service'),
                                'class'=>'glyphicon glyphicon-trash',
                                'data' => [
                                    'confirm' => Yii::t('messages', 'Are you sure you want to delete this service?'),
                                    'method' => 'post']
                            ]);
                        }
                    ],
                ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
