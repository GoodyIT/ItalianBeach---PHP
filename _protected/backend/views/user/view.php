<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Users'), 'url' => ['index', 'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view card">
<div class="card-header">
    <h1><?= Html::encode($this->title) ?>

    <div class="pull-right">
        <?= Html::a(Yii::t('messages', 'Back'), ['index', 'lang' => Yii::$app->language], ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('messages', 'Update'), ['update', 'id' => $model->id, 'lang' => Yii::$app->language], [
            'class' => 'btn btn-primary']) ?>

    </div>

    </h1>
</div>
<div class="card-body card-padding">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          //  'id',
            'username',
            'email:email',
            //'password_hash',
            [
                'attribute'=>'status',
                'value' => $model->getStatusName(),
            ],
          /*  [
                'attribute'=>'item_name',
                'value' => $model->getRoleName(),
            ],*/
            //'auth_key',
            //'password_reset_token',
            //'account_activation_token',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>
</div>
</div>
