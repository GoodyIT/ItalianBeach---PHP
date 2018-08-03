<?php
	use yii\helpers\Url;

	$this->title = Yii::t('messages', 'Booking Cart');
?>

<style type="text/css">
	@media only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
		 /*
	    Label the data
	    */
	    .table > tbody > tr > td.my-cart:nth-of-type(1):before { content: "ID"; text-align: left; }
	    .table > tbody > tr > td.my-cart:nth-of-type(2):before { content: "<?=Yii::t('messages', 'Sunshade')?>"; text-align: left; }
	    .table > tbody > tr > td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Check In')?>"; text-align: left; }
	    .table > tbody > tr > td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Service Type')?>"; text-align: left; }
	    .table > tbody > tr > td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Guests')?>"; text-align: left;}
	    .table > tbody > tr > td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Price')?>"; text-align: left;}
	    .table > tbody > tr > td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', 'Cart')?>"; text-align: left;}
	    .table > tbody > tr > td.my-cart:nth-of-type(8):before { content: "<?=Yii::t('messages', 'ADD')?>"; text-align: left;}

	    .form-control{padding: 6px 3px;}

	    .container-fluid.azz
        {
            padding-left: 0px;
            padding-right: 0px;
        }

        .container-fluid.azz .container
        {
            padding-left: 0px;
            padding-right: 0px;
        }
	}
</style>


<script type="text/javascript">
	var myCart = <?=json_encode($myCart)?>;
	var arrayOfSunshades = <?=json_encode($jsonValue)?>;
	var arrayOfPrices = <?=json_encode($price)?>;
	var updatecart_url = '<?=Url::to(['site/updatecart', 'lang' => Yii::$app->language])?>';
	var readcart_url = "<?=Url::to(['site/readcart', 'lang' => Yii::$app->language])?>";
	var removesunshadefromcartwithid_url = "<?=Url::to(['site/removesunshadefromcartwithid', 'lang' => Yii::$app->language])?>";
	var savetocart_url = '<?=Url::to(['site/savetocart', 'lang' => Yii::$app->language])?>';
	var readSunshades_url = '<?=Url::to(['site/readsunshades'])?>';
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>';
	var lang = "<?=Yii::$app->language?>";
	var msg_please_checkin = '<?=Yii::t("messages", "Please select a check in")?>';
	var msg_please_service = '<?=Yii::t("messages", "Please select a service")?>';
	var msg_available_from = '<?=Yii::t("messages", "This sunshade is not available <br> from ")?>';
	var msg_select_service = '<?=Yii::t("messages", "Select...")?>'
	var msg_to = '<?=Yii::t("messages", " to ")?>';
	var msg_day = '<?=Yii::t("messages", " Day")?>';
	var msg_days = '<?=Yii::t("messages", " Days")?>';
	var msg_all_season = '<?=Yii::t("messages", "All season")?>';
	var msg_available_to = '<?=Yii::t("messages", ".<br/>Please input the right checkin and service")?>';
	var msg_checkin_less_checkout = '<?=Yii::t("messages", "Sorry. Please input the valid milestone and its corresponding date correctly.")?>';
	var msg_cannot_exceed = '<?=Yii::t('messages', "Sorry. The sum of the price of each milestone cannot exceed the total price.")?>';

	var title_remove = '<?=Yii::t("messages", "[REMOVE]")?>';
	var title_milestone = '<?=Yii::t("messages", "Milestone")?>';
	var title_price = '<?=Yii::t("messages", "Price")?>';
	var title_money = '<?=Yii::t("messages", "Money")?>';
	var title_date = '<?=Yii::t("messages", "Date")?>';
	var title_savechange = '<?=Yii::t("messages", "Save Change")?>';
	var title_close = '<?=Yii::t("messages", "Close")?>';
	var tooltip_remove = '<?=Yii::t("messages", "Remove from Cart")?>';
	var tooltip_new = '<?=Yii::t("messages", "Duplicate");?>';

	var option_lang = 	"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
	<?php if (Yii::$app->language == "it") : ?>
		option_lang = 	"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
	<?php endif ?>
</script>

<div class="card card-padding">
	<div class="card-header">
		<form id="gotocart-form" class="display-inline" action="<?=Url::to(["guest/create", "lang" => Yii::$app->language])?>">
            <button type="submit" class="btn btn-success text-uppercase dropdown-toggle proceed-cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i><?=Yii::t("messages", " Proceed")?></button>
            </form>
    </div>
    <div class="card-body card-padding">
		<div class="table-responsive">
			<table id="grid-data" class="table display" style="max-width: 100%;">
			    <thead class="my-cart">
			        <tr class="my-cart">
			            <th class="my-cart" data-column-id="id">ID</th>
			            <th class="my-cart" data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
			            <th class="my-cart" data-column-id="checkin" data-sortable="false"><?=Yii::t('messages', 'Check In')?></th>
			            <th class="my-cart" data-column-id="servicetype"  data-sortable="false"><?=Yii::t('messages', 'Service Type')?></th>
						<th class="my-cart" data-column-id="guests" data-sortable="false"><?=Yii::t('messages', 'Guests')?></th>
			            <th class="my-cart" data-column-id="price" ><?=Yii::t('messages', 'Price')?></th>
			            <th class="my-cart" data-column-id="cart"  data-sortable="false"><?=Yii::t('messages', 'Cart')?></th>
			            <th class="my-cart" data-column-id="add"  data-sortable="false"><?=Yii::t('messages', 'ADD')?></th>
			        </tr>
			    </thead>
			    <tbody class="my-cart">
			        
		        </tbody>
			</table>
		</div>
	</div>
</div>

<div id="myMilestone-dialog"></div>

<!-- The template for adding new field -->
 <div class='row hide template' id="milestone">
    <div class="form-group" >
        <Strong class="col-xs-4 col-sm-4 col-lg-4 text-uppercase valign line-height-40" align="right">
            <?=Yii::t('messages', 'Milestone')?> <span id="number">1</span>  
        </Strong>
        <div class="col-xs-3 cole-md-3">
            <input type="number" class="form-control Milestone_Price" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>" />
        </div>
        <div class="col-xs-4 cole-md-4 nopl">
            <input type="text" readonly='readonly' class="form-control Milestone_Date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
        </div>
        <div class="col-xs-1 cole-md-1 nopl">
            <button type="button" class="btn btn-danger removeButton"><i class="fa fa-minus"></i></button>
        </div>
    </div>
</div>
<!-- end  Milestone -->


