<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Bookinfo */

$this->title = $model->seat;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Book Information'), 'url' => ['guest/guestdetail', 'id' =>$guestId,  'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = $this->title;
$totalInTable = 0;
?>

<form id="view-form" action="<?=Url::to(['book-lookup/view', 'sunshadeId' => $sunshadeId, 'guestId' => $guestId, 'bookId' => $bookId, 'booklookupId' =>$booklookupId])?>" id="milestone-form" method="POST">
<div class="bookinfo-view card card-padding table-responsive">
<div class="card-header card-padding ">
   <span class="pull-left" style="font-size: 20px;"><?php if($model->seat[0] == 1) echo Yii::t('messages', 'Room'); else echo Yii::t('messages', 'Sunshade');?> <span  class="label label-info"> <?=$model->seat?></span></span>
    <?php if(!$milestoneCompleteState)  echo '<button type="submit" class="btn btn-success update-milistone text-center pull-right inline-block">' . Yii::t("messages", "Update"). '</button>';?>
</div>
<div class="card-body card-padding table-responsive">
      <table class="table table-striped  hover" style="width: 100%">
        <thead>
          <tr>
            <th>#</th>
            <th><?=Yii::t('messages', 'Property')?></th>
            <th><?=Yii::t('messages', 'Content')?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><?=Yii::t('messages', 'Bookstate')?></td>
            <td><?=$model->bookstate?></td>
          </tr>
          <tr>
            <td>2</td>
            <td><?=Yii::t('messages', 'Username')?></td>
            <td><?= ($guest['firstname'] . ' ' . $guest['lastname'])?></td>
          </tr>
          <tr>
            <td>3</td>
            <td><?=Yii::t('messages', 'Address')?></td>
            <td><?=$guest['address']?></td>
          </tr>
          <tr>
            <td>4</td>
            <td><?=Yii::t('messages', 'Email')?></td>
            <td><?=$guest['email']?></td>
          </tr>
          <tr>
            <td>5</td>
            <td><?=Yii::t('messages', 'Phonenumber')?></td>
            <td><?=$guest['phonenumber']?></td>
          </tr>
          <tr>
            <td>6</td>
            <td><?=Yii::t('messages', 'Service Type')?></td>
            <td><?=$book['servicetype']?></td>
          </tr>
          <tr>
            <td>6</td>
            <td><?=Yii::t('messages', 'Check In')?></td>
            <td><?=date_create($book['checkin'])->format('F j, Y')?></td>
          </tr>
          <tr>
            <td>7</td>
            <td><?=Yii::t('messages', 'Check Out')?></td>
            <td><?=date_create($book['checkout'])->format('F j, Y')?></td>
          </tr>
          <?php if(intval($sunshadeId) < 168): ?>
            <tr>
              <td>8</td>
              <td><?=Yii::t('messages', 'Service Type')?></td>
              <td><?=$book['servicetype']?></td>
            </tr>
          <?php endif ?>
          <tr>
            <td>9</td>
            <td><?=Yii::t('messages', 'Total Paid')?> &nbsp; (&euro;)</td>
            <td><?=$book['paidprice'].'/'.$book['price']?>
              <?php 
                  if ($model->bookstate != "available") {
                      if($milestoneCompleteState) {
                          echo '<span class="badge bgm-red">' . Yii::t('messages', 'Fully Paid') . '</span>';
                        } else {
                          echo '<span class="badge bgm-gray brd-20">' . Yii::t('messages', 'Not Fully Paid Yet') . '</span>';
                        }
                    }
                ?>
            </td>
          </tr>
          <tr>
            <td>10</td>
            <td><?=Yii::t('messages', 'Guests')?></td>
            <td><?=$book['guests']?></td>
          </tr>
          <?php for($i = 0; $i < count($milestones); $i++) { 
                $totalInTable += 1;
            ?>
              <tr>
                <td><?=$i+11?></td>
                <td><?= Yii::t('messages', 'Milestone')?> &nbsp; <?=$i+1; ?></td>
                <td>
                  <div style="display: block;">
                    <div class="label label-primary"><?=Yii::t('messages', 'Paid money')?> (&euro;)</div> <div><?=$milestones[$i]['price']?> &nbsp;&nbsp;</div>
                  </div>
                  <div style="display: block;">  
                    <div class="label label-primary"><?=Yii::t('messages', 'Date of Payment')?></div> <div><?=date_create($milestones[$i]['period'])->format('M d, Y')?></div>
                  </div>
                </td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    
      <div id="milestone-block">
          <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
          <input type="hidden" name="guestId" value="<?=$guest['Id']?>">
          <input type="hidden" name="sunshadeseat" value="<?=$model->seat?>">
          <input type="hidden" name="paidprice" value="<?=$book['paidprice'] ?>" />
          <input type="hidden" name="timestamp" value="<?=$timestamp?>">
          <input type="hidden" name="price" value="<?=$book['price']?>">
          <input type="hidden" name="bookId" value="<?=$book['Id']?>">
          <input type="hidden" name="milestoneCompleteState" value="<?php echo $milestoneCompleteState ? 1: 0;?>">
      <!-- Milestone -->
          <div class='row col-xs-12 well p-l-5 p-r-5 m-l-5 m-l-5 <?php if($milestoneCompleteState)  echo "hide"; else echo"shown";?>'>
            <Strong class="col-sm-4 col-xs-12 text-uppercase text-center valign line-height-40 " align="right" >
               <?=Yii::t('messages', 'Milestone')?> <span id="number"><?=count($milestones) +1?></span>  
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
                  <input id="textinput" name="Milestone_Date[]" type="text" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" class="form-control input-md Milestone_Date" readonly>
                </div>
              </div>
            </div>
            <div class="col-sm-1 col-xs-12 text-center">
              <button type="button" class="btn btn-success addButton"><i class="fa fa-plus"></i></button>
            </div>
        </div>
      </div>
  </div>
</div>
</form>

<!-- The template for adding new field -->
 <div class='row hide template col-xs-12 well p-l-5 p-r-5 m-l-5 m-l-5 ' id="milestone">
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
        <input id="textinput" name="Milestone_Date[]" type="text" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" class="form-control input-md Milestone_Date" readonly>
      </div>
    </div>
  </div>
  <div class="col-sm-1 col-xs-12 text-center">
    <button type="button" class="btn btn-danger removeButton"><i class="fa fa-minus"></i></button>
  </div>
</div>
<!-- end  Milestone -->
   
<script type="text/javascript">
  var prevPrice = 0;
  var msg_checkin_less_checkout = '<?=Yii::t("messages", "Sorry. Please input the valid milestone and its corresponding date correctly.")?>';
  var msg_cannot_exceed = '<?=Yii::t('messages', "Sorry. The sum of the price of each milestone cannot exceed the total price.")?>';
  <?php for ($i=0; $i < count($milestones); $i++): ?> 
    prevPrice += parseInt(<?=$milestones[$i]['price']?>);
  <?php endfor ?>
  var totalPrice = <?=$book['price']?>;
  var firstPrice = totalPrice;
  var allSunshades = [];
  allSunshades.push('<?=$model->seat?>');
;(function($) {
  $(function () {
      // Dynamically created milestone
     $('body').on('click', '.Milestone_Date' , function() {
        $(this).datepicker({
                daysOfWeekHighlighted: "0",
                autoclose: true,
                todayHighlight: true,
                format: "M dd, yyyy",
                orientation: "auto",
                keyboardNavigation: true,
                startDate: today,
              }).focus();
    });

     $('.update-milistone').click(function(event) {
        event.preventDefault();
        var _isValid = true;
        if (!validateMilestone("<?=$totalInTable?>", "<?=$book['price']?>", '<?=$model->seat?>')){
            return;
        }

       $('#view-form').submit();
     })

    bookIndex = <?=count($milestones) +1?>;
    $("#milestone-block") // Add button click handler
        .on('click', '.addButton', function() {
            var $template = $('#milestone'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .addClass('shown')
                                .removeAttr('id')
                                .attr('data-book-index', bookIndex);
                $("#milestone-block").append($clone);
            // Update the name attributes
            $clone
                .find('#number').html(bookIndex+1).
                    end();
            bookIndex++;
        })

        // Remove button click handler
        .on('click', '.removeButton', function() {
            var $row  = $(this).parents('.template'),
                index = $row.attr('data-book-index');

            // Remove element containing the fields
            $row.remove();
            bookIndex--;
        });
   });
})(jQuery);
</script>
