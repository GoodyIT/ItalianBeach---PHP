<?php
    $this->title = Yii::t('messages', Yii::$app->name);
?>

<script>

    sunshadeList = [];
    lang = '<?=Yii::$app->language?>';

    function bookingCart() {
        $('.modal').modal('hide');

        if (sunshadeList.length == 0)
        {
            $("#myModal-warning").modal();
            return;
        }

        bookingCartUrl = '<?= Yii::$app->urlManager->createUrl(["guest/sunshade"]) ?>';
        bookingCartUrl += '?id=' + sunshadeList;
        bookingCartUrl += '&lang=' + '<?=Yii::$app->language?>';
        bookingCartUrl += '&day=' + $('.span2.date').val();
        window.location.href = bookingCartUrl;
    }

     ;(function($) {
        $(document).ready(function() {
            var currYear = new Date().getFullYear();
            var startDate = new Date(currYear,5,1);
            var endDate = new Date(currYear, 8, 8);

            $day = '<?=$day?>';
            $('.span2.date').datepicker({
                daysOfWeekHighlighted: "0",
                autoclose: true,
                todayHighlight: true,
                format: "yyyy-mm-dd",
            /*    startDate: startDate,
                endDate: endDate,*/
                orientation: "bottom",
                keyboardNavigation: true,
                setDate: $day,
            }).on('changeDate', function(ev){
                $('form').attr('action', '<?=Yii::$app->urlManager->createUrl(['site/index'])?>' + "?day=" + ev.format());
            });

            $('.span2.date').datepicker('setDate', $day);

            function Demo_Function () {
                $('#sunshademap').smoothZoom('addLandmark', 
                [
                <?php 
                  for ($i = 0; $i < count($jsonValue); $i++) {
                    $mark_image_name = "";
                    if ($jsonValue[$i]['bookstate'] == "booked") {
                        $mark_image_name = "red";
                    } else if ($jsonValue[$i]['bookstate'] == "booking") {
                        $mark_image_name = "yellow";    
                    } else {
                        $mark_image_name = "blue";
                    }
                    
                    echo "'" . '<div class="item mark" data-show-at-zoom="0" data-allow-scale = "true" data-position="'. $jsonValue[$i]['x'] .', '. $jsonValue[$i]['y'] .'">\
                            <div>\
                        <span class="sunshade text mark '.$mark_image_name.'" data-id="'. $jsonValue[$i]['Id'] .'" data-balloon-pos="up" data-balloon="'. $jsonValue[$i]['seat'] .'">\
                                </div>\
                        </span>\
                    </div>' . "' ,";
                  } 
                ?>
                ]
                );
            }

            Demo_Function();

            // check sunshade booking state
        $('.sunshade').on('click touchstart', function() {
            var sunshade = $(this).data('balloon');
            var id = $(this).data('id');
            var msg = "";
            var msgType = "yellow";
            /* check if it is booked or not */
            var bookingState = 'available';
            if ($(this).hasClass('blue')) {
                if (lang == "it") {
                    msg = sunshade + " è stato inserito nella vostro carrello prenotazione con successo <br>";
                } else {
                    msg = sunshade + " was inserted into your booking list successfully <br>";   
                }
                msgType = "success";
                bookingstate = 'booked';
                $(this).removeClass('blue').addClass('yellow');
            } else if ($(this).hasClass('red')) {
                if (lang == "it") {
                    msg = "Mi dispiace, questo ombrellone è stato già prenotato da altri!";
                } else {
                    msg = "Sorry, This sunshade was already booked by others! ";
                }
                bookingstate = 'error';
                msgType = "danger";
                id = 0;
            } else if ($(this).hasClass('yellow')) {
                if (lang == "it") {
                    msg = "La Prenotazione dell'Ombrellone per " + sunshade + " è stata annullata.";
                } else {
                    msg = "Sunshade booking for " + sunshade + " was cancelled.";
                }
                bookingstate = 'cancel';
                msgType = "warning";
                $(this).removeClass('yellow').addClass('blue');
            }

          //  showMessage(msg, msgType);
            manageSunshadeList(sunshadeList, sunshade);

            $('.message').html(msg);
            $('.number').html(sunshade);
            $('#myModal').modal();
        });
   });
})(jQuery);

</script>

<div class="site-index" style="padding-bottom: 20px;">
     <div class="row" style= "margin-top: 10px;">
        <div class="col-sm-4 col-md-4 newfont">
            <form role="search" action="" method="post">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input class="span2 date " id="appendedInputButton" type="text" style="  padding-left: 15px; width: 200px;" placeholder="<?=Yii::t('messages', 'Check Availability')?>">
                <button  type="submit" class="check-availability" >
                    <i class="fa fa-search"></i>
                </button >
            </form>
        </div>
        <div class="col-sm-8 col-md-8 lead newfont" style="color:white">
            <?=Yii::t('messages', 'Choose your Sunshade and Book it now!')?>
        </div>
    </div>
    <div class="row " style= "margin-top: 10px;">
        <div id="zoom_container">
            <img id="sunshademap"   src="https://s3.eu-central-1.amazonaws.com/suntickets/BeachClubIppocampo/beachclubippocampo.jpg"  width="12993px" height="6663px" />
            <div class="landmarks" data-show-at-zoom="100" data-allow-drag="true" data-allow-scale="false"> 
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="row modal-content overlay" style=" overflow-x: hidden;">
        <div class="col-xs-2 col-sm-2 left">
            <img src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
            <div class="number" style="padding-left: 8px;">C23</div>
        </div>
        <div class="col-xs-9 col-sm-9 left">
            <div class="message"><?=Yii::t('messages', 'This sunshine is available now!')?></div>
            <button class="add_sunshine" onclick="javascript:bookingCart();"><?=Yii::t('messages', 'GO TO CART')?>
            </button>
        </div>
        <div class="col-xs-1 col-sm-1 left" style="padding-left: 0px; padding-right: 20px;  cursor: pointer;">
            <i class="fa fa-close modal-close" aria-hidden="true"></i>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal-warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="row modal-content overlay" style=" overflow-x: hidden;">
        <div class="col-xs-2 col-sm-2 left">
            <img src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
        </div>
        <div class="col-xs-9 col-sm-9 left ">
            <div class="message-warning"><?=Yii::t('messages', 'There is no book list yet.')?></div>
            <button class="add_sunshine" style="width: 100px;" onclick="javascript:dismissModal();"><?=Yii::t('messages', 'OK')?>
            </button>
        </div>
        <div class="col-xs-1 col-sm-1 left" style="padding-left:0px;  padding-right: 20px; cursor: pointer;">
            <i class="fa fa-close modal-close" aria-hidden="true"></i>
        </div>
    </div>
  </div>
</div>
