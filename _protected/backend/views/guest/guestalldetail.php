<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = Yii::t('messages', 'Guest Detail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Customers Info'), 'url' => ['guest/guestinfo', 'lang' => Yii::$app->language]];

$this->params['breadcrumbs'][] = $this->title = Yii::t('messages', 'Guest Detail');
?>
<script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jQuery.print.js"></script>

<div class="guest-view">
        <form id="sendinfo-form" method="post">
            <div class="card">
                        <div class="card-header">
                            <h2></h2>
                        </div>
                        <div class="table-responsive">
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-type="numeric">ID</th>
                                    <th data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
                                    <th data-column-id="arrive"><?=Yii::t('messages', 'Arrive')?></th>
                                    <th data-column-id="paidprice"><?=Yii::t('messages', 'Total Paid')?>&nbsp;(€)</th>
                                    <th data-column-id="price"><?=Yii::t('messages', 'Total Price')?>&nbsp;(€)</th>
                                    <th data-column-id="booked"><?=Yii::t('messages', 'State')?></th>
                                    <th data-column-id="servicetype"><?=Yii::t('messages', 'Service Type')?></th>
                                    <th data-column-id="checkout"><?=Yii::t('messages', 'Checkout Date')?></th>
                                    <th data-column-id="bookingdate"><?=Yii::t('messages', 'Booking date')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 0; $i < count($guest); $i++) {?>
                                <tr>
                                    <td><?=$i+1?></td>
                                    <td><?=$guest[$i]['sunshade']?></td>
                                    <td><?=$guest[$i]['arrival']?></td>
                                    <td><?=$guest[$i]['paidprice']?></td>
                                    <td><?=$guest[$i]['price']?></td>
                                    <td><?=$guest[$i]['bookstate']?></td>
                                    <td><?=$guest[$i]['servicetype']?></td>
                                    <td><?=$guest[$i]['checkout']?></td>
                                    <td><?=$guest[$i]['bookingdate']?></td>
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

        var grid = $("#data-table-command").bootgrid({
            caseSensitive: false,
            css: {
                icon: 'zmdi icon',
                iconColumns: 'zmdi-view-module',
                iconDown: 'zmdi-expand-more',
                iconRefresh: 'zmdi-refresh',
                iconUp: 'zmdi-expand-less'
            },
             formatters: {
                commands: function (column, row)
                {
                    return "<button type=\"button\" class=\"btn btn-icon command-view waves-effect waves-circle\" data-row-id=\"" + row.guestId + "\"><span class=\"zmdi zmdi-view-toc zmdi-hc-fw\"></span></button> ";
                }
            },
            rowSelect: true,
            selection: true,
        }).
        on("loaded.rs.jquery.bootgrid", function(e){
            grid.find("td").on("click", function(e){
                 var id = $('.command-view').data('row-id');

                if(e.target == this && id != undefined)
                    location.href = '<?=Url::to(["guest/guestdetail"])?>' + '/' + id +'?lang='  + '<?=Yii::$app->language?>';
            }).end().find(".command-view").on("click", function(e)
            {
                 var id = $(this).data('row-id');

                if(id != undefined)
                    location.href = '<?=Url::to(["guest/guestdetail"])?>' + '/' + id +'?lang='  + '<?=Yii::$app->language?>';
           });
        });

       
        });
})(jQuery);
</script>
