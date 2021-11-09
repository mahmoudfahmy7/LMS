<!-- start page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('certificate'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
              <div class="col-lg-12">
                <h4 class="mb-3 header-title"><?php echo get_phrase('category_add_form'); ?></h4>

                <form class="required-form" action="<?php echo site_url('admin/certificate/add'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('pick_certificate_image'); ?></label>
                        <input type="file"  id="cert_file" name = "cert_file" onchange="startupload();">
                        <input type="hidden"  id="path" name = "path" value="<?php echo $lastCert[0]->s_image?>">
                        <input type="hidden"  id="pk_i_id" name = "pk_i_id" value="<?php echo $lastCert[0]->pk_i_id?>">
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
							<span class="" style="    min-height: 64px; width: 64px;">
								<img  src="<?php echo base_url()?>uploads/cert_files/<?php echo $lastCert[0]->s_image?>" id="target">
							</span>
							<div class="tag" style="position: absolute; top: <?php echo $lastCert[0]->y1?>px; left: <?php echo $lastCert[0]->x1?>px; color: #000000; ">
								<h2 style="padding: 0px;    margin: 0px;">
                                    Student Name
                                </h2>
							</div>
							<div class="tag1" style="position: absolute; top: <?php echo $lastCert[0]->y2?>px; left: <?php echo $lastCert[0]->x2?>px; color: #000000; ">
								<h3 style="padding: 0px;    margin: 0px;">
                                    Course Name
                                </h3>
							</div>
						</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('std_name_pos'); ?>  </label> &nbsp; &nbsp;x:
                        <input type="number"  id="x1" name = "x1" value="<?php echo $lastCert[0]->x1?>" min="1" onchange="adjustLabel('tag',$('#x1').val(),$('#y1').val())"> y:
                        <input type="number"  id="y1" name = "y1" value="<?php echo $lastCert[0]->y1?>" min="1" onchange="adjustLabel('tag',$('#x1').val(),$('#y1').val())">
                    </div>
                    
                    <div class="form-group">
                        <label for="code"><?php echo get_phrase('crs_name_pos'); ?></label> &nbsp; &nbsp;x:
                        <input type="number"  id="x2" name = "x2" value="<?php echo $lastCert[0]->x2?>" min="1" onchange="adjustLabel('tag1',$('#x2').val(),$('#y2').val())">y:
                        <input type="number"  id="y2" name = "y2" value="<?php echo $lastCert[0]->y2?>" min="1" onchange="adjustLabel('tag1',$('#x2').val(),$('#y2').val())">
                    </div>
                    
                    
                    <button type="button" class="btn btn-primary" onclick="checkRequiredFields()"><?php echo get_phrase("submit"); ?></button>
                </form>
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script type="text/javascript">
    function startupload(){
        var formData = new FormData($(".required-form")[0]);
            $.ajax({
                url: '<?php echo base_url()?>admin/uploadCert',
                type: 'POST',
                data: formData,
                dataType:"json",
                async: true,
                success: function (data) {
                    if(data.length>0){
                        $("#target").attr('src','<?php echo base_url()?>uploads/cert_files/'+data);
                        $("#path").val(data)
                    }
                    else {
                        
                    }
                    $(".loader").addClass('hide');
                },
                error:function(){
                   
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
    }
</script>


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
