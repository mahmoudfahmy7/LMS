<html>
<body>
<div id="fb-root"></div>

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
                console.log(response);
                // The response object is returned with a status field that lets the
                // app know the current login status of the person.
                // Full docs on the response object can be found in the documentation
                // for FB.getLoginStatus().
                if (response.status === 'connected') {
                    // Logged into your app and Facebook.
                    console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me', function (response) {
                        
                console.log(response);
                        console.log('Successful login for: ' + response.name);
                        document.getElementById('message').innerHTML =
                          'Thanks for logging in, ' + response.name + '! ID: ' +response.id;
                    });
                } else {
                    // The person is not logged into your app or we are unable to tell.
                    document.getElementById('message').innerHTML = 'Please log ' +
                      'into this app.';
                }
            }

</script>
<fb:login-button 
  scope="public_profile,email"
  onlogin="checkLoginState();">
</fb:login-button>

<div align="center">
<h2>Facebook OAuth Javascript Demo</h2>

<div id="message">
Logs:<br/>
</div>
</div>
</body>
</html>