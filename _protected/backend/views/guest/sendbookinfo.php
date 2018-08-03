<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->title = Yii::t('messages', 'Send Book Info');
$this->params['breadcrumbs'][] = Yii::t('messages', 'Book Info');
?>

<script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jQuery.print.js"></script>


<style type="text/css">
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
         /*
        Label the data
        */
        .table > tbody > tr > td.my-cart:nth-of-type(1):before { content: ""; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(2):before { content: "ID"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(3):before { content: "<?=Yii::t('messages', 'Username')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(4):before { content: "<?=Yii::t('messages', 'Email')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(5):before { content: "<?=Yii::t('messages', 'Phonenumber')?>"; text-align: left; }
        .table > tbody > tr > td.my-cart:nth-of-type(6):before { content: "<?=Yii::t('messages', 'Check In')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(7):before { content: "<?=Yii::t('messages', 'Check Out')?>"; text-align: left;}
        .table > tbody > tr > td.my-cart:nth-of-type(8):before { content: "<?=Yii::t('messages', 'Sunshade')?>"; text-align: left;}

        .form-control{padding: 6px 3px;}

        .table > tbody > tr > td, .table > tfoot > tr > td.my-cart {
            /* Behave  like a "row" */
            /*border: 2px;*/
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 150px;
        }

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

<div class="guest-view card card-padding">
    <div class="card-header  card-padding">
        <button class="btn btn-danger waves-effect delete-booking"><?=Yii::t('messages', 'Delete Booking(s)')?></button>
    </div>
    <div class="card-body  card-padding table-responsive">
        <table id="sendinfo-data" class="table display">
            <thead class="my-cart  text-center">
                <tr class="my-cart  text-center">
                    <th class="my-cart  text-center" data-sortable="false"><input name="select_all" value="1" type="checkbox"></th>
                    <th class="my-cart  text-center" data-column-id="id"  data-type="numeric">ID</th>
                    <th class="my-cart  text-center" data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                    <th class="my-cart  text-center" data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                    <th class="my-cart  text-center" data-column-id="phonenumber"><?=Yii::t('messages', 'Phonenumber')?></th>
                    <th class="my-cart  text-center" data-column-id="checkin"><?=Yii::t('messages', 'Check In')?></th>
                    <th class="my-cart  text-center" data-column-id="checkout"><?=Yii::t('messages', 'Check Out')?></th>
                    <th class="my-cart  text-center" data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
                    <th class="my-cart  text-center" data-column-id="commands" data-sortable="false"></th>
                </tr>
            </thead>
            <tbody class="my-cart  text-center">
            <?php for ($i = 0; $i < count($guest); $i++) {?>
                <tr class="my-cart  text-center">
                    <td class="my-cart  text-center checkbox-column"></td>
                    <td class="my-cart  text-center" ><?=$i+1?></td>
                    <td class="my-cart  text-center" ><?=$guest[$i]['username']?></td>
                    <td class="my-cart  text-center" ><?=$guest[$i]['email']?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['phonenumber']?></td>
                    <td class="my-cart  text-center"><?=date_create($guest[$i]['checkin'])->format('F j, Y')?></td>
                    <td class="my-cart  text-center"><?=date_create($guest[$i]['checkout'])->format('F j, Y')?></td>
                    <td class="my-cart  text-center"><?=$guest[$i]['sunshade']?></td>
                    <td class="my-cart  text-center">
                        <button type="button" class="btn btn-icon command-view waves-effect waves-circle" data-row-lookipid="<?=$guest[$i]['Id']?>" ><span class="zmdi zmdi-view-toc zmdi-hc-fw" title="<?=Yii::t('messages', 'View')?>"></span></button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-icon command-delete waves-effect  waves-circle bgm-red" data-row-paidprice="<?=$guest[$i]['paidprice']?>" data-row-guestid = "<?=$guest[$i]['guestId']?>" data-row-bookid="<?=$guest[$i]['bookId']?>" data-row-lookipid="<?=$guest[$i]['Id']?>" title="<?=Yii::t('messages', 'Delete')?>"><span class="fa fa-trash-o"></span></button>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
var guest = <?=json_encode($guest)?>;
// Array holding selected row IDs
var rows_selected = [];
var lookupIds = [];
var guestIds = [];
var bookIds = [];
var paidPrices = [];

function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}

;(function($) {
    $(function () {
      var option_lang =   "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json";
      <?php if (Yii::$app->language == "it") : ?>
        option_lang =   "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json";
      <?php endif ?>
        $(".command-view").click(function(){
            var lookipid = $(this).data('row-lookipid');

            if(lookipid != undefined)
                location.href = '<?=Url::to(["guest/detailview"])?>' + '/' + lookipid +'?lang='  + '<?=Yii::$app->language?>';
        });

        $(".command-delete").click(function(){
            var lookipid = $(this).data('row-lookipid');
            var guestid = $(this).data('row-guestid');
            var bookid = $(this).data('row-bookid');
            var paidprice = $(this).data('row-paidprice');
            lookupIds = [];
            guestIds = [];
            bookIds = [];
            paidPrices = [];
            lookupIds.push(lookipid);
            guestIds.push(guestid);
            bookIds.push(bookid);
            paidPrices.push(paidprice);
            deleteBooking();
        });

        $('.delete-booking').on('click', function(e) {
            e.preventDefault();
            lookupIds = [];
            guestIds = [];
            bookIds = [];
            paidPrices = [];
              // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, rowId){
                 // Create a hidden element 
                 rowId = parseInt(rowId) - 1;
                lookupIds.push(guest[rowId].Id);
                guestIds.push(guest[rowId].guestId);
                bookIds.push(guest[rowId].bookId);
                paidPrices.push(guest[rowId].paidprice);
            });
            if (lookupIds.length < 1) return false;

            deleteBooking();
        });

    
       var table = $('#sendinfo-data').DataTable({
          dom: 'Bfrtip',
          buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5',
              'print'
          ],
          "pagingType": "full_numbers",
          'columnDefs': [{
             'targets': 0,
             'searchable':false,
             'orderable':false,
             'width':'1%',
             'className': 'dt-body-center',
             'render': function (data, type, full, meta){
                 return '<input type="checkbox">';
             }
          }],
          'order': [1, 'asc'],
          'rowCallback': function(row, data, dataIndex){
             // Get row ID
             var rowId = data[1];

             // If row ID is in the list of selected row IDs
             if($.inArray(rowId, rows_selected) !== -1){
                $(row).find('input[type="checkbox"]').prop('checked', true);
                $(row).addClass('selected');
             }
          },
          "language": {
                "url" : option_lang
            }
       });
       // Handle click on checkbox
       $('#sendinfo-data tbody').on('click', 'input[type="checkbox"]', function(e){
          var $row = $(this).closest('tr');

          // Get row data
          var data = table.row($row).data();

          // Get row ID
          var rowId = data[1];

          // Determine whether row ID is in the list of selected row IDs 
          var index = $.inArray(rowId, rows_selected);

          // If checkbox is checked and row ID is not in list of selected row IDs
          if(this.checked && index === -1){
             rows_selected.push(rowId);

          // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
          } else if (!this.checked && index !== -1){
             rows_selected.splice(index, 1);
          }

          if(this.checked){
             $row.addClass('selected');
          } else {
             $row.removeClass('selected');
          }

          // Update state of "Select all" control
          updateDataTableSelectAllCtrl(table);

          // Prevent click event from propagating to parent
          e.stopPropagation();
       });

       // Handle click on table cells with checkboxes
       $('#sendinfo-data').on('click', 'tbody td:not(:last-child), .command-delete,  thead th:first-child', function(e){
          $(this).parent().find('input[type="checkbox"]').trigger('click');
       });

       // Handle click on "Select all" control
       $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
          if(this.checked){
             $('#sendinfo-data tbody input[type="checkbox"]:not(:checked)').trigger('click');
          } else {
             $('#sendinfo-data tbody input[type="checkbox"]:checked').trigger('click');
          }

          // Prevent click event from propagating to parent
          e.stopPropagation();
       });

       // Handle table draw event
       table.on('draw', function(){
          // Update state of "Select all" control
          updateDataTableSelectAllCtrl(table);
       });
        
        function deleteBooking()
        {
            swal({   
                title: "<?=Yii::t('messages', 'Are you sure?')?>",   
                text: "<?=Yii::t('messages', 'The selected booking information(s) will be deleted!')?>",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "<?=Yii::t('messages', 'Yes')?>",   
                cancelButtonText: "<?=Yii::t('messages', 'No')?>",   
                closeOnConfirm: false,   
                closeOnCancel: false 
            }, function(isConfirm){   
                if (isConfirm) {
                   $.ajax({
                     type: "post",
                     url: "<?=Url::to(['guest/deletebooking'])?>",
                     data: {
                        lookupIds: lookupIds,
                        guestIds: guestIds,
                        bookIds: bookIds,
                        paidPrices: paidPrices
                      },
                     success: function(msg)
                     {
                       location.reload();
                        // console.log(msg);
                     }
                   });
                } else {     
                    swal("<?=Yii::t('messages', 'Cancelled!')?>", "<?=Yii::t('messages', 'The information of the current information remains safe')?>", "error");  

                    return false;
                } 
            });
        }
    });
})(jQuery);
</script>

