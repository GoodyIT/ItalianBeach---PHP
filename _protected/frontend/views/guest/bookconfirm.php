<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('messages', 'Book Confirmation');
?>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <form id="book-place" action="<?= Url::to(['guest/bookplace']);?>"
                class="prenotazione step_1_right" id="order" method="post" novalidate="novalidate">
                <div class='row p-l-15'>
                    <strong class="col-md-5 text-right" ><?=  Yii::t('messages', 'Total Amount');?> (€)</strong>
                    <div class="col-md-7 total-price"><?=$totalPrice?></div>
                </div>
                <div class="row p-l-15">
                     <strong class="col-md-5 text-right" ><?=  Yii::t('messages', 'Description');?></strong>
                     <div class="col-md-7"> <?=Yii::t('messages', 'Sunshade Booking')?></div>
                </div>
                <div class="row p-l-15">
                     <strong class="col-md-5 text-right" ><?=  Yii::t('messages', 'Payment method');?></strong>
                     <div class="col-md-7"> <?=Yii::t('messages', 'PayPal or Credit Card')?></div>
                </div>
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input name="book[description]" value="<?=Yii::t('messages', 'Sunshade Booking')?>" type="hidden" />
                <input name="book[amount]" type="hidden" class="total-price-input" value="<?=$totalPrice?>" />
                <input name="book[payment_method]" type="hidden" value="PayPal" />
                <input type="hidden" name="lang" value="<?=Yii::$app->language?>">
                <br>
                <div class="form-group align-right logo_sx " style="margin-top: 20px; overflow: auto; padding-right: 50px;">
                    <div class="col-md-12 col-sm-8">
                        <button type="submit" class="btn btn-success btn-bookconfirm text-uppercase"><?=  Yii::t('messages', 'Place Book');?>
                        </button>                    
                    </div>            
                </div>  
            </form>
        </div>
        <div class="col-md-8">
            <div class="prenotazione step_1">
                <?=$this->render('cartinfo', ['price' => $price, 'myCart' => $myCart])?>
            </div>
        </div>
    </div>  
</div>  
