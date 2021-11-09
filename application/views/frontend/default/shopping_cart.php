<section class="page-header-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo get_phrase('shopping_cart'); ?></a></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo get_phrase('shopping_cart'); ?></h1>
            </div>
        </div>
    </div>
</section>


<section class="cart-list-area">
    <div class="container">
        <div class="row" id = "cart_items_details">
            <div class="col-lg-9">

                <div class="in-cart-box">
                    <div class="title"><?php echo sizeof($this->session->userdata('cart_items')).' '.get_phrase('courses_in_cart'); ?></div>
                    <div class="">
                        <ul class="cart-course-list">
                            <?php
                            $actual_price = 0;
                            $total_price  = 0;
                            foreach ($this->session->userdata('cart_items') as $cart_item):
                                $course_details = $this->crud_model->get_course_by_id($cart_item)->row_array();
                                $instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
                                ?>
                                <li>
                                    <div class="cart-course-wrapper">
                                        <div class="image">
                                            <a href="<?php echo site_url('home/course/'.slugify($course_details['title']).'/'.$course_details['id']); ?>">
                                                <img src="<?php echo $this->crud_model->get_course_thumbnail_url($cart_item);?>" alt="" class="img-fluid">
                                            </a>
                                        </div>
                                        <div class="details">
                                            <a href="<?php echo site_url('home/course/'.slugify($course_details['title']).'/'.$course_details['id']); ?>">
                                                <div class="name"><?php echo $course_details['title']; ?></div>
                                            </a>
                                            <a href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>">
                                                <div class="instructor">
                                                    <?php echo get_phrase('by'); ?>
                                                    <span class="instructor-name"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></span>,
                                                </div>
                                            </a>
                                        </div>
                                        <div class="move-remove">
                                            <div id = "<?php echo $course_details['id']; ?>" onclick="removeFromCartList(this)" style="color:#ec5252;padding:5px;"><i class="fa fa-trash"></i></div>
                                            <!-- <div>Move to Wishlist</div> -->
                                        </div>
                                        <div class="price">
                                            <a href="">
                                                <?php 
												$hasDiscount=false;
													$id=$this->session->userdata('course_id');
													$type=$this->session->userdata('type');
														if ($this->session->userdata('code') != null && $id==0?true:($id==$course_details['id'])) {
															$coupon_details = $this->db->get_where('coupons', array('code' => $this->session->userdata('code')))->row_array();
															if($coupon_details['course_id']==$course_details['id'] || $coupon_details['type']=='public'){
																$hasDiscount=true;
																//if($this->session->userdata('i_factor')==1) // same page courses_page.php
																if($coupon_details['i_factor']==1) // same page courses_page.php
																	$price = $course_details['price'] - ($course_details['price'] * $this->session->userdata('percent') / 100);
																else
																	$price = $course_details['price'] - $this->session->userdata('percent');
	//$price = $course_details['price'] - ($course_details['price'] * $this->session->userdata('percent') / 100);
															}
															else
																$price = $course_details['price'];
														} else {
															$price = $course_details['price'];
														}
														//echo $price;
												if ($course_details['discount_flag'] == 1): 
												
													
												?>
                                                    <div class="current-price">
                                                        <?php
                                                        /*$total_price += $course_details['discounted_price'];
                                                        echo currency($course_details['discounted_price']);*/
                                                        $price=$course_details['discounted_price'];
														$total_price += $price;
                                                        echo currency($price) ;
                                                        ?>
                                                    </div>
                                                    <div class="original-price">
                                                        <?php
                                                        /*$actual_price += $course_details['price'];
                                                        echo currency($course_details['price']);*/
														
														//$total_price += $price;
                                                        $price=$course_details['price'];
                                                        echo currency($price);
                                                        ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="current-price">
                                                        <?php
														/*
                                                        $actual_price += $course_details['price'];
                                                        $total_price  += $course_details['price']  - ($course_details['price'] * $this->session->userdata('percent') / 100);
                                                        echo currency($course_details['price']);*/
														$total_price += $price;
                                                        echo currency($price);
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="coupon-tag">
                                                    <i class="fas fa-tag"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-lg-3">
                <div class="cart-sidebar">
                    <div class="total"><?php echo get_phrase('total'); ?>:</div>
                    <span id = "total_price_of_checking_out" hidden><?php echo $total_price; ?></span>
                    <div class="total-price"><?php echo currency($total_price); ?></div>
                    <div class="total-original-price" <?php if($this->session->userdata('code') == null){ echo "hidden" ; } ?> >
                        <span class="original-price"><?php echo currency($actual_price); ?></span>
						<?php if($type=='public'){?>
                        <p>Coupon : <?php echo $this->session->userdata('code');?></p>
						<?php }?>
                        <!-- <span class="discount-rate">95% off</span> -->
                    </div>
                    <button type="button" class="btn btn-primary btn-block checkout-btn" onclick="handleCheckOut()"><?php echo get_phrase('checkout'); ?></button>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://www.paypalobjects.com/js/external/dg.js"></script>
