<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $role common\rbac\models\Role; */

$this->title = Yii::t('messages', 'Update User') . ': ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Update');
?>
<div class="user-update card col-lg-5">
<div class="card-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
<div class="card-body card-padding">

        <?= $this->render('_form', [
            'user' => $user,
            'role' => $role,
        ]) ?>

</div>
</div>
