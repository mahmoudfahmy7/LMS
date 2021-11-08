<?php
if(isset($_POST['date_range'])){
			$date_range = $this->input->post('date_range');
			$date_range = explode(" - ", $date_range);
			$timestamp_start = strtotime($date_range[0]);
			$timestamp_end = strtotime($date_range[1]);
			//var_dump($this->input->post('date_range'));
			$where ="  payment.date_added>=".strtotime($date_range[0])." and  payment.date_added<=".strtotime($date_range[1])."";
			if($this->input->post('type')==0)
			    $where .="  ";
			if($this->input->post('type')==1)
				$where .="  and payment.instructor_payment_status=1";
			if($this->input->post('type')==2)
				$where .="  and payment.instructor_payment_status=0";
				
		}
	//echo $where;
    $payments= $this->crud_model->getByLic($this->session->userdata('user_id'),$where);
	//echo $this->db->last_query();
	//var_dump($payments);
    //$payment_histories = $this->crud_model->get_instructor_wise_payment_history($this->session->userdata('user_id'));
?>

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title">
                    <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo get_phrase('instructor_revenue'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('instructor_revenue'); ?></h4>
				<div class="row justify-content-md-center">
					<form class="form-inline" action="<?php echo site_url('user/instructor_revenue') ?>" method="post" style="width:100%">
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
                    <?php 
                    if (count($payments) > 0): ?>
                        <table id="basic-datatable" class="table table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('course_name'); ?></th>
								<th><?php echo get_phrase('course_price'); ?></th>
								<th><?php echo get_phrase('revenue_share_%'); ?></th>
                                <th><?php echo get_phrase('revenue_amount'); ?></th>
                                <th><?php echo get_phrase('payment_status'); ?></th>
                                <th><?php echo get_phrase('payment_method'); ?></th>
                                <th><?php echo get_phrase('student_name'); ?></th>
                                <th><?php echo get_phrase('discount_type'); ?></th>
                                <th><?php echo get_phrase('discount_code'); ?></th>
                                <th><?php echo get_phrase('purchase_date'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php //var_dump($payments);exit;
							foreach ($payments as $payment)://;
							    //var_dump($payment);exit;
                                //$user_data = $this->db->get_where('users', array('id' => $payment['user_id']))->row_array();
								//echo $user_data;
                                /*$course_data = $this->db->get_where('course', array('id' => $payment['course_id']))->row_array();*/
								$rowShred='';
								if($payment->amount>0){
								$shared=($payment->instructor_revenue/$payment->amount)*100;
								//if(ceil($shared)==$shared)
									$rowShred=ceil($shared).'%';
								//else 
									//$rowShred=$shared.'$';
								}
								else
									$rowShred='';
                                ?>
                                <tr class="gradeU">
                                    <td>
                                        <strong>
										<a href="<?php echo site_url('user/course_form/course_edit/' . $payment->course_id); ?>"
                                                   target="_blank"><?php echo ($payment->title); ?></a></strong>
                                    </td>
									<td><?php echo currency($payment->amount); ?></td>
									<!--<td><?php //echo round($rowShred) ?></td>-->
									<td><?php echo $payment->instructor_revenue_percentage.'%'; ?></td>
                                    <td><?php echo currency($payment->instructor_revenue); ?></td>
                                    <td><?php echo $payment->instructor_payment_status==0?" Not paid":" Paid" ?></td>
                                    <td><?php echo $payment->payment_type; ?></td>
                                    <td><?php echo $payment->first_name." ".$payment->last_name; ?></td>
                                    <td><?php echo $payment->discount_type; ?></td>
                                    <td><?php echo $payment->discount_code; ?></td>
                                    <td><?php echo date('D, d-M-Y', $payment->date_added); ?></td>
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
<script type="text/javascript">
function update_date_range()
{
    var x = $("#selectedValue").html();
    $("#date_range").val(x);
           
}
</script>
