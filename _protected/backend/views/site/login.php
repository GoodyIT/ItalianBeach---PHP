<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('messages', 'Login');
?>
<div class="site-login card col-lg-5">
<div class="card-header">
    <h1 class="login"><?= Html::encode($this->title) ?></h1>
</div>
<div class="card-body card-padding">

        <p><?= Yii::t('messages', 'Please fill out the following fields to login:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?php //-- use email or username field depending on model scenario --// ?>
        <?php if ($model->scenario === 'lwe'): ?>
            <?= $form->field($model, 'email') ?>        
        <?php else: ?>
            <?= $form->field($model, 'username') ?>
        <?php endif ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox(['style'=>['opacity'=> 1]]) ?>
        <?= Html::a(Yii::t('messages', 'Have you forgot your password?'), Url::to(['site/request-password-reset', 'lang' => Yii::$app->language]))?>
        <br />
        <div class="form-group">
            <?= Html::submitButton(Yii::t('messages', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

  </div>
</div>
