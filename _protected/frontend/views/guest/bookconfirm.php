<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Books');
?>

<div class="container row top-padding">
    <div class="col-sm-5">
        <?= $this->render('bookinfo');?>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><?=  Yii::t('messages', 'Book Confirmation');?></h3>
            </div>
            <div class="panel-body">
                <form  action="<?= Url::to(['guest/bookplace']);?>"
                        class="simple_form form-horizontal new_order" id="order"
                        method="post" novalidate="novalidate">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <div class='row' style="padding-left: 30px">
                            <span><?=  Yii::t('messages', 'Total Amount');?> (€)  <?= $amount;?> </span>
                            <input id="order_amount" name="book[amount]" type="hidden" value="<?=$amount;?>" />
                        </div>
                        <div class='row' style="padding-left: 30px">
                            <span><?=  Yii::t('messages', 'Description');?>  <?= $description;?></span>
                            <input id="book_description" name="book[description]" type="hidden" value="<?= $description;?>" />
                        </div>
                        <div class="row" style="padding-left: 30px">
                             <span><?=  Yii::t('messages', 'Payment method');?> <?= $payment;?></span>
                             <input id="book__payment_method" name="book[payment_method]" type="hidden" value="paypal" />
                        </div>
                        <div class='row' style="padding-left: 30px">
                             <button class="btn btn btn-primary" name="commit" type="submit"><?=  Yii::t('messages', 'Place Book');?></button> 
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
