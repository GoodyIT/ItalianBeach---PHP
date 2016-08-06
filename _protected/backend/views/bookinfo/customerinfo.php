<?php
    use yii\helpers\Html;
?>

<div class="card m-t-20">
    <div class="card-header">
        <h2><i class="zmdi zmdi-account m-r-5"></i><?=Yii::t('messages', 'Customer Information')?></h2>
    </div>
    <div class="card-body p-l-20 p-b-20">
        <div class="pmbb-view">
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Firstname')?></dt>
                <dd><?=$guest['firstname']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Lastname')?></dt>
                <dd><?=$guest['lastname']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Email')?></dt>
                <dd><?=$guest['email']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Country')?></dt>
                <dd><?=$guest['country']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Phonenumber')?></dt>
                <dd><?=$guest['phonenumber']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Address')?></dt>
                <dd><?=$guest['address']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'City')?></dt>
                <dd><?=$guest['city']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Zipcode')?></dt>
                <dd><?=$guest['zipcode']?></dd>
            </dl>
            <dl class="dl-horizontal">
                <dt><?=Yii::t('messages', 'Number of Booking')?></dt>
                <dd><?=$guest['recurringcount']?></dd>
            </dl>

            <input type="hidden" name="lang" value="<?=Yii::$app->language?>">
        </div>
    </div>
</div>