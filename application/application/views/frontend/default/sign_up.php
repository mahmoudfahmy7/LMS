<section class="category-header-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="#">
                                <?php echo $page_title; ?>
                            </a>
                        </li>
                    </ol>
                </nav>
                <h1 class="category-name">
                    <?php echo get_phrase('register_yourself'); ?>
                </h1>
            </div>
        </div>
    </div>
</section>
<style>
    .selectOption{
         color:#B2B4B9;
    }
    
    .selectOption:hover{
         color:#06D755;
    }
    
    .selectedOption{
         color:#06D755;
    }
</style>
<section class="category-course-list-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
              <div class="user-dashboard-box mt-3">
                  <div class="user-dashboard-content w-100 login-form hidden">
                      <div class="content-title-box">
                          <div class="title"><?php echo get_phrase('login'); ?></div>
                          <div class="subtitle"><?php echo get_phrase('provide_your_valid_login_credentials'); ?>.</div>
                      </div>
                      <form action="<?php echo site_url('login/validate_login/user'); ?>" method="post">
                          <div class="content-box">
                              <div class="basic-group">
                                  <div class="form-group">
                                      <label for="login-email"><span class="input-field-icon"><i class="fas fa-envelope"></i></span> <?php echo get_phrase('email'); ?>:</label>
                                      <input type="email" class="form-control" name = "email" id="login-email" placeholder="<?php echo get_phrase('email'); ?>" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="login-password"><span class="input-field-icon"><i class="fas fa-lock"></i></span> <?php echo get_phrase('password'); ?>:</label>
                                      <input type="password" class="form-control" name = "password" placeholder="<?php echo get_phrase('password'); ?>" required>
                                  </div>
                              </div>
                          </div>
                          <div class="content-update-box">
                              <button type="submit" class="btn"><?php echo get_phrase('login'); ?></button>
                          </div>
                          <div class="forgot-pass text-center">
                              <span>or</span>
                              <a href="javascript::" onclick="toggoleForm('forgot_password')"><?php echo get_phrase('forgot_password'); ?></a>
                          </div>
                          <div class="account-have text-center">
                              <?php echo get_phrase('do_not_have_an_account'); ?>? <a href="javascript::" onclick="toggoleForm('registration')"><?php echo get_phrase('sign_up'); ?></a>
                          </div>
                      </form>
                  </div>
                  <div class="user-dashboard-content w-100 register-form">
                      <div class="content-title-box" >
                          <div class="title"><?php echo get_phrase('registration_form'); ?></div>
                          <div class="subtitle"><?php echo get_phrase('sign_up_and_start_learning'); ?>.</div>
                      </div>
                      <form action="<?php echo site_url('login/register'); ?>" method="post" id="register">
                          
                          <div class="content-box row" style="display:none;">
                              <div class="col-md-3"></div>
                              <div class="col-sm-12 col-md-3 mt-3 selectOption " id="3">
                                  <a class="user-dashboard-box" style="text-align:center;padding:10px;">
                                      <i class="fas fa-user-graduate" style="font-size:72px;"></i><br/>
                                      Customer
                                  </a>
                              </div>
                              <div class="col-sm-12 col-md-3 mt-3 selectOption"id="2">
                                  <a class="user-dashboard-box" style="text-align:center;padding:10px;">
                                      <i class="fas fa-chalkboard-teacher" style=" font-size:72px"></i><br />
                                      Instructor
                                  </a>
                              </div>
                              <div class="col-md-3"></div>
                              
                          </div>
                          <div class="content-box">
                              <div class="basic-group">
								  <div class="form-group" style="color:#ff0000">
									<?php echo $this->session->flashdata('error_message');?>
								  </div>
                                  <div class="form-group">
                                      <label for="first_name"><span class="input-field-icon"><i class="fas fa-user"></i></span> <?php echo get_phrase('first_name'); ?>:</label>
                                      <input type="text" class="form-control" name = "first_name" id="first_name" placeholder="<?php echo get_phrase('first_name'); ?>" value="" required>
                                      <input type="hidden" name = "user_type" id="user_type" value="3">
                                  </div>
                                  <div class="form-group">
                                      <label for="last_name"><span class="input-field-icon"><i class="fas fa-user"></i></span> <?php echo get_phrase('last_name'); ?>:</label>
                                      <input type="text" class="form-control" name = "last_name" id="last_name" placeholder="<?php echo get_phrase('last_name'); ?>" value="" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="registration-email"><span class="input-field-icon"><i class="fas fa-envelope"></i></span> <?php echo get_phrase('email'); ?>:</label>
                                      <input type="email" class="form-control" name = "email" id="registration-email" placeholder="<?php echo get_phrase('email'); ?>" value="" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="registration-password"><span class="input-field-icon"><i class="fas fa-lock"></i></span> <?php echo get_phrase('password'); ?>:</label>
                                      <input type="password" class="form-control" name = "password" id="registration-password" placeholder="<?php echo get_phrase('password'); ?>" value="" required>
                                  </div>
                              </div>
                          </div>
                          <div class="content-update-box">
                              <button type="submit" class="btn"><?php echo get_phrase('sign_up'); ?></button>
                              <input type="hidden" name = "oauth_uid" id="oauth_uid">
                                      <input type="hidden" name = "oauth_provider" id="oauth_provider">
                          </div>
                          <div class="content-update-box">
                              <div class="row">
                                  <div class="col-sm-12 col-md-6">
                                      <a onclick="checkLoginState();" class="btn" style="background: #007bff; border-color: #007bff; color:#ffffff;"> 
                                      <img src="<?php echo base_url()?>assets/frontend/default/img/icons8-facebook-36.png" style="background:#ffffff" /> Sign up with Facebook</a>
                                      
                                  </div>
                                  <div class="col-sm-12 col-md-6">
                                      <a href="<?php echo $loginURL;?>" class="btn" style="background: #007bff; border-color: #007bff; color:#ffffff;"> 
                                      <img src="<?php echo base_url()?>assets/frontend/default/img/google.png" style="background:#ffffff" /> Sign up with Google</a>
                                      
                                  </div>
                              </div>
                              
                          </div>
                          <div class="account-have text-center">
                              <?php echo get_phrase('already_have_an_account'); ?>? <a href="javascript::" onclick="toggoleForm('login')"><?php echo get_phrase('login'); ?></a>
                          </div>
                      </form>
                  </div>

                  <div class="user-dashboard-content w-100 forgot-password-form hidden">
                      <div class="content-title-box">
                          <div class="title"><?php echo get_phrase('forgot_password'); ?></div>
                          <div class="subtitle"><?php echo get_phrase('provide_your_email_address_to_get_password'); ?>.</div>
                      </div>
                      <form action="<?php echo site_url('login/forgot_password/frontend'); ?>" method="post">
                          <div class="content-box">
                              <div class="basic-group">
                                  <div class="form-group">
                                      <label for="forgot-email"><span class="input-field-icon"><i class="fas fa-envelope"></i></span> <?php echo get_phrase('email'); ?>:</label>
                                      <input type="email" class="form-control" name = "email" id="forgot-email" placeholder="<?php echo get_phrase('email'); ?>" value="" required>
                                      <small class="form-text text-muted"><?php echo get_phrase('provide_your_email_address_to_get_password'); ?>.</small>
                                  </div>
                              </div>
                          </div>
                          <div class="content-update-box">
                              <button type="submit" class="btn"><?php echo get_phrase('reset_password'); ?></button>
                          </div>
                          <div class="forgot-pass text-center">
                              <?php echo get_phrase('want_to_go_back'); ?>? <a href="javascript::" onclick="toggoleForm('login')"><?php echo get_phrase('login'); ?></a>
                          </div>
                      </form>
                  </div>
              </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function(){
    $(".selectOption").on('click',function(){
        $(".selectOption").removeClass("selectedOption")
        $(this).addClass("selectedOption")
        $("#user_type").val($(".selectedOption").attr('id'))
        console.log('done')
    })
})
  function toggoleForm(form_type) {
    if (form_type === 'login') {
      $('.login-form').show();
      $('.forgot-password-form').hide();
      $('.register-form').hide();
    }else if (form_type === 'registration') {
      $('.login-form').hide();
      $('.forgot-password-form').hide();
      $('.register-form').show();
    }else if (form_type === 'forgot_password') {
      $('.login-form').hide();
      $('.forgot-password-form').show();
      $('.register-form').hide();
    }
  }
</script>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '231197541499689',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v7.0'
    });
  };
</script>
<script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '231197541499689',
      cookie     : true,
      xfbml      : true,
      version    : 'v7.0'
    });
      
    FB.AppEvents.logPageView();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
   


function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

function statusChangeCallback(response) {
                /*console.log('statusChangeCallback');*/
                //console.log(response);
                // The response object is returned with a status field that lets the
                // app know the current login status of the person.
                // Full docs on the response object can be found in the documentation
                // for FB.getLoginStatus().
                if (response.status === 'connected') {
                    // Logged into your app and Facebook.
                    //console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me', function (response) {
                        $("#oauth_provider").val('facebook');
                        $("#oauth_uid").val(response.id);
                        $("#first_name").val(response.name);
                        $("#register").trigger('submit');
                    });
                } else {
                    // The person is not logged into your app or we are unable to tell.
                    /*document.getElementById('message').innerHTML = 'Please log ' +
                      'into this app.';*/
                      
                    FB.login(function(response) {
                      statusChangeCallback(response)
                    }, {scope: 'public_profile,email'});
                }
            }

</script>
