<?php
use nenad\passwordStrength\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('messages', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup col-lg-5 card card-padding">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body card-padding">
        <p><b><?= Yii::t('messages', 'Please input your email and you will get the credential for Sunticket') ?></b></p>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <input type="hidden" name="SignupForm[password]" value="TestingMemories@2017">
            <input type="hidden" name="SignupForm[username]" class="username-input">
            <?= $form->field($model, 'email')?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('messages', 'Signup'), ['class' => 'btn btn-primary btn-signup', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
           $('.btn-signup').click(function(e){
                e.preventDefault();
                $('.username-input').val($('#signupform-email').val());
                $('#form-signup').submit();
           });
        });

</script>