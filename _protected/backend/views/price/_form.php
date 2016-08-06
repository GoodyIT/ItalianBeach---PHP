<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Servicetype;

/* @var $this yii\web\View */
/* @var $model frontend\models\Price */
/* @var $form yii\widgets\ActiveForm */
$listdata = ['A'=>'A', 'B'=>'B', 'C'=>'C', 'D'=>'D', 'E'=>'E', 'F'=>'F', '101'=>'101', '102'=>'102', '103'=>'103', '104'=>'104', '105'=>'105', '106'=>'106' ];
?>

<div class="price-form">

    <?php $form = ActiveForm::begin([
        'id' => 'price-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <?php /*echo $form->field($model, 'rowid')->dropDownList($listdata, ['options' => [
         $model->rowid => ['selected' => true]
     ]]); */ ?>
     <?php 
        if ($model->isNewRecord) {
            echo $form->field($model, 'rowid')->dropDownList($listdata, ['options' => [$model->rowid => ['selected' => true]
            ]]);
            echo '<div class="form-group field-price-servicetype_id">
                    <label class="control-label" for="price-servicetype_id">Service Type  ID</label>
                    <select id="price-servicetype_id" class="form-control hidden-servicetype" name="Price[servicetype_Id]">
                        <option value="1">1 day</option>
                        <option value="2">7 days</option>
                        <option value="3">31 days</option>
                        <option value="4">All season</option>
                    </select>

                    <div class="help-block"></div>
                    </div>';   
        } 
        else {
            echo "<input type = 'hidden' name='Price[serivcetype_Id]' value='" . $model->servicetype_Id . "'>"; 
     }
    ?>

    <?= $form->field($model, 'mainprice')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'supplement')->textInput() ?>

    <?= $form->field($model, 'maxguests')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('messages', 'Create') : Yii::t('messages', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<select class="form-control hidden-servicetype hidden-text hide" name="Price[servicetype_Id]">
    <option value="5">Rooms</option>
</select>

<select class="form-control hidden-servicetype hidden-select hide" name="Price[servicetype_Id]">
    <option value="1">1 day</option>
    <option value="2">7 days</option>
    <option value="3">31 days</option>
    <option value="4">All season</option>
</select>

<script type="text/javascript">
  ;(function($) {
    $(document).ready(function() {
        $('#price-rowid').change(function(){
            console.log($(this).children('option:selected').val());
             $('.field-price-servicetype_id .hidden-servicetype').remove();
            if ($(this).children('option:selected').val().length > 1) {
                $(".hidden-servicetype.hidden-text").clone().removeClass('hide').insertAfter($('.field-price-servicetype_id label'));
            } else {
                $(".hidden-servicetype.hidden-select").clone().removeClass('hide').insertAfter($('.field-price-servicetype_id label'));   
            }
        });
  });
})(jQuery);
</script>
