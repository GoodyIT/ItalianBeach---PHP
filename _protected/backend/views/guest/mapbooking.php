<?php
    use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
    $lang = Yii::$app->language;

    $this->title = Yii::t('messages', 'Map Booking');
    $this->params['breadcrumbs'][] =  !empty($guest) ?  Yii::t('messages', 'Recursive Booking') :  Yii::t('messages', 'Map Booking');
    if (!empty($guest)) {
        $this->params['breadcrumbs'][] = $guest['firstname'] . ' ' . $guest['lastname'];
    } 
?>

<style type="text/css">
    /* Portrait */
    @media only screen 
    and (max-width: 480px) {
     
        .container-fluid.azz
        {
            padding-left: 0px;
            padding-right: 0px;
        }

        .container-fluid.azz .container
        {
            padding-left: 0px;
            padding-right: 0px;
        }
    }
</style>

<div class="block-header">
     <div class="row pull-right m-b-10">
        <button href="<?=Url::to(['guest/gotocart', 'lang' => Yii::$app->language])?>" class="btn btn-success text-uppercase  m-r-10 btn-gotocart" ><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?=Yii::t('messages', 'Booking Cart')?></button>
        <button class="btn btn-success text-uppercase change-dates "><i class="fa fa-calendar" aria-hidden="true"></i> <?=Yii::t('messages', 'Check Availability')?></button>
    </div>
</div>

<div class="guest-view m-b-10 w-100">
    <div id="zoom_container" class=" w-100">
        <img id="sunshademap" src="<?=Yii::getAlias('@web')?>/../img/beachdev.jpg"/>
        <div class="landmarks" data-show-at-zoom="100" data-allow-drag="true" data-allow-scale="false"> 
        </div>
    </div>
</div>

<div class="modal fade" id="myModal-search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content overlay" >
            <div class="modal-header p-l-5 p-r-5" >
                <div class="col-sm-2 col-xs-3">
                    <img src="<?=Yii::getAlias('@web')?>/img/disponibile.png">
                    </div>
                    <div class="col-sm-9 col-xs-7 text-center">
                    <strong class="c-white f-700">
                            <?=Yii::t('messages', 'Find the upcoming available sunshade(s)')?>
                        </strong>
                    </div>
                    <div class="col-sm-1 col-xs-1">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form id="update-form"  role="form" data-toggle="validator">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="row">
                            <div class="form-group group-from">
                                <div><?=Yii::t('messages', 'Check In')?></div>
                                <div class="input-group date">
                                    <span class="input-group-addon round  bgm-gray"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="from"  class="form-control from p-l-5" data-error="This field cannot be blank" required readonly style="font-size: 12px;">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group group-to">
                                <div><?=Yii::t('messages', 'Check Out')?></div>
                                <div class="input-group date">
                                    <span class="input-group-addon round  bgm-gray"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="to" id="search-to"  class="form-control to p-l-5" data-error="This field cannot be blank" required readonly style="font-size: 12px;">
                                </div>
                                <div class="help-block with-errors"></div>  
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="lang" value="<?=Yii::$app->language?>">
                        <button type="submit" class="btn btn-success btn-update-map text-uppercase"><strong><?=Yii::t('messages', 'Update Map')?></strong>
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<script>
var guestId = "<?=$id?>";
var lang = '<?=Yii::$app->language?>';
var savetocart_url = '<?=Url::to(['guest/savetocart', 'lang' => Yii::$app->language])?>';
var removesunshadefromcartwithid_url = '<?=Url::to(['guest/removesunshadefromcartwithid', 'lang' => Yii::$app->language])?>';
var csrf = '<?=Yii::$app->request->getCsrfToken()?>';
var isFirstStep = ('<?=$from?>' == "" && '<?=$to?>' == "") ? true : false;
;(function($) {
    $(document).ready(function() {
        function Demo_Function () {
            $('#sunshademap').smoothZoom('addLandmark', 
            [
            <?php
          for ($i = 0; $i < count($jsonValue); $i++) {
            $sunshade = $jsonValue[$i];
            $mark_image_name = "";
            if ($sunshade['bookstate'] == "booked") {
                $mark_image_name = "red";
            } else if($sunshade['bookstate'] == "bookingcart") {
                $mark_image_name = "blue";
            } else if ($sunshade['bookstate'] == "possible") {
                $mark_image_name = "yellow";
            } else {
                $mark_image_name = "green";
            }

            $count = count($sunshade['bookedinfo']);
            $cartId = $sunshade['cartid'];
            $bookedData = "data-count=$count";
            for ($j = 0; $j <  $count; $j++) {
                $checkin = $sunshade['bookedinfo'][$j]['checkin'];
                $checkout = $sunshade['bookedinfo'][$j]['checkout'];
                $bookedData .= " data-checkin$j = $checkin data-checkout$j = $checkout";
            }

            echo "'" . '<div class="item mark" data-show-at-zoom="0" data-allow-scale = "true" data-position="'. $sunshade['x'] .', '. $sunshade['y'] .'">\
                    <div>\
                <span class="sunshade text mark '.$mark_image_name.'" data-sunshadeid="'. $sunshade['Id'] .'" data-cartid= "'.$cartId.'" data-balloon-pos="up" data-balloon="'. $sunshade['seat'] .'"' . $bookedData . '>\
                        </div>\
                </span>\
            </div>' . "' ,";
          } 
        ?>]
            );
        }

        Demo_Function();

        // Place the change view button the on the top left of the map
        $('div.noSel').append('<div style="position: absolute; left: 10px; top: 5px; width: 40px; height: 30px; z-index: 20; display: block;" class="noSel;"><a style="cursor:pointer; position: absolute; display: block; top: 5px; width: 100%; z-index: 7; background-color: rgb(0, 0, 0); opacity: 0.55; color: white" class="btn btn-default change-view icon-mode"  aria-label="Change View"><i class="fa fa-cog" aria-hidden="true"></i></a></div>');

        resumeViewMode();

        $('.change-view').click(function(){
            toggleViewMode();
        });
   });
})(jQuery);

</script>
