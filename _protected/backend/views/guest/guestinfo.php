<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = Yii::t('messages', 'Customers Info');
$this->params['breadcrumbs'][] = Yii::t('messages', 'Customers Info');

?>

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
                    <th data-column-id="sunshadeId" data-type="numeric" data-visible="false">sunshadeId</th>
                    <th data-column-id="id" data-type="numeric">ID</th>
                    <th data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                    <th data-column-id="country"><?=Yii::t('messages', 'Country')?></th>
                    <th data-column-id="state"><?=Yii::t('messages', 'State')?></th>
                    <th data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                    <th data-column-id="phonenumber"><?=Yii::t('messages', 'Telephone/Mobile')?></th>
                    <th data-column-id="price"><?=Yii::t('messages', 'Total Paid')?>&nbsp;(€)</th>
                    <th data-column-id="numberOfBooking"><?=Yii::t('messages', '# of Bookings')?></th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false"></th>
                </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($guest); $i++) {?>
                <tr>
                    <td><?=$guest[$i]['Id']?></td>
                    <td><?=$i+1?></td>
                    <td><?=$guest[$i]['username']?></td>
                    <td><?=$guest[$i]['country']?></td>
                    <td><?=$guest[$i]['state']?></td>
                    <td><?=$guest[$i]['email']?></td>
                    <td><?=$guest[$i]['phonenumber']?></td>
                    <td><?=$guest[$i]['paidprice']?></td>
                    <td><?=$guest[$i]['numberOfBooking']?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
    </div>
    </form>
</div>

<div class="modal fade" id="recursive-booking" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?=Yii::t('messages', 'Please select the sunshade or room you want to make a reservation.')?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <span class="col-sm-2">
                        <?=  Html::dropDownList('List', [], $sunshadeList);?>
                    </span>
                     <span class="col-sm-2">
                        <span class="bookstate">
                            <?=$availableSunshadePair[1]?>
                        </span>
                     </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link recurise-book"><?=Yii::t('messages', 'Reserve');?></button>
                <button type="button" class="btn btn-link" data-dismiss="modal"><?=Yii::t('messages', 'Cancel');?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
;(function($) {
    $(function () {
        var availableSunshadePair = '<?=json_encode($availableSunshadePair)?>';
        availableSunshadePair = JSON.parse(availableSunshadePair);
        $('select').change(function(){
            var selectedSunshade = $(this).children("option:selected").val();
            console.log(availableSunshadePair[selectedSunshade]);
            $('.bookstate').html(availableSunshadePair[selectedSunshade]);
        })

        var id = 0;
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
                    return "<button type=\"button\" class=\"btn btn-icon command-edit waves-effect waves-circle\" data-row-id=\"" + row.sunshadeId + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + "<button type=\"button\" class=\"btn btn-icon command-view waves-effect waves-circle\" data-row-id=\"" + row.sunshadeId + "\"><span class=\"zmdi zmdi-view-toc zmdi-hc-fw\"></span></button>" + "<button type=\"button\" class=\"btn btn-icon command-plus waves-effect waves-circle\" data-row-id=\"" + row.sunshadeId + "\"><span class=\"zmdi zmdi-plus\"></span></button> ";
                }
            },
            rowSelect: true,
            selection: true,
        }).
        on("loaded.rs.jquery.bootgrid", function(e){
            grid.find(".command-view").on("click", function(e)
            {
                id = $(this).data('row-id');

                if(id != undefined)
                    location.href = '<?=Url::to(["guest/guestdetail"])?>' + '/' + id +'?lang='  + '<?=Yii::$app->language?>';
           }).end().find(".command-edit").on("click", function(e){
                id = $(this).data('row-id');

                if(id != undefined)
                    location.href = '<?=Url::to(["guest/update"])?>' + '/' + id +'?lang='  + '<?=Yii::$app->language?>';
           }).end().find(".command-plus").on("click", function(e){
                id = $(this).data('row-id');
                if(id != undefined) {
                    $('#recursive-booking').modal('show');
                }
           });
        });

        $('body').on('click', '.recurise-book', function() {
           var seletedId =  $('select option:selected').val();
           location.href = '<?=Url::to(["bookinfo/bookupdate"])?>' + '/' + id + '?selectedId=' + seletedId +'&lang='  + '<?=Yii::$app->language?>';
        });
    });
})(jQuery);
</script>
