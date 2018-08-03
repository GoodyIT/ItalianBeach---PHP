<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$frontendLink = "http://www.doitweb.it/sunticketsDemo";
$buyerEmail = "sales-buyer@memoriesoffices.com";
$password = "";
?>

<p>
 <?=Yii::t('messages', 'Hi,')?>
</p>

<p>
 <?=Yii::t('messages', 'You’re very welcome to test our DEMO on the frontend part at this link:')?>
</p>

<p>
	http://www.doitweb.it/sunticketsDemo
</p>

<p>
<?=Yii::t('messages', 'You can use these credentials on PayPal when the booking ends up:')?>
</p>

<p>
<strong><?=Yii::t('messages', 'Username')?>:</strong> sales-buyer@memoriesoffices.com
</p>

<p>
<strong><?=Yii::t('messages', 'Password')?>:</strong> TestingMemories2016
</p>
