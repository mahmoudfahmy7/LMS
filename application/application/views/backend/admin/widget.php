<!-- start page title -->
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

                <form class="required-form" action="<?php echo site_url('admin/widget/add'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('title'); ?></label>
                        <input type="text"  id="title" name = "title" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('widget_html'); ?></label>
                        <textarea id="html" name="content" id="content" style="width:100%" rows="10" class="form-control"></textarea>
                        <input type="hidden"  id="type" name = "type" value="1">
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?php echo get_phrase("submit"); ?></button>
                </form>
                
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

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

                
                <form class="required-form" action="<?php echo site_url('admin/widget/add'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('title'); ?></label>
                        <input type="text"  id="title" name = "title" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('widget_html'); ?></label>
                        <input type="file"  id="content" name = "content">
                        <input type="hidden"  id="type" name = "type" value="2">
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?php echo get_phrase("submit"); ?></button>
                </form>
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> 
                <?php echo get_phrase('widget'); ?>
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
                  <table class="table table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('title'); ?></th>
								<th class="text-center"><?php echo get_phrase('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                               <?php foreach ($widget_list as $row){?>
                               <tr class="gradeU">
                                    <td>
                                        <?php echo $row->title ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url()?>admin/edit_widget/<?php echo $row->pk_i_id?>" class="btn btn-icon btn-outline-info btn-sm" 
                                        id="category-edit-btn-9" style="">
                                            <i class="mdi mdi-wrench"></i> Edit                     
                                        </a>
                                        <a href="#" class="btn btn-icon btn-outline-danger btn-sm" 
                                        style="float: right;" onclick="confirm_modal('<?php echo base_url()?>admin/widget/delete/<?php echo $row->pk_i_id?>');">
                         <i class="mdi mdi-delete"></i> Delete                     </a>
                                    </td>
                                <tr />
                               <?php }?>
                            </tbody>
                        </table>
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<script>
function adjustLabel(img,x,y){
    $("."+img).attr('style','position: absolute; top: '+y+'px; left: '+x+'px; color: #000000;')
}
$( '#target' ).on( 'click', function( e ) {
    var x = e.pageX - this.offsetLeft;
    var y = e.pageY - this.offsetTop;
    $(".tag").attr('style','position: absolute; top: '+y+'px; left: '+x+'px;')
    console.log('x='+e.pageX,'y='+e.pageY)
});
</script>
