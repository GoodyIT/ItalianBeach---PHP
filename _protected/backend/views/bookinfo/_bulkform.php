<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Bookinfo */
/* @var $form yii\widgets\ActiveForm */
$lang = Yii::$app->language;
    if ($lang == 'en')
        $people = 'people';
    else
        $people = 'persone';
   
    // print_r($priceLists);
    $services = [];
    $guestsList = [];
    $guestNum = 1;

    foreach ($rowRestriction as $rowid => $service) {
        $services[$service] = $serviceTypeLists[$service] . '  ( max ' . $priceLists[$seat][$service]['maxguests'] . ' ' . $people . ' )';
        $guestsList[$guestNum++] = $priceLists[$seat][$service]['maxguests'];
    }

    $guests = ['1'=>'1', '2'=>'2'];
        /*for ($guestsNum = 1; $guestsNum < count($priceLists[$seat][$service]['maxguests']); $guestsNum++) {
        $guests[$guestsNum] = $guestsNum;
    } */

    $keys = array_keys($services);
    $service = $priceLists[$seat][$keys[0]];
    $price = intval($service['mainprice']) + intval($service['supplement']);

    $listData = array("available"=>"Available","booked"=>"Booked");
?>

<style>
    .avail-table .table_header th {
        font-size: .875em;
        height: 35px;
        line-height: 35px;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: left;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding-left: .75em;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    
    .avail-table tr:last-child {
    border-bottom: none;
}
.avail-table .table_header {
    background: #666;
    color: #fff;
    border-bottom: none;
    border-right: none;
}
.avail-table tr {
    vertical-align: top;
    border-bottom: 1px solid #ccc;
    background-color: #FAFAFA;
}
    
.center-div {    
      position: absolute;
      margin: auto;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      width: 100px;
      height: 100px;
      background-color: #ccc;
      border-radius: 3px;
}

    .center{
        width: 150px;
        margin: 40px auto;

    }

    .form-control {
        min-height: 30px;
    }
    .btn {
        margin: 0px;
        margin-left: 15px;
    }

    .hcenter {
        margin: 0 auto;
        float: none;
    }
</style>
<script type="text/javascript">
;(function($) {
    $(function () {
        isSubmitted = false;
        $priceLists = [];
        $guestsList = [];

        var currYear = new Date().getFullYear();
        var startDate = new Date(currYear,5,1);
        var endDate = new Date(currYear, 8, 8);

        var isRoomBooking = '<?=$model[0] == 1?>';

        if (isRoomBooking) {
            endDate = null;
        }
        
        $('.input-group.arrival').datepicker({
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true,
            format: "yyyy-mm-dd",
            startDate: startDate,
            endDate: endDate,
            orientation: "auto top",
            keyboardNavigation: true,
        });

        $('.input-group.checkout').datepicker({
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true,
            format: "yyyy-mm-dd",
            startDate: startDate,
            endDate: endDate,
            orientation: "auto top",
            keyboardNavigation: true,
        });
        
         function checksum() {
            var _arrival = $("input[name='Book[arrival]']").val();
            if (_arrival === "" || _arrival === null) {

               return false;
            }
            return true;
         }
        
        $priceLists['<?=$model?>'] = <?= json_encode($priceLists[$seat])?>;
        $guestsList['<?=$model?>'] = <?= json_encode($priceLists[$seat])?>;
        
       /* $('.guests').children('select').change(function(){
             $guestCount = $(this).children("option:selected").val();
             $('input:hidden[name=guests]').val($guestCount);
        });*/
        
        $('.servicetype').children('select').change(function(){
            $model = '<?=$model?>';
            $priceIndex = $(this).children("option:selected").val();
            $('.hidden-servicetype').val($priceIndex);
            $price = $priceLists[$model][parseInt($priceIndex)];
            $_price = parseInt($price['mainprice']) + parseInt($price['supplement']);
            $('.hidden-price').val($_price).trigger('change');
            $('.hidden-mainprice').val(parseInt($price['mainprice'])).trigger('change');
            $('.hidden-tax').val(parseInt($price['tax'])).trigger('change');
            $('.hidden-supplement').val(parseInt($price['supplement'])).trigger('change');
            $('.hidden-maxguests').val($guestsList[$model][$priceIndex]['maxguests']).trigger('change');
            $('.guests').val($guestsList[$model][$priceIndex]['maxguests']);

            $('.price').html($_price);
            /*$('.guests').html('<select name="listname" id="guestgroup"></select>');
            for (var i = 1; i <= $guestsList[$model][parseInt($priceIndex)]; i++) {
                $('<option>').val(i).text(i).appendTo('#guestgroup');        
            }*/
        });

        // set the max guests
        $('.guests').val(<?=$guestsList['1']?>);

        sign = -1;
        $('.btn-number').click(function(e){
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type      = $(this).attr('data-type');
            var input = $("input[name='"+fieldName+"']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                if(type == 'minus') {
                    sign = -1;
                    if(currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if(parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if(type == 'plus') {
                    sign = 1;
                    if(currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if(parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }
                }
            } else {
                input.val(0);
            }
        });

        $('.guests').focusin(function(){
            $(this).data('oldValue', $(this).val());
        });

        $('.guests').change(function() {

            minValue =  parseInt($(this).attr('min'));
            maxValue =  parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            name = $(this).attr('name');
            if(valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
            } else {
                alert('Sorry, the minimum value was reached');
                $(this).val($(this).data('oldValue'));
            }
            if(valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
            } else {
                alert('Sorry, the maximum value was reached');
                $(this).val($(this).data('oldValue'));
            }

            /**
             *  Consider the tax fomula
             *  price += tax * ("number of booking people" - "maxpeople for service")
             */
            $model = '<?=$model?>';
            _priceIndex = $('.servicetype option:selected').val();
            if (_priceIndex == undefined) {
                _priceIndex = 5;
            }
            _maxPeople = parseInt($guestsList[$model][_priceIndex]['maxguests']);
            console.log(_maxPeople);
            _price = $priceLists[$model][_priceIndex];
            _tax = parseInt(_price['tax']);
            var _curPrice = $('.price').html();

            if (sign == 1) {
                if (valueCurrent > _maxPeople) {
                    _curPrice = parseInt($('.price').html()) + _tax;
                }
            } else {
                if (valueCurrent >= _maxPeople) {
                    _curPrice = parseInt($('.price').html()) - _tax;
                   
                }
            }
           
           $('.price').html(_curPrice);
           $('.hidden-price').val(_curPrice);
           $('.hidden-maxguests').val(valueCurrent).trigger('change');
        });

        $(".guests").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        function checkDate() {
            var arrivalDate = $("input[name='Book[arrival]']").val();
            var checkoutDate = $("input[name='Book[checkout]']").val();
            if (moment(checkoutDate).isBefore(arrivalDate)) 
                return false;

            return true;
        }

        function checkMilestones() {
            var nonEmptyDates = $('.shown .form-control.milestone-date').map(function(){return $(this).val();}).get();
            var length = nonEmptyDates.length;
            var dateLength = 0;
            for (var i = nonEmptyDates.length - 1; i >= 0; i--) {
                if(nonEmptyDates[i] && nonEmptyDates[i].length > 0) { dateLength++;}
            }

            if (dateLength < length) { return 1;}
            for(var i = 0; i < dateLength; i++){
                if (moment(nonEmptyDates[i]).isBefore($("input[name='Book[arrival]']").val()))
                {
                    return 1;
                }
            }
            /*$milestonePrices = $(".shown input[name='Milestone_Price[]']").map(function(){return $(this).val();}).get();
            var total = 0;
            for(var i = 0; i < $milestonePrices.length; i++) {
                total += parseInt($milestonePrices[i]);
            }            
            if (total == parseInt($('.price').html())) {
                return 0;
            }*/

            return 0;
        }

        function showNotify(message){
            $.notify({
                message: message}, 
                {
                    animate: {
                        enter: 'animated fadeInRight',
                        exit: 'animated fadeOutRight'
                    },
                    type: 'danger'
                });
        }

        function checkEmailExistence(){
              //  console.log($('#guest-email').val());

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
                                    return false;
                                } 
                            });
                        } else {
                             jQuery('#guest-form').yiiActiveForm('submitForm'); 
                        }
                    }
                });
            }

        // hook form submit to check validity
        $('.confirmbook').click(function(event) {
            event.preventDefault();
            if (isSubmitted) { return;}
                
            if (!checksum()) {
                 showNotify('<?=Yii::t('messages', "Please select the arrival time.")?>');
                 return;
            }
            if (checkMilestones() == 2){
                showNotify('<?=Yii::t('messages', "Please add more milestones to reach.")?> ' + $('.price').html() + "\u20ac");
               return;
            }

            if (isRoomBooking && !checkDate()) {
                 showNotify('<?=Yii::t('messages', "Please select the correct arrival or checkout date.")?>');
               return;
            }

            if (!checkEmailExistence()) { return;}

            jQuery('#guest-form').yiiActiveForm('submitForm');             
        })

        // Dynamically created milestone
         $('body').on('click', '.milestone-date' , function() {
            $(this).datepicker('destroy').datepicker({
                                            daysOfWeekHighlighted: "0",
                                            autoclose: true,
                                            todayHighlight: true,
                                            format: "yyyy-mm-dd",
                                            orientation: "auto top",
                                            keyboardNavigation: true,}).focus();
        });

         bookIndex = 0;
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

<?php $form = ActiveForm::begin([
                // 'action' => ['bookinfo/bulkupdate'],
                'id' => 'guest-form',
                'enableAjaxValidation' => false,
            ]); ?>

            <input type="hidden" name="lang" value="<?=Yii::$app->language?>">

<div class="panel panel-success">
    <div class="panel-heading" style="color: white; font-size: 20px; padding: 10px 15px;">
        <?=  Yii::t('messages', 'Enter Customer Details');?> 
         <a href="" class="bgm-amber pull-right confirmbook" style="width: 40px; height: 40px; border-radius: 50%; text-align: center; color: #fff; display: inline-block; line-height: 40px; font-size: 30px; -webkit-transition: all; -o-transition: all; transition: all; -webkit-transition-duration: 300ms;  transition-duration: 300ms;"><i class="zmdi zmdi-check"></i></a>
        <div class="clearfix"></div>

    </div>
    <div class="panel-body">
        <div class="guest-form">
            
            <?php
              foreach ($selection as $key=>$val) {
                echo "<input type=\"hidden\" name=\"selection[$key]\" value=\"$val\">";
            } ?>
            <input type="hidden" name="lang" value="<?=$lang?>">

            <!--  Book information -->

            <div class='row' style="padding-left: 10px">
                <Strong class= "col-xs-4 col-sm-4 col-lg-4" align="right">
                    <?php if ($model[0] == 1) {
                        echo Yii::t('messages', 'ROOM');
                    } else {
                        echo Yii::t('messages', 'SUNSHADE');
                    }?> 
                </Strong> 
                <span class= "col-sm-6" align="left">
                    <?=$model?>
                </span>
                 
            </div>
             <br />
             <div class='row' style="padding-left: 10px">
                <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'ARRIVAL')?> </Strong>
                <span class="col-sm-4 input-group date arrival" style=" width: 50%px;  padding-left: 15px;"  align="left">
                    <input type="text" name="Book[arrival]" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </span>
            </div>
            <br />

            <?php if ($model[0] == 1) { ?>
                <div class='row' style="padding-left: 10px">
                    <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'Checkout Date')?> </Strong>
                    <span class="col-sm-4 input-group date checkout" style="width: 50%px;  padding-left: 15px;"  align="left">
                        <input type="text" name="Book[checkout]" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </span>
                </div>
                <br />
            <?php } else { ?>

             <div class='row' style="padding-left: 10px">
                    <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right"><?=Yii::t('messages', 'SERVICE TYPE')?> </Strong>
                    <span align="left small"  class="col-sm-4 servicetype" style="width: 50px; font-size: 90%">
                        <?=  Html::dropDownList('servicetype', [], $services);?>
                    </span>
            </div>
            <?php }?>
            <br />
             <div class='row' style="padding-left: 10px">
                    <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style="vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'GUESTS')?> </Strong>
                    <div class="input-group " style=" width: 150px;">
                          <span class="input-group-btn">
                              <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="quant[2]">
                                  <span class="glyphicon glyphicon-minus"></span>
                              </button>
                          </span>
                          <input type="text" name="quant[2]" class="form-control guests text-center" value="2" min="1" max="100">
                          <span class="input-group-btn">
                              <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="quant[2]">
                                  <span class="glyphicon glyphicon-plus"></span>
                              </button>
                          </span>
                    </div>
            </div>
            <br />
            <div class='row' style="padding-left: 10px">
                  <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right"><?=Yii::t('messages', 'PRICE')?> (&euro;) </Strong>
                  <span class="col-sm-4 price"><?=$price?></span>
            </div>

            <br />
            <div class="milestone-block">
                <!-- Milestone -->
                <div class='row shown'>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'Milestone')?> <span id="number">1</span> </Strong>
                            <div class="col-xs-3 col-md-3">
                                <input type="text" class="form-control" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>" />
                            </div>
                            <div class="col-xs-3 col-md-3" style="padding-left: 0px;">
                                <input type="text" class="form-control milestone-date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
                            </div>
                            <div class="col-xs-1 cole-md-1" style="padding-left: 0px;">
                                <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <br />
            <div class="row" align="center">

                <input type="hidden" name="Book[sunshadeseat]" value="<?=$model?>"> 
                <input type="hidden" class="hidden-servicetype" name="Book[servicetype]" value="<?=$keys[0]?>">
                <input type="hidden" class='hidden-maxguests' name="Book[guests]" value="<?=$guestsList['1']?>">
                <input type="hidden" class='hidden-price' name="Book[price]" value="<?=$price?>">
                
                <input type="hidden" class="hidden-mainprice" name="Book[mainprice]" value="<?=$service['mainprice']?>">
                <input type="hidden" class="hidden-tax" name="Book[tax]" value="<?=$service['tax']?>">
                <input type="hidden" class="hidden-supplement" name="Book[supplement]" value="<?=$service['supplement']?>">
            </div>

            <!--  Guest Information -->
            <div class='row '>
                <div class="col-sm-12">
                    <?= $form->field($guest, 'firstname')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class='row'>
                <div class="col-sm-12">
                    <?= $form->field($guest, 'lastname')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class='row'>
                 <div class="col-sm-12">
                    <?= $form->field($guest, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class='row'>
                <div class="col-sm-12">
                    <?= $form->field($guest, 'address')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class='row'>
                <div class="col-sm-12">
                    <?= $form->field($guest, 'city')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class='row'>
                <div class="col-sm-12">
                    <?= $form->field($guest, 'zipcode')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class='row'>
                <div class="col-sm-12 form-group field-guest-country">
                    <label class="control-label" for="guest-country"><?=Yii::t('messages', 'Country')?></label>
                    <?php require_once 'country.php'; ?>
                    <div class="help-block"></div>
                </div>
            </div>

            <div class='row'>
                <div class="col-sm-12">
                    <?= $form->field($guest, 'phonenumber')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <input type="hidden" name="Guest[recurringcount]" value="<?=$guest->recurringcount?>">
            <br />
            <?= Html::submitButton(Yii::t('messages', 'CONFIRM BOOKING'),  ['class' => 'btn btn-success confirmbook text-center pull-right']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- The template for adding new field -->
 <div class='row hide template' id="milestone">
    <div class="col-sm-12">
        <div class="form-group" >
            <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle; line-height: 40px;">
                <?=Yii::t('messages', 'Milestone')?> <span id="number">1</span>  
            </Strong>
            <div class="col-xs-3 cole-md-3">
                <input type="text" class="form-control" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>" />
            </div>
            <div class="col-xs-3 cole-md-3" style="padding-left: 0px;">
                <input type="text" class="form-control milestone-date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
            </div>
            <div class="col-xs-1 cole-md-1" style="padding-left: 0px;">
                <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- end  Milestone -->
