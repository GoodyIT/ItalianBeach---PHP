<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $role common\rbac\models\Role */

$this->title = Yii::t('messages', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create card">
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

