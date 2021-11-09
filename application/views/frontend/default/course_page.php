<?php
$course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
$instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
$subscription = ($this->session->userdata('subscription') > strtotime(date("Y-m-d"))) ? 1 : 0;

?>

                                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                                <script type="text/javascript" src="<?php echo base_url();?>assets/playerjs/SubPlayerJS.js" ></script>
<section class="course-header-area">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="course-header-wrap">
                    <h1 class="title"><?php echo $course_details['title']; ?></h1>
                    <p class="subtitle"><?php echo $course_details['short_description']; ?></p>
                    <div class="rating-row">
                        <span class="course-badge best-seller"><?php echo ucfirst($course_details['level']); ?></span>
                        <?php
                        $total_rating = $this->crud_model->get_ratings('course', $course_details['id'], true)->row()->rating;
                        $number_of_ratings = $this->crud_model->get_ratings('course', $course_details['id'])->num_rows();
                        if ($number_of_ratings > 0) {
                            $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                        } else {
                            $average_ceil_rating = 0;
                        }

                        for ($i = 1; $i < 6; $i++):?>
                            <?php if ($i <= $average_ceil_rating): ?>
                                <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                            <?php else: ?>
                                <i class="fas fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span><span>(<?php echo $number_of_ratings . ' ' . get_phrase('ratings'); ?>)</span>
                        <span class="enrolled-num">
            <?php
            $number_of_enrolments = $this->crud_model->enrol_history($course_details['id'])->num_rows();
            echo $number_of_enrolments . ' ' . get_phrase('students_enrolled');
            ?>
          </span>
                    </div>
                    <div class="created-row">
          <span class="created-by">
            <?php echo get_phrase('created_by'); ?>
            <a href="<?php echo site_url('home/instructor_page/' . $course_details['user_id']); ?>"><?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?></a>
          </span>
                        <?php if ($course_details['last_modified'] > 0): ?>
                            <span class="last-updated-date"><?php echo get_phrase('last_updated') . ' ' . date('D, d-M-Y', $course_details['last_modified']); ?></span>
                        <?php else: ?>
                            <span class="last-updated-date"><?php echo get_phrase('last_updated') . ' ' . date('D, d-M-Y', $course_details['date_added']); ?></span>
                        <?php endif; ?>
                        <span class="comment"><i
                                    class="fas fa-comment"></i><?php echo ucfirst($course_details['language']); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

            </div>
        </div>
    </div>
</section>


