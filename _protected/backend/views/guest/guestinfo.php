<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = Yii::t('messages', 'Customers Info');
$this->params['breadcrumbs'][] = Yii::t('messages', 'Customers Info');

?>

<style type="text/css">
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
         /*
        Label the data
        */
        .table > tbody > tr > td.my-cart:nth-of-type(1):before { content: "ID"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(2):before { content: "<?=Yii::t('messages', 'Username')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Country')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Email')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Telephone/Mobile')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Total Paid')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', '# of Bookings')?>"; text-align: left;}

        .form-control{padding: 6px 3px;}

        .table > tbody > tr > td, .table > tfoot > tr > td.my-cart {
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

<div class="guest-view">
<form id="sendinfo-form" method="post">
    <div class="card card-padding">
        <div class="card-header">
        </div>
        <div class="card-body card-padding table-responsive">
        <table id="guestinfo-data" class="table display hover w-100">
            <thead class="my-cart">
                <tr class="my-cart">
                    <th class="my-cart text-center" data-column-id="id" data-type="numeric">ID</th>
                    <th class="my-cart text-center" data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                    <th class="my-cart text-center" data-column-id="country"><?=Yii::t('messages', 'Country')?></th>
                    <th class="my-cart text-center" data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                    <th class="my-cart text-center" data-column-id="phonenumber"><?=Yii::t('messages', 'Telephone/Mobile')?></th>
                    <th class="my-cart text-center" data-column-id="price"><?=Yii::t('messages', 'Total Paid')?>&nbsp;(€)</th>
                    <th class="my-cart text-center" data-column-id="numberOfBooking"><?=Yii::t('messages', '# of Bookings')?></th>
                    <th class="my-cart text-center" data-column-id="commands" data-sortable="false"></th>
                </tr>
            </thead>
            <tbody class="my-cart">
            <?php for ($i = 0; $i < count($guest); $i++) {?>
                <tr class="my-cart">
                    <td class="my-cart  text-center"><?=$i+1?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['username']?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['country']?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['email']?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['phonenumber']?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['totalpaid']?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['recurringcount']?></td>
                    <td class="my-cart  text-center">
                        <button type="button" class="btn btn-icon command-view waves-effect waves-circle" data-row-guestid="<?=$guest[$i]['guestId']?>" title="<?=Yii::t('messages', 'View')?>"><span class="zmdi zmdi-view-toc zmdi-hc-fw"></span></button>&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-icon command-plus waves-effect waves-circle bgm-green" data-toggle="modal" data-target="#recursive-booking" data-row-guestid="<?=$guest[$i]['guestId']?>" title="<?=Yii::t('messages', 'Delte')?>"><span class="zmdi zmdi-plus"></span></button>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
    </div>
    </form>
</div>

<script type="text/javascript">
;(function($) {
    $(function () {
        var option_lang =   "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
        <?php if (Yii::$app->language == "it") : ?>
            option_lang =   "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
        <?php endif ?>
        $('#guestinfo-data').DataTable({
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
            var guestId = $(this).data('row-guestid');
            if(guestId != undefined) {
                location.href = '<?=Url::to(["guest/guestdetail"])?>' +'?id=' + guestId + '&lang='  + '<?=Yii::$app->language?>';
            }
        });

        $('.command-plus').click(function(){
            var guestId = $(this).data('row-guestid');
                if(guestId != undefined) {
                    location.href = '<?=Url::to(["guest/mapbooking"])?>' + '/' + guestId + '?lang='  + '<?=Yii::$app->language?>';
                }
        });
    });
})(jQuery);
</script>
