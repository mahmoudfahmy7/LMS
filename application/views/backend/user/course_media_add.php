<?php
  $course_media_files = themeConfiguration(get_frontend_settings('theme'), 'course_media_files');
  $course_media_placeholders = themeConfiguration(get_frontend_settings('theme'), 'course_media_placeholders');
  foreach ($course_media_files as $course_media => $size): ?>
  <div class="col-xl-8">
    <div class="form-group row mb-3">
      <label class="col-md-2 col-form-label" for="<?php echo $course_media.'_label' ?>"><?php echo get_phrase($course_media); ?></label>
      <div class="col-md-10">
          <img src="<?php echo base_url()?>assets/backend/images/loader.gif" class="Loader" style="height: 32px; display:none;" />
        <div class="wrapper-image-preview" style="margin-left: -6px;">
          <div class="box" style="width: 250px;">
            <div class="js--image-preview" style="background-image: url(<?php echo base_url().$course_media_placeholders[$course_media.'_placeholder']; ?>); background-color: #F5F5F5;"></div>
            <div class="upload-options">
              <label for="<?php echo $course_media; ?>" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase($course_media); ?> <br> <small>(<?php echo $size; ?>)</small> </label>
              <input id="<?php echo $course_media; ?>" style="visibility:hidden;" type="file" class="image-upload" name="<?php echo $course_media; ?>" accept="image/*">
              <input id="thumb<?php echo $course_media; ?>" name="thumb<?php echo $course_media; ?>" style="visibility:hidden;" type="hidden">
            </div>
          </div>
          <input type="hidden" id="s_image1" />
          <button type="button" class="btn btn-primary hide Loader1" style="display:none;" onclick="popWindow()">
            Crop image
          </button>
          
        </div>
      </div>
    </div>
  </div>
<?php endforeach; 

if($course_media=='course_thumbnail'){
    ?>
    

    <script>
    function popWindow(){
        url='<?php echo base_url()?>user/imgCropper/'+$("#s_image1").val();
        window.open(url,'1493616402117','width=700,height=700,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;
    }
    
    function showCoords(c)
    {
        // variables can be accessed here as
        // c.x, c.y, c.x2, c.y2, c.w, c.h
        $("#x1").val(c.x);
        $("#y1").val(c.y);
        $("#x2").val(c.x2);
        $("#y2").val(c.y2);
        $("#w").val(c.w);
        $("#h").val(c.h);
    };
    
        var _URL = window.URL || window.webkitURL;
        $(document).ready(function(){
            
            $("#course_thumbnail").change(function (e) {
                var file, img;
                if ((file = this.files[0])) {
                    img = new Image();
                    var objectUrl = _URL.createObjectURL(file);
                    img.onload = function () {
                        //alert(this.width + " " + this.height);
                        //_URL.revokeObjectURL(objectUrl);
                        if(this.width<600 || this.height<600){
                            setTimeout(function(){ 
                                $(".js--image-preview").attr('style','background-image: url(https://hub.arablap.com/assets/frontend/default/img/course_thumbnail_placeholder.jpg); background-color: #F5F5F5;')
                            alert('Your image size less than 600px*600px.\r\n System would replace your image with thumbnil')
                            }, 300);
                        }
                        else
                        {
                                        document.getElementsByClassName("list-inline").display = 'none';
                            $(".Loader").show();
                            var formData = new FormData($(".required-form")[0]);
                            $.ajax({
                                url: '<?php echo base_url() ?>user/DoUpload',
                                type: 'POST',
                                data: formData,
                                dataType:"json",
                                async: true,
                                success: function (data) {
                                    if(data.length>5){
                                        $("#thumbcourse_thumbnail").val(data);
                                        $("#thumbnail").attr('src','<?php echo base_url()?>uploads/'+data);
                                        $("#s_image1").val(data);
                                        $(".Loader1").show();
                                    }
                                    else {
                                        $(".alert-success").addClass('hide');
                                        $(".alert-danger").removeClass('hide');
                                        $("#dangerMsg").text(data.status.msg)
                                    }
                                    $(".Loader").hide();
                                },
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                            return false;
                        }
                    };
                    img.src = objectUrl;
                }
            });
        })
    </script>
    <?php
}
?>
