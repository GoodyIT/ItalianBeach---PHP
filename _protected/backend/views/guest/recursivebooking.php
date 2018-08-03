<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\Url;

	 $lang = Yii::$app->language;
 
    $this->title = Yii::t('messages', 'Recursive Booking');
    $this->params['breadcrumbs'][] = Yii::t('messages', 'Room Booking');
   
   	$isRecursive = !empty($guest) && isset($guest['firstname']);
    if ($isRecursive) {
    	$this->params['breadcrumbs'][] = Yii::t('messages', 'Recursive Booking');
        $this->params['breadcrumbs'][] = $guest['firstname'] . ' ' . $guest['lastname'];
    } 
?>

<script type="text/javascript">
    var priceLists = <?= json_encode($priceLists)?>;
    var allSunshades = <?=json_encode($allSunshades)?>;
    var firstPrice = <?=$firstPrice?>;
    var totalPrice = <?=$initialtotalprice?>;
    var allowSubmit = false;
    var msg_please_checkin = '<?=Yii::t("messages", "Please select a check in")?>';
    var msg_checkin_less_checkout = '<?=Yii::t("messages", "Sorry. Please input the valid milestone and its corresponding date correctly.")?>';
    var msg_cannot_exceed = '<?=Yii::t('messages', "Sorry. The sum of the price of each milestone cannot exceed the total price.")?>';
