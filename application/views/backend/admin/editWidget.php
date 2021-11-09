<!-- start page title -->
<?php if($widget_list[0]->type==1){?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> 
                <?php echo get_phrase('widget_html_form'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
              <div class="col-lg-12">

                <form class="required-form" action="<?php echo site_url('admin/widget/update'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('title'); ?></label>
                        <input type="text"  id="title" name = "title" value="<?php echo $widget_list[0]->title?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('widget_html'); ?></label>
                        <textarea id="html" name="content" id="content" style="width:100%" rows="10" class="form-control"><?php echo $widget_list[0]->content?></textarea>
                        <input type="hidden"  id="type" name = "type" value="1">
                        <input type="hidden"  id="pk_i_id" name = "pk_i_id" value="<?php echo $widget_list[0]->pk_i_id?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?php echo get_phrase("submit"); ?></button>
                </form>
                
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<?php } else { ?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> 
                <?php echo get_phrase('widget_img_form'); ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
              <div class="col-lg-12">

                
                <form class="required-form" action="<?php echo site_url('admin/widget/update'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('title'); ?></label>
                        <input type="text"  id="title" name = "title" value="<?php echo $widget_list[0]->title?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('widget_html'); ?></label>
                        <input type="file"  id="content" name = "content">
                        <input type="hidden"  id="type" name = "type" value="2">
                        <input type="hidden"  id="pk_i_id" name = "pk_i_id" value="<?php echo $widget_list[0]->pk_i_id?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?php echo get_phrase("submit"); ?></button>
                </form>
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<?php }?>
