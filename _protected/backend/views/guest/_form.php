<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="panel panel-success">
    <div class="panel-heading" style="color: white; font-size: 14px; padding: 10px 15px;">
     <?=  Yii::t('messages', 'Update Customer Details');?> 
    </div>
    <div class="panel-body">
        <div class="guest-form">

            <?php $form = ActiveForm::begin([
                'id' => 'guest-form',
                'enableAjaxValidation' => false,
            ]); ?>

            <?=Html::hiddenInput('Guest[Id]', $model->Id);?>

            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

            <?=Html::hiddenInput('Guest[email]', $model->email);?>

            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>

            <?=Html::hiddenInput('Guest[recurringcount]', $model->recurringcount);?>

            <?=Html::hiddenInput('Guest[paymentId]', $model->paymentId);?>

            <div class="form-group field-guest-country">
                <label class="control-label" for="guest-country"><?=Yii::t('messages', 'Country')?></label>
                <?php require_once 'country.php'; ?>
            </div>

            <?= $form->field($model, 'phonenumber')->textInput(['maxlength' => true]) ?>

            <br>

            <?= Html::submitButton($model->isNewRecord ? Yii::t('messages', 'CONFIRM BOOKING') : Yii::t('messages', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary  btn-update']) ?>
            <button class="btn btn-danger waves-effect delete-customer pull-right"><?=Yii::t('messages', 'Delete')?></button>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    ;(function($) {
        $(function () {
            $('#guest-country').val('<?=$model->country?>');

            $('.btn-update').click(function(e){
                e.preventDefault();
                if($('#guest-email').val() == "") return;
                $.ajax({
                    type: 'get',
                    url: '<?=Url::to(["guest/checkcustomerexistence"])?>',
                    data: {
                        email: $('#guest-email').val(),
                    },
                    success: function(msg) {
                        if (msg != null || msg != "") {
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
            
                        }
                    }
                });
            });

            $('.delete-customer').on('click', function(e){
                e.preventDefault();

                swal({   
                    title: "<?=Yii::t('messages', 'Are you sure?')?>",   
                    text: "<?=Yii::t('messages', 'You will not be able to recover this information!')?>",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "<?=Yii::t('messages', 'Yes')?>",   
                    cancelButtonText: "<?=Yii::t('messages', 'No')?>",   
                    closeOnConfirm: false,   
                    closeOnCancel: false 
                }, function(isConfirm){   
                    if (isConfirm) {
                        var id = '<?=$model->Id?>';
                         $.ajax({
                            type: 'POST',
                            url: '<?=Url::to(["guest/delete"])?>',
                            data: {id: id, _csrf: '<?=Yii::$app->request->getCsrfToken()?>'},
                            success: function(msg) {
                                location.href = '<?=Url::to(["guest/guestinfo"])?>';
                            }
                        }) 
                    } else {     
                        swal("<?=Yii::t('messages', 'Cancelled!')?>", "Current customer's information remains safe:)", "error");   
                    } 
                });
            });
        });
    })(jQuery);
</script>


