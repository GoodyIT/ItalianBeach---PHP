<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Bookinfo */
/* @var $form yii\widgets\ActiveForm */
$listData = array("available"=>"Available","booked"=>"Booked");
?>

<div class="bookinfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bookstate')->dropDownList($listData)?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('messages', 'Create') : Yii::t('messages', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
