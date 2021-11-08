<!-- start page title -->
<?php
$coupon = $coupon[0];
$code = $coupon['code'];
$CouponUrl = $coupon['url'];
$url = $_SERVER['HTTP_HOST'];
?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('show_coupon'); ?>
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
                    <h4 class="mb-3 header-title"><?php echo get_phrase('coupon_show_form'); ?></h4>


                    <div class="form-group">
                        <label for="type"><?php echo get_phrase('type'); ?><span
                                    class="required">*</span></label></label>
                        <select class="form-control select2" data-toggle="select2" name="type" id="type"
                                onchange="checkCategoryType(this.value)" disabled>
                            <option value="<?php echo $coupon['type']; ?>"><?php echo $coupon['type']; ?></option>
                            <?php foreach ($type as $row): ?>
                                <?php if (count($row) != 0 && $row != $coupon['type']): ?>
                                    <option value="<?php echo $row; ?>"><?php echo $row; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group"
                         id="dev_course_id" <?php if ($coupon['type'] == 'public') echo 'style="display: none"' ?>>
                        <label for="course_id"><?php echo get_phrase('course_title'); ?><span
                                    class="required">*</span></label></label>
                        <select class="form-control select2" data-toggle="select2" name="course_id" id="course_id"
                                onchange="checkCourses(this.value)" disabled>
                            <option value="<?php echo $coupon['course_id']; ?>"><?php echo $this->crud_model->getCourseTitleById($coupon['course_id']); ?></option>
                            <?php foreach ($courses as $course): ?>
                                <?php if ($course['id'] != 0 && $course['id'] != $coupon['course_id']): ?>
                                    <option value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('coupon_code'); ?><span
                                    class="required">*</span></label></label>
                        <input type="text" class="form-control" id="code" name="code" value="<?php echo $code; ?>"
                               >
                    </div>
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('coupon_url'); ?><span
                                    class="required">*</span></label></label>
                        <input type="text" class="form-control url" id="url" name="url"
                               value="<?php echo $CouponUrl; ?>" >
                    </div>

					<div class="form-group">
                            <label for="code"><?php echo get_phrase('amount_of_discount'); ?> <span
                                        class="required">*</span></label>
							<div class="row">
										<div class="col-sm-6">
								<input type="number" class="form-control url" id="percent" name="percent" value="<?php echo $coupon['percent'];?>" required>
							</div>
							<div class="col-sm-6">
							<select class="form-control" name="i_factor">
								<option value="1" <?php echo $coupon['i_factor']==1?"selected":""?>><?php echo get_phrase('percent'); ?></option>
								<option value="2" <?php echo $coupon['i_factor']==2?"selected":""?>><?php echo get_phrase('fixed'); ?></option>
							</select>
							</div>
							
							</div>
                    </div>
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('expired_data'); ?> (from/to) <span
                                    class="required">*</span></label></label>
                        <div id="reportrange" class="form-control" data-toggle="date-picker-range"
                             data-target-display="#selectedValue" data-cancel-class="btn-light" style="width: 100%;">
                            <i class="mdi mdi-calendar"></i>&nbsp;
                            <span id="selectedValue"><?php echo date("F d, Y", $timestamp_start) . " - " . date("F d, Y", $timestamp_end); ?></span>
                            <i class="mdi mdi-menu-down"></i>
                        </div>
                        <input id="date_range" type="hidden" name="expired_data"
                               value="<?php echo date("d F, Y", $coupon['start']) . " - " . date("d F, Y", $coupon['end']); ?>"
                               disabled>
                    </div>

                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('expired_count'); ?><span
                                    class="required">*</span></label></label>
                        <input type="number" class="form-control url" id="expired_count" name="expired_count"
                               value="<?php echo $coupon['expired_count']; ?>" disabled>
                    </div>

                    <div class="form-group">

                    </div>

                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script type="text/javascript">
    function checkCourses(id) {
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
            const coupon_url = url + title.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "").replace(/ /g, '-').toLowerCase() + '/' + id + "?code=" + code;
            $('#url').val(coupon_url);
        }
    }

    function checkCategoryType(type) {
        if (type !== "specific") {
            $('#course_id').val(null);
            $('#dev_course_id').hide();
        } else {
            $('#course_id').val("<?php echo $coupon['course_id']; ?>");
            $('#dev_course_id').show();
        }
    }
</script>
