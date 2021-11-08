<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('add_new_course'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3"><?php echo get_phrase('course_adding_form'); ?>
                    <a href="<?php echo site_url('user/courses'); ?>" class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm"> <i class=" mdi mdi-keyboard-backspace"></i> <?php echo get_phrase('back_to_course_list'); ?></a>
                </h4>

                <div class="row1">
                    <div class="col-xl-12">
                        <form class="required-form" action="<?php echo site_url('user/course_actions/add'); ?>" method="post" enctype="multipart/form-data">
                            <div id="basicwizard">

                                <ul class="nav nav-pills nav-justified form-wizard-header mb-3" style="width:100%">
                                    <li class="nav-item">
                                        <a href="#basic" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-fountain-pen-tip mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php echo get_phrase('basic'); ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#requirements" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-bell-alert mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php echo get_phrase('requirements'); ?></span>
                                        </a>
                                    </li>
                                    <!--li class="nav-item">
                                        <a href="#outcomes" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-camera-control mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php //echo get_phrase('outcomes'); ?></span>
                                        </a>
                                    </li-->
                                    <li class="nav-item">
                                        <a href="#pricing" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-currency-cny mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php echo get_phrase('pricing'); ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#media" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-library-video mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php echo get_phrase('media'); ?></span>
                                        </a>
                                    </li>
                                    <!--li class="nav-item">
                                        <a href="#seo" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-tag-multiple mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php //echo get_phrase('seo'); ?></span>
                                        </a>
                                    </li-->
                                    <li class="nav-item">
                                        <a href="#finish" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                            <i class="mdi mdi-checkbox-marked-circle-outline mr-1"></i>
                                            <span class="d-none d-sm-inline"><?php echo get_phrase('finish'); ?></span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content b-0 mb-0">
                                    <div class="tab-pane" id="basic">
                                        <div class="row justify-content-center">
                                            <div class="col-xl-12 alert" style="color:#ff0000">
											<?php echo $this->session->flashdata('errorImg');?>
											</div>
                                            <div class="col-xl-8">
											
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2 col-form-label" for="course_title"><?php echo get_phrase('course_title'); ?> <span class="required">*</span> </label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" id="course_title" name = "title" placeholder="<?php echo get_phrase('enter_course_title'); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2 col-form-label" for="short_description"><?php echo get_phrase('short_description'); ?></label>
                                                    <div class="col-md-10">
                                                        <textarea name="short_description" id = "short_description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2 col-form-label" for="description"><?php echo get_phrase('description'); ?></label>
                                                    <div class="col-md-10">
                                                        <textarea name="description" id = "description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2 col-form-label" for="sub_category_id"><?php echo get_phrase('category'); ?><span class="required">*</span></label>
                                                    <div class="col-md-10">
                                                        <select class="form-control select2" data-toggle="select2" name="sub_category_id" id="sub_category_id" required>
                                                            <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                                                            <?php foreach ($categories->result_array() as $category): ?>
                                                                <optgroup label="<?php echo $category['name']; ?>">
                                                                    <?php $sub_categories = $this->crud_model->get_sub_categories($category['id']);
                                                                    foreach ($sub_categories as $sub_category): ?>
                                                                    <option value="<?php echo $sub_category['id']; ?>"><?php echo $sub_category['name']; ?></option>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
												<div class="form-group row mb-3">
													<label class="col-md-2 col-form-label" for="website_keywords"><?php echo get_phrase('tags'); ?></label>
													<div class="col-md-10">
														<input type="text" class="form-control" id ="meta_keywords" name="meta_keywords" placeholder="<?php echo get_phrase('enter_tags_like_:_tag1,tag2...'); ?>" style="width: 100%;"/>
													</div>                                                                
												</div> 
												
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="level"><?php echo get_phrase('level'); ?></label>
                                                <div class="col-md-10">
                                                    <select class="form-control select2" data-toggle="select2" name="level" id="level">
                                                        <option value="beginner"><?php echo get_phrase('beginner'); ?></option>
                                                        <option value="advanced"><?php echo get_phrase('advanced'); ?></option>
                                                        <option value="intermediate"><?php echo get_phrase('intermediate'); ?></option>
														<option value="allLevels"><?php echo get_phrase('All_levels'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="language_made_in"><?php echo get_phrase('language_made_in'); ?></label>
                                                <div class="col-md-10">
                                                    <select class="form-control select2" data-toggle="select2" name="language_made_in" id="language_made_in">
                                                          <!--option value="AF">Afrikaans</option> 
														  <option value="SQ">Albanian</option>
														  <option value="AR">Arabic</option>
														  <option value="HY">Armenian</option>
														  <option value="EU">Basque</option>
														  <option value="BN">Bengali</option>
														  <option value="BG">Bulgarian</option>
														  <option value="CA">Catalan</option>
														  <option value="KM">Cambodian</option>
														  <option value="ZH">Chinese (Mandarin)</option>
														  <option value="HR">Croatian</option>
														  <option value="CS">Czech</option>
														  <option value="DA">Danish</option>
														  <option value="NL">Dutch</option>
														  <option value="EN">English</option>
														  <option value="ET">Estonian</option>
														  <option value="FJ">Fiji</option>
														  <option value="FI">Finnish</option>
														  <option value="FR">French</option>
														  <option value="KA">Georgian</option>
														  <option value="DE">German</option>
														  <option value="EL">Greek</option>
														  <option value="GU">Gujarati</option>
														  <option value="HE">Hebrew</option>
														  <option value="HI">Hindi</option>
														  <option value="HU">Hungarian</option>
														  <option value="IS">Icelandic</option>
														  <option value="ID">Indonesian</option>
														  <option value="GA">Irish</option>
														  <option value="IT">Italian</option>
														  <option value="JA">Japanese</option>
														  <option value="JW">Javanese</option>
														  <option value="KO">Korean</option>
														  <option value="LA">Latin</option>
														  <option value="LV">Latvian</option>
														  <option value="LT">Lithuanian</option>
														  <option value="MK">Macedonian</option>
														  <option value="MS">Malay</option>
														  <option value="ML">Malayalam</option>
														  <option value="MT">Maltese</option>
														  <option value="MI">Maori</option>
														  <option value="MR">Marathi</option>
														  <option value="MN">Mongolian</option>
														  <option value="NE">Nepali</option>
														  <option value="NO">Norwegian</option>
														  <option value="FA">Persian</option>
														  <option value="PL">Polish</option>
														  <option value="PT">Portuguese</option>
														  <option value="PA">Punjabi</option>
														  <option value="QU">Quechua</option>
														  <option value="RO">Romanian</option>
														  <option value="RU">Russian</option>
														  <option value="SM">Samoan</option>
														  <option value="SR">Serbian</option>
														  <option value="SK">Slovak</option>
														  <option value="SL">Slovenian</option>
														  <option value="ES">Spanish</option>
														  <option value="SW">Swahili</option>
														  <option value="SV">Swedish </option>
														  <option value="TA">Tamil</option>
														  <option value="TT">Tatar</option>
														  <option value="TE">Telugu</option>
														  <option value="TH">Thai</option>
														  <option value="BO">Tibetan</option>
														  <option value="TO">Tonga</option>
														  <option value="TR">Turkish</option>
														  <option value="UK">Ukrainian</option>
														  <option value="UR">Urdu</option>
														  <option value="UZ">Uzbek</option>
														  <option value="VI">Vietnamese</option>
														  <option value="CY">Welsh</option>
														  <option value="XH">Xhosa</option-->
														<?php foreach ($languages as $language): ?>
                                                            <option value="<?php echo $language; ?>"><?php echo ucfirst($language); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--div class="form-group row mb-3">
                                                <div class="offset-md-2 col-md-10">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="is_top_course" id="is_top_course" value="1">
                                                        <label class="custom-control-label" for="is_top_course">< ?php echo get_phrase('check_if_this_course_is_top_course'); ?></label>
                                                    </div>
                                                </div>
                                            </div-->
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div> <!-- end tab pane -->

                                <div class="tab-pane" id="requirements">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="requirements"><?php echo get_phrase('requirements'); ?></label>
                                                <div class="col-md-9">
                                                    <div id = "requirement_area">
                                                        <div class="d-flex mt-2">
                                                            <div class="flex-grow-1 px-3">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="requirements[]" id="requirements" placeholder="<?php echo get_phrase('provide_requirements'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                <button type="button" class="btn btn-success btn-sm" style="" name="button" onclick="appendRequirement()"> <i class="fa fa-plus"></i> </button>
                                                            </div>
                                                        </div>
                                                        <div id = "blank_requirement_field">
                                                            <div class="d-flex mt-2">
                                                                <div class="flex-grow-1 px-3">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" name="requirements[]" id="requirements" placeholder="<?php echo get_phrase('provide_requirements'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="">
                                                                    <button type="button" class="btn btn-danger btn-sm" style="margin-top: 0px;" name="button" onclick="removeRequirement(this)"> <i class="fa fa-minus"></i> </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
											
											<div class="form-group row mb-3">
                                                <label class="col-md-3 col-form-label" for="outcomes"><?php echo get_phrase('outcomes'); ?></label>
                                                <div class="col-md-9">
                                                    <div id = "outcomes_area">
                                                        <div class="d-flex mt-2">
                                                            <div class="flex-grow-1 px-3">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="outcomes[]" id="outcomes" placeholder="<?php echo get_phrase('provide_outcomes'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                <button type="button" class="btn btn-success btn-sm" name="button" onclick="appendOutcome()"> <i class="fa fa-plus"></i> </button>
                                                            </div>
                                                        </div>
                                                        <div id = "blank_outcome_field">
                                                            <div class="d-flex mt-2">
                                                                <div class="flex-grow-1 px-3">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" name="outcomes[]" id="outcomes" placeholder="<?php echo get_phrase('provide_outcomes'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="">
                                                                    <button type="button" class="btn btn-danger btn-sm" style="margin-top: 0px;" name="button" onclick="removeOutcome(this)"> <i class="fa fa-minus"></i> </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
											
                                        </div>
                                    </div>
                                </div>

                                <!--div class="tab-pane" id="outcomes">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="outcomes"><?php //echo get_phrase('outcomes'); ?></label>
                                                <div class="col-md-10">
                                                    <div id = "outcomes_area">
                                                        <div class="d-flex mt-2">
                                                            <div class="flex-grow-1 px-3">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="outcomes[]" id="outcomes" placeholder="<?php //echo get_phrase('provide_outcomes'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                <button type="button" class="btn btn-success btn-sm" name="button" onclick="appendOutcome()"> <i class="fa fa-plus"></i> </button>
                                                            </div>
                                                        </div>
                                                        <div id = "blank_outcome_field">
                                                            <div class="d-flex mt-2">
                                                                <div class="flex-grow-1 px-3">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control" name="outcomes[]" id="outcomes" placeholder="<?php //echo get_phrase('provide_outcomes'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="">
                                                                    <button type="button" class="btn btn-danger btn-sm" style="margin-top: 0px;" name="button" onclick="removeOutcome(this)"> <i class="fa fa-minus"></i> </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div-->

                                <div class="tab-pane" id="pricing">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row mb-3">
                                                <div class="offset-md-2 col-md-10">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="is_free_course" id="is_free_course" value="1" onclick="togglePriceFields(this.id)">
                                                        <label class="custom-control-label" for="is_free_course"><?php echo get_phrase('check_if_this_is_a_free_course'); ?></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="paid-course-stuffs">
                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2 col-form-label" for="price"><?php echo get_phrase('course_price').' ('.currency_code_and_symbol().')'; ?></label>
                                                    <div class="col-md-10">
                                                        <!-- <input type="number" class="form-control" id="price" name = "price" placeholder="<?php echo get_phrase('enter_course_course_price'); ?>" min="0"> -->
														<select  class="form-control" id="price" name = "price">
														<?php for($i=10;$i<=200;$i+=10){?>
															<option value="<?php echo $i;?>"><?php echo $i;?></option>
														<?php }?>
														</select>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-3">
                                                    <div class="offset-md-2 col-md-10">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" name="discount_flag" id="discount_flag" value="1">
                                                            <label class="custom-control-label" for="discount_flag"><?php echo get_phrase('check_if_this_course_has_discount'); ?></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-3">
                                                    <label class="col-md-2 col-form-label" for="discounted_price"><?php echo get_phrase('discounted_price').' ('.currency_code_and_symbol().')'; ?></label>
                                                    <div class="col-md-10">
                                                        <input type="number" class="form-control" name="discounted_price" id="discounted_price" onkeyup="calculateDiscountPercentage(this.value)" min="0">
                                                        <small class="text-muted"><?php echo get_phrase('this_course_has'); ?> <span id = "discounted_percentage" class="text-danger">0%</span> <?php echo get_phrase('discount'); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div> <!-- end tab-pane -->
                                <div class="tab-pane" id="media">
                                    <div class="row justify-content-center">

                                        <div class="col-xl-8" style="display:none">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="course_overview_provider"><?php echo get_phrase('course_overview_provider'); ?></label>
                                                <div class="col-md-10">
                                                    <select class="form-control select2" data-toggle="select2" name="course_overview_provider" id="course_overview_provider" onchange="ChangeInput()">
                                                        <option value="youtube"><?php echo get_phrase('youtube'); ?></option>
                                                        <option value="vimeo"><?php echo get_phrase('vimeo'); ?></option>
                                                        <option value="html5" selected><?php echo get_phrase('HTML5'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-xl-8">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="course_overview_url"><?php echo get_phrase('course_overview_url'); ?></label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="course_overview_url" id="course_overview_url" placeholder="E.g: https://www.youtube.com/watch?v=oBtf8Yglw2w">
                                                </div>
                                            </div>
                                        </div> <!-- end col -->

                                        <!-- this portion will be generated theme wise from the theme-config.json file Starts-->
                                        <?php include 'course_media_add.php'; ?>
                                        <!-- this portion will be generated theme wise from the theme-config.json file Ends-->
                                    </div> <!-- end row -->
                                </div>
                                <!--div class="tab-pane" id="seo">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="website_keywords1"><?php //echo get_phrase('meta_keywords1'); ?></label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control bootstrap-tag-input" id = "meta_keywords1" name="meta_keywords1" data-role="tagsinput" style="width: 100%;"/>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                        <!--div class="col-xl-8">
                                            <div class="form-group row mb-3">
                                                <label class="col-md-2 col-form-label" for="meta_description"><?php //echo get_phrase('meta_description'); ?></label>
                                                <div class="col-md-10">
                                                    <textarea name="meta_description" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    <!--/div> <!-- end row -->
                                <!--/div-->
                                <div class="tab-pane" id="finish">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-center">
                                                <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                                <h3 class="mt-0"><?php echo get_phrase("thank_you"); ?> !</h3>

                                                <p class="w-75 mb-2 mx-auto"><?php echo get_phrase('you_are_just_one_click_away'); ?></p>

                                                <div class="mb-3 mt-3">
                                                    <button type="button" class="btn btn-primary text-center" onclick="checkRequiredFields()"><?php echo get_phrase('submit'); ?></button>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>

                                <ul class="list-inline mb-0 wizard text-center">
                                    <li class="previous list-inline-item">
                                        <a href="javascript::" class="btn btn-info"> <i class="mdi mdi-arrow-left-bold"></i> </a>
                                    </li>
                                    <li class="next list-inline-item">
                                        <a href="javascript::" class="btn btn-info"> <i class="mdi mdi-arrow-right-bold"></i> </a>
                                    </li>
                                </ul>

                            </div> <!-- tab-content -->
                        </div> <!-- end #progressbarwizard-->
                    </form>
                </div>
            </div><!-- end row-->
        </div> <!-- end card-body-->
    </div> <!-- end card-->
</div>
</div>


<script type="text/javascript">
function ChangeInput(){
    if($("#course_overview_provider").val()=='html5'){
        $("#course_overview_url").attr('type','file')
        $("#course_overview_url").removeAttr('class')
    }
    else
    {
        $("#course_overview_url").attr('type','text')
        $("#course_overview_url").attr('class','form-control')   
    }
}
  $(document).ready(function () {
    initSummerNote(['#description']);
    ChangeInput();
  });
</script>

<script type="text/javascript">
var blank_outcome = jQuery('#blank_outcome_field').html();
var blank_requirement = jQuery('#blank_requirement_field').html();
jQuery(document).ready(function() {
  jQuery('#blank_outcome_field').hide();
  jQuery('#blank_requirement_field').hide();
});
function appendOutcome() {
  jQuery('#outcomes_area').append(blank_outcome);
}
function removeOutcome(outcomeElem) {
  jQuery(outcomeElem).parent().parent().remove();
}

function appendRequirement() {
  jQuery('#requirement_area').append(blank_requirement);
}
function removeRequirement(requirementElem) {
  jQuery(requirementElem).parent().parent().remove();
}

function priceChecked(elem){
  if (jQuery('#discountCheckbox').is(':checked')) {

    jQuery('#discountCheckbox').prop( "checked", false );
  }else {

    jQuery('#discountCheckbox').prop( "checked", true );
  }
}

function topCourseChecked(elem){
  if (jQuery('#isTopCourseCheckbox').is(':checked')) {

    jQuery('#isTopCourseCheckbox').prop( "checked", false );
  }else {

    jQuery('#isTopCourseCheckbox').prop( "checked", true );
  }
}

function isFreeCourseChecked(elem) {

  if (jQuery('#'+elem.id).is(':checked')) {
    $('#price').prop('required',false);
  }else {
    $('#price').prop('required',true);
  }
}

function calculateDiscountPercentage(discounted_price) {
  if (discounted_price > 0) {
    var actualPrice = jQuery('#price').val();
    if ( actualPrice > 0) {
      var reducedPrice = actualPrice - discounted_price;
      var discountedPercentage = (reducedPrice / actualPrice) * 100;
      if (discountedPercentage > 0) {
        jQuery('#discounted_percentage').text(discountedPercentage.toFixed(2)+'%');

      }else {
        jQuery('#discounted_percentage').text('<?php echo '0%'; ?>');
      }
    }
  }
}
</script>

<style media="screen">
body {
  overflow-x: hidden;
}
</style>
