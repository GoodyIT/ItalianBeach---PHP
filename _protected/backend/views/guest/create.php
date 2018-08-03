<?php  
	use yii\helpers\Html;
	use yii\helpers\Url;

	$lang = Yii::$app->language;
	$isRecursive = !empty($guest) && isset($guest['firstname']);
?>

<script type="text/javascript">
   	var allowSubmit = false;

	function checkEmailExistence(){
        $.ajax({
            type: 'get',
            url: '<?=Url::to(["guest/checkcustomerexistence"])?>',
            data: {
                email: $('.guest-email').val(),
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
                        	allowSubmit = true;
                            $('#guest-form').submit(); 

                        } else {     
                            swal("<?=Yii::t('messages', 'Cancelled!')?>", "<?=Yii::t('messages', 'The information of the current customer remains safe')?>", "error");   
                            return false;
                        } 
                    });
                } else {
					allowSubmit = true;
                    $('#guest-form').submit(); 
                }
            }
        });
    }
;(function($) {
    $(function () {

	    $('body').on('submit', '#guest-form', function (e) {
	    	if (!allowSubmit) {
     		 	if (e.isDefaultPrevented()) {
				    console.log('invalid');
				} else {
				  	e.preventDefault();
				    checkEmailExistence();
				}
			}
     	});
    });
})(jQuery);
</script>

<div class="card">
	<form id="guest-form" action="<?=Url::to(['guest/create', 'lang' => $lang])?>" method="POST" data-toggle="validator" role="form">
<div class="card-header">
	<button type="submit" class="btn btn-success confirmbook text-center pull-right"><?=Yii::t('messages', 'CONFIRM BOOKING')?></button>
</div>
<div class="card-body card-padding">

	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

	<input type="hidden" name="lang" value="<?=Yii::$app->language?>">
	<div class="form-group has-feedback">
	    <label for="inputName" class="control-label"><?=Yii::t('messages', 'First Name')?></label>
	    <div class="fg-line">
	    	<input type="text" class="form-control" name="Guest[firstname]" id="inputName" data-error="<?=Yii::t('messages', 'First Name')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>

	<div class="form-group has-feedback">
	    <label for="inputName" class="control-label"><?=Yii::t('messages', 'Last Name')?></label>
	    <div class="fg-line">
	    	<input type="text" class="form-control" name="Guest[lastname]" id="inputName" data-error="<?=Yii::t('messages', 'Last Name')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>
	<div class="form-group has-feedback">
	    <label for="inputEmail" class="control-label"><?=Yii::t('messages', 'Email')?></label>
	    <div class="fg-line">
	    	<input type="email" class="form-control guest-email" name="Guest[email]" id="inputEmail"data-error="<?=Yii::t('messages', 'Email is not a valid email address')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>

	<div class="form-group has-feedback">
	    <label for="inputName" class="control-label"><?=Yii::t('messages', 'Address')?></label>
	    <div class="fg-line">
	    	<input type="text" class="form-control" name="Guest[address]" id="inputName"  data-error="<?=Yii::t('messages', 'Address')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>

	<div class="form-group has-feedback">
	    <label for="inputName" class="control-label"><?=Yii::t('messages', 'City')?></label>
	    <div class="fg-line">
	    	<input type="text" class="form-control" name="Guest[city]" id="inputName" data-error="<?=Yii::t('messages', 'City')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>

	<div class="form-group has-feedback">
	    <label for="inputName" class="control-label"><?=Yii::t('messages', 'Zip Code')?></label>
	    <div class="fg-line">
	    	<input type="text" class="form-control" name="Guest[zipcode]" id="inputName" data-error="<?=Yii::t('messages', 'Zip Code')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>

	<div class="form-group field-guest-country has-feedback">
	    <label class="control-label" for="guest-country"><?=Yii::t('messages', 'Country')?></label>
	    <?php require_once 'country.php'; ?>
	</div>

	<div class="form-group has-feedback">
	    <label for="inputName" class="control-label"><?=Yii::t('messages', 'Phone Number')?></label>
	    <div class="fg-line">
	    	<input type="text" class="form-control" name="Guest[phonenumber]" id="inputName"  data-error="<?=Yii::t('messages', 'Phone Number')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
	    </div>
	    <div class="help-block with-errors"></div>
	    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	</div>
	</div>
	</form>
</div>