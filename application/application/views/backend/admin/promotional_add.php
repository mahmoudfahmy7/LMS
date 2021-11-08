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

                    <form class="required-form" action="<?php echo site_url('admin/promotional/add'); ?>" method="post"
                          enctype="multipart/form-data">



                        <div class="form-group">
                            <label for="code"><?php echo get_phrase('promotional_url'); ?><span
                                        class="required">*</span></label></label>
                            <input type="text" class="form-control url" id="url" name="url" value="<?php echo  "http://" . $url . "/home/?promotional=" . $code; ?>">
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

