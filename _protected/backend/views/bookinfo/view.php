<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Guest;
use frontend\models\Book;
use frontend\models\Milestone;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Bookinfo */

$this->title = $model->seat;
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Book Information'), 'url' => ['index', 'lang' => Yii::$app->language]];
$this->params['breadcrumbs'][] = $this->title;

  $milestoneCompleteState = true;
  if(intval($book['paidprice']) < intval($book['price'])) {
      $milestoneCompleteState = false;  
  } 

  $timestamp = 0;
  if (count($milestones)) {
    $timestamp = $milestones[0]['timestamp'];
  } else {
    $timestamp = time();  
  }
  
?>


<?php Pjax::begin(); ?>
<form id="milestone-form" method="POST">
<div class="bookinfo-view card">
<div class="card-header">
   <span class="pull-left" style="font-size: 20px;"><?php if($model->seat[0] == 1) echo Yii::t('messages', 'Room'); else echo Yii::t('messages', 'Sunshade');?> <span  class="label label-info"> <?=$model->seat?></span></span>
        <?php if(!$milestoneCompleteState)  echo '<button type="submit" class="btn btn-success update-milistone text-center pull-right waves-effect">' . Yii::t("messages", "Update Milestones"). '</button>';?>
</div>
<div class="card-body card-padding">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr >
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
            <td><?=Yii::t('messages', 'Arrival Date')?></td>
            <td><?=$book['arrival']?></td>
          </tr>
          <tr>
            <td>7</td>
            <td><?=Yii::t('messages', 'Checkout Date')?></td>
            <td><?=$book['checkout']?></td>
          </tr>
          <tr>
            <td>8</td>
            <td><?=Yii::t('messages', 'Service Type')?></td>
            <td><?php if($book['servicetype']) echo Yii::$app->params['servicetype'][$book['servicetype']]; ?></td>
          </tr>
          <tr>
            <td>9</td>
            <td><?=Yii::t('messages', 'Total Paid')?> &nbsp; (&euro;)</td>
            <td><?=$book['paidprice'].'/'.$book['price']?>
              <?php 
                  if ($model->bookstate != "available") {
                      if($milestoneCompleteState) {
                          echo '<span class="badge" style="background-color: #F44336!important;">' . Yii::t('messages', 'Fully Paid') . '</span>';
                        } else {
                          echo '<span class="badge" style="border-radius: 20px; background-color: #9E9E9E!important;">' . Yii::t('messages', 'Not Fully Paid Yet') . '</span>';
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
          <?php for($i = 0; $i < count($milestones); $i++) { ?>
                <tr>
                <td><?=$i+10?></td>
                <td><?= Yii::t('messages', 'Milestone')?> &nbsp; <?=$i+1; ?></td>
                <td>
                    <span class="label label-primary"><?=Yii::t('messages', 'Paid money')?> (&euro;)</span> <?=$milestones[$i]['price']?> &nbsp;&nbsp;
                    <span class="label label-primary"><?=Yii::t('messages', 'Date of Payment')?></span> <?=$milestones[$i]['period']?>
                    
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
          <div class='row <?php if($milestoneCompleteState)  echo "hide"; else echo"shown";?>'>
            <div class="col-sm-12">
                <div class="form-group">
                    <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle; line-height: 40px;"><?=Yii::t('messages', 'Milestone')?> <span id="number"><?=count($milestones) +1?></span> </Strong>
                    <div class="col-xs-3 col-md-3">
                        <input type="text" class="form-control" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>"/>
                    </div>
                    <div class="col-xs-4 col-md-4" style="padding-left: 0px;">
                        <input type="text" class="form-control milestone-date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
                    </div>
                    <div class="col-xs-1 cole-md-1" style="padding-left: 0px;">
                        <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
      </div>
</div>
</div>
</form>

<!-- The template for adding new field -->
     <div class='row hide template' id="milestone">
        <div class="col-sm-12">
            <div class="form-group">
                <Strong class="col-xs-4 col-sm-4 col-lg-4" align="right" style=" vertical-align: middle; line-height: 40px;">
                    <?=Yii::t('messages', 'Milestone')?> <span id="number">1</span>  
                </Strong>
                <div class="col-xs-3 cole-md-3">
                    <input type="text" class="form-control" name="Milestone_Price[]" placeholder="<?=Yii::t('messages', 'Money')?>" />
                </div>
                <div class="col-xs-4 cole-md-4" style="padding-left: 0px;">
                    <input type="text" class="form-control milestone-date" name="Milestone_Date[]" placeholder="<?=Yii::t('messages', 'Date of Payment')?>" />
                </div>
                <div class="col-xs-1 cole-md-1" style="padding-left: 0px;">
                    <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
    </div>
    <!-- end  Milestone -->
   
<?php Pjax::end(); ?>
<script type="text/javascript">
;(function($) {
    $(function () {
        // Dynamically created milestone
         $('body').on('click', '.milestone-date' , function() {
            $(this).datepicker('destroy').datepicker({
                                            daysOfWeekHighlighted: "0",
                                            autoclose: true,
                                            todayHighlight: true,
                                            format: "yyyy-mm-dd",
                                            orientation: "auto top",
                                            keyboardNavigation: true,}).focus();
        });

         $('body').on('click', '.update-milistone', function(e) {
            // e.preventDefault();

            // var isValid = true;
            // var nonEmptyDates = $('.shown .form-control.milestone-date').map(function(){return $(this).val();}).get();
            // var length = nonEmptyDates.length;
            // var dateLength = 0;
            // for (var i = nonEmptyDates.length - 1; i >= 0; i--) {
            //     if(nonEmptyDates[i] && nonEmptyDates[i].length > 0) { dateLength++;}
            // }

            // if (dateLength < length) { isValid = false;}

            // if (!isValid) {
            //   $.notify({
            //     message: '<?=Yii::t('messages', 'Please correct the time period.')?>'}, 
            //     {
            //         animate: {
            //             enter: 'animated fadeInRight',
            //             exit: 'animated fadeOutRight'
            //         },
            //         type: 'danger'
            //     });
            //   return;
            // }
            $('form').submit();
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
