<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
?>
<h4 >
    <a href="http://beachclubippocampo.rentals" style="display: flex; padding-left: 60px; padding-top: 8px;  color: blue"><img src="http://beachclubippocampo.rentals/img/disponibile.png"><strong>Beach Club Ippocampo</strong>
    </a>
</h4>

<div class="guest-view hidden">
    <form id="sendinfo-form" method="post">
        
        <section id="tables" class="printableArea">
            <table class="table table-striped table-inner table-vmiddle">
                <tbody>
                    <tr>
                        <th><?=Yii::t('messages', 'Name & Surname')?></th> <td><?=$model['username']?></td>
                    </tr>
                    <tr>
                        <th><?=Yii::t('messages', 'Address')?></th><td><?=$model['address']?></td>
                    </tr>
                    <tr>
                        <th><?=Yii::t('messages', 'Email')?></th> <td><?=$model['email']?></td>
                    </tr>
                    <tr>
                        <th><?=Yii::t('messages', 'Phonenumber')?></th><td><?=$model['phonenumber']?></td>
                    </tr>
                    <tr>    
                        <th><?=Yii::t('messages', 'Country')?></th> <td><?=$model['country']?></td>
                    </tr>
                    <tr>
                        <th><?=Yii::t('messages', 'checkin Date')?></th><td><?=$model['checkin']?></td>
                    </tr>
                    <tr>    
                        <th><?=Yii::t('messages', 'Checkout Date')?></th><td><?=$model['checkout']?></td>
                    </tr>
                    <tr>    
                        <th><?=Yii::t('messages', 'Sunshade')?></th><td><?=$model['sunshade']?></td>
                    </tr>
                    <tr>    
                        <th><?=Yii::t('messages', 'Guests')?></th><td><?=$model['guests']?></td>
                    </tr>
                    <tr>    
                        <th><?=Yii::t('messages', 'Paid / Total')?>&nbsp;(€)</th><td><?=$model['paidprice']?>/<?=$model['price']?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </form>
</div>
