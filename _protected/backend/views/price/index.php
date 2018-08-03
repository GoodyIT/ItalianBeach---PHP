<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\Pricesearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('messages', 'Prices');
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
         /*
        Label the data
        */
        .table > tbody > tr > td.my-cart:nth-of-type(1):before { content: "ID"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(2):before { content: "<?=Yii::t('messages', 'Sunshades Row')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Service')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Main Price')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Add.Pass')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Supplement')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', 'Max Guests')?>"; text-align: left;}

        .form-control{padding: 6px 3px;}

        .container-fluid.azz
        {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>

<div class="price-index m-b-30">
    <h1>
        <?= Html::a(Yii::t('messages', 'Add Price Service'), ['create', 'lang' => Yii::$app->language], ['class' => 'btn btn-success']) ?>
    </h1>
    <div class="card card-padding">
        <div class="card-body card-padding">
            <div class="table-responsive">
                <table id="price-data" class="table table-condensed hover table-striped table-vmiddle my-cart">
                    <thead class="my-cart">
                        <tr class="my-cart">
                            <th class="my-cart text-center" >ID</th>
                            <th class="my-cart text-center" ><?=Yii::t('messages', 'Sunshades Row')?></th>
                            <th class="my-cart"><?=Yii::t('messages', 'Service')?></th>
                            <th class="my-cart"><?=Yii::t('messages', 'Main Price')?></th>
                            <th class="my-cart"><?=Yii::t('messages', 'Add.Pass')?></th>
                            <th class="my-cart"><?=Yii::t('messages', 'Supplement')?></th>
                            <th class="my-cart" ><?=Yii::t('messages', 'Max Guests')?></th>
                            <th class="my-cart" data-sortable="false"></th>
                        </tr>
                    </thead>
                    <tbody class="my-cart">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var lang = '<?=Yii::$app->language?>';
    var arrayOfPrices = <?=json_encode($prices)?>; 
    var viewUrl = '<?=Url::to(['price/view', 'lang'=>Yii::$app->language])?>';
    var updateUrl = '<?=Url::to(['price/update', 'lang'=>Yii::$app->language])?>';
    var deleteUrl = '<?=Url::to(['price/delete'])?>';
    var msg_canceled = "<?=Yii::t('messages', 'Cancelled!')?>";
    var title_cancel = "<?=Yii::t('messages', 'The information of the current information remains safe')?>";
    var msg_yes = "<?=Yii::t('messages', 'Yes')?>";
    var msg_no = "<?=Yii::t('messages', 'No')?>";
    var msg_sure = "<?=Yii::t('messages', 'Are you sure?')?>";
    var title_modal = "<?=Yii::t('messages', 'The selected booking information(s) will be deleted!')?>";
    var tooltip_view = "<?=Yii::t('messages', 'View')?>";
    var tooltip_edit = "<?=Yii::t('messages', 'Edit')?>";
    var tooltip_delete = "<?=Yii::t('messages', 'Delete')?>";

    var option_lang =   "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
    <?php if (Yii::$app->language == "it") : ?>
        option_lang =   "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
    <?php endif ?>
</script>