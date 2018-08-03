<?php
	use yii\helpers\Url;

	$this->title = Yii::t('messages', 'Booking Cart');
	if (!empty($guest)){
		$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Map Recursive Booking'), 'url' => ['guest/mapbooking', 'id' =>$guest['Id'], 'lang' => Yii::$app->language]];
	} else {
		$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Map Booking'), 'url' => ['guest/mapbooking', 'lang' => Yii::$app->language]];
	}
	$this->params['breadcrumbs'][] = Yii::t('messages', 'Booking Cart');

	$guestinfoUrl = Url::to(["guest/create", "lang" => Yii::$app->language]);
	if (!empty($guest)) {
		$guestinfoUrl = Url::to(["guest/customerrecursive", "id"=>$guest["Id"], "lang" => Yii::$app->language]);
	}
?>

<style type="text/css">
	@media only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
		 /*
	    Label the data
	    */
	     td.my-cart:nth-of-type(1):before { content: "ID"; text-align: left; }
	     td.my-cart:nth-of-type(2):before { content: "<?=Yii::t('messages', 'Sunshade')?>"; text-align: left; }
	     td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Check In')?>"; text-align: left; }
	     td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Service Type')?>"; text-align: left; }
	     td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Guests')?>"; text-align: left;}
	     td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Price')?>"; text-align: left;}
	     td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', 'Cart')?>"; text-align: left;}
	     td.my-cart:nth-of-type(8):before { content: "<?=Yii::t('messages', 'Milestones')?>"; text-align: left;}
	     td.my-cart:nth-of-type(9):before { content: "<?=Yii::t('messages', 'ADD')?>"; text-align: left;}

	    .form-control{padding: 6px 3px;}

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

        .modal-body {
		    position: relative;
		    max-height: 250px;
		    overflow-y: scroll;
		}
	}
</style>

<script type="text/javascript">
	myCart = <?=json_encode($myCart)?>;
	var arrayOfSunshades = <?=json_encode($jsonValue)?>;
	var arrayOfPrices = <?=json_encode($price)?>;
	var lang = "<?=Yii::$app->language?>";
	var updatecart_url = "<?=Url::to(['guest/updatecart', 'lang' => Yii::$app->language])?>";
	var savetocart_url = "<?=Url::to(['guest/savetocart', 'lang' => Yii::$app->language])?>";
	var readcart_url = "<?=Url::to(['guest/readcart', 'lang' => Yii::$app->language])?>";
	var readSunshades_url = '<?=Url::to(['guest/readsunshades'])?>';
	var removesunshadefromcartwithid_url = "<?=Url::to(['guest/removesunshadefromcartwithid', 'lang' => Yii::$app->language])?>";
	var readmilestone_url = "<?=Url::to(['guest/readmilestone'])?>";
	var addMilestoneToCart_url = "<?=Url::to(['guest/addmilestonetocart'])?>";
	var csrf = '<?=Yii::$app->request->getCsrfToken()?>';
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

	var title_proceed = '<?=Yii::t("messages", " Proceed")?>';
	var title_remove = '<?=Yii::t("messages", "[REMOVE]")?>';
	var title_milestone = '<?=Yii::t("messages", "Milestone")?>';
	var title_price = '<?=Yii::t("messages", "Price")?>';
	var title_money = '<?=Yii::t("messages", "Money")?>';
	var title_date = '<?=Yii::t("messages", "Date")?>';
	var title_savechange = '<?=Yii::t("messages", "Save Change")?>';
	var title_close = '<?=Yii::t("messages", "Close")?>';
	var tooltip_remove = '<?=Yii::t("messages", "Remove from Cart")?>';
	var tooltip_milestone = '<?=Yii::t("messages", "Update milestone");?>';
	var tooltip_new = '<?=Yii::t("messages", "Duplicate");?>';
	
	var csrf_token = '<?=Yii::$app->request->getCsrfToken()?>';

	var option_lang = 	"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
	<?php if (Yii::$app->language == "it") : ?>
		option_lang = 	"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
	<?php endif ?>
</script>

<div class="card card-padding">
	<div class="card-header card-padding">
		<?php
			if (!empty($guest)) {
		       echo '<div class="form-group"><span class="m-r-10">'.Yii::t('messages', 'Recusive Booking').'</span><a href="'.Url::to(['guest/view', 'id' =>$guest['Id'], 'lang'=>Yii::$app->language]).'"  class="lable label-success text-uppercase round p-5 m-b-20 white-color">'. $guest['firstname'] . ' ' . $guest['lastname'] . '</a></div>';
		    } 
		?>
		<form id="gotocart-form" method="POST" action="<?=$guestinfoUrl?>">
            <input type="hidden" name="_csrf" value="'+csrf_token+'" />
            <input type="hidden" name="lastBookingId" >
            <button type="submit" class="btn btn-success text-uppercase dropdown-toggle proceed-cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i><?=Yii::t("messages", " Proceed")?></button>
        </form>
    </div>
    <div class="card-body card-padding">
		<div class="table-responsive">
			<table id="grid-data" class="table display" style="    width: 100%;">
			    <thead class="my-cart">
			        <tr class="my-cart">
			            <th class="my-cart" data-column-id="id" >ID</th>
			            <th class="my-cart" data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
			            <th class="my-cart" data-column-id="checkin" data-formatter="checkin" data-sortable="false"><?=Yii::t('messages', 'Check In')?></th>
			            <th class="my-cart" data-column-id="servicetype" data-formatter="servicetype" data-sortable="false"><?=Yii::t('messages', 'Service Type')?></th>
						<th class="my-cart" data-column-id="guests" data-formatter="guests" data-sortable="false"><?=Yii::t('messages', 'Guests')?></th>
			            <th class="my-cart" data-column-id="price" ><?=Yii::t('messages', 'Price')?></th>
			            <th class="my-cart" data-column-id="cart" data-formatter="cart" data-sortable="false"><?=Yii::t('messages', 'Cart')?></th>
			            <th class="my-cart" data-column-id="milestones" data-formatter="milestones" data-sortable="false"><?=Yii::t('messages', 'Milestones')?></th>
			            <th class="my-cart" data-column-id="plus" data-formatter="plus" data-sortable="false"><?=Yii::t('messages', 'ADD')?></th>
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
<div class='hide template col-xs-12 well p-l-10 p-r-10' id="milestone">
  <Strong class="col-sm-4 col-xs-12 text-uppercase text-center valign line-height-40 " align="right" >
        <?=Yii::t('messages', 'Milestone')?> <span id="number">1</span>
  </Strong>
  <div class="col-sm-3 col-xs-12 text-center noplr">
    <div class="form-group">
        <div class="col-sm-12">
          <input id="textinput" name="Milestone_Price[]" type="number" placeholder="<?=Yii::t('messages', 'Amount')?>" class="form-control input-md Milestone_Price">
        </div>
    </div>
  </div>
  <div class="col-sm-4 col-xs-12 text-center noplr">
    <div class="form-group">
      <div class="col-sm-12">
        <input id="textinput" name="Milestone_Date[]" type="text" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" class="form-control input-md Milestone_Date" readonly style="font-size: 12px;">
      </div>
    </div>
  </div>
  <div class="col-sm-1 col-xs-12 text-center">
    <button type="button" class="btn btn-danger removeButton"><i class="fa fa-minus"></i></button>
  </div>
</div>
<!-- end  Milestone -->



