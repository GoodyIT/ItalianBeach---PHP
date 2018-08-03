<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'lang' => Yii::$app->language,
    'token' => $user->password_reset_token]);
?>

<p>
<?=Yii::t('messages', 'Hello ')?><?= Html::encode($user->username) ?>,
</p>

<p>
<?= Html::a(Yii::t('messages', 'Please, click here to reset your password.'), $resetLink) ?>
</p>
