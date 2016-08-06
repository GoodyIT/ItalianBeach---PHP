<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Bookinfo */

$this->title = Yii::t('messages', 'Update {modelClass}', [
        'modelClass' => 'Bookinfo',
    ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Bookinfos'), 'url' => ['index', 'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Update');
?>

<div class="bookinfo-update card" style="float: none; margin:0 auto">
<div class="card-body card-padding">

    <?= $this->render('_bulkform', [
        'selection' => $selection,
        'guest' => $guest,
        'model' => $model,
        'seat' => $seat,
        'rowRestriction' => $rowRestriction,
        'rowRestrictionLists' => $rowRestrictionLists,
        'priceLists' => $priceLists,
        'serviceTypeLists' => $serviceTypeLists,
        'day' => ''
    ]) ?>
</div>
</div>
