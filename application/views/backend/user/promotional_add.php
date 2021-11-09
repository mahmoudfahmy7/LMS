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
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('add_new_promotional_link'); ?>
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
                    <h4 class="mb-3 header-title"><?php echo get_phrase('promotional_link_add_form'); ?></h4>

                    <form class="required-form" action="<?php echo site_url('user/promotional/add'); ?>" method="post"
                          enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="course_id"><?php echo get_phrase('course_title'); ?><span
                                        class="required">*</span></label></label>
                            <select class="form-control select2" data-toggle="select2" name="course_id" id="course_id"
                                    onchange="checkCourses(this.value)" required>
                                <option value="0"><?php echo get_phrase('none'); ?></option>
                                <?php foreach ($courses as $course): ?>
                                    <?php if ($course['id'] != 0): ?>
                                        <option value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('promotional_url'); ?><span
                                        class="required">*</span></label></label>
                            <input type="text" class="form-control url" id="url" name="url">
                        </div>
                        <div class="form-group" hidden>
                            <label for="code"><?php echo get_phrase('code'); ?></label></label>
                            <input type="text" class="form-control url" id="code" name="code"
                                   value="<?php echo $code; ?>">
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
            const promotional_url = url + title.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "").replace(/ /g, '-').toLowerCase() + '/' + id + "?promotional=" + code;
            $('#url').val(promotional_url);
        }
    }
</script>
