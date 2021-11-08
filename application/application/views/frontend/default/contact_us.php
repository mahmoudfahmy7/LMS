<style>
    .container-lg {
        background-image: url(<?php echo  site_url('/uploads/home-banner.jpg') ?>);
    }
    body{
        width:100%;
    }
</style>
<section class="category-header-area" style="display:none">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <h1 class="category-name text-center py-5">
                    <?php echo $page_title; ?>ee
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="category-course-list-area">
    <div class="container">
        <div class="row">
            <div class="col" style="padding: 35px;">
                <?php echo get_frontend_settings('contact'); ?>
            </div>
        </div>
    </div>
    <div class="container">

        <div class="row">
            <div class="col" style="padding: 35px;">
                <div>
                    <b> CONTACT INFO </b>
                </div>
                <hr>
                <!--  -->
                <div class="">
                    <?php echo get_frontend_settings('contact_info'); ?>
                </div>
                <!--  -->
            </div>

            <div class="col" style="padding: 35px;">
                <div>
                    <b> SEND MESSAGE </b>
                </div>
                <hr>
                <div class="text-center">
                    <form id="contact-form" method="post" action="<?php echo base_url()?>home/contact_us" role="form">
                        <div class="messages"></div>
                        <div class="controls">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input id="form_name" type="text" name="name" class="form-control"
                                               placeholder="Please enter your Name" required="required"
                                               data-error="Firstname is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input id="form_email" type="email" name="email" class="form-control"
                                               placeholder="Please enter your email" required="required"
                                               data-error="Valid email is required.">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea id="form_message" name="message" class="form-control"
                                                  placeholder="Message for me " rows="4" required="required"
                                                  data-error="Please, leave us a message."></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-success btn-send" value="Send message">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Main layout-->
    <main class=" m-0 p-0">
        <div class="container-fluid m-0 p-0">
            <!--Google map-->
            <div id="map-container-google-4" class="z-depth-1-half map-container-4" >
                <iframe src="https://maps.google.com/maps?q=manhatan&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        frameborder="0"
                        style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </main>
    <!--Main layout-->
    <style>
        .map-container-4 {
            overflow: hidden;
            /*padding-bottom: 56.25%;*/
            position: relative;
            height: 350px;
        }

        .map-container-4 iframe {
            left: 0;
            top: 0;
            height: 350px;
            width: 100%;
            position: absolute;
        }
    </style>
</section>


