<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = Yii::t('messages', 'Send Book Info');
$this->params['breadcrumbs'][] = Yii::t('messages', 'Book Info');
?>

<script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jQuery.print.js"></script>

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
            selection: true,
            multiSelect: true,
            rowSelect: true,
            keepSelection: true,
            formatters: {
                commands: function (column, row)
                {
                    return "<button type=\"button\" class=\"btn btn-icon command-view waves-effect waves-circle\" data-row-id=\"" + row.sunshadeId + "\"><span class=\"zmdi zmdi-view-toc zmdi-hc-fw\"></span></button> ";
                }
            },
        });

        grid.on("loaded.rs.jquery.bootgrid", function(e){
            grid.find(".command-view").on("click", function(e)
            {
                 var id = $(this).data('row-id');

                if(id != undefined)
                    location.href = '<?=Url::to(["guest/detailview"])?>' + '/' + id +'?lang='  + '<?=Yii::$app->language?>';
           });
        });

        $('.delete-booking').on('click', function(e) {
            e.preventDefault();
            if ($("#data-table-command").bootgrid('getSelectedRows').length < 1) return;

          $('#confirm-modal').modal('show');                 
            
        });
    });
})(jQuery);
</script>



<div class="guest-view">
          <div class="card">
                <div class="card-header">
                    <div class="pull-right">
                        <button class="btn btn-danger waves-effect delete-booking"><?=Yii::t('messages', 'Delete Booking')?></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="data-table-command" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-column-id="sunshadeId" data-identifier="true"  data-type="numeric" data-visible="false">sunshadeId</th>
                                <th data-column-id="id"  data-type="numeric">ID</th>
                                <th data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                                <th data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                                <th data-column-id="phonenumber"><?=Yii::t('messages', 'Phonenumber')?></th>
                                <th data-column-id="arrival"><?=Yii::t('messages', 'Arrival Date')?></th>
                                <th data-column-id="checkout"><?=Yii::t('messages', 'Checkout Date')?></th>
                                <th data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
                                <th data-column-id="commands" data-formatter="commands" data-sortable="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < count($guest); $i++) {?>
                            <tr>
                                <td><?=$guest[$i]['Id']?></td>
                                <td><?=$i+1?></td>
                                <td><?=$guest[$i]['username']?></td>
                                <td><?=$guest[$i]['email']?></td>
                                <td><?=$guest[$i]['phonenumber']?></td>
                                <td><?=$guest[$i]['arrival']?></td>
                                <td><?=$guest[$i]['checkout']?></td>
                                <td><?=$guest[$i]['sunshadeseat']?></td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
</div>

<div class="modal confirm-modal fade" id="preventClick" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper. Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla. Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link">Save changes</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
