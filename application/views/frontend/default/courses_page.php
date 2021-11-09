<?php
isset($layout) ? "": $layout = "list";
isset($selected_category_id) ? "": $selected_category_id = "all";
isset($selected_level) ? "": $selected_level = "all";
isset($selected_language) ? "": $selected_language = "all";
isset($selected_rating) ? "": $selected_rating = "all";
isset($selected_price) ? "": $selected_price = "all";
isset($selected_crs) ? "": $selected_crs = "all";
// echo $selected_category_id.'-'.$selected_level.'-'.$selected_language.'-'.$selected_rating.'-'.$selected_price;
$number_of_visible_categories = 10;
if (isset($sub_category_id)) {
    $sub_category_details = $this->crud_model->get_category_details_by_id($sub_category_id)->row_array();
    $category_details     = $this->crud_model->get_categories($sub_category_details['parent'])->row_array();
    $category_name        = $category_details['name'];
    $sub_category_name    = $sub_category_details['name'];
}
?>

<section class="category-header-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="#">
                                <?php echo get_phrase('courses'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php
                                if ($selected_category_id == "all") {
                                    echo get_phrase('all_category');
                                }else {
                                    $category_details = $this->crud_model->get_category_details_by_id($selected_category_id)->row_array();
                                    echo $category_details['name'];
                                }
                             ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>


<section class="category-course-list-area">
    <div class="container">
        <div class="category-filter-box filter-box clearfix">
            <span><?php echo get_phrase('showing_on_this_page'); ?> : <?php echo count($courses); ?></span>
            <a href="javascript::" onclick="toggleLayout('grid')" style="float: right; font-size: 19px; margin-left: 5px;"><i class="fas fa-th"></i></a>
            <a href="javascript::" onclick="toggleLayout('list')" style="float: right; font-size: 19px;"><i class="fas fa-th-list"></i></a>
            <a href="<?php echo site_url('home/courses'); ?>" style="float: right; font-size: 19px; margin-right: 5px;"><i class="fas fa-sync-alt"></i></a>
        </div>
        <div class="row">
            <div class="col-lg-3 filter-area">
                <div class="card">
                    <a href="javascript::"  style="color: unset;">
                        <div class="card-header filter-card-header" id="headingOne" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                            <h6 class="mb-0">
                                <?php echo get_phrase('filter'); ?>
                                <i class="fas fa-sliders-h" style="float: right;"></i>
                            </h6>
                        </div>
                    </a>
                    <div id="collapseFilter" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body pt-0">
                            <div class="filter_type">
                                <h3>&nbsp;</h3>
                                <select onchange="filter(this)" class="form-control select2 categories">
                                        <option
                                                id="category_all" name="sub_category" value="all" 
                                                <?php if($selected_category_id == 'all') echo 'selected'; ?>><?php echo get_phrase('all_category'); ?></option>
                                    <?php
                                    $counter = 1;
                                    $total_number_of_categories = $this->db->get('category')->num_rows();
                                    $categories = $this->crud_model->get_categories()->result_array();
                                    foreach ($categories as $category): ?>
                                        <optgroup 
                                            id="category-<?php echo $category['id'];?>" 
                                            name="sub_category" 
                                            label="<?php echo $category['name']; ?>" 
                                            class="<?php if ($counter > $number_of_visible_categories): ?> hidden-categories hidden <?php endif; ?>">
                                        <?php foreach ($this->crud_model->get_sub_categories($category['id']) as $sub_category):
                                            $counter++; ?>
                                            <option
                                                class="<?php if ($counter > $number_of_visible_categories): ?> hidden-categories hidden <?php endif; ?>" 
                                                id="sub_category-<?php echo $sub_category['id'];?>" name="sub_category" 
                                                txt="<?php echo urlencode($sub_category['name'])?>"
                                                value="<?php echo $category['slug']?>"
                                                <?php if(urldecode($selected_crs) == $sub_category['name']) echo 'selected'; ?>><?php echo $sub_category['name']?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                                <a href="javascript::" id = "city-toggle-btn" onclick="showToggle(this, 'hidden-categories')" style="font-size: 12px;"><?php echo $total_number_of_categories > $number_of_visible_categories ? get_phrase('show_more') : ""; ?></a>
                            </div>
                            <hr>
                            <div class="filter_type">
                                <div class="form-group">
                                    
                                <select onchange="filter(this)" class="form-control select2 prices">
                                        <option id="price_all" name="price" value="all" 
                                        <?php if($selected_price == 'all') echo 'selected'; ?>>
                                            <?php echo get_phrase('all')." ".get_phrase('price'); ?>
                                        </option>
                                        <option id="price_free" name="price" value="free" 
                                        <?php if($selected_price == 'free') echo 'selected'; ?>>
                                            <?php echo get_phrase('free'); ?>
                                        </option>
                                        <option id="price_paid" name="price" value="paid" 
                                        <?php if($selected_price == 'paid') echo 'selected'; ?>>
                                            <?php echo get_phrase('paid'); ?>
                                        </option>
                                </select>
                                </div>
                            </div>
                            <hr>
                            <div class="filter_type">
                                <select onchange="filter(this)" class="form-control select2 level">
                                        <option id="all" name="level" value="all" 
                                        <?php if($selected_level == 'all') echo 'selected'; ?>>
                                            <?php echo get_phrase('all')." ".get_phrase('level'); ?>
                                        </option>
                                        <option id="beginner" name="level" value="beginner" 
                                        <?php if($selected_level == 'beginner') echo 'selected'; ?>>
                                            <?php echo get_phrase('beginner'); ?>
                                        </option>
                                        <option id="intermediate" name="level" value="intermediate" 
                                        <?php if($selected_level == 'intermediate') echo 'selected'; ?>>
                                            <?php echo get_phrase('intermediate'); ?>
                                        </option>
                                        <option id="advanced" name="level" value="advanced" 
                                        <?php if($selected_level == 'advanced') echo 'selected'; ?>>
                                            <?php echo get_phrase('advanced'); ?>
                                        </option>
                                </select>
                                
                            </div>
                            <hr>
                            <div class="filter_type">
                                    <select  class="languages form-control" onchange="filter(this)" >
                                        <option  value="<?php echo 'all'; ?>"><?php echo get_phrase('all').' '.get_phrase('language'); ?></option>
                                    <?php
                                    $languages = $this->crud_model->get_all_languages();
                                    foreach ($languages as $language): 
                                     if ($language=='') continue;?>
                                        <option  value="<?php echo $language; ?>"><?php echo ucfirst($language); ?></option>
                                    <?php endforeach; ?>
                                    </select>
                            </div>
                            <hr>
                            <div class="filter_type">
                                <select onchange="filter(this)" class="form-control select2 ratings">
                                        <option id="all_rating" name="level" value="<?php echo 'all'; ?>" 
                                        <?php if($selected_rating == 'all') echo 'selected'; ?>>
                                            <?php echo get_phrase('all')." ".get_phrase('ratings'); ?>
                                        </option>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                        <option id="rating_<?php echo $i; ?>" name="level" value="<?php echo $i; ?>" 
                                        <?php if($selected_rating == $i) echo 'selected'; ?>>
                                            <?php  echo $i; ?>
                                        </option>
                                        
                                        <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
            //var_dump($widget);
            foreach($widget as $w) {?>
                <div class="card">
                    <a href="javascript::"  style="color: unset;">
                        <div class="card-header filter-card-header" id="headingOne" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                            <h6 class="mb-0">
                                <?php echo $w->title; ?>
                                <i class="fas fa-sliders-h" style="float: right;"></i>
                            </h6>
                        </div>
                    </a>
                    <div id="collapseFilter" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body pt-0">
                            <?php if($w->type==1){?>
                                    <p><?php echo $w->content; ?></p>
                                    <?php }
                                    else {?>
                                    <p><img src="<?php echo base_url().'uploads/wedget_files/'.$w->content ?>"
                                                             alt="" class="img-fluid"></p>
                                <?php }?>
                        </div>
                    </div>
                </div>
                
            <?php }?>
            </div>
            <div class="col-lg-9">
                <div class="category-course-list">
                    <?php include 'category_wise_course_'.$layout.'_layout.php'; ?>
                    <?php if (count($courses) == 0): ?>
                        <?php echo get_phrase('no_result_found'); ?>
                    <?php endif; ?>
                </div>
                <nav>
                    <?php if ($selected_category_id == "all" && $selected_price == 0 && $selected_level == 'all' && $selected_language == 'all' && $selected_rating == 'all'){
                        echo $this->pagination->create_links();
                    }?>
                </nav>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

function get_url() {
    var urlPrefix 	= '<?php echo site_url('home/courses?'); ?>'
    var urlSuffix = "";
    var slectedCategory = "";
    var selectedPrice = "";
    var selectedLevel = "";
    var selectedLanguage = "";
    var selectedRating = "";

    // Get selected category
        slectedCategory = $('.categories').val()+'&crs='+$('.categories option:selected').attr('txt');

    // Get selected price
        selectedPrice = $('.prices').val();

    // Get selected difficulty Level
        selectedLevel = $('.level').val();

    // Get selected difficulty Level
        selectedLanguage = $('.languages').val();

    // Get selected rating
        selectedRating = $('.ratings').val();

    urlSuffix = "category="+slectedCategory+"&&price="+selectedPrice+"&&level="+selectedLevel+"&&language="+selectedLanguage+"&&rating="+selectedRating;
    var url = urlPrefix+urlSuffix;
    return url;
}
function filter() {
    var url = get_url();
    window.location.replace(url);
    //console.log(url);
}

function toggleLayout(layout) {
    $.ajax({
        type : 'POST',
        url : '<?php echo site_url('home/set_layout_to_session'); ?>',
        data : {layout : layout},
        success : function(response){
            location.reload();
        }
    });
}

function showToggle(elem, selector) {
    $('.'+selector).slideToggle(20);
    if($(elem).text() === "<?php echo get_phrase('show_more'); ?>")
    {
        $(elem).text('<?php echo get_phrase('show_less'); ?>');
    }
    else
    {
        $(elem).text('<?php echo get_phrase('show_more'); ?>');
    }
}
</script>
