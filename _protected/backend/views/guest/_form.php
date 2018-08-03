<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
/* @var $form yii\widgets\ActiveForm */

$lang = Yii::$app->language;
?>

<div class="panel panel-success">
    <div class="panel-heading" style="color: white; font-size: 14px; padding: 10px 15px;">
     <?=  Yii::t('messages', 'Update Customer Details');?> 
    </div>
    <div class="panel-body">
        <div class="guest-form">

           <form id="guest-form" action="<?=Url::to(['guest/create', 'lang' => $lang])?>" method="POST" data-toggle="validator" role="form">

            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

            <?=Html::hiddenInput('Guest[Id]', $model->Id);?>

            <input type="text" name="Guest[firstname]" value="<?=$model->firstname?>">

            <input type="text" name="Guest[lastname]" value="<?=$model->lastname?>">

            <input type="text" name="Guest[lastname]" value="<?=$model->lastname?>">

            <?=Html::hiddenInput('Guest[email]', $model->email);?>

            <input type="text" name="Guest[address]" value="<?=$model->address?>">

            <input type="text" name="Guest[city]" value="<?=$model->city?>">

            <input type="text" name="Guest[zipcode]" value="<?=$model->zipcode?>">

            <div class="form-group field-guest-country">
                <label class="control-label" for="guest-country"><?=Yii::t('messages', 'Country')?></label>
                <?php require_once 'country.php'; ?>
            </div>

            <input type="text" name="Guest[phonenumber]" value="<?=$model->phonenumber?>">
            <br>

            <?= Html::submitButton($model->isNewRecord ? Yii::t('messages', 'CONFIRM BOOKING') : Yii::t('messages', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary  btn-update']) ?>
            <button class="btn btn-danger waves-effect delete-customer pull-right"><?=Yii::t('messages', 'Delete')?></button>
            </form>
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


