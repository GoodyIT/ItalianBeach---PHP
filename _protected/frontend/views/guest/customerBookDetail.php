
<h4  style="background: url(http://beachclubippocampo.rentals/img/disponibile.png) left center no-repeat; min-height: 55px;">
    <a href="www.doitweb.it/sunticketsDemo" style="display: flex; padding-left: 60px; padding-top: 8px;  color: blue"><strong>SunTickets</strong>
    </a>
</h4>

<h3>
    <b>
        <?php
            if ($array["sunshade"][0] == 1) 
            {
                echo Yii::t('messages', 'Book');
            } else {
                echo Yii::t('messages', 'Sunshade');
            }
        ?> 
    :</b> <?=  $array["sunshade"]?> 
</h3>

<p>
    <b>- <?= Yii::t('messages', 'Service Type')?> :</b> <?=$array["servicetype"]?>
</p>

<p>
    <b>- <?=Yii::t('messages', 'Number of Guests')?> :</b> <?= $array["guests"]?> 
</p>

<p>
    <b>- <?=Yii::t('messages', 'Paid Money')?> (€) : </b> <?=$array["paidprice"]?> / <?=$array["price"]?> 
</p>

<p>
    <b>- <?=Yii::t('messages', 'Username')?> :</b> <?=$array["username"]?> 
</p>

<p>
    <b>- <?=Yii::t('messages', 'Address')?> :</b>  <?=$array["address"]?>
</p>
        
<p>
    <b>- <?=Yii::t('messages', 'Email')?> :</b> <?=$array["email"]?>
</p>

<p>
    <b>- <?=Yii::t('messages', 'Phone Number')?> :</b> <?=$array["phonenumber"]?>
</p>

<p>
    <b>- <?=Yii::t('messages', 'Check In')?> :</b> 
        <?=date_create($array["checkin"])->format('d M, Y')?>
</p>

<p>
    <b>- <?=Yii::t('messages', 'Check Out')?> :</b> <?=date_create($array["checkout"])->format('d M, Y')?> 
</p>

--------------------------------------------------------