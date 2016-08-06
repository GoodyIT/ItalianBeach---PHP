<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Setting */

$this->title = Yii::t('messages', 'Update Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Settings'), 'url' => ['update', 'id'=>1, 'lang' => Yii::$app->language]];
?>
<div class="setting-update card col-lg-5">
<div class="card-body card-padding">
    <div class="row">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
    </div>    
</div>
</div>
