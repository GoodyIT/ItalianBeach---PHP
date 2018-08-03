<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
$lang = Yii::$app->language;

$this->title = Yii::t('messages', 'Send Book Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Customers Info'), 'url' => ['guest/guestinfo', 'lang' => $lang]];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Customer Detail');

//$baseurl = Yii::getAlias("@appRoot") //"http://www.beachclubippocampo.rentals";
?>

<style type="text/css">
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
         /*
        Label the data
        */
        .table > tbody > tr > td.my-cart:nth-of-type(1):before { content: "ID"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(2):before { content: "<?=Yii::t('messages', 'Username')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Address')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Email')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Country')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Phonenumber')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', 'Check In')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(8):before { content: "<?=Yii::t('messages', 'Check Out')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(9):before { content: "<?=Yii::t('messages', 'Sunshade')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(10):before { content: "<?=Yii::t('messages', 'Paid / Total (€)')?>"; text-align: left; }

        .form-control{padding: 6px 3px;}

        .table > tbody > tr > td.my-cart, .table > tfoot > tr > td.my-cart {
            /* Behave  like a "row" */
            /*border: 2px;*/
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 150px;
        }

        .container-fluid.azz
        {
            padding-left: 10px;
            padding-right: 10px;
        }

        .container-fluid.azz .container
        {
            padding-left: 0px;
            padding-right: 0px;
        }
    }
</style>

<div class="guest-detail">
	<div id="zoom_container">
        <img id="sunshademap" src="<?=Yii::getAlias('@web')?>/../img/beachdev.jpg"/>
        <div class="landmarks" data-show-at-zoom="100" data-allow-drag="true" data-allow-scale="false"> 
        </div>
    </div>

    <div class="page-header">
        <span class="sendbookinfo pull-left btn btn-success m-b-10" style="margin-right: 10px"> 
         <?=Yii::t('messages', 'Send Via Email')?>
        </span>

        <span class="createPDF pull-left btn btn-success m-b-10" style="margin-right: 10px"> 
            <?=Yii::t('messages', 'Create PDF for Book Info')?>
        </span> 
        <span class="print pull-left btn btn-success m-b-10"> 
            <?=Yii::t('messages', 'Print Book Info')?>
        </span>
        <div class="clearfix"></div>
    </div>
   <div class="card card-padding">
        <div class="card-header">

        </div>
        <div class="table-responsive card-body card-padding">
            <table id="data-table-command" class="table display hover lala" style="width: 100%">
                <thead class="my-cart">
                    <tr class="my-cart">
                        <th class="my-cart" data-column-id="id" data-type="numeric">ID</th>
                        <th class="my-cart  text-center" data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                        <th class="my-cart  text-center" data-column-id="address"><?=Yii::t('messages', 'Address')?></th>
                        <th class="my-cart  text-center" data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                        <th class="my-cart" data-column-id="phonenumber"><?=Yii::t('messages', 'Phonenumber')?></th>
                        <th class="my-cart  text-center" data-column-id="country"><?=Yii::t('messages', 'Country')?></th>
                        <th class="my-cart  text-center" data-column-id="checkin"><?=Yii::t('messages', 'Check In')?></th>
                        <th class="my-cart  text-center" data-column-id="checkout"><?=Yii::t('messages', 'Check Out')?></th>
                        <th class="my-cart  text-center" data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
                        <th class="my-cart  text-center" data-column-id="paidprice"><?=Yii::t('messages', 'Paid / Total')?>&nbsp;(€)</th>
                        <th class="my-cart  text-center" data-column-id="commands" dadata-sortable="false"></th>
                    </tr>
                </thead>
                <tbody>
                <?php for ($i = 0; $i < count($guest); $i++) {?>
                    <tr class="my-cart">
                        <td class="my-cart  text-center"><?=$i+1?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['username']?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['address']?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['email']?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['phonenumber']?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['country']?></td>
                        <td class="my-cart  text-center"><?=date_create($guest[$i]['checkin'])->format('d M, Y')?></td>
                        <td class="my-cart  text-center"><?=date_create($guest[$i]['checkout'])->format('d M, Y')?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['sunshade']?></td>
                        <td class="my-cart  text-center"><?=$guest[$i]['paidprice']?>/<?=$guest[$i]['price']?></td>
                        <td class="my-cart text-center">
                            <button type="button" class="btn btn-icon command-view waves-effect waves-circle" 
                            data-row-booklookupid = "<?=$guest[$i]['booklookupId']?>"
                            data-row-bookid="<?=$guest[$i]['bookId']?>" data-row-sunshadeid="<?=$guest[$i]['Id']?>" title="<?=Yii::t('messages', 'View')?>"><span class="zmdi zmdi-view-toc zmdi-hc-fw"></span></button>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <form id="guest-form" method="post" action="<?= Yii::$app->urlManager->createUrl(["guest/sendbulkemail"]) ?>">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <input type="hidden" name="id" value= "<?=$id?>" /> 
        <input type="hidden" name="lang" value= "<?=Yii::$app->language?>" /> 
    </form>
</div>

<script type="text/javascript">
    lang = '<?=$lang?>';
    seat = '';

    ;(function($) {
        $(document).ready(function() {
            var option_lang =  "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
            <?php if (Yii::$app->language == "it") : ?>
                option_lang = "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
            <?php endif ?>
            var grid = $("#data-table-command").DataTable({
                dom: 'Bfrtip',
                  buttons: [
                      'copyHtml5',
                      'excelHtml5',
                      'csvHtml5',
                      'pdfHtml5',
                      'print'
                  ],
                "pagingType": "full_numbers",
                "language": {
                    "url" : option_lang
                }
            });
            
            $('.command-view').click(function(){
                var sunshadeId = $(this).data('row-sunshadeid');
                var bookId = $(this).data('row-bookid');
                var booklookupId = $(this).data('row-booklookupid');

                if(sunshadeId != undefined) {
                    location.href = '<?=Url::to(["book-lookup/view", 'guestId' => $id, 'lang'=>Yii::$app->language])?>' + '&sunshadeId=' + sunshadeId + '&bookId=' + bookId + '&booklookupId=' + booklookupId;
                }
            })

        	$('.sendbookinfo').click(function() {
	            $('#guest-form').submit();
	        });

	        $('.createPDF').click(function() {
	            window.location.href = '<?= Yii::$app->urlManager->createUrl(["guest/pdfall"]) ?>?id=<?=$id?>';
	        });

	        $('.print').click(function() {
	             $.print("#data-table-command");
	            
	        });

        	function checkPosition() {
                if (window.matchMedia('(max-width: 767px)').matches) {
                    tagWidth = 8;
                    tagFontSize = 3;
                    tagHeight = 8;
                    dy = 4;
                    dx = 1;
                } else {
                }
            }    
        
            checkPosition();

            function Demo_Function () {
                $('#sunshademap').smoothZoom('addLandmark', 
                [
                    <?php 
                      for ($i = 0; $i < count($jsonValue); $i++) {
                        $mark_image_name = "";
                        if ($jsonValue[$i]['bookstate'] == "booked") {
                            $mark_image_name = "red";
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

        });
    })(jQuery);
</script>