;(function($) {
    $(function () {
        isSubmitted = false;

        var currYear = new Date().getFullYear();
        var startDate = new Date(currYear,5,1);
        var endDate = new Date(currYear, 8, 8);

        var isRoomBooking = '<?=$isRookBook == 1?>';

        if (isRoomBooking) {
            endDate = null;
        }
        
        function isCheckIn() {
            var _checkin = $("input[name='Book[checkin]']").val();
            if (_checkin === "" || _checkin === null) {

               return false;
            }
            return true;
         }

         function isCheckOut() {
            var checkout = $("input[name='Book[checkout]']").val();
            if (checkout === "" || checkout === null) {

               return false;
            }
            return true;
         }
         
        // initalize max guests & price & total price
        $('.maxguests').val('<?=$initialMaxGuests?>');
        $('.price').html('<?=$initialprice?>');
        $('.total-price').html(totalPrice);

        $(".maxguests").TouchSpin({
                initval: 1,
                min: 1,
                buttondown_class: "btn btn-danger btn-number glyphicon glyphicon-minus",
                buttonup_class: "btn btn-success btn-number glyphicon glyphicon-plus"
        }).on('touchspin.on.stopspin', function (e) {
            var value = $(this).val();
            var service = $(this).data('sunshade');
                
            if (value == 1){
                $(this).prev().prev().children('.bootstrap-touchspin-down').addClass('disabled');
            } else
            {
                $(this).prev().prev().children('.bootstrap-touchspin-down').removeClass('disabled');
            } 
            if(value == 100) {
                $(this).next().next().children('.bootstrap-touchspin-up').addClass('disabled');
            } else {
                $(this).next().next().children('.bootstrap-touchspin-up').removeClass('disabled');
            } 

            var servicetype = 0;
            var eachprice = '';
            var _totalprice = 0;
            var _price = $('.price').html().split(',');
            for (var i = 0; i < allSunshades.length; i++) {
               price = parseInt(_price[i]);
                var sunshadeIndex = allSunshades[i];
                var _maxPeople =  parseInt(priceLists[sunshadeIndex][servicetype]['maxguests']);
                var _tax =  parseInt(priceLists[sunshadeIndex][servicetype]['tax']);
                if (value > _maxPeople) {
                    price +=_tax;
                }
                eachprice += price + ', ';
                _totalprice += price;

                if (i == allSunshades.length - 1) {initialprice = price;}
            }

           eachprice = eachprice.trim();
           eachprice = eachprice.substring(0, eachprice.length-1);
           totalPrice = _totalprice;
           $('.price').html(eachprice);
           $('.total-price').html(_totalprice);
           $('.hidden-price').val(eachprice);
           $('.hidden-guests').val(value).trigger('change'); 
        });

        $(".btn.btn-number").text("");

        function checkDate() {
            var checkinDate = $("input[name='Book[checkin]']").val();
            var checkoutDate = $("input[name='Book[checkout]']").val();
            if (moment(checkoutDate).isBefore(checkinDate)) 
                return false;

            return true;
        }

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
                                jQuery('#room-form').submit(); 
                            } else {     
                                swal("<?=Yii::t('messages', 'Cancelled!')?>", "<?=Yii::t('messages', 'The information of the current customer remains safe')?>", "error");   
                                return false;
                            } 
                        });
                    } else {
                        allowSubmit = true;
                         jQuery('#room-form').submit(); 
                    }
                }
            });
        }

        // hook form submit to check validity
        $('body').on('submit', '#room-form', function (e) {
            if (!allowSubmit) {
                if (e.isDefaultPrevented()) {
                    console.log('invalid');
                } else {
                    e.preventDefault();
                    var _isValid = true;
                    if (!validateMilestone(0, totalPrice, <?=$bookableSunshades?>)){
                        return;
                    }
                    var room_msg = '';
                    if (!isCheckIn()) {
                        room_msg = '<?=Yii::t('messages', "Please select the check in.")?>';
                        _isValid = false;
                    } else if (!isCheckOut()) {
                        room_msg = '<?=Yii::t('messages', "Please select the check out.")?>';
                        _isValid = false;
                    } else if (!checkDate()) {
                        room_msg = '<?=Yii::t('messages', "Please select the check in or check out correctly.")?>';
                        _isValid = false;
                    }
                    if (!_isValid) {
                        $('.message-warning').html(room_msg);
                        $('.number').html(<?=$bookableSunshades?>);
                        $('#myModal-warning').modal();
                        return;
                    }
                   
                    <?php if(!$isRecursive) :?>
                        if (!checkEmailExistence()) { return false;}
                    <?php endif; ?>
                    checkEmailExistence();
                }
            }
        });

        // Dynamically created milestone
         $('body').on('click', '.Milestone_Date' , function() {
            $(this).datepicker({
                autoclose: true,
                todayHighlight: true,
                format: "dd M, yyyy",
                orientation: "auto bottom",
                keyboardNavigation: true,}).focus();
        });

        var bookIndex = 0;
        $(".milestone-block") // Add button click handler
            .on('click', '.addButton', function() {
                bookIndex++;
                var $template = $('#milestone'),
                    $clone    = $template
                                    .clone()
                                    .removeClass('hide')
                                    .addClass('shown')
                                    .removeAttr('id')
                                    .attr('data-book-index', bookIndex);
                    $(".milestone-block").append($clone);

                // Update the name attributes
                $clone
                    .find('#number').html(bookIndex+1).
                        end();
            })
            // Remove button click handler
            .on('click', '.removeButton', function() {
                var $row  = $(this).parents('.template'),
                    index = $row.attr('data-book-index');

                // Remove element containing the fields
                $row.remove();
                bookIndex--;
            });
     });
})(jQuery);
</script> 

