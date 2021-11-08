<?php if(isset($img)){
    //$img=base_url()."uploads/".$_GET['path'];
    ?>
<script src="<?php echo base_url(); ?>jcrop/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>jcrop/jquery.validate.js"></script>
<meta charset="utf-8"/>
<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" onsubmit="return false" id="formData">
    <div id="myContainer" style="margin-left:140px;margin-top:40px;">
        
            <input type="submit" name="addNews" class="btn btn-success" value="Crop image">
    <img src="<?php echo base_url().'uploads/'.$img?>" id="thumbnail" name="">
    <input id="pk_i_id" name="pk_i_id" type="hidden" value="<?php echo $pk_i_id ?>">
        <input id="img" name="img" type="hidden" value="<?php echo $img ?>">
    <input id="x1" name="x1" type="hidden" value="0">
    <input id="y1" name="y1" type="hidden" value="0">
    <input id="x2" name="x2" type="hidden" value="600">
    <input id="y2" name="y2" type="hidden" value="600">
    <input id="w" name="w" type="hidden" value="600">
    <input id="h" name="h" type="hidden" value="600">
        <div id="myContainer" style="margin-left:140px;margin-top:40px;">

            <input type="submit" name="addNews" class="btn btn-success" value="Crop image">
            </div>
</div>
</form>
<script src="<?php echo base_url()?>jcrop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>jcrop/jquery.Jcrop.css" type="text/css" />

<script>
    jQuery(function($) {
        $('#thumbnail').Jcrop({
                onSelect:    showCoords,
                bgColor:     'black',
                bgOpacity:   .4,
                setSelect:   [ 0, 0, 600, 600 ],
                onSelect: showCoords,
                onChange: showCoords
            }
        );
    });

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

    $(document).ready(function(){

        $("#formData").validate({
            rules: {
                i_type: {
                    required: true,
                },
                s_title: {
                    required: true,
                    minlength: 3
                },
                s_image: {
                    required: true,
                    minlength: 3
                },
            },
            messages: {
                i_type: {
                    required: 'هذا الحقل مطلوب',
                },
                s_title: {
                    required: 'هذا الحقل مطلوب',
                    minlength: 'طول الحقل لا يقل عن 3 حروف'
                },
                s_image: {
                    required: 'هذا الحقل مطلوب',
                    minlength: 'طول الحقل لا يقل عن 3 حروف'
                },
            },

            submitHandler: function() {
                var formData = new FormData($("#formData")[0]);
                $.ajax({
                    url: '<?php echo base_url() ?>user/DoimgCropper',
                    type: 'POST',
                    data: formData,
                    dataType:"json",
                    async: false,
                    success: function (data) {
                        if(data==1){
                            window.opener.document.getElementById("thumbcourse_thumbnail").value = 't<?php echo $img ?>';
                            window.opener.document.getElementsByClassName("list-inline").display = 'block';
                            preview=window.opener.document.getElementsByClassName("js--image-preview")
                            $(preview).attr('style','background-image: url(<?php echo base_url()?>uploads/t<?php echo $img ?>); background-color: #F5F5F5;')
                            window.close();
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
                return false;
            }
        });


    });

</script>
<?php }
else
echo "Please attach a photo"?>
