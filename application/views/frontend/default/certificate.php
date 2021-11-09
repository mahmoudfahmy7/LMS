
    <head>
    <title>Certificate | <?php echo $lastCert[0]->first_name." ".$lastCert[0]->last_name?></title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <link href="http://hub.arablap.com/assets/backend/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/buttons.bootstrap4.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/select.bootstrap4.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/fullcalendar.min.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/vendor/dropzone.css" rel="stylesheet" type="text/css">
<!-- third party css end -->
<!-- App css -->
<link href="http://hub.arablap.com/assets/backend/css/app.min.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/icons.min.css" rel="stylesheet" type="text/css">

<link href="http://hub.arablap.com/assets/backend/css/main.css" rel="stylesheet" type="text/css">

<!-- font awesome 5 -->
<link href="http://hub.arablap.com/assets/backend/css/fontawesome-all.min.css" rel="stylesheet" type="text/css">
<link href="http://hub.arablap.com/assets/backend/css/font-awesome-icon-picker/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">

</head>
<body>
<span class="" style="    min-height: 64px; width: 64px;">
	<img  src="<?php echo base_url()?>uploads/cert_files/<?php echo $lastCert[0]->s_image?>" id="target">
</span>
<div class="tag" style="position: absolute; top: <?php echo $lastCert[0]->y1?>px; left: <?php echo $lastCert[0]->x1?>px; color: #000000; ">
	<h2 style="padding: 0px;    margin: 0px;">
        <?php echo $lastCert[0]->first_name." ".$lastCert[0]->last_name?>
    </h2>
</div>
<div class="tag1" style="position: absolute; top: <?php echo $lastCert[0]->y2?>px; left: <?php echo $lastCert[0]->x2?>px; color: #000000; ">
	<h3 style="padding: 0px;    margin: 0px;">
        <?php echo $lastCert[0]->title?>
    </h3>
</div>
    
</body>