<!-- start page title -->
<?php
$code = substr(md5(rand(0, 1000000)), 0, 10);
$url = $_SERVER['HTTP_HOST'];
?>

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('add_new_coupon'); ?>
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
                    <h4 class="mb-3 header-title"><?php echo get_phrase('coupon_add_form'); ?></h4>

                    <form class="required-form" action="<?php echo site_url('admin/coupons/add'); ?>" method="post"
                          enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="type"><?php echo get_phrase('type'); ?><span
                                        class="required">*</span></label></label>
                            <select class="form-control select2" data-toggle="select2" name="type" id="type"
                                    onchange="checkCategoryType(this.value)" required>
                                    <!--option value="0"><?php //echo get_phrase('none'); ?></option-->
                                <?php foreach ($type as $row): ?>
                                    <?php if (count($row) != 0): ?>
                                        <option value="<?php echo $row; ?>"><?php echo $row; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" id="dev_course_id">
                            <label for="course_id"><?php echo get_phrase('course_title'); ?><span
                                        class="required">*</span></label></label>
                            <select class="form-control select2" data-toggle="select2" name="course_id" id="course_id"
                                    onchange="checkCourses(this.value)" required>
                                <option value="0"><?php echo get_phrase('none'); ?></option>
                                <?php foreach ($courses as $course): ?>
                                    <?php if ($course['id'] != 0): ?>
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
                            <input type="text" class="form-control url" id="url" name="url" value="<?php echo $url . "/home/?code=". $code; ?>">
                        </div>

                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('amount_of_discount'); ?>  <span
                                        class="required">*</span></label></label>
							<div class="row">
								<div class="col-sm-6">
									<input type="number" class="form-control url" id="percent" name="percent" required value="0" onchange="changeOption()" >
								</div>
								<div class="col-sm-6">
								<select class="form-control" name="i_factor" id="i_factor"  onchange="changeOption()">
									<option value="1"><?php echo get_phrase('percent'); ?></option>
									<option value="2"><?php echo get_phrase('fixed'); ?></option>
								</select>
								</div>
							</div>
							<label style="color:blue;" for="note1" id="note1" ><?php echo get_phrase('This_coupon_will_deduct'); ?> <span  id="percent_1"></span> <?php echo get_phrase('%_from_course_total_price'); echo "<br>"; ?></label>
							<label for="note2" id="note2" style="display:none;color:blue;"><?php echo 'This coupon will deduct'; ?> <span  id="percent_2"></span> <?php echo 'USD from course total price'; ?></label>
							<span id="comm1" style="color:green;"><?php echo "<br>";?>Course price before discount : </span><span style="color:green;" id="id_price"></span>
							<span id="comm2" style="color:red;"><?php echo "<br>";?>Course price after discount : </span><span style="color:red;" id="id_price_after"></span>
                        </div>
                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('expired_data'); ?> (from/to) <span
                                        class="required">*</span></label></label>
                            <div id="reportrangeFuture" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light" style="width: 100%;">
                                <i class="mdi mdi-calendar"></i>&nbsp;
                                <span id="selectedValue" ><?php echo date("F d, Y" , $timestamp_start) . " - " . date("F d, Y" , $timestamp_end);?></span> <i class="mdi mdi-menu-down"></i>
                            </div>
                            <input id="date_range" type="hidden" name="expired_data" value="<?php echo date("d F, Y" , $timestamp_start) . " - " . date("d F, Y" , $timestamp_end);?>" required>
                        </div>

                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('expired_count'); ?></label>
                            <input type="number" class="form-control url" id="expired_count" name="expired_count">
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
	var type = $('#type option:selected').attr('typeName');
	
	if(optionVal==1){
	    var newPrice = currPrice-((percent*currPrice)/100);
    	$('#id_price_after').html(newPrice)
    	$('#percent_1').html($('#percent').val());
    	$('#percent_2').html($('#percent').val());
    	$("#note1").show()
    	$("#note2").hide()
    	}
    	else if(optionVal==2){
    		var newPrice = currPrice-percent;
    		$('#id_price_after').html(newPrice)
    		$('#percent_1').html($('#percent').val());
    		$('#percent_2').html($('#percent').val());
    		$("#note2").show()
    		$("#note1").hide()
    	}
	
	
}

    function checkCourses(id) {
		$('#id_price').html($('#course_id option:selected').attr('price'));
		$('#id_price_after').html($('#course_id option:selected').attr('price'));
		$('#percent').val(0);
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
        if(optionVal==1){
    	        $("#note1").show()
    	        $("#note2").hide()
    	    }
    	    else if(optionVal==2){
    	        $("#note2").show()
    	        $("#note1").hide()
    	        
    	    }
    	    
        if (type !== "specific") {
            $('#dev_course_id').hide();
            $("#comm1").hide()
    	    $("#comm2").hide()
    	    $("#id_price").hide()
    	    $("#id_price_after").hide()
    	    
        } else {
            $('#dev_course_id').show();
             $("#comm1").show()
    	    $("#comm2").show()
    	    $("#id_price").show()
    	    $("#id_price_after").show()
    	    
           
        }
    }
</script>