<div class="card">
    <form id="room-form" action="<?=Url::to(['guest/recursivebooking', 'lang' => $lang])?>" method="POST" data-toggle="validator" role="form">
    <div class="card-header text-uppercase">
        <h4>
            <strong><?=  Yii::t('messages', 'Enter Customer Details');?></strong> 
            <?= Html::submitButton(Yii::t('messages', 'CONFIRM BOOKING'),  ['class' => 'btn btn-success confirmbook text-center pull-right']) ?>
        </h4>
    </div>
    <div class="card-body card-padding">
            <?php
              foreach ($allSunshades as $key=>$val) {
                echo "<input type=\"hidden\" name=\"allSunshades[$key]\" value=\"$val\">";
            } ?>
            <input type="hidden" name="lang" value="<?=$lang?>">

            <!--  Book information -->
            <div class='row p-l-10'>
                <Strong class= "col-xs-4 col-sm-4 col-lg-4" align="right">
                    <?php if ($isRookBook) {
                        echo Yii::t('messages', 'ROOM');
                    } else {
                        echo Yii::t('messages', 'SUNSHADE');
                    }?> 
                </Strong> 
                <span class= "col-xs-6 col-sm-6" align="left">
                    <?=$bookableSunshades?>
                </span>
            </div>
             <br />
             <div class='row p-l-10'>
                <Strong class="col-xs-4 col-sm-4 col-lg-4 text-uppercase"  align="right" style=" vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'Check In')?> </Strong>
                <span class="col-xs-4 col-sm-4 input-group date p-l-15 checkin" style="width: 200px;"  align="left">
                    <input type="text" readonly='readonly' name="Book[checkin]" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </span>
            </div>
            <br />

            <div class='row p-l-10'>
                <Strong class="col-xs-4 col-sm-4 col-lg-4 vertical-align text-uppercase"  align="right" style="line-height: 40px;"><?=Yii::t('messages', 'Check Out')?> </Strong>
                <span class="col-xs-4 col-sm-4 input-group date p-l-15 checkout" style="width: 200px;"  align="left">
                    <input type="text" readonly='readonly' name="Book[checkout]" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </span>
            </div>

            <br />
             <div class='row p-l-10'>
                    <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style="vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'GUESTS')?> </Strong>
                    <div class="col-xs-4 col-sm-4 input-group p-l-15" align="left" style=" width: 150px;">
                          <input class="maxguests text-center">
                    </div>
            </div>
            <br />
            <div class='row p-l-10'>
                  <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right"><?=Yii::t('messages', 'PRICE')?> (&euro;) </Strong>
                  <span class="col-xs-6 col-sm-6 price" align="left"><?=$initialprice?></span>
            </div>

            <?php if(count($allSunshades) > 1)  {?>
            <br />
            <div class='row p-l-10'>
                  <Strong class="col-xs-4 col-sm-4 col-lg-4 text-uppercase" align="right"><?=Yii::t('messages', 'Total Price')?> (&euro;) </Strong>
                  <span class="col-xs-2 col-sm-2 total-price" align="left"><?=intval($initialtotalprice) * count($allSunshades)?></span>
            </div>
            <?php }?>
            <br />
            <div class="milestone-block">
                <!-- Milestone -->
                <div class='row shown'>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <Strong class="col-xs-4 col-sm-4 col-lg-4 text-uppercase nopl" align="right" style=" vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'Milestone')?> <span id="number">1</span> </Strong>
                            <div class="col-xs-3 col-md-3">
                                <input type="number" class="form-control Milestone_Price" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>" />
                            </div>
                            <div class="col-xs-4 col-md-4 nopl">
                                <input type="text" readonly='readonly' class="form-control Milestone_Date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
                            </div>
                            <div class="col-xs-1 cole-md-1 nopl">
                                <button type="button" class="btn btn-success addButton"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row" align="center">
            	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            	<input type="hidden" name="guestId" value="<?=$guest['Id']?>">
                <input type="hidden" class="hidden-price" name="price" value="<?=$initialprice?>">
                <input type="hidden" class="hidden-servicetype" name="Book[servicetype]" value="<?=$initialServicetype?>">
                <!-- guests -->
                <input type="hidden" class="hidden-guests" name="Book[guests]" value="<?=$initialMaxGuests?>">
            </div>

            <?php if(!$isRecursive) {?>
            <!-- Guest Info -->
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
			<?php } else {?>
				<input type="hidden" name="guestId" value="<?=$guest['Id']?>">
			<?php }?>
            <div class="form-group m-b-30">
                <?= Html::submitButton(Yii::t('messages', 'CONFIRM BOOKING'),  ['class' => 'btn btn-success confirmbook text-center pull-right']) ?>
            </div>
        </div>
    </form>
</div>

<!-- The template for adding new field -->
 <div class='row hide template' id="milestone">
    <div class="col-sm-12">
        <div class="form-group" >
            <Strong class="col-xs-4 col-sm-4 col-lg-4 text-uppercase nopl" align="right" style=" vertical-align: middle; line-height: 40px;">
                <?=Yii::t('messages', 'Milestone')?> <span id="number">1</span>  
            </Strong>
            <div class="col-xs-3 cole-md-3">
                <input type="number" class="form-control Milestone_Price" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>" />
            </div>
            <div class="col-xs-4 cole-md-4 nopl">
                <input type="text" readonly='readonly' class="form-control Milestone_Date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
            </div>
            <div class="col-xs-1 cole-md-1 nopl" >
                <button type="button" class="btn btn-danger removeButton"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- end  Milestone -->