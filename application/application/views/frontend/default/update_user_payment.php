<?php
    $social_links = json_decode($user_details['social_links'], true);
 ?>
 <section class="page-header-area my-course-area">
     <div class="container">
         <div class="row">
             <div class="col">
                 <h1 class="page-title"><?php echo get_phrase('purchase_history'); ?></h1>
                 <ul>
                     <li><a href="<?php echo site_url('home/my_courses'); ?>"><?php echo get_phrase('all_courses'); ?></a></li>
                     <li><a href="<?php echo site_url('home/my_wishlist'); ?>"><?php echo get_phrase('wishlists'); ?></a></li>
                     <li><a href="<?php echo site_url('home/my_messages'); ?>"><?php echo get_phrase('my_messages'); ?></a></li>
                     <li><a href="<?php echo site_url('home/purchase_history'); ?>"><?php echo get_phrase('purchase_history'); ?></a></li>
                     <li class="active"><a href=""><?php echo get_phrase('user_profile'); ?></a></li>
                 </ul>
             </div>
         </div>
     </div>
 </section>

<section class="user-dashboard-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="user-dashboard-box">
                    <div class="row" style="width:100%">
                    <div class="user-dashboard-sidebar col-sm-12 col-xl-3">
                        <div class="user-box">
                            <img src="<?php echo base_url().'uploads/user_image/'.$this->session->userdata('user_id').'.jpg';?>" alt="" class="img-fluid">
                            <div class="name">
                                <div class="name"><?php echo $user_details['first_name'].' '.$user_details['last_name']; ?></div>
                            </div>
                        </div>
                        <div class="user-dashboard-menu">
                            <ul>
                                <li><a href="<?php echo site_url('home/profile/user_profile'); ?>"><?php echo get_phrase('profile'); ?></a></li>
                                <li><a href="<?php echo site_url('home/profile/user_credentials'); ?>"><?php echo get_phrase('account'); ?></a></li>
                                <li><a href="<?php echo site_url('home/profile/user_photo'); ?>"><?php echo get_phrase('photo'); ?></a></li>
                                <li class="active"><a href="<?php echo site_url('home/profile/user_payment'); ?>"><?php echo get_phrase('update_user_payment'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="user-dashboard-content col-sm-12 col-xl-9">
                        <div class="content-title-box">
                            <div class="title"><?php echo get_phrase('update_user_payment'); ?></div>
                            <div class="subtitle"><?php echo get_phrase('Select_payment_method_to_recive_your_revenue'); ?>.</div>
                        </div>
                        <form action="<?php echo site_url('home/update_profile/update_payment'); ?>" method="post">
                            <div class="content-box">
                                <div class="basic-group">
                                    <div class="form-group">
                                        <label for="payment_type"><?php echo get_phrase('payout_type'); ?>:</label>
                                        <select class="form-control" name = "payment_type" id="payment_type" style="padding: 0px 12px;">
                                            <option value="paypal" <?php echo $user_details['payment_type']=='paypal'?'selected':''; ?>>Paypal</option>
                                            <option value="Payoneer" <?php echo $user_details['payment_type']=='Payoneer'?'selected':''; ?>>Payoneer </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_id"><?php echo get_phrase('payment_id'); ?>:</label>
                                        <input type="text" class="form-control" name = "payment_id" id="payment_id" placeholder="<?php echo get_phrase('payment_id'); ?>" value="<?php echo $user_details['payment_id']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="content-update-box">
                                <button type="submit" class="btn">Save</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
