<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title">
                    <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo get_phrase('user_conversions'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('conversions'); ?></h4>
				<div class="row justify-content-md-center">
				
                      <form class="form-inline" action="<?php echo site_url('admin/conversions/show/'.$userid.'/filter_by_date_range') ?>" method="post" style="width:100%">
                      <!--form class="form-inline" action="<?php //echo site_url('admin/conversions/filter_by_date_range') ?>" method="get" -->
							<div class="col-xl-2">
								<div class="form-group">
									<label for="type"><?php echo get_phrase('payment_status'); ?></label>
									<select class="form-control select2" data-toggle="select2" name="type" id="type">
										<option value="0"><?php echo get_phrase('all'); ?></option>
										<option value="1"><?php echo get_phrase('paid'); ?></option>
										<option value="2"><?php echo get_phrase('not_paid'); ?></option>
									</select>
								</div>
							</div>
							<div class="col-xl-8">
									<div class="form-group">
										<label for="type"><?php echo get_phrase('date_range'); ?></label>
										<div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light" style="width: 100%;">
											<i class="mdi mdi-calendar"></i>&nbsp;
											<span id="selectedValue"><?php echo date("F d, Y" , $timestamp_start) . " - " . date("F d, Y" , $timestamp_end);?></span> <i class="mdi mdi-menu-down"></i>
										</div>
										<input id="date_range" type="hidden" name="date_range" value="<?php echo date("d F, Y" , $timestamp_start) . " - " . date("d F, Y" , $timestamp_end);?>">
									</div>
							</div>
							<div class="col-xl-2">
									<label for=".." class="text-white"><?php echo get_phrase('..'); ?></label>
									<button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter');?></button>
							</div>
                
                      </form>
				</div>
				
                <div class="table-responsive-sm mt-4" style="overflow-x:auto;">
                    <?php /*var_dump($payment_history);exit;*/ if (count($payment_history) > 0): ?>
                        <table id="course-datatable" class="table table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('course_name'); ?></th>
								<th><?php echo get_phrase('course_price'); ?></th>
								<th><?php echo get_phrase('revenue_share_%'); ?></th>
                                <th><?php echo get_phrase('instructor_revenue'); ?></th>
                                <th><?php echo get_phrase('payment_status'); ?></th>
								<th><?php echo get_phrase('admin_revenue'); ?></th>
                                <th><?php echo get_phrase('payment_method'); ?></th>
                                <th><?php echo get_phrase('student_name'); ?></th>
                                <th><?php echo get_phrase('discount_type'); ?></th>
                                <th><?php echo get_phrase('discount_code'); ?></th>
                                <th><?php echo get_phrase('purchase_date'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
							//var_dump($payment_history);exit;
							foreach ($payment_history as $row):
                                $user_data = $this->db->get_where('users', array('id' => $row->user_course_id))->row_array();
                                $course_data = $this->db->get_where('course', array('id' => $row->course_id))->row_array();
								
								$rowShred='';
								if($row->amount>0){
									$shared=($row->instructor_revenue*100/$row->amount);
									
									if(ceil($shared)== $shared )
										$rowShred=$shared.'%';
									else 
										$rowShred=$shared.'$';
								}
								else
									$rowShred='';
                                ?>
                                <tr class="gradeU">
                                    <td>
                                        <strong><a href="<?php echo site_url('user/course_form/course_edit/' . $course_data['id']); ?>"
                                                   target="_blank"><?php echo ellipsis($course_data['title']); ?></a></strong><br>
                                    </td>
									<td><?php echo currency($row->amount); ?></td>
									<td><?php echo $row->instructor_revenue_percentage.'%'; ?></td>
									<!--<td><?php //echo round($shared)."%" ?></td>-->
                                    <td><?php echo currency($row->instructor_revenue); ?></td>
                                    <td><?php echo $row->instructor_payment_status==0?" Not paid":" Paid" ?></td>
                                    <td><?php echo currency($row->admin_revenue); ?></td>
									<td><?php echo $row->payment_type; ?></td>
                                    <td><?php echo $row->first_name." ".$row->last_name; ?></td>
                                    <td><?php echo $row->discount_type; ?></td>
                                    <td><?php echo $row->discount_code; ?></td>
                                    <td><?php echo date('D, d-M-Y', $row->date_added); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    
                    <?php else :
                    ?>
                     <div class="img-fluid w-100 text-center">
                        <img style="opacity: 1; width: 100px;"
                             src="<?php echo base_url('assets/backend/images/file-search.svg'); ?>"><br>
                        <?php echo get_phrase('no_data_found'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<script>
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
	
		
});
</script>

<script type="text/javascript">
function update_date_range()
{
    var x = $("#selectedValue").html();
    $("#date_range").val(x);
           
}
</script>