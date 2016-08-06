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

            $('body').on('click', '.room-book', function() {
                var keys = $("input[name='selection[]']:checked");
                if (keys.length == 0 || keys.length > 1) {
                    notify('<?=Yii::t("messages", "Please select one room you want change")?>');
                    return;
                } 

                $('form').submit();
            });

        });
    })(jQuery);
</script>

<div class="book-index">
<?php Pjax::begin(); ?>
<?=Html::beginForm(['bookinfo/bulk'],'post');?>
    <?=Html::Button(Yii::t('messages', 'Book A Room'), ['class' => 'btn btn-success room-book',]);?>

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
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'seat',
                'label' => Yii::t('messages', 'Room'),
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
                    $params = array_merge(["bookinfo/bulk"], ['selection[]'=>$data['Id'], 'lang' =>Yii::$app->language]);
                    $href = Yii::$app->urlManager->createUrl($params);              
                    $result = "<a href='". $href ."' title='" . Yii::t('messages', 'Change / Make a Reservation') . "' data-confirm='" . Yii::t('messages', 'Are you sure you want to change the state of this sunshade?') . "'>" . $value ." <i class= 'fa fa-pencil-square-o fa-2'></i></a>";
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