
<style type="text/css">
	@media only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
		 /*
	    Label the data
	    */
	    td.my-cart:nth-of-type(1):before { content: "ID"; text-align: left; }
	    td.my-cart:nth-of-type(2):before { content: "<?=Yii::t('messages', 'Sunshade')?>"; text-align: left;}
	    td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Check In')?>"; text-align: left; }
	    td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Service Type')?>"; text-align: left; }
	    td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Guests')?>"; text-align: left;}
	    td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Total Price')?>"; text-align: left;}
	    td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', 'Price')?>"; text-align: left;}
	    td.my-cart:nth-of-type(8):before { content: "<?=Yii::t('messages', 'Supplement')?>"; text-align: left;}
	    td.my-cart:nth-of-type(9):before { content: "<?=Yii::t('messages', 'Add.Pass')?>"; text-align: left;}

	    .form-control{padding: 6px 6px;}

	    .container-fluid.azz
        {
            padding-left: 10px;
            padding-right: 10px;
        }

        .container-fluid.azz .container
        {
            padding-left: 0px;
            padding-right: 0px;
        }
	}

</style>

<div class="table-responsive card-body card-padding">
	<table id="cartinfo-data" class="table display my-cart">
	    <thead class="my-cart">
	        <tr class="my-cart">
	            <th class="my-cart" data-column-id="id" data-type="numeric">ID</th>
	            <th class="my-cart" data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
	            <th class="my-cart" data-column-id="checkin"><?=Yii::t('messages', 'Check In')?></th>
	            <th class="my-cart" data-column-id="servicetype" data-formatter="servicetype" ><?=Yii::t('messages', 'Service Type')?></th>
				<th class="my-cart" data-column-id="guests"><?=Yii::t('messages', 'Guests')?></th>
	            <th class="my-cart" data-column-id="price"><?=Yii::t('messages', 'Total Price')?></th>
	            <th class="my-cart" data-column-id="mainprice"><?=Yii::t('messages', 'Price')?></th>
	            <th class="my-cart" data-column-id="supplement"><?=Yii::t('messages', 'Supplement')?></th>
	            <th class="my-cart" data-column-id="tax"><?=Yii::t('messages', 'Add.Pass')?></th>
	        </tr>
	    </thead>
	    <tbody class="my-cart">
        </tbody>
	</table>
</div>

<script type="text/javascript">
	var arrayOfPrices = <?=json_encode($price)?>;
	var myCart = <?=json_encode($myCart)?>;
	var msg_day = '<?=Yii::t("messages", " Day")?>';
	var msg_days = '<?=Yii::t("messages", " Days")?>';
	var msg_all_season = '<?=Yii::t("messages", "All season")?>';

	var option_lang = 	"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
	<?php if (Yii::$app->language == "it") : ?>
		option_lang = 	"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
	<?php endif ?>
</script>