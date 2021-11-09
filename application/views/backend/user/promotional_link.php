
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i
                            class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('promotional_link'); ?>
                    <a href="<?php echo site_url('user/promotional_form/add_promotional'); ?>"
                       class="btn btn-outline-primary btn-rounded alignToTitle"><i
                                class="mdi mdi-plus"></i><?php echo get_phrase('add_new_promotional_link'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('promotional_link'); ?></h4>
                <div class="table-responsive-sm mt-4">
                    <?php if (count($promotionals) > 0): ?>
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('course_title'); ?></th>
                                <th><?php echo get_phrase('link'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($promotionals as $promotional):
                                $course_data = $this->db->get_where('course', array('id' => $promotional['course_id']))->row_array(); ?>
                                <tr class="gradeU">
                                    <td>
                                        <strong><a href="<?php echo site_url('user/course_form/course_edit/' . $course_data['id']); ?>"
                                                   target="_blank"><?php echo ellipsis($course_data['title']); ?></a></strong>
                                    </td>
                                    <td><?php echo $promotional['url']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <?php if (count($promotionals/*->result_array()*/) == 0): ?>
                        <div class="img-fluid w-100 text-center">
                            <img style="opacity: 1; width: 100px;"
                                 src="<?php echo base_url('assets/backend/images/file-search.svg'); ?>"><br>
                            <?php echo get_phrase('no_data_found'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<script type="text/javascript">
    function update_date_range() {
        var x = $("#selectedValue").html();
        $("#date_range").val(x);
    }
</script>
