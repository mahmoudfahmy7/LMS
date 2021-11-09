<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('accounts'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
<?php //var_dump($payment_history); //exit;?>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('accounts'); ?></h4>
                <div class="table-responsive-sm mt-4">
                    <?php if (count($payment_history) > 0): ?>
                    
                        <div class="img-fluid w-100 text-center">
                            <a href="<?php echo base_url()?>/admin/exportMasPayment/paypal" class="btn btn-outline-info btn-sm btn-rounded">
                                <?php echo get_phrase('export_excel'); ?>
                            </a>
                            <a href="<?php echo base_url()?>/admin/exportMasPayment/Payoneer" class="btn btn-outline-info btn-sm btn-rounded">
                                <?php echo get_phrase('export_excel_payoneer'); ?>
                            </a>
                        </div>
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('instructor_name'); ?></th>
                                <th><?php echo get_phrase('instructor_revenue'); ?></th>
                                <th><?php echo get_phrase('admin_revenue'); ?></th>
                                <th><?php echo get_phrase('view_details'); ?></th>
								<th class="text-center"><?php echo get_phrase('revenue_unpaid'); ?></th>
								<th class="text-center" style=" display:none"><?php echo get_phrase('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
							$remain=0;
							foreach ($payment_history as $row){
							
                                $user_data = $this->db->get_where('users', array('id' => $row->id))->row_array();
								//$course_data = $this->db->get_where('course', array('id' => $row['course_id']))->row_array();
								$paypal_keys          = json_decode($user_data['paypal_keys'], true);
								$stripe_keys          = json_decode($user_data['stripe_keys'], true);
                                ?>
                                <tr class="gradeU">
                                    <td>
                                        <strong><?php echo $row->first_name . ' ' . $row->last_name; ?></strong>
                                    </td>
                                    <td><?php echo currency($row->sumIPaid); ?></td>
                                    <td><?php echo currency($row->sumAPaid); ?></td>
                                    <td>
                                        <a href="<?php echo site_url('admin/conversions/show/' . $row->id); ?>"
                                           class="btn btn-icon btn-outline-info btn-sm"> <i class="mdi mdi-eye"></i></a>
                                    </td>
									
									<td class="text-center">
									<?php 
										$remain=(double)$row->sumIPaid-(double)$row->sumPaid;
										echo $remain==0?'0':currency($remain);
									?>
									</td>
									
									<td style="text-align: center; display:none">
									<?php if ((double)$remain > 0){
										if(sizeof($paypal_keys)>0){?>
											<?php if ($paypal_keys[0]['production_client_id'] != ""){ ?>
											<form action="<?php echo site_url('admin/paypal_checkout_for_instructor_revenue'); ?>" method="post">
											  <input type="hidden" name="amount_to_pay"        value="<?php echo $remain; ?>">
											  <input type="hidden" name="payment_id"           value="<?php echo $row->id; ?>">
											  <input type="hidden" name="instructor_name"      value="<?php echo  $row->first_name . ' ' . $row->last_name; ?>">
											  <input type="hidden" name="course_title"         value="<?php echo '';//$course_data['title']; ?>">
											  <input type="hidden" name="production_client_id" value="<?php echo $paypal_keys[0]['production_client_id']; ?>">
											  <input type="submit" class="btn btn-outline-info btn-sm btn-rounded"        value="<?php echo get_phrase('pay_with_paypal'); ?>">
											</form>
										  <?php }else{ ?>
											<button type="button" class = "btn btn-outline-danger btn-sm btn-rounded" name="button" onclick="alert('<?php echo get_phrase('this_instructor_has_not_provided_valid_paypal_client_id'); ?>')"><?php echo get_phrase('pay_with_paypal'); ?></button>
										  <?php } ?>
										<?php }if(sizeof($stripe_keys)>0){?>
										<?php if ($stripe_keys[0]['public_live_key'] != "" && $stripe_keys[0]['secret_live_key']){ ?>
										<form action="<?php echo site_url('admin/stripe_checkout_for_instructor_revenue'); ?>" method="post">
										  <input type="hidden" name="amount_to_pay"   value="<?php echo $remain; ?>">
										  <input type="hidden" name="payment_id"      value="<?php echo $row->id; ?>">
										  <input type="hidden" name="instructor_name" value="<?php echo $row->first_name . ' ' . $row->last_name; ?>">
										  <input type="hidden" name="course_title"    value="<?php echo ''; ?>">
										  <input type="hidden" name="public_live_key" value="<?php echo $stripe_keys[0]['public_live_key']; ?>">
										  <input type="hidden" name="secret_live_key" value="<?php echo $stripe_keys[0]['secret_live_key']; ?>">
										  <input type="submit" class="btn btn-outline-info btn-sm btn-rounded"   value="<?php echo get_phrase('pay_with_stripe'); ?>">
										</form>
									  <?php }else{ ?>
										<button type="button" class = "btn btn-outline-danger btn-sm btn-rounded" name="button" onclick="alert('<?php echo get_phrase('this_instructor_has_not_provided_valid_public_key_or_secret_key'); ?>')"><?php echo get_phrase('pay_with_stripe'); ?></button>
									  <?php } ?>
										<?php 
										}
									}
										?>
									</td>
                                </tr>
                            <?php 
							} ?>
                            </tbody>
                        </table>
                        
                        <div class="img-fluid w-100 text-center">
                            <a href="<?php echo base_url()?>/admin/exportMasPayment/paypal" class="btn btn-outline-info btn-sm btn-rounded">
                                <?php echo get_phrase('export_excel'); ?>
                            </a>
                            <a href="<?php echo base_url()?>/admin/exportMasPayment/Payoneer" class="btn btn-outline-info btn-sm btn-rounded">
                                <?php echo get_phrase('export_excel_payoneer'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if (count($payment_history) == 0): ?>
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
    function update_date_range() {
        var x = $("#selectedValue").html();
        $("#date_range").val(x);
    }
</script>
