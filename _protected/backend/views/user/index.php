<?php
use common\helpers\CssHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('messages', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index card card-padding">
    <div class="card-header card-padding">
    <h1>
    <small>
        <?= Html::encode($this->title) ?>
    </small>
    <span class="pull-right">
        <?= Html::a(Yii::t('messages', 'Create User'), ['create', 'lang' => Yii::$app->language], ['class' => 'btn btn-success']) ?>
    </span>         

    </h1>
    </div>
    <div class="card-body card-padding">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"{pager}\n{items}",
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'email:email',
            // role
            [
                'attribute'=>'item_name',
                'filter' => $searchModel->rolesList,
                'value' => function ($data) {
                    return $data->roleName;
                },
                'contentOptions'=>function($model, $key, $index, $column) {

                    return ['class'=>CssHelper::roleCss($model->roleName)];
                }
            ],
            // status
            [
                'attribute'=>'status',
                'filter' => $searchModel->statusList,
                'value' => function ($data) {
                    return $data->statusName;
                },
                'contentOptions'=>function($model, $key, $index, $column) {
                    return ['class'=>CssHelper::statusCss($model->statusName)];
                }
            ],
            // buttons
            ['class' => 'yii\grid\ActionColumn',
            'header' => "Menu",
            'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $url .= '?lang='.Yii::$app->language;
                        return Html::a('', $url, ['title'=>Yii::t('messages', 'View user'), 
                            'class'=>'glyphicon glyphicon-eye-open']);
                    },
                    'update' => function ($url, $model, $key) {
                        $url .= '?lang='.Yii::$app->language;
                        return Html::a('', $url, ['title'=>Yii::t('messages', 'Manage user'), 
                            'class'=>'glyphicon glyphicon-user']);
                    },
                    'delete' => function ($url, $model, $key) {
                        $url .= '?lang='.Yii::$app->language;
                        if ($model->username == "admin") {
                            $url = "#";
                        }
                        return Html::a('', $url,
                        ['title'=>Yii::t('messages', 'Delete user'),
                            'class'=>'glyphicon glyphicon-trash',
                            'data' => [
                                'confirm' => Yii::t('messages', 'Are you sure you want to delete this user?'),
                                'method' => 'post']
                        ]);
                    }
                ]
            ], // ActionColumn
        ], // columns
    ]); ?>
    </div>
</div>
