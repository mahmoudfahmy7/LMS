<!DOCTYPE html>
<html lang="en">
<head>

    <?php if ($page_name == 'course_page'):
        $title = $this->crud_model->get_course_by_id($course_id)->row_array() ?>
        <title><?php echo $title['title'] . ' | ' . get_settings('system_name'); ?></title>
    <?php else: ?>
        <title><?php echo ucwords($page_title) . ' | ' . get_settings('system_name'); ?></title>
    <?php endif; ?>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="<?php echo get_settings('author') ?>"/>

    
    <?php 
    if(isset($CourseData)){
//    var_dump($CourseData);?>
        <meta name="keywords" content="<?php echo $CourseData[0]->meta_keywords; ?>"/>
        <meta name="description" content="<?php echo $CourseData[0]->title?>"/>
    <?php }
    else 
    {?>
        <meta name="keywords" content="Lacyna,is,a,leading,trusted,platform,for,teaching,and,learning,with,many,classes,for,creative,and,curious,people,on,various,topics,including,illustration,design,photography,video,freelancing,and,more."/>
        <meta name="description" content="Lacyna is a leading trusted platform for teaching and learning, with many classes for creative and curious people, on various topics including illustration, design, photography, video, freelancing, and more."/>
    <?php }?>

    <link name="favicon" type="image/x-icon" href="<?php echo base_url() . 'uploads/system/favicon.png' ?>"
          rel="shortcut icon"/>
    <?php include 'includes_top.php'; ?>

</head>
<body class="gray-bg">
<?php
if ($this->input->get('promotional')) {
    $this->session->set_userdata('promotional', $this->input->get('promotional'));
}

if ($this->session->userdata('user_login')) {
    if ($this->input->get('code')) {
        $code = $this->input->get('code');
        $coupon = $this->crud_model->couponFiendBy(array('key' => 'code', 'value' => $code));
        if (count($coupon) > 0) {
            $this->session->set_userdata('percent', $coupon[0]['percent']);
            $this->session->set_userdata('code', $coupon[0]['code']);
        }
    }
    include 'logged_in_header.php';
} else {

    include 'logged_out_header.php';
}
include $page_name . '.php';
include 'footer.php';
include 'includes_bottom.php';
include 'modal.php';
include 'common_scripts.php';
?>
</body>
</html>
