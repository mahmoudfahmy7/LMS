<?php
if($this->session->userdata('user_id')){
$user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
$this->session->set_userdata('wish_items', json_decode($user_details['wishlist']));
$cart_items = $this->session->userdata('wish_items');
?>
<div class="icon">
    <a><i class="far fa-heart"></i></a>
    <span class="number"><?php echo sizeof($cart_items); ?></span>
</div>
<div class="dropdown course-list-dropdown corner-triangle top-right">
    <div class="list-wrapper">
        <div class="item-list">
            <ul>
                <?php foreach ($this->session->userdata('wish_items') as $cart_item):
					$course_details = $this->crud_model->get_course_by_id($cart_item)->row_array();
					$instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
					?>
					<li>
						<div class="item clearfix">
							<div class="item-image">
								<a href="">
									<img src="<?php echo $this->crud_model->get_course_thumbnail_url($cart_item);?>" alt="" class="img-fluid">
								</a>
							</div>
							<div class="item-details">
								<a href="<?php echo base_url()?>home/course/<?php echo $course_details['title']; ?>/<?php echo $course_details['id']; ?>">
									<div class="course-name"><?php echo $course_details['title']; ?></div>
									<div class="instructor-name"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></div>
									<div class="item-price">
										<?php if ($course_details['discount_flag'] == 1):
											$total_price += $course_details['discounted_price'];?>
											<span class="current-price"><?php echo currency($course_details['discounted_price']); ?></span>
											<span class="original-price"><?php echo currency($course_details['price']); ?></span>
										<?php else:
											$total_price += $course_details['price'];?>
											<span class="current-price"><?php echo currency($course_details['price']); ?></span>
										<?php endif; ?>
									</div>
								</a>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
            </ul>
        </div>
        <div class="dropdown-footer">
            <a href = "<?php echo site_url('home/my_wishlist'); ?>"><?php echo get_phrase('go_to_wishlist'); ?></a>
        </div>
    </div>
    <div class="empty-box text-center d-none">
        <p><?php echo get_phrase('your_wishlist_is_empty'); ?>.</p>
        <a href=""><?php echo get_phrase('explore_courses'); ?></a>
    </div>
</div>
<?php }?>