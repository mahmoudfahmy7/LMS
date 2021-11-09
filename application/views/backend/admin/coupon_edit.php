<!-- start page title -->
<?php
$coupon = $coupon[0];
$code = $coupon['code'];
$CouponUrl = $coupon['url'];
$priceOfCourse=$this->crud_model->getCoursePriceById($coupon['course_id']);
$url = $_SERVER['HTTP_HOST'];
?>
<h1><?php //var_dump($coupon['course_id']);exit;?></h1>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('edit_coupon'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <h4 class="mb-3 header-title"><?php echo get_phrase('coupon_edit_form'); ?></h4>

                    <form class="required-form" action="<?php echo site_url('admin/coupons/edit/'.$coupon['id']); ?>" method="post"
                          enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="type"><?php echo get_phrase('type'); ?><span
                                        class="required">*</span></label></label>
                            <select class="form-control select2" data-toggle="select2" name="type" id="type"
                                    onchange="checkCategoryType(this.value)" required>
                                    <option value="<?php echo $coupon['type']; ?>"><?php echo $coupon['type']; ?></option>
                                <?php foreach ($type as $row): ?>
                                    <?php if (count($row) != 0 && $row != $coupon['type']): ?>
                                        <option value="<?php echo $row; ?>"><?php echo $row; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
