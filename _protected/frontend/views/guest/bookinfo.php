<?php

    use frontend\models\Servicetype;
    use yii\helpers\Url; 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $cookies = Yii::$app->request->cookies;
    if (($cookie = $cookies->get('sunshadeseat')) !== null){ 
        $sunshadeseat = $cookie->value;
    }
    
    if (($cookie = $cookies->get('arrival')) !== null){ 
            $arrival = $cookie->value;
    }
    
    if (($cookie = $cookies->get('price')) !== null) { 
            $price = $cookie->value;  
    }
    
    if (($cookie = $cookies->get('mainprice')) !== null) {
            $mainprice = $cookie->value;  
    }
    
    if (($cookie = $cookies->get('tax')) !== null) {
            $tax = $cookie->value;  
    }
    
    if (($cookie = $cookies->get('supplement')) !== null) {
            $supplement = $cookie->value;  
    }

    if (($cookie = $cookies->get('servicetype')) !== null) {
            $servicetype = $cookie->value;
            $servicetype = Servicetype::find()->where(['id' => $servicetype])->one();
    }
    
    if (($cookie = $cookies->get('guests')) !== null) {
            $guests = $cookie->value;
    }
?>
<div class="panel panel-warning">
    <div class="panel-heading" style="color: white; font-size: 14px; padding: 10px 15px;">
        <h3 class="panel-title"><?=Yii::t('messages', 'Booking Info')?></h3>
    </div>
    <div class="panel-body">
        <div class="row" style="padding-left: 30px"><strong><?=Yii::t('messages', 'Sunshadeseat')?>:</strong>  <?= $sunshadeseat?> <a style="margin-left: 0.5em;" href="<?= Url::to(['site/index', 'lang' => Yii::$app->language]);?>"><span> <?=Yii::t('messages', 'CHANGE SUNSHADE')?></span>  <i class="fa fa-chain" aria-hidden="true"></i></a></div>

        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Arrival')?>:</strong>  <?= $arrival?></div>

        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Guests')?>:</strong>  <?= $guests?></div>

        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Total Price')?>(€):</strong>  <?= $price?></div>
        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Main Price')?>(€):</strong>  <?= $mainprice?></div>
        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Tax')?>(€):</strong>  <?= $tax?></div>
        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Supplement')?>(€):</strong>  <?= $supplement?></div>

        <div class="row" style="padding-left: 30px;"><strong><?=Yii::t('messages', 'Service')?>:</strong> <?= $servicetype->servicename?> <a href="<?= Url::to(['site/sunshade', 'id'=>$sunshadeseat, 'lang' => Yii::$app->language]);?>" style="margin-left: 1.5em;"><span> <?=Yii::t('messages', 'Change Booking')?></span>  <i class="fa fa-chain" aria-hidden="true"></i></a> </div>

    </div>
</div>