<section class="course-content-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">

                <div class="what-you-get-box">
                    <div class="what-you-get-title"><?php echo get_phrase('what_will_i_learn'); ?>?</div>
                    <ul class="what-you-get__items">
                        <?php foreach (json_decode($course_details['outcomes']) as $outcome): ?>
                            <?php if ($outcome != ""): ?>
                                <li><?php echo $outcome; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <br>
                <div class="course-curriculum-box">
                    <div class="course-curriculum-title clearfix">
                        <div class="title float-left"><?php echo get_phrase('curriculum_for_this_course'); ?></div>
                        <div class="float-right">
              <span class="total-lectures">
                <?php echo $this->crud_model->get_lessons('course', $course_details['id'])->num_rows() . ' ' . get_phrase('lessons'); ?>
              </span>
                            <span class="total-time">
                <?php
                echo $this->crud_model->get_total_duration_of_lesson_by_course_id($course_details['id']);
                ?>
              </span>
                        </div>
                    </div>
                    <div class="course-curriculum-accordion">
                        <?php
                        $sections = $this->crud_model->get_section('course', $course_id)->result_array();
                        $counter = 0;
                        foreach ($sections as $section): ?>
                            <div class="lecture-group-wrapper">
                                <div class="lecture-group-title clearfix" data-toggle="collapse"
                                     data-target="#collapse<?php echo $section['id']; ?>"
                                     aria-expanded="<?php if ($counter == 0) echo 'true'; else echo 'false'; ?>">
                                    <div class="title float-left">
                                        <?php echo $section['title']; ?>
                                    </div>
                                    <div class="float-right">
                                      <span class="total-lectures">
                                        <?php echo $this->crud_model->get_lessons('section', $section['id'])->num_rows() . ' ' . get_phrase('lessons'); ?>
                                      </span>
                                                            <span class="total-time">
                                        <?php echo $this->crud_model->get_total_duration_of_lesson_by_section_id($section['id']); ?>
                                      </span>
                                    </div>
                                </div>

                                <div id="collapse<?php echo $section['id']; ?>"
                                     class="lecture-list collapse <?php if ($counter == 0) echo 'show'; ?>">
                                    <ul>
                                        <?php $lessons = $this->crud_model->get_lessons('section', $section['id'])->result_array();
                                        foreach ($lessons as $lesson):?>
                                            <li class="lecture has-preview">
                                                <span class="lecture-title">
													<?php 
                        $is_purchased=is_purchased($course_details['id']);
                                                    if($course_details['is_free_course'] ==1){ 
                                                    ?>
                                                    <a href="<?php echo base_url()?>home/lesson/<?php echo $slug."/".$course_id."/".$lesson['id'];?>" target="_blank"><?php echo $lesson['title']; ?></a>
                                                    <?php }
                                                    else
                                                    if($is_purchased){?>
                                                    <a href="<?php echo base_url()?>home/lesson/<?php echo $slug."/".$course_id."/".$lesson['id'];?>" target="_blank"><?php echo $lesson['title']; ?></a>
                                                    <?php }
                                                    else{?>
                                                    <a  data-toggle="modal" data-target="#CoursePreviewModal"><?php echo $lesson['title']; ?></a>
                                                    <?php }
                                                    ?>
                                                </span>
                                                <span class="lecture-time float-right"><?php echo $lesson['duration']; ?></span>
												<?php   
                                                    if($lesson['preview'] ==1){ 
															$provider = "";
															$id = "";
															$translatoinFile='';
                                                        if($lesson['attachment_type']=='url'){
    														if($lesson['video_type']=='html5'){
    																$provider = 'html';
																    $id=base_url().$lesson['video_url'];
																    $srt=json_decode($lesson['attachment']);
																    //echo $srt;
																    $key1='';
                                                                    $value1='';
                                                                    foreach($srt as $key=>$val){
                                                                        $key1=$key;
                                                                        $value1=base_url().'uploads/lesson_files/'.$val;
                                                                        break;
                                                                    }
																    $translatoinFile=$value1;
    														    
    														}
    														else
														    if ($lesson['video_url'] != ""){
    															$video_details = $this->video_model->getVideoDetails($lesson['video_url']);
    															if(sizeof($video_details)>0){
    																$provider = $video_details['provider'];
																    $id=$video_details['video_id'];
															}
														}
                                                        }
														else
														{
														    $provider=$lesson['title'];
														    $id=base_url()."uploads/lesson_files".$lesson['attachment'];
														}
													
                                                    //echo $lesson['attachment'];
                                                    if($id<>'' || $id <>base_url()){
                                                ?>
												<a  class="lecture-preview float-right" onclick="ShowModal('<?php echo strtolower($provider)?>','<?php echo $id?>','<?php echo $translatoinFile ?>')" target="_blank">Preview</a>
                                                <!--<span class="lecture-preview float-right" data-toggle="modal" data-target="#CoursePreviewModal">Preview</span>-->
												<?php   
                                                    } 
                                                    }
                                                ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                            $counter++;
                        endforeach; ?>
                    </div>
                </div>

                <div class="requirements-box">
                    <div class="requirements-title"><?php echo get_phrase('requirements'); ?></div>
                    <div class="requirements-content">
                        <ul class="requirements__list">
                            <?php foreach (json_decode($course_details['requirements']) as $requirement): ?>
                                <?php if ($requirement != ""): ?>
                                    <li><?php echo $requirement; ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="description-box view-more-parent">
                    <div class="view-more" onclick="viewMore(this,'hide')">
                        + <?php echo get_phrase('view_more'); ?></div>
                    <div class="description-title"><?php echo get_phrase('description'); ?></div>
                    <div class="description-content-wrap">
                        <div class="description-content">
                            <?php echo $course_details['description']; ?>
                        </div>
                    </div>
                </div>


                <div class="compare-box view-more-parent">
                    <div class="view-more" onclick="viewMore(this)">+ <?php echo get_phrase('view_more'); ?></div>
                    <div class="compare-title"><?php echo get_phrase('other_related_courses'); ?></div>
                    <div class="compare-courses-wrap">
                        <?php
                        $other_realted_courses = $this->crud_model->get_courses($course_details['category_id'], $course_details['sub_category_id'])->result_array();
                        foreach ($other_realted_courses as $other_realted_course):
                            if ($other_realted_course['id'] != $course_details['id'] && $other_realted_course['status'] == 'active'): ?>
                                <div class="course-comparism-item-container this-course">
                                    <div class="course-comparism-item clearfix">
                                        <div class="item-image float-left">
                                            <a href="<?php echo site_url('home/course/' . slugify($other_realted_course['title']) . '/' . $other_realted_course['id']); ?>"><img
                                                        src="<?php $this->crud_model->get_course_thumbnail_url($other_realted_course['id']); ?>"
                                                        alt="" class="img-fluid"></a>
                                            <div class="item-duration">
                                                <b><?php echo $this->crud_model->get_total_duration_of_lesson_by_course_id($other_realted_course['id']); ?></b>
                                            </div>
                                        </div>
                                        <div class="item-title float-left">
                                            <div class="title"><a
                                                        href="<?php echo site_url('home/course/' . slugify($other_realted_course['title']) . '/' . $other_realted_course['id']); ?>"><?php echo $other_realted_course['title']; ?></a>
                                            </div>
                                            <?php if ($other_realted_course['last_modified'] > 0): ?>
                                                <div class="updated-time"><?php echo get_phrase('updated') . ' ' . date('D, d-M-Y', $other_realted_course['last_modified']); ?></div>
                                            <?php else: ?>
                                                <div class="updated-time"><?php echo get_phrase('updated') . ' ' . date('D, d-M-Y', $other_realted_course['date_added']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item-details float-left">
                <span class="item-rating">
                  <i class="fas fa-star"></i>
                  <?php
                  $total_rating = $this->crud_model->get_ratings('course', $other_realted_course['id'], true)->row()->rating;
                  $number_of_ratings = $this->crud_model->get_ratings('course', $other_realted_course['id'])->num_rows();
                  if ($number_of_ratings > 0) {
                      $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                  } else {
                      $average_ceil_rating = 0;
                  }
                  ?>
                  <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span>
                </span>
                                            <span class="enrolled-student">
                                              <i class="far fa-user"></i>
                                              <?php echo $this->crud_model->enrol_history($other_realted_course['id'])->num_rows(); ?>
                                            </span>
                                            <?php if ($other_realted_course['is_free_course'] == 1): ?>
                                                <span class="item-price">
                                                    <span class="current-price"><?php echo get_phrase('free'); ?></span>
                                                  </span>
                                            <?php else: ?>
                                                <?php if ($other_realted_course['discount_flag'] == 1): ?>
                                                    <span class="item-price">
                                                      <span class="original-price"><?php echo currency($other_realted_course['price']); ?></span>
                                                      <span class="current-price"><?php echo currency($other_realted_course['discounted_price']); ?></span>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="item-price">
                                                      <span class="current-price"><?php echo currency($other_realted_course['price']); ?></span>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="about-instructor-box">
                    <div class="about-instructor-title">
                        <?php echo get_phrase('about_the_instructor'); ?>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="about-instructor-image">
                                <img src="<?php echo $this->user_model->get_user_image_url($instructor_details['id']); ?>"
                                     alt="" class="img-fluid">
                                <ul>
                                    <!-- <li><i class="fas fa-star"></i><b>4.4</b> Average Rating</li> -->
                                    <li><i class="fas fa-comment"></i><b>
                                            <?php echo $this->crud_model->get_instructor_wise_course_ratings($instructor_details['id'], 'course')->num_rows(); ?>
                                        </b> <?php echo get_phrase('reviews'); ?></li>
                                    <li><i class="fas fa-user"></i><b>
                                            <?php
                                            $course_ids = $this->crud_model->get_instructor_wise_courses($instructor_details['id'], 'simple_array');
                                            $this->db->select('user_id');
                                            $this->db->distinct();
                                            $this->db->where_in('course_id', $course_ids);
                                            echo $this->db->get('enrol')->num_rows();
                                            ?>
                                        </b> <?php echo get_phrase('students') ?></li>
                                    <li><i class="fas fa-play-circle"></i><b>
                                            <?php echo $this->crud_model->get_instructor_wise_courses($instructor_details['id'])->num_rows(); ?>
                                        </b> <?php echo get_phrase('courses'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="about-instructor-details view-more-parent">
                                <div class="view-more" onclick="viewMore(this)">
                                    + <?php echo get_phrase('view_more'); ?></div>
                                <div class="instructor-name">
                                    <a href="<?php echo site_url('home/instructor_page/' . $course_details['user_id']); ?>"><?php echo $instructor_details['first_name'] . ' ' . $instructor_details['last_name']; ?></a>
                                </div>
                                <div class="instructor-title">
                                    <?php echo $instructor_details['title']; ?>
                                </div>
                                <div class="instructor-bio">
                                    <?php echo $instructor_details['biography']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="student-feedback-box">
                    <div class="student-feedback-title">
                        <?php echo get_phrase('student_feedback'); ?>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="average-rating">
                                <div class="num">
                                    <?php
                                    $total_rating = $this->crud_model->get_ratings('course', $course_details['id'], true)->row()->rating;
                                    $number_of_ratings = $this->crud_model->get_ratings('course', $course_details['id'])->num_rows();
                                    if ($number_of_ratings > 0) {
                                        $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                                    } else {
                                        $average_ceil_rating = 0;
                                    }
                                    echo $average_ceil_rating;
                                    ?>
                                </div>
                                <div class="rating">
                                    <?php for ($i = 1; $i < 6; $i++): ?>
                                        <?php if ($i <= $average_ceil_rating): ?>
                                            <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                                        <?php else: ?>
                                            <i class="fas fa-star" style="color: #abb0bb;"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="title"><?php echo get_phrase('average_rating'); ?></div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="individual-rating">
                                <ul>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <li>
                                            <div class="progress">
                                                <div class="progress-bar"
                                                     style="width: <?php echo $this->crud_model->get_percentage_of_specific_rating($i, 'course', $course_id); ?>%"></div>
                                            </div>
                                            <div>
                                                <span class="rating">
                                                  <?php for ($j = 1; $j <= (5 - $i); $j++): ?>
                                                      <i class="fas fa-star"></i>
                                                  <?php endfor; ?>
                                                    <?php for ($j = 1; $j <= $i; $j++): ?>
                                                        <i class="fas fa-star filled"></i>
                                                    <?php endfor; ?>
                                
                                                </span>
                                                <span><?php echo $this->crud_model->get_percentage_of_specific_rating($i, 'course', $course_id); ?>%</span>
                                            </div>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="reviews">
                        <div class="reviews-title"><?php echo get_phrase('reviews'); ?></div>
                        <ul>
                            <?php
                            $ratings = $this->crud_model->get_ratings('course', $course_id)->result_array();
                            foreach ($ratings as $rating):
                                ?>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="reviewer-details clearfix">
                                                <div class="reviewer-img float-left">
                                                    <img src="<?php echo $this->user_model->get_user_image_url($rating['user_id']); ?>"
                                                         alt="">
                                                </div>
                                                <div class="review-time">
                                                    <div class="time">
                                                        <?php echo date('D, d-M-Y', $rating['date_added']); ?>
                                                    </div>
                                                    <div class="reviewer-name">
                                                        <?php
                                                        $user_details = $this->user_model->get_user($rating['user_id'])->row_array();
                                                        echo $user_details['first_name'] . ' ' . $user_details['last_name'];
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="review-details">
                                                <div class="rating">
                                                    <?php
                                                    for ($i = 1; $i < 6; $i++):?>
                                                        <?php if ($i <= $rating['rating']): ?>
                                                            <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-star" style="color: #abb0bb;"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="review-text">
                                                    <?php echo $rating['review']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="course-sidebar natural">
                    <?php 
                    $promo=$this->crud_model->get_course_thumbnail_url($course_details['id']);
                    if ($course_details['video_url'] != ""): ?>
                        <div class="preview-video-box">
                            <a data-toggle="modal" data-target="#CoursePreviewModal">
                                <img src="<?php echo $promo ?>"
                                     alt="" class="img-fluid">
                                <span class="preview-text"><?php echo get_phrase('preview_this_course'); ?></span>
                                <span class="play-btn"></span>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="course-sidebar-text-box">
                        <div class="price" style="padding-top:10px;">
                            <?php 
                            if ($course_details['is_free_course'] == 1): ?>
                                <span class="current-price"><span
                                            class="current-price"><?php echo get_phrase('free'); ?></span></span>
                            <?php else: ?>
                                <?php if ($course_details['discount_flag'] == 1): ?>
                                    <span class="current-price"><span
                                                class="current-price"><?php echo currency($course_details['discounted_price']); ?></span></span>
                                    <span class="original-price"><?php echo currency($course_details['price']) ?></span>
                                    <input type="hidden" id="total_price_of_checking_out"
                                           value="<?php echo currency($course_details['discounted_price']); ?>">
                                <?php else: ?>

                                    <?php
									//var_dump($this->session->userdata('i_factor'),$this->session->userdata('code'));
									$hasDiscount=false;
									$id=$this->session->userdata('course_id');
                                        if ($this->session->userdata('code') != null && $id==0?true:($id==$course_details['id'])) {
											$coupon_details = $this->db->get_where('coupons', array('code' => $this->session->userdata('code')))->row_array();
											//var_dump($coupon_details['i_factor']);
											
											if($coupon_details['course_id']==$course_details['id'] || $coupon_details['type']=='public'){
												$hasDiscount=true;
											//if($this->session->userdata('i_factor')==1)// 1 percentage
												if($coupon_details['i_factor']==1)// 1 percentage
													$price = $course_details['price'] - ($course_details['price'] * $this->session->userdata('percent') / 100);
												else
													$price = $course_details['price'] - $this->session->userdata('percent'); // else fixed value
											}
											else
                                            $price = $course_details['price'];
                                        } else {
                                            $price = $course_details['price'];
                                        }
                                    ?>
                                    <?php if($this->session->userdata('code') != null): ?>
                                        <span class="current-price"><span
                                                    class="current-price"><?php echo currency($price); ?></span></span><br>
													<?php if($hasDiscount){?>
													<span class="original-price"><?php echo currency($course_details['price']); ?></span><?php }?>
													<?php else: ?>
                                        <span class="current-price"><span
                                                    class="current-price"><?php echo currency($price); ?></span></span>
                                    <?php endif; ?>
                                    <input type="hidden" id="total_price_of_checking_out"
                                           value="<?php echo currency($price); ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($course_details['id'], $this->session->userdata('cart_items'))): ?>
                                        <a style="padding-top: 10px;float:right">
                                            <i style="color:#EC5252;font-size: 30px;" class="fas far fa-shopping-cart addedToWish" onclick="handleCartItems(this)" title="<?php echo get_phrase('added_to_cart'); ?>"></i>
                                        </a>
                                    <?php else: ?>
                                        <a style="padding-top: 10px;float:right">
                                            <i style="font-size: 30px;"  id="<?php echo $course_details['id']; ?>" class="fas fa-shopping-cart " onclick="handleCartItems(this)" title="<?php echo get_phrase('add_to_cart'); ?>"></i>
                                        </a>   
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($course_details['id'], $this->session->userdata('wish_items'))): ?>
                                        <a style="padding-top: 10px;float:right">
                                            <i style="font-size: 30px;color:#EC5252;" id="<?php echo $course_details['id']; ?>" class="fas fa-heart addedToWish" onclick="handleWishItems(this,0)" title="<?php echo get_phrase('added_to_wishlist'); ?>"></i>
                                        </a>
                                    <?php else: ?>
                                        <a style="padding-top: 10px;float:right">
                                            <i style="font-size: 30px;" id="<?php echo $course_details['id']; ?>" class="fas far fa-heart " onclick="handleWishItems(this,1)" title="<?php echo get_phrase('add_to_wishlist'); ?>"></i>
                                        </a>
                                    <?php endif; ?>
                        </div>

                        <?php 
                        $is_purchased=is_purchased($course_details['id']);
                        if ($is_purchased) : ?>
                            <div class="already_purchased">
                                <a href="<?php echo site_url('home/my_courses'); ?>"><?php echo get_phrase('already_purchased'); ?></a>
                            </div>
                        <?php else: ?>
                            <?php if ($course_details['is_free_course'] == 1): ?>
                                <div class="buy-btns">
                                    <?php if ($this->session->userdata('user_login') != 1): ?>
                                        <a href="#" class="btn btn-buy-now"
                                           onclick="handleEnrolledButton()"><?php echo get_phrase('get_enrolled'); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('home/get_enrolled_to_free_course/' . $course_details['id']); ?>"
                                           class="btn btn-buy-now"><?php echo get_phrase('get_enrolled'); ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="buy-btns">
                                    <input id="course-id" type="hidden" value="<?php echo $course_details['id']; ?>">
                                    <a href="javascript:;" class="btn btn-buy-now"
                                       id="course_<?php echo $course_details['id']; ?>"
                                       onclick="handleBuyNow(this)"><?php echo get_phrase('buy_now'); ?></a>
                                    
                                    
                                    
                                    <?php if ($this->session->userdata('code') == null && $other_realted_course['is_free_course'] != 1): ?>

                                            <button class="btn btn-add-cart" type="button"
                                                    onclick="handleCoupon()"><?php echo get_phrase('enter_coupon_code'); ?></button>
                                        <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>


                        <div class="includes">
                            <div class="title"><b><?php echo get_phrase('includes'); ?>:</b></div>
                            <ul>
                                <li><i class="far fa-file-video"></i>
                                    <?php
                                    echo $this->crud_model->get_total_duration_of_lesson_by_course_id($course_details['id']) . ' ' . get_phrase('on_demand_videos');
                                    ?>
                                </li>
                                <li>
                                    <i class="far fa-file"></i><?php echo $this->crud_model->get_lessons('course', $course_details['id'])->num_rows() . ' ' . get_phrase('lessons'); ?>
                                </li>
                                <li><i class="far fa-compass"></i><?php echo get_phrase('full_lifetime_access'); ?></li>
                                <li>
                                    <i class="fas fa-mobile-alt"></i><?php echo get_phrase('access_on_mobile_and_tv'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<?php 
//echo $this->crud_model->get_course_thumbnail_url($course_details['id'])."<br />".get_video_extension($course_details['video_url']);

if ($course_details['video_url'] != ""):
//
    $provider = "";
    $video_details = array();
    if ($course_details['course_overview_provider'] == "html5") {
        $provider = 'html5';
    } else {
        $video_details = $this->video_model->getVideoDetails($course_details['video_url']);
		if(sizeof($video_details)>0){
			$provider = $video_details['provider'];
        }
        
    }
	//var_dump($course_details['course_overview_provider']);
    ?>
    
    <div class="modal fade" id="CoursePreviewModal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content course-preview-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span><?php echo get_phrase('course_preview') ?>:</span><?php echo $course_details['title']; ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="pausePreview()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="course-preview-video-wrap">
                        <div class="embed-responsive embed-responsive-16by9">
                            <?php if (strtolower(strtolower($provider)) == 'youtube'): ?>
                                <!------------- PLYR.IO ------------>
                            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">

                                <div class="plyr__video-embed" id="player">
                                    <iframe height="500"
                                            src="<?php echo $course_details['video_url']; ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                                            allowfullscreen allowtransparency allow="autoplay"></iframe>
                                </div>

                                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                                <script>const player = new Plyr('#player');</script>
                                <!------------- PLYR.IO ------------>
                            <?php elseif (strtolower($provider) == 'vimeo'): ?>
                                <!------------- PLYR.IO ------------>
                            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
                                <div class="plyr__video-embed" id="player">
                                    <iframe height="500"
                                            src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                                            allowfullscreen allowtransparency allow="autoplay"></iframe>
                                </div>

                                <script src="<?php echo base_url(); ?>assets/global/plyr/plyr.js"></script>
                                <script>const player = new Plyr('#player');</script>
                                <!------------- PLYR.IO ------------>
                            <?php else : ?>
                                <!------------- PLYR.IO ------------>
                            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plyr/plyr.css">
                            <div id="vedioPlayer">
                                
                            </div>
                            
                            <script>
                             var newPlayer2 = new SubPlayerJS('#vedioPlayer', '<?php echo $course_details['video_url'] ?>');
                             newPlayer2.setSubtitle(srt); 
                             $(".inner-container-SPJS").attr("style",'width: 100%;height: 400px;');
                             </script>
                               
                                <!------------- PLYR.IO ------------>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php  endif; ?>
<!-- Modal -->
    <div class="modal fade" id="CoursePreviewModal1" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content course-preview-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span><?php echo get_phrase('course_preview') ?>:</span><?php echo $course_details['title']; ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="pausePreview()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="course-preview-video-wrap">
                        <div class="embed-responsive embed-responsive-16by9">
							<div class="YouTubeDiv allDivVid" style="display:none">
                                <!------------- PLYR.IO ------------>

								<div class="plyr__video-embed" id="player">
									
                                    <iframe height="500" class="YouTubeFrm allDivVid"
                                            src="<?php echo $course_details['video_url']; ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                                            allowfullscreen allowtransparency allow="autoplay"></iframe>
								</div>
							</div>
                                <!------------- PLYR.IO ------------>
                                <!------------- PLYR.IO ------------>
							<div class="VimeoDiv  allDivVid" style="display:none">
                                <div class="plyr__video-embed" id="player">
                                    <iframe height="500" class="VimeoFrm allDivVid" style="display:none"
                                            src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                                            allowfullscreen allowtransparency allow="autoplay"></iframe>
                                </div>

							</div>
                                <!------------- PLYR.IO ------------>
                                <!------------- PLYR.IO ------------>
							<div class="htmlDiv  allDivVid" style="display:none">
							    
                                <div class="plyr__video-embed" id="htmlFrm" width="800px">
                                    
                                </div>

							</div>
                                <!------------- PLYR.IO ------------>
                                <!------------- PLYR.IO ------------>
							<div class="AllVedioDiv  allDivVid" style="display:none">
                                
							</div>
                                <!------------- PLYR.IO ------------>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style media="screen">
    .embed-responsive-16by9::before {
        padding-top: 0px;
    }
</style>
<script type="text/javascript">
function ShowModal(provider,video_url,srt){
$("#CoursePreviewModal1").modal('show')
	$(".allDivVid").attr('style','display:none');
	if(provider=='youtube'){
    console.log(provider,video_url);
		$(".YouTubeDiv").removeAttr('style');
		$(".YouTubeFrm").removeAttr('style');
		$(".YouTubeFrm").attr('src','https://www.youtube.com/embed/'+video_url);
//VimeoDiv	  .VimeoFrm
	}else
	if(provider=='vimeo'){
		$(".VimeoDiv").removeAttr('style');
		$(".VimeoFrm").removeAttr('style');
		$(".VimeoFrm").attr('src','https://player.vimeo.com/video/'+video_url);
//VimeoDiv	  .VimeoFrm
	}else
	if(provider=='html'){
		$(".htmlDiv").removeAttr('style');
		$("#htmlFrm").removeAttr('style');
		/*$("#htmlFrm").html('<video oncontextmenu="return false;" id="myVideo" controls  controlsList="nodownload">'
                             +' <source src="<?php echo base_url()?>'+video_url+'" type="video/mp4">'
                            +' </video>');*/
         var newPlayer2 = new SubPlayerJS('#htmlFrm', video_url);
         newPlayer2.setSubtitle(srt); 
         $(".inner-container-SPJS").attr("style",'width: 100%;height: 400px;');
         console.log(video_url,srt)
//VimeoDiv	  .VimeoFrm
	}
	else
	{
		$(".AllVedioDiv").removeAttr('style');
		$(".AllVedioDiv").html('<div style="text-align: center;margin:10px"><div class="col-sm-12"><a href="'+video_url+'" class="btn btn-sign-up" download="" style="color: #fff;">'
		+' <i class="fa fa-download" style="font-size: 20px;"></i>'
		+'Download '+provider+'</a></div></div>');
	}
}
    function handleCoupon() {
        $('#couponModal').modal('show');
    }

    function handleCartItems(elem) {
        url1 = '<?php echo site_url('home/handleCartItems');?>';
        url2 = '<?php echo site_url('home/refreshWishList');?>';
        $.ajax({
            url: url1,
            type: 'POST',
            data: {course_id: elem.id},
            success: function (response) {
                $('#cart_items').html(response);
                if ($(elem).hasClass('addedToCart')) {
                    $(elem).removeClass('addedToCart')
                    $(elem).attr("style","font-size: 30px;");
                } else {
                    $(elem).addClass('addedToCart')
                    $(elem).attr("style","font-size: 30px;color:#EC5252;");
                }
            }
        });
    }
    
    function handleWishItems(elem,state) {
        url2 = '<?php echo site_url('home/handleWishList');?>';
        //url2 = '<?php echo site_url('home/handleWishListItems');?>';
        $.ajax({
            url: url2,
            type: 'POST',
            data: {course_id: elem.id, state: state},
            success: function (response) {
                $('#wishlist_items').html(response);
                if ($(elem).hasClass('addedToWish')) {
                    $(elem).removeClass('addedToWish')
                    $(elem).attr("style","font-size: 30px;");
                } else {
                    $(elem).addClass('addedToWish')
                    $(elem).attr("style","font-size: 30px;color:#EC5252;");
                }
            }
        });
    }

    function handleBuyNow(elem) {

        url1 = '<?php echo site_url('home/handleCartItemForBuyNowButton');?>';
        url2 = '<?php echo site_url('home/refreshWishList');?>';
        const subscription = "<?php echo $subscription;?>";
        if (subscription === "1") {
            urlToRedirect = '<?php echo site_url('home/payment_success/paypal/' . $this->session->userdata('user_id') . "/1000"); ?>';
        } else {
            urlToRedirect = '<?php echo site_url('home/shopping_cart'); ?>';
        }
        var explodedArray = elem.id.split("_");
        var course_id = explodedArray[1];

        $.ajax({
            url: url1,
            type: 'POST',
            data: {course_id: course_id},
            success: function (response) {
                $('#cart_items').html(response);
                $.ajax({
                    url: url2,
                    type: 'POST',
                    success: function (response) {
                        $('#wishlist_items').html(response);
                        toastr.warning('<?php echo get_phrase('please_wait') . '....'; ?>');
                        setTimeout(
                            function () {
                                window.location.replace(urlToRedirect);
                            }, 1500);
                    }
                });
            }
        });
    }

    function handleEnrolledButton() {
        console.log('here');
        $.ajax({
            url: '<?php echo site_url('home/isLoggedIn');?>',
            success: function (response) {
                if (!response) {
                    window.location.replace("<?php echo site_url('login'); ?>");
                }
            }
        });
    }

    function pausePreview() {
        player.pause();
		$(".allDivVid").removeAttr("src");
		$("#htmlFrm").html('');
    }
</script>
