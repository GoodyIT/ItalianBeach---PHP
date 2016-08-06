<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
/* @var $form yii\widgets\ActiveForm */

?>
    
<script src="<?=Yii::getAlias('@web')?>/js/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
<script src="<?=Yii::getAlias('@web')?>/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
<link href="<?=Yii::getAlias('@web')?>/js/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
<link href="<?=Yii::getAlias('@web')?>/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">

<div class="panel panel-success">
    <div class="panel-heading" style="color: white; font-size: 14px; padding: 10px 15px;">
     <?=  Yii::t('messages', 'Enter Your Details');?> 
    </div>
    <div class="panel-body">
        <div class="guest-form">

            <?php $form = ActiveForm::begin([
                'id' => 'guest-form',
                'enableAjaxValidation' => false,
            ]); ?>
            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

            <?=Html::hiddenInput('Guest[recurringcount]', $model->recurringcount);?>

            <div class="form-group field-guest-country">
                <label class="control-label" for="guest-country"><?=Yii::t('messages', 'Country')?></label>
                <?php require_once 'country.php'; ?>
                <div class="help-block"></div>
            </div>

            <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => true]) ?>

            <br>

            <?= Html::submitButton($model->isNewRecord ? Yii::t('messages', 'CONFIRM BOOKING') : Yii::t('messages', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-create' : 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    ;(function($) {
        $(document).ready(function() {
            $('.btn-create').click(function(e){
                e.preventDefault();
                if($('#guest-email').val() == "") return;
                $.ajax({
                    type: 'get',
                    url: '<?=Url::to(["guest/checkcustomerexistence"])?>',
                    data: {
                        email: $('#guest-email').val(),
                    },
                    success: function(msg) {
                        if (msg == 1) {
                            swal({   
                                title: "<?=Yii::t('messages', 'Are you sure?')?>",   
                                text: "<?=Yii::t('messages', 'The customer with the same email exists!')?>" + "<?=Yii::t('messages', 'This action will update the existing information.')?>",   
                                type: "warning",   
                                showCancelButton: true,   
                                confirmButtonColor: "#DD6B55",   
                                confirmButtonText: "<?=Yii::t('messages', 'Yes')?>",   
                                cancelButtonText: "<?=Yii::t('messages', 'No')?>",   
                                closeOnConfirm: false,   
                                closeOnCancel: false 
                            }, function(isConfirm){   
                                if (isConfirm) {
                                       jQuery('#guest-form').yiiActiveForm('submitForm'); 
                                } else {     
                                    swal("<?=Yii::t('messages', 'Cancelled!')?>", "<?=Yii::t('messages', 'The information of the current customer remains safe')?>", "error");   
                                } 
                            });            
                        } else {
                            jQuery('#guest-form').yiiActiveForm('submitForm'); 
                        }
                    }
                });
            });
        });
    })(jQuery); 
</script>

