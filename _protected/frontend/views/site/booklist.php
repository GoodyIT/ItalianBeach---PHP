<?php
    use yii\helpers\Html;

    $this->title = Yii::t('messages', 'Book Information');

    $lang = Yii::$app->language;
    if ($lang == 'en')
        $people = 'people';
    else
        $people = 'persone';

    $seat = $model[0];
    
    $rowRestriction = $rowRestrictionLists[$seat];
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

        // control the menu item
        $( "nav ul li" ).slice( 0, 4 ).removeClass('active');
        $( "li.waves-effect:nth-child(3)").addClass('active');

    	$priceLists = [];
       	$guestsList = [];

        var currYear = new Date().getFullYear();
        var today = new Date();
        var startDate = moment(today).isAfter(new Date(currYear,5,1)) ? 
                        today : new Date(currYear,5,1);
        var endDate = new Date(currYear, 8, 8);
        
        $('.input-group.date').datepicker({
        	daysOfWeekHighlighted: "0",
		    autoclose: true,
		    todayHighlight: true,
		    format: "yyyy-mm-dd",
		    startDate: startDate,
            endDate: endDate,
            orientation: "auto top",
            keyboardNavigation: true,
        });

        $( ".input-group.date" ).datepicker( "disable" );

        $day = '<?=$day?>';
        $('.input-group.date').datepicker('startDate', $day);

        
         function checksum() {
            var startDate = new Date(currYear,5,1);
            var _arrival = $("input[name='arrival']").val();
            if (_arrival === "" || _arrival === null || moment(_arrival).isBefore(today) || moment(_arrival).isBefore(startDate)) {

               return false;
            }
            return true;
         }
        
        $('.reserve').click(function (e) {
            e.preventDefault();
            if (checksum()) {
                $('#bookform').submit();
            }
            else {
                $.notify("<?=Yii::t('messages', 'Please select the correct arrival time.')?>", {
                    animate: {
                        enter: 'animated fadeInRight',
                        exit: 'animated fadeOutRight'
                    }
                });
            }
        })


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
        $('.guests').val('<?=$guestsList[1]?>');

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
            _maxPeople = parseInt($guestsList[$model][_priceIndex]['maxguests']);
            _price = $priceLists[$model][_priceIndex];
            _tax = parseInt(_price['tax']);

            _curPrice = 0;
            if (sign == 1) {
                if (valueCurrent > _maxPeople) {
                    _curPrice = parseInt($('.price').html()) + _tax;
                    $('.price').html(_curPrice);
                }
            } else {
                if (valueCurrent >= _maxPeople) {
                    _curPrice = parseInt($('.price').html()) - _tax;
                    $('.price').html(_curPrice);
                }
            }

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
     });
})(jQuery);
</script> 

<div class="container site-booklist text-center top-padding">
    <h1> <?=Yii::t('messages', 'When would you like to stay here')?> ?
        
    </h1>  
    <div style="height: 1em"></div>
<div class="row">
    <div class="col-sd-6 col-sm-6 col-xm-6 hcenter">
        <div class="panel panel-success">
            <div class="panel-heading" style="color: white;font-size: 14px;padding: 10px 15px;">
                <h3 class="panel-title"><?=  Yii::t('messages', 'Book Information');?></h3>
            </div>
            <div class="panel-body">
                 <form id="bookform" method="post" action="<?= Yii::$app->urlManager->createUrl(["site/book"]) ?>">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <input type="hidden" name="lang" value="<?=$lang?>">
                    <div class='row' style="padding-left: 10px">
                            <Strong class= "col-xs-4 col-sm-4 col-lg-4" align="right"><?=Yii::t('messages', 'SUNSHADE')?> </Strong> 
                            <div class= "col-sm-6" align="left"><?=$model?> </div>
                         
                    </div>
                    <br>
                     <div class='row' style="padding-left: 10px">
                        <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle;
    line-height: 40px;"><?=Yii::t('messages', 'ARRIVAL')?> </Strong>
                        <span class="col-sm-4 input-group date" style=" width: 50%;  padding-left: 15px;"  align="left">
                            <input type="text" name="arrival" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </span>
                    </div>
                    <br>
                     <div class='row' style="padding-left: 10px">
                            <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right"><?=Yii::t('messages', 'SERVICE TYPE')?> </Strong>
                            <div class="col-sm-4 servicetype input-group">
                                <?=  Html::dropDownList('servicetype', [], $services);?>
                            </div>
                           
                    </div>
                    <br>
                     <div class='row' style="padding-left: 10px">
                            <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style="vertical-align: middle;
    line-height: 40px;"><?=Yii::t('messages', 'GUESTS')?> </Strong>
                            <div class="input-group" style=" width: 150px;">
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
                           <input type="hidden" name="guests" value="1">
                    </div>
                    <br>
                    <div class='row' style="padding-left: 10px">
                          <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right"><?=Yii::t('messages', 'PRICE')?>(&euro;) </Strong>
                          <div class="col-sm-4 price" align="left"><?=$price?></div>
                    </div>
                    <br>
                    <div class="row" align="center">

                      <input type="hidden" name="day" value="<?=$day?>">
                      <input type="hidden" name="sunshadeseat" value="<?=$model?>"> 
                     <input type="hidden" class="hidden-servicetype" name="servicetype" value="<?=$keys[0]?>">
                      <input type="hidden" class="hidden-maxguests" name="guests" value="1">
                    <input type="hidden" class="hidden-price" name="price" value="<?=$price?>">
                      <input type="hidden" class="hidden-mainprice"  name="mainprice" value="<?=$service['mainprice']?>">
                    <input type="hidden" class="hidden-tax" name="tax" value="<?=$service['tax']?>">
                    <input type="hidden" class="hidden-supplement" name="supplement" value="<?=$service['supplement']?>">
                         <button type="submit" class="reserve btn btn-success">
                            <span class="lead"><?=Yii::t('messages', 'reserve')?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
</div>