<script>
    var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'submitBtn' });
    dgFlow = top.dgFlow || top.opener.top.dgFlow;
    dgFlow.closeFlow();
    // top.close();
</script>

<script type="text/javascript">
function removeFromCartList(elem) {
    url1 = '<?php echo site_url('home/handleCartItems');?>';
    url2 = '<?php echo site_url('home/refreshWishList');?>';
    url3 = '<?php echo site_url('home/refreshShoppingCart');?>';
    $.ajax({
        url: url1,
        type : 'POST',
        data : {course_id : elem.id},
        success: function(response)
        {

            $('#cart_items').html(response);
            if ($(elem).hasClass('addedToCart')) {
                $('.big-cart-button-'+elem.id).removeClass('addedToCart')
                $('.big-cart-button-'+elem.id).text("<?php echo get_phrase('add_to_cart'); ?>");
            }else {
                $('.big-cart-button-'+elem.id).addClass('addedToCart')
                $('.big-cart-button-'+elem.id).text("<?php echo get_phrase('added_to_cart'); ?>");
            }

            $.ajax({
                url: url2,
                type : 'POST',
                success: function(response)
                {
                    $('#wishlist_items').html(response);
                }
            });

            $.ajax({
                url: url3,
                type : 'POST',
                success: function(response)
                {
                    $('#cart_items_details').html(response);
                }
            });
        }
    });
}

function handleCheckOut() {
    $.ajax({
        url: '<?php echo site_url('home/isLoggedIn');?>',
        success: function(response)
        {
            if (!response) {
                window.location.replace("<?php echo site_url('login'); ?>");
            }else {
                //$('#paymentModal').modal('show');
                $('.total_price_of_checking_out').val($('#total_price_of_checking_out').text());
                $(".stripe").trigger('click')
            }
        }
    });
}

function handleCartItems(elem) {
    url1 = '<?php echo site_url('home/handleCartItems');?>';
    url2 = '<?php echo site_url('home/refreshWishList');?>';
    url3 = '<?php echo site_url('home/refreshShoppingCart');?>';
    $.ajax({
        url: url1,
        type : 'POST',
        data : {course_id : elem.id},
        success: function(response)
        {
            $('#cart_items').html(response);
            if ($(elem).hasClass('addedToCart')) {
                $('.big-cart-button-'+elem.id).removeClass('addedToCart')
                $('.big-cart-button-'+elem.id).text("<?php echo get_phrase('add_to_cart'); ?>");
            }else {
                $('.big-cart-button-'+elem.id).addClass('addedToCart')
                $('.big-cart-button-'+elem.id).text("<?php echo get_phrase('added_to_cart'); ?>");
            }
            $.ajax({
                url: url2,
                type : 'POST',
                success: function(response)
                {
                    $('#wishlist_items').html(response);
                }
            });

            $.ajax({
                url: url3,
                type : 'POST',
                success: function(response)
                {
                    $('#cart_items_details').html(response);
                }
            });
        }
    });
}
</script>
