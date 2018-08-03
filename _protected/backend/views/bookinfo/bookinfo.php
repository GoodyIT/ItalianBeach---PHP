<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use frontend\models\Guest;
use frontend\models\Book;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('messages', 'common.reserve');
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    ;(function($) {
        $(function () {
            function notify(title, message){
                $.notifyClose('all');

                $.notifyDefaults({
                    placement: {
                        from:'top',
                        align:'center'
                    },
                    animate: {
                        enter: 'animated bounceInDown',
                        exit: 'animated bounceOutUp'
                    },
                    offset: {
                        x: 20,
                        y: 50
                    }
                });
                $.notify({
                    message: "<strong>" + title + "</strong>"
                },{
                    type: 'danger',
                    allow_dismiss: true,    
                    delay: 10000,
                });
            };

            $('body').on('click', '.change-bookstate', function(e){
                var self = $(this);
                e.preventDefault();

                 swal({   
                        title: "<?=Yii::t('messages', 'Are you sure?')?>",   
                        text: "<?=Yii::t('messages', 'Do you want to change the state of this sunshade?')?>",   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "<?=Yii::t('messages', 'Yes')?>",   
                        cancelButtonText: "<?=Yii::t('messages', 'No')?>",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm) {
                            doStopedAction(self.attr('href'));
                        } else {     
                            swal("<?=Yii::t('messages', 'Cancelled!')?>", "<?=Yii::t('messages', 'The information of the current sunshade remains safe')?>", "error");   
                            return false;
                        } 
                    }); 
            });

            function doStopedAction(href) {
                window.location.href = href;
            }

            function getSelectedRow() {
                var selection = [];
                $.each($("input[name='selection[]']:checked"), function(){
                    selection.push($(this).val());
                });
                return selection;
            }

            function doConfirm() {
                 swal({   
                    title: "<?=Yii::t('messages', 'Are you sure?')?>",   
                    text: "<?=Yii::t('messages', 'Do you want to change the state of this sunshade?')?>",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "<?=Yii::t('messages', 'Yes')?>",   
                    cancelButtonText: "<?=Yii::t('messages', 'No')?>",   
                    closeOnConfirm: false,   
                    closeOnCancel: false 
                }, function(isConfirm){   
                    if (isConfirm) {
                        $('form').submit();
                    } else {     
                        swal("<?=Yii::t('messages', 'Cancelled!')?>", "<?=Yii::t('messages', 'The information of the current sunshade remains safe')?>", "error");   
                        return false;
                    } 
                }); 
            }

            $('body').on('click', '.bulkupdate', function() {
                var keys =  getSelectedRow();   
                if (keys.length == 0) {
                    notify('<?=Yii::t("messages", "Please select sunshades you want change")?>');
                    return;
                } else if(keys.length >= 1) {
                    $.ajax({
                        type: 'post',
                        url: '<?=Url::to(["bookinfo/checkbulkbookavailability"])?>',
                        data: {
                            selection: keys,
                            _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
                        },
                        success: function(msg){
                            if (msg == '1') {
                                doConfirm();
                            } else {
                                notify('<?=Yii::t("messages", "They have no common service type")?>');
                            }
                        }
                    });
                }
            });
        });
    })(jQuery);
</script>


<div class="book-index  m-b-25">
<?php Pjax::begin(); ?>
<?=Html::beginForm(['guest/recursivebooking', 'lang' => Yii::$app->language],'post');?>
    <?=Html::Button(Yii::t('messages', 'Change / Make a Reservation'), ['class' => 'btn btn-success bulkupdate',]);?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>"{pager}\n{summary}\n{items}",
        'showFooter'=>true,
        'showHeader' => true,
        'showOnEmpty'=>false,
        'emptyCell'=>'-',
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => '#',
                'format' => 'raw',
                'value' => function ($model) {                      
                        return '<div>'.$model->Id.'</div>';
                },
            ],
            [
                'attribute' => 'seat',
                'label' => Yii::t('messages', 'Sunshade'),
                'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'tbl_bookinfo'];
                },
                'content'=>function($data){
                    $value = $data['seat'];
                    $params = array_merge(["bookinfo/view"], ['id'=>$data['Id'], 'lang' =>Yii::$app->language]);

                    $href = Yii::$app->urlManager->createUrl($params);
                    $result = "<a href='". $href ."'>" . $value ." <i class='zmdi zmdi-view-list-alt zmdi-hc-fw'></i></a>";
                    return $result;
                }   
            ],
            [
                'attribute' =>'bookstate',
                'label' =>Yii::t('messages', 'Book State'),
                'filter'=>array("available"=>"Available","booked"=>"Booked", "booking"=>"Booking"),
		        'content'=>function($data){
                    $value = $data['bookstate'];
                    $params = array_merge(["guest/recursivebooking"], ['selection'=>$data['Id'], 'lang' =>Yii::$app->language]);
                    $href = Yii::$app->urlManager->createUrl($params);              
                    $result = "<a class='change-bookstate' href='". $href ."' title='" . Yii::t('messages', 'Change / Make a Reservation') . "'>" . $value ." <i class= 'fa fa-pencil-square-o fa-2'></i></a>";
                    return $result;
		          }
            ],
            [
                'attribute'=>'Guest Detail',
                'label' => Yii::t('messages', 'Guest Detail'),
                'filter' => '',
                'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'tbl_column_name'];
                },
                'content'=>function($data){
                   if ($data['guestId']) {
                        $sql = sprintf('SELECT firstname, lastname FROM tbl_guest where Id=%s', $data['guestId']);
                        $guest = Guest::findBySql($sql)->one();
                       $value = $guest['firstname'] . ' ' . $guest['lastname'] . " <i class='zmdi zmdi-view-list-alt zmdi-hc-fw'></i>";
                    }
                    else {
                        $value = "-";
                    }
                    $params = array_merge(["guest/view"], ['id'=>$data['guestId'], 'lang' =>Yii::$app->language]);
                    $href = Yii::$app->urlManager->createUrl($params);
                    if ($data['guestId'] == null){
                        $href = '#';
                    }
                    $result = "<a href='". $href ."'>" . $value ."</a>";
                    return $result;
                }
            ], 
            [
                'attribute'=>'bookingdate',
                'label' => Yii::t('messages', 'Book Date'),
                'filter' => '',
                'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'tbl_column_name'];
                },
                'content'=>function($data){
                    $value = $data['bookingdate'] ? $data['bookingdate'] : "-";
                    return $value;
                }
            ],         
           
        ],
    
    ]); ?>

<?= Html::endForm();?> 
<?php Pjax::end(); ?>
</div>

