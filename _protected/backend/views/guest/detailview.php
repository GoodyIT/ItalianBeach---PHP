<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = $model['username'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Book Info'), 'url' => ['guest/sendbookinfo', 'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = $model['username'];
$lang = Yii::$app->language;

$baseurl = Yii::getAlias("@web") //"http://www.beachclubippocampo.rentals";
?>
 <div class="preloader pl-sm " style="display: none;">
    <svg class="pl-circular" viewBox="25 25 50 50">
        <circle class="plc-path" cx="50" cy="50" r="20"></circle>
    </svg>
</div>

<script type="text/javascript" src="<?=$baseurl?>/js/jQuery.print.js"></script>
<div class="row">
    <!-- Recent Items -->
            <ul class="actions">
                    <li>
                        <span class="sendbookinfo pull-left btn btn-success" style="margin-right: 10px"> 
                         <?=Yii::t('messages', 'Send Via Email')?>
                        </span>
                    </li>
                    <li>
                        <span class="createPDF pull-left btn btn-success" style="margin-right: 10px">
                            <?=Yii::t('messages', 'Create PDF for Book Info')?>
                        </span> 
                    </li>
                    <li>
                        <span class="print pull-left btn btn-success"> 
                            <?=Yii::t('messages', 'Print Book Info')?>
                        </span>
                    </li>
                </ul>
    <div class="card  col-sm-6">
        <div class="card-header">
               		
		        
        </div>
        
        <div class="card-body m-t-10">
            <table class="table table-striped table-inner table-vmiddle">

                <tbody>
                    <tr>
                    	<th><?=Yii::t('messages', 'Name & Surname')?></th> <td><?=$model['username']?></td>
                    </tr>
                    <tr>
                        <th><?=Yii::t('messages', 'Address')?></th><td><?=$model['address']?></td>
                   	</tr>
                   	<tr>
                        <th><?=Yii::t('messages', 'Email')?></th> <td><?=$model['email']?></td>
                    </tr>
                   	<tr>
                        <th><?=Yii::t('messages', 'Phonenumber')?></th><td><?=$model['phonenumber']?></td>
                    </tr>
                   	<tr>    
                        <th><?=Yii::t('messages', 'Country')?></th> <td><?=$model['country']?></td>
                    </tr>
                   	<tr>
                        <th><?=Yii::t('messages', 'Arrival Date')?></th><td><?=$model['arrival']?></td>
                    </tr>
                   	<tr>    
                        <th><?=Yii::t('messages', 'Checkout Date')?></th><td><?=$model['checkout']?></td>
                    </tr>
                   	<tr>    
                        <th><?=Yii::t('messages', 'Sunshade')?></th><td><?=$model['sunshadeseat']?></td>
                    </tr>
                    <tr>    
                        <th><?=Yii::t('messages', 'Guests')?></th><td><?=$model['guests']?></td>
                    </tr>
                   	<tr>    
                        <th><?=Yii::t('messages', 'Paid / Total')?>&nbsp;(€)</th><td><?=$model['paidprice']?>/<?=$model['price']?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
          
    tagWidth = 12;
    tagFontSize = 6;
    tagHeight = 12;
    lang = '<?=$lang?>';

    seat = '';

    var $img;
    ;(function($) {
        $(document).ready(function() {
			$('.sendbookinfo').click(function() {
                $('.preloader').show();
	            $.ajax({
	            	type: 'POST',
	            	url: '<?= Yii::$app->urlManager->createUrl(["guest/sendemail"]) ?>',
	            	data: {
	            		id: "<?=$model['Id']?>",
                        lang: "<?=Yii::$app->language?>"
	            	},
	            	success: function(msg) {
	            		$('.preloader').hide();
	            	}
	            })
	        });

	        $('.createPDF').click(function() {
	            window.location.href = '<?= Yii::$app->urlManager->createUrl(["guest/pdf"]) ?>?id=<?=$model["Id"]?>';
	        });

	        $('.print').click(function() {
	             $.print(".table");
	            
	        });

        });
    })(jQuery);
</script>
                    
