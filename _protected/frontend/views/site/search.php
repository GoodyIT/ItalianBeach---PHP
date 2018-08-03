
 <div class="panel panel-default">
 	<div class="panel-body">
	<div class="table-responsive">
		<table id="grid-data" class="table table-condensed table-hover table-striped table-vmiddle">
		    <thead>
		        <tr>
		            <th data-column-id="sunshadeId" data-type="numeric" data-visible="false">sunshadeId</th>
		            <th data-column-id="id" data-type="numeric">ID</th>
		            <th data-column-id="sunshade">Sunshade</th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php for ($i = 0; $i < count($searchResult); $i++):
		        	if (!isset($searchResult[$i]['searched'])) {
		        		continue;
		        	}
		        ?>
		            <tr>
		                <td><?=$searchResult[$i]['Id']?></td>
		                <td><?=$i+1?></td>
		                <td><?=$searchResult[$i]['seat']?></td>
		            </tr>
		        <?php endfor ?>
	        </tbody>
		</table>
	</div>
    </div>
</div>    
<script type="text/javascript">
	;(function($) {
        $(document).ready(function() {
			$("#grid-data").bootgrid();

			if ($('.input-group.date')[0]) {
		        $('.input-group.date').datepicker({
		            minViewMode: datepickerViewMode,
		            daysOfWeekHighlighted: "0",
		            autoclose: true,
		            todayHighlight: true,
		            format: "yyyy-mm-dd",
		    /*        startDate: startDate,
		            endDate: endDate,*/
		            orientation: "auto bottom",
		            keyboardNavigation: true,
		        });
		    }

		    if ($('#radioBtn a')[0]) {
		        $('#radioBtn a').on('click', function(){
		            var sel = $(this).data('title');
		            var tog = $(this).data('toggle');
		            datepickerViewMode = sel;
		            $('.input-group.date').datepicker('destroy');
		            $('.input-group.date').datepicker({
		                minViewMode: datepickerViewMode,
		                daysOfWeekHighlighted: "0",
		                autoclose: true,
		                todayHighlight: true,
		                format: "yyyy-mm-dd",
		        /*        startDate: startDate,
		                endDate: endDate,*/
		                orientation: "auto bottom",
		                keyboardNavigation: true,
		            });
		            
		            $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
		            $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');
		        }); 
		    }
        });
    })(jQuery);
</script>