<?php  ?>
                        <div class="form-group" id="dev_course_id" <?php if ($coupon['type'] == 'public') echo 'style="display: none"'?>>
                            <label for="course_id"><?php echo get_phrase('course_title'); ?><span
                                        class="required">*</span></label></label>
                            <select class="form-control select2" data-toggle="select2" name="course_id" id="course_id"
                                    onchange="checkCourses(this.value)" required>
                                <option price="<?php echo $priceOfCourse; ?>" value="<?php echo $coupon['course_id'];?>"><?php echo $this->crud_model->getCourseTitleById($coupon['course_id']); ?></option>
                                <?php foreach ($courses as $course): ?>
                                    <?php if ($course['id'] != 0 &&  $course['id'] != $coupon['course_id']): /*$priceOfCourse=$course['price'];*/ ?>
                                        <option price="<?php echo $course['price']; ?>" value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('coupon_code'); ?><span
                                        class="required">*</span></label></label>
                            <input type="text" class="form-control" id="code" name="code" value="<?php echo $code; ?>">
                        </div>
                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('coupon_url'); ?><span
                                        class="required">*</span></label></label>
                            <input type="text" class="form-control url" id="url" name="url"  value="<?php echo $CouponUrl; ?>">
                        </div>

                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('amount_of_discount'); ?>  <span
                                        class="required">*</span></label></label>
							<div class="row">
							<div class="col-sm-6">
								<input type="number" class="form-control url" id="percent" name="percent" value="<?php echo $coupon['percent'];?>" required onchange="changeOption()">
							</div>
							<div class="col-sm-6">
							<select class="form-control" name="i_factor" id="i_factor" onchange="changeOption()">
								<option value="1" <?php echo $coupon['i_factor']==1?"selected":""?>><?php echo get_phrase('percent'); ?></option>
								<option value="2" <?php echo $coupon['i_factor']==2?"selected":""?>><?php echo get_phrase('fixed'); ?></option>
							</select>
							</div>
							<?php $priceAfterDissount = $priceOfCourse;
							        if($coupon['i_factor']==1){
							            $priceAfterDissount = $priceOfCourse-(($coupon['percent']*$priceOfCourse)/100);
							            }
							        else if ($coupon['i_factor']==2){
							            $priceAfterDissount = $priceOfCourse-$coupon['percent'];
							            }
							?>
							</div>
						    	<label style="color:blue" id="note" ><?php echo get_phrase('This_coupon_will_deduct'); ?> <span  id="percent_11"><?php echo $coupon['percent'];?></span> <?php if($coupon['i_factor']==1){echo '% ';}else if($coupon['i_factor']==2){echo 'USD ';}; echo get_phrase('from_course_total_price'); echo "<br>"; ?></label>
							
							    <label style="color:blue;display:none" for="note1" id="note1" ><?php echo get_phrase('This_coupon_will_deduct'); ?> <span  id="percent_1"><?php echo $coupon['percent'];?></span> <?php echo get_phrase('%_from_course_total_price'); echo "<br>"; ?></label>
							
							    <label style="color:blue;display:none" for="note2" id="note2" ><?php echo 'This coupon will deduct '; ?> <span  id="percent_2"><?php echo $coupon['percent'];?></span> <?php echo ' USD from course total price'; ?></label>
							
							<span id="s1" <?php if($coupon['type']=='public'){?>style="display:none"<?php } ?>><span id="comm1" style="color:green;"><?php echo "<br>";?>Course price before discount :</span><span style="color:green;" id="id_price"><?php echo $priceOfCourse;?></span></span>
							<span id="s2" <?php if($coupon['type']=='public'){?>style="display:none"<?php } ?>><span id="comm2" style="color:red;"><?php echo "<br>";?>Course price after discount : </span><span style="color:red;" id="id_price_after"><?php echo $priceAfterDissount;?></span></span>
							
							
                        </div>
                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('expired_data'); ?> (from/to) <span
                                        class="required">*</span></label></label>
                            <div id="reportrangeFuture" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light" style="width: 100%;">
                                <i class="mdi mdi-calendar"></i>&nbsp;
                                <span id="selectedValue"><?php echo date("F d, Y" , $coupon['start']) . " - " . date("F d, Y" , $coupon['end']);?></span> <i class="mdi mdi-menu-down"></i>
                            </div>
                            <input id="date_range" type="hidden" name="expired_data" value="<?php echo date("d F, Y" , $coupon['start']) . " - " . date("d F, Y" , $coupon['end']);?>" required>
                        </div>

                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('expired_count'); ?><span
                                        class="required">*</span></label></label>
                            <input type="number" class="form-control url" id="expired_count" name="expired_count" value="<?php echo $coupon['expired_count'];?>" required>
                        </div>

                        <div class="form-group">

                        </div>

                        <button type="button" class="btn btn-primary"
                                onclick="checkRequiredFields()"><?php echo get_phrase("submit"); ?></button>
                    </form>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script type="text/javascript">
    function changeOption(){
	var optionVal = $("#i_factor").val();
	var percent = $("#percent").val();
	var currPrice = $('#course_id option:selected').attr('price');
	
	if(optionVal==1){
		var newPrice = currPrice-((percent*currPrice)/100);
		$('#id_price_after').html(newPrice)
		$('#percent_1').html($('#percent').val());
    	$('#percent_2').html($('#percent').val());
		$("#note1").show()
		$("#note2").hide()
		$("#note").hide()
	}
	else if(optionVal==2){
		var newPrice = currPrice-percent;
		$('#id_price_after').html(newPrice)
		$('#percent_1').html($('#percent').val());
    	$('#percent_2').html($('#percent').val());
		$("#note2").show()
		$("#note1").hide()
		$("#note").hide()
	}
}
    function checkCourses(id) {
        $('#id_price').html($('#course_id option:selected').attr('price'));
        
        	var optionVal = $("#i_factor").val();
        	var percent = $("#percent").val();
        	var currPrice = $('#course_id option:selected').attr('price');
        	
        	if(optionVal==1){
        		var newPrice = currPrice-((percent*currPrice)/100);
        		$('#id_price_after').html(newPrice)
        		
        	}
        	else if(optionVal==2){
        		var newPrice = currPrice-percent;
        		$('#id_price_after').html(newPrice)
        		
        	}
		//$('#id_price_after').html($('#course_id option:selected').attr('price'));
		$('#percent_1').html($('#percent').val());
		$('#percent_2').html($('#percent').val());
        
        if (id > 0) {
            let coursesObj = <?php echo json_encode($courses); ?>;
            const url = "http://" + "<?php echo $url; ?>" + "/home/course/";
            const code = "<?php echo $code; ?>";
            let title = "";
            for (var i = 0; i < coursesObj.length; i++) {
                if (coursesObj[i]['id'] === id) {
                    title = coursesObj[i]['title']
                }
            }
            const coupon_url = url + title.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "").replace(/ /g, '-').toLowerCase() + '/'+ id + "?code="+ code;
            $('#url').val(coupon_url);
        }
    }

    function checkCategoryType(type) {
        var optionVal = $("#i_factor").val();
        var percent = $("#percent").val();
        var currPrice = $('#course_id option:selected').attr('price');
        var newPrice = currPrice;
        if(optionVal==1){
                newPrice = currPrice-((percent*currPrice)/100);
        		$('#id_price_after').html(newPrice)
    	        $("#note1").show()
    	        $("#note2").hide()
    	    }
    	    else if(optionVal==2){
    	        newPrice = currPrice-percent;
        		$('#id_price_after').html(newPrice)
    	        $("#note2").show()
    	        $("#note1").hide()
    	        
    	    }
    	    alert( $('#course_id').val("<?php echo $coupon['course_id']; ?>"))
        if (type !== "specific") {
            $('#course_id').val(null);
            $('#dev_course_id').hide();
            $("#comm1").hide()
    	    $("#comm2").hide()
    	    $("#id_price").hide()
    	    $("#id_price_after").hide()
        } else {
            $('#course_id').val("<?php echo $coupon['course_id']; ?>");
            $('#dev_course_id').show();
            $("#comm1").show()
    	    $("#comm2").show()
    	    $("#id_price").show()
    	    $("#id_price_after").show()
    	    $('#id_price_after').html(newPrice)
    	    $("#s1").show()
    	    $("#s2").show()
    	    $("#note").hide()
    	    $('#id_price').html('0')
    	    $('#id_price_after').html('0')
    	    $("<option>", { value: '', selected: true }).prependTo("#course_id")
    	    //$('#course_id').val('')
        }
    }
</script>
