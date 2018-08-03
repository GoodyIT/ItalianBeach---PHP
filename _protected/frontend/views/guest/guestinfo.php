<?php
    use yii\helpers\Url; 
?>

<div class="row">
	<div class="col-xs-12">
		<div class="col-md-4">
			<form id="guest-form" class="prenotazione step_1" action="<?=Url::to(['guest/create', 'lang' => Yii::$app->language])?>" method="POST" data-toggle="validator" role="form">
	            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	            <input type="hidden" name="PendingGuest[token]" value="<?=$token?>">
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="email"><label for="inputName" class="control-label"><?=Yii::t('messages', 'First Name')?></label></label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="PendingGuest[firstname]" id="inputName" data-error="<?=Yii::t('messages', 'First Name')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
					<div class="help-block with-errors"></div>
				</div>
			  </div>
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="inputName" class="control-label"><?=Yii::t('messages', 'Last Name')?></label>
				</div>
				<div class="col-sm-8">
					 <input type="text" class="form-control" name="PendingGuest[lastname]" id="inputName" data-error="<?=Yii::t('messages', 'Last Name')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
					 <div class="help-block with-errors"></div>
				</div>
			  </div>
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="inputEmail" class="control-label"><?=Yii::t('messages', 'Email')?></label>
				</div>
				<div class="col-sm-8">
					<input type="email" class="form-control" name="PendingGuest[email]" id="inputEmail"data-error="<?=Yii::t('messages', 'Email is not a valid email address')?>" required>
					<div class="help-block with-errors"></div>
				</div>
			  </div>
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="inputName" class="control-label"><?=Yii::t('messages', 'Address')?></label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="PendingGuest[address]" id="inputName"  data-error="<?=Yii::t('messages', 'Address')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
					<div class="help-block with-errors"></div>
				</div>
			  </div>
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="inputName" class="control-label"><?=Yii::t('messages', 'City')?></label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="PendingGuest[city]" id="inputName" data-error="<?=Yii::t('messages', 'City')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
					<div class="help-block with-errors"></div>
				</div>
			  </div>
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="inputName" class="control-label"><?=Yii::t('messages', 'Zip Code')?></label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="PendingGuest[zipcode]" id="inputName" data-error="<?=Yii::t('messages', 'Zip Code')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
					<div class="help-block with-errors"></div>
				</div>
			  </div>
			 
			  <div class="form-group ">
				<div class="col-sm-4">
					<label for="inputName" class="control-label"><?=Yii::t('messages', 'Phone Number')?></label>
				</div>
				<div class="col-sm-8">
					 <input type="text" class="form-control" name="PendingGuest[phonenumber]" id="inputName"  data-error="<?=Yii::t('messages', 'Phone Number')?> <?=Yii::t('messages', 'cannot be blank.')?>" required>
					 <div class="help-block with-errors"></div>
				</div>
			  </div>
			  	<div class="form-group " style="overflow:visible">
					<div class="col-sm-4">
						<label class="control-label" for="pendingguest-country"><?=Yii::t('messages', 'Country')?></label>
					</div>
					<div class="col-sm-8" >
						<?php require_once 'country.php'; ?>
					</div>
				</div>
				<br>
				<br>
				<br>
			  	<div class="logo_sx align-right p-l-25" >
					<button type="submit" class="btn btn-success "><?=Yii::t('messages', 'Proceed to booking')?></button>
				</div>
			    <div class=" col-xs-12 col-sm-8 col-md-offset-4  align-right ">
				</div>
			</form>
		</div>
		<div class="col-md-8">
			<div class="card card-padding">
				<div class="card-header">
					<h3><?=Yii::t('messages', 'Total Price')?>(€) <span class="label label-success total-price"><?=$totalPrice?></span></h3>
				</div>
				<?=$this->render('cartinfo', ['price' => $price, 'myCart' =>$myCart])?>
			</div>
		</div>
	</div>	
</div>	

