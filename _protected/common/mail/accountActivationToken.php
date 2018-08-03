<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/activate-account', 'lang' => Yii::$app->language,
    'token' => $user->account_activation_token]);
?>
<p>
	<?=Yii::t('messages', 'Hello ')?><?= Html::encode($user->username) ?>,
</p>

<p>
	<?=Yii::t('messages', 'Follow this link to activate your account: ')?>
</p>

<p>
	<?= Html::a(Yii::t('messages', 'Please, click here to activate your account.'), $resetLink) ?>
</p>
