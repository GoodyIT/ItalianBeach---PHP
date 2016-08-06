<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

?>
    
<div class="container guest-create top-padding">
    <div class="row" >
        <div class="col-sm-5">
            <?= $this->render('bookinfo');?>
        </div>


        <div class="col-sm-7">
            <?= $this->render('_form', [
                'model' => $model,
                
            ]) ?>
        </div>
    </div>
</div>
