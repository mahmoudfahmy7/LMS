<script type="text/javascript">
  function togglePriceFields(elem) {
    if($("#"+elem).is(':checked')){
      $('.paid-course-stuffs').slideUp();
    }else
      $('.paid-course-stuffs').slideDown();
    }
</script>

<script type="text/javascript">
jQuery(document).ready(function($) {
      $.fn.dataTable.ext.errMode = 'throw';
      $('#course-datatable-server-side').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax":{
              "url": "<?php echo site_url(strtolower($this->session->userdata('role')).'/get_courses') ?>",
              "dataType": "json",
              "type": "POST",
              "data" : {selected_category_id : '<?php echo $selected_category_id; ?>',
                        selected_status : '<?php echo $selected_status ?>',
                        selected_instructor_id : '<?php echo $selected_instructor_id ?>',
                        selected_price : '<?php echo $selected_price ?>'}
          },
          "columns": [
              { "data": "#" },
              { "data": "title" },
              { "data": "category" },
              { "data": "lesson_and_section" },
              { "data": "enrolled_student" },
              { "data": "status" },
              { "data": "price" },
              { "data": "actions" },
          ],
          "columnDefs": [{
              targets: "_all",
              orderable: false
           }]
      });
  });
</script>

<script type="text/javascript">
$(function() {

    var start = moment();
    var end = moment().add(29, 'days');

    function cb(start, end) {
        $('#reportrangeFuture span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#date_range').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrangeFuture').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Tommorow': [moment().add(1, 'days'), moment().add(1, 'days')],
           'Next 7 Days': [ moment(), moment().add(6, 'days')],
           'Next 30 Days': [moment(), moment().add(29, 'days')],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
        }
    }, cb);

    cb(start, end);
	
		<?php if(isset($coupon) && sizeof($coupon)>0) {?>
			$('#date_range').val('<?php echo date("d F, Y" , $coupon['start']) . " - " . date("d F, Y" , $coupon['end']);?>');	
			$('#reportrangeFuture span').html('<?php echo date("F d,Y" , $coupon['start'])?> - <?php echo date("F d,Y" , $coupon['end'])?>');
		<?php }?>

});
</script>