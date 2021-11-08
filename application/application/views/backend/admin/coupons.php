<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('coupons'); ?>
                    <a href="<?php echo site_url('admin/coupon_form/add_coupon'); ?>"
                       class="btn btn-outline-primary btn-rounded alignToTitle"><i
                                class="mdi mdi-plus"></i><?php echo get_phrase('add_new_coupons'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('coupons'); ?></h4>
                <!--                <div class="row justify-content-md-center">-->
                <!--                    <div class="col-xl-6">-->
                <!--                        <form class="form-inline" action="-->
                <?php //echo site_url('admin/coupons/filter_by_date_range') ?><!--"-->
                <!--                              method="get">-->
                <!--                            <div class="col-xl-10">-->
                <!--                                <div class="form-group">-->
                <!--                                    <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light" style="width: 100%;">-->
                <!--                                        <i class="mdi mdi-calendar"></i>&nbsp;-->
                <!--                                        <span id="selectedValue">-->
                <?php //echo date("F d, Y" , $timestamp_start) . " - " . date("F d, Y" , $timestamp_end);?><!--</span> <i class="mdi mdi-menu-down"></i>-->
                <!--                                    </div>-->
                <!--                                    <input id="date_range" type="hidden" name="date_range" value="-->
                <?php //echo date("d F, Y" , $timestamp_start) . " - " . date("d F, Y" , $timestamp_end);?><!--">-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                            <div class="col-xl-2">-->
                <!--                                <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> -->
                <?php //echo get_phrase('filter');?><!--</button>-->
                <!--                            </div>-->
                <!---->
                <!--                        </form>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="table-responsive-sm mt-4" style="overflow-x:auto;">
                    <?php if (count($coupons) > 0): ?>
                        <table class="table table-striped table-centered mb-0" >
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('type'); ?></th>
                                <th><?php echo get_phrase('created_by'); ?></th>
                                <th><?php echo get_phrase('discount'); ?></th>
                                <th><?php echo get_phrase('start'); ?></th>
                                <th><?php echo get_phrase('end'); ?></th>
                                <th><?php echo get_phrase('coupon_code'); ?></th>
                                <th style="display:none"><?php echo get_phrase('coupon_link'); ?></th>
                                <th><?php echo get_phrase('expired_count'); ?></th>
                                <th><?php echo get_phrase('count_of_use'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($coupons as $coupon):
							$isPercent=''; 
							if($coupon['i_factor']==1)$isPercent='%';
                                $user_data = $this->db->get_where('users', array('id' => $coupon['user_id']))->row_array();
//                                $course_data = $this->db->get_where('course', array('id' => $coupon['course_id']))->row_array();
                                ?>
                                <tr class="gradeU">
                                    <td><?php echo $coupon['type']; ?></td>
                                    <td>
                                        <strong><?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?></strong>
                                    </td>
                                    <td><strong><?php echo $coupon['percent'].' '.$isPercent; ?></strong></td>
                                    <td><?php echo date('D, d-M-Y', $coupon['start']); ?></td>
                                    <td><?php echo date('D, d-M-Y', $coupon['end']); ?></td>
                                    <td><?php echo $coupon['code']; ?></td>
                                    <td style="display:none"><?php echo $coupon['url']; ?></td>
                                    <td><?php echo $coupon['expired_count']; ?></td>
                                    <td><?php echo $coupon['count_of_use']; ?></td>
                                    <td>
									    <a onclick="copyLink('<?php echo $coupon['url']; ?>')" title="Copy Coupon Link"
                                           class="btn btn-icon btn-outline-success btn-sm"> <i class="mdi mdi-content-copy"></i></a>
                                        <a href="<?php echo site_url('admin/coupon_form/edit_coupon/' . $coupon['id']); ?>" title="Edit Coupon"
                                           class="btn btn-icon btn-outline-info btn-sm"> <i class="mdi mdi-wrench"></i></a>

                                        <!--a href="<?php //echo site_url('admin/coupons/delete/' . $coupon['id']); ?>"
                                           class="btn btn-icon btn-outline-danger btn-sm"> <i
                                                    class="dripicons-trash"></i></a-->
										<a href="#" title="Delete Coupon" onclick="confirm_modal('<?php echo site_url('admin/coupons/delete/'.$coupon['id']); ?>')" class="btn btn-icon btn-outline-danger btn-sm"><i class="dripicons-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    
                    <?php if (count($coupons) == 0): ?>
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
<input type="text" style="display:none" id="myInput" />
<script type="text/javascript">
    function update_date_range() {
        var x = $("#selectedValue").html();
        $("#date_range").val(x);
    }
	
	function copyLink(url){
		console.log(url);
		$("#myInput").val(url);
		$("#myInput").attr('style','display:block');
		var copyText = document.getElementById("myInput");
		  copyText.select();
		  copyText.setSelectionRange(0, 99999)
		  document.execCommand("copy");
		  alert("Link copied" );
		$("#myInput").attr('style','display:none');
		  
	}
</script>
