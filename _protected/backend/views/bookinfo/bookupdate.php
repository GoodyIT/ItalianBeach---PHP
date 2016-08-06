<?php
    $this->title = Yii::t('messages', 'Recursive Booking');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Customers Info'), 'url' => ['guest/guestinfo', 'lang' => Yii::$app->language]];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="row" >
    <div class="col-sm-5">
        <?= $this->render('customerinfo', [
        	'guest' => $guest,
        ]);?>
    </div>

    <div class="col-sm-6">
        <?= $this->render('_bookupdate', [
            'selection' => $id,
            'guest' => $guest,
            'selectedId' => $selectedId,
            'seat' => $seat,
            'model' => $sunshade,
            'rowRestriction' => $rowRestriction,
            'rowRestrictionLists' => $rowRestrictionLists,
            'priceLists' => $priceLists,
            'serviceTypeLists' => $serviceTypeLists,
        ]) ?>
    </div>
</div>