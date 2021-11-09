<div class="bs-example">
    <a href="<?php echo site_url('admin/unread_contact'); ?>" style="padding: 20px; font-size: 20px;" >
        <i class="fas fa-envelope"></i></a>
    <a href="<?php echo site_url('admin/contact_us'); ?>" style="padding: 20px; font-size: 20px; ">
        <i class="fas fa-envelope-open"></i></a>
</div>



<?php
//var_dump($showContact['name'],$showContact['email'] ,$showContact['msg']); die;
?>

<div class="container">
    <div class="mail-box">
        <aside class="lg-side">
            <div class="inbox-body">
                <div class="mail-option">
                <table class="table table-inbox table-hover">
                    <tbody>
                    <tr class="">
                         <td>Name</td>
                        <td class="view-message dont-show"><?php echo $showContact['name'];  ?></td>
                    </tr>
                       <tr>
                           <td>Message</td>
                           <td class="view-message view-message"><?php echo $showContact['msg'];  ?></td>
                       </tr>
                    <tr>
                        <td>Date</td>
                        <td class="view-message text-right"><?php echo date('d-M-Y',$showContact['updated_at']);  ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </aside>
    </div>
</div>
