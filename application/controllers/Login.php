<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function index() {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        }elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }else {
            redirect(site_url('home/login'), 'refresh');
        }
    }

	public function valid_password($password = '')
    {
        return true;
        $password = trim($password);
        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number = '/[0-9]/';
        if (empty($password))
        {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
            return FALSE;
        }
        if (preg_match_all($regex_uppercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
            return FALSE;
        }
        if (preg_match_all($regex_number, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
            return FALSE;
        }
        if (strlen($password) < 8)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 8 characters in length.');
            return FALSE;
        }
        return TRUE;
    }
  

    public function validate_login($from = "") {
		
		
        $email = $this->input->post('email');
        $password = $this->input->post('password');
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'callback_valid_password');

		if($this->form_validation->run() !== false){

			$credential = array('email' => $email, 'password' => sha1($password), 'status' => 1);

			// Checking login credential for admin
			$query = $this->db->get_where('users', $credential);

			if ($query->num_rows() > 0) {
				$row = $query->row();
				$this->session->set_userdata('user_login',1);
				$this->session->set_userdata('subscription', $row->subscription);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('role_id', $row->role_id);
				$this->session->set_userdata('role', get_user_role('user_role', $row->id));
				$this->session->set_userdata('name', $row->first_name.' '.$row->last_name);
				$this->session->set_flashdata('flash_message', get_phrase('welcome').' '.$row->first_name.' '.$row->last_name);
				if ($row->role_id == 1) {
					$this->session->set_userdata('admin_login', '1');
					redirect(site_url('admin/dashboard'), 'refresh');
				}else if($row->role_id == 2  ){
					$this->session->set_userdata('user_login', '1');
					redirect(site_url('user'), 'refresh');
				}else if($row->role_id == 3  ){
					$this->session->set_userdata('user_login', '1');
					redirect(site_url('home'), 'refresh');
				}
			}else {
				$this->session->set_flashdata('error_message',get_phrase('invalid_login_credentials'));
				redirect(site_url('home/login'), 'refresh');
			}
   
		}
		else {
			$this->session->set_flashdata('formValidationError',  validation_errors('<p class="error">', '</p>'));
			redirect('home/login','refresh');
		} 
	}
	
	
    public function loginSocialProvider($id='',$provider='')
    {
        $query=$this->db->query("select * from users where oauth_provider='".$provider."' and oauth_uid='".$id."'");
        //echo $this->db->last_query();exit;
        $res=$query->result();
        if(sizeof($res)>0){
            $row=$res[0];
            $this->session->set_userdata('user_login',1);
			$this->session->set_userdata('subscription', $row->subscription);
			$this->session->set_userdata('user_id', $row->id);
			$this->session->set_userdata('role_id', $row->role_id);
			$this->session->set_userdata('role', get_user_role('user_role', $row->id));
			$this->session->set_userdata('name', $row->first_name.' '.$row->last_name);
			$this->session->set_flashdata('flash_message', get_phrase('welcome').' '.$row->first_name.' '.$row->last_name);
			if ($row->role_id == 1) {
				$this->session->set_userdata('admin_login', '1');
				redirect(site_url('admin/dashboard'), 'refresh');
			}else if($row->role_id == 2  ){
				$this->session->set_userdata('user_login', '1');
				redirect(site_url('user'), 'refresh');
			}else if($row->role_id == 3  ){
				$this->session->set_userdata('user_login', '1');
				redirect(site_url('home'), 'refresh');
			}
        }
        else
        {
			$this->session->set_flashdata('error_message', get_phrase('invalid_login_credentials'));
				redirect(site_url('home'), 'refresh');
        }
    }

    public function register() {
        
        if(isset($_GET['code']))
		{
			$this->googleplus->getAuthenticate();
			$this->session->set_userdata('login',true);
			$this->session->set_userdata('userProfile',$this->googleplus->getUserInfo());
			$gp=$this->googleplus->getUserInfo();
			$data['first_name'] = $gp['name'];
            $data['last_name']  = html_escape($this->input->post('last_name'));
            $data['email']  = $gp['email'];
            $data['password']  = sha1($gp['email']);
            $data['oauth_uid']  = $gp['id'];
            $data['oauth_provider']  = 'google';
            /*var_dump($gp);
            exit;*/
            $validity = $this->user_model->check_duplication('on_create', $data['email']);
        	if (!$validity ) {
				//$this->session->set_flashdata('error_message', get_phrase('email_duplication'));
				//echo base_url().'login/loginSocialProvider/'. $gp['id'].'/google'; exit;
			    redirect(base_url().'login/loginSocialProvider/'. $data['oauth_uid'].'/google', 'refresh');	
            }
		}
		else
		{
            $data['oauth_uid']  = $this->input->post('oauth_uid');
            $data['oauth_provider']  = $this->input->post('oauth_provider');
                $data['first_name'] = html_escape($this->input->post('first_name'));
            if(strlen($data['oauth_uid'])==0){
                $data['last_name']  = html_escape($this->input->post('last_name'));
                $data['email']  = html_escape($this->input->post('email'));
                $data['password']  = sha1($this->input->post('password'));
            }
        }
        
        if( $data['oauth_provider']=='google' || $data['oauth_provider']=='facebook' ){
            $query=$this->db->query("select * from users where oauth_provider='".$data['oauth_provider']."' and oauth_uid='".$data['oauth_uid']."'");
            $res=$query->result();
            if(sizeof($res)>0)
			    redirect(base_url().'login/loginSocialProvider/'. $data['oauth_uid'].'/'.$data['oauth_provider'], 'refresh');	
        }
        
//var_dump($_POST);exit;
		$this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
    		$this->form_validation->set_rules('password', 'Password', 'callback_valid_password');
    		$passCheck=true;
		if(strlen($data['oauth_uid'])==0){
    		$passCheck=false;
        }
		if($this->form_validation->run() !== false || $passCheck){
			$verification_code =  md5(rand(100000000, 200000000));
			$data['verification_code'] = $verification_code;

			if (get_settings('student_email_verification') == 'enable') {
				$data['status'] = 0;
			}else {
				$data['status'] = 1;
			}
			if(strlen($data['oauth_uid'])>2)
				$data['status'] = 1;
			

			$data['wishlist'] = json_encode(array());
			$data['watch_history'] = json_encode(array());
			$data['date_added'] = strtotime(date("Y-m-d H:i:s"));
			$social_links = array(
				'facebook' => "",
				'twitter'  => "",
				'linkedin' => ""
			);
			$data['social_links'] = json_encode($social_links);
			$data['role_id']  = $this->input->post('user_type')>1?$this->input->post('user_type'):3;

			// Add paypal keys
			$paypal_info = array();
			$paypal['production_client_id'] = "";
			array_push($paypal_info, $paypal);
			$data['paypal_keys'] = json_encode($paypal_info);
			// Add Stripe keys
			$stripe_info = array();
			$stripe_keys = array(
				'public_live_key' => "",
				'secret_live_key' => ""
			);
			array_push($stripe_info, $stripe_keys);
			$data['stripe_keys'] = json_encode($stripe_info);

			if(strlen($data['oauth_uid'])==0)
			    $validity = $this->user_model->check_duplication('on_create', $data['email']);
			else
			    $validity=true;
			if ($validity ) {
				$user_id = $this->user_model->register_user($data);
				//echo $this->db->last_query();exit;
				/*$this->session->set_userdata('user_login', '1');
				$this->session->set_userdata('user_id', $user_id);
				$this->session->set_userdata('role_id', 2);
				$this->session->set_userdata('role', get_user_role('user_role', 2));
				$this->session->set_userdata('name', $data['first_name'].' '.$data['last_name']);*/

				if (get_settings('student_email_verification') == 'enable') {
				    if(strlen($data['oauth_uid'])==0){
					    $this->email_model->send_email_verification_mail($data['email'], $verification_code);
					    $this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done').'. '.get_phrase('please_check_your_mail_inbox_to_verify_your_email_address').'.');
				    }
				    else
				    {
				        redirect("login/loginSocialProvider/".$data['oauth_uid']."/".$data['oauth_provider'],"refresh");
					    $this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done'));
				    }
				}else {
					$this->session->set_flashdata('flash_message', get_phrase('your_registration_has_been_successfully_done'));
				}

			}else {
				$this->session->set_flashdata('error_message', get_phrase('email_duplication'));
			}
			redirect(site_url('home'), 'refresh');
		}else{
				$this->session->set_flashdata('error_message', 'Error! <ul>' . validation_errors('<li>', '</li>') . '</ul>');
			redirect(site_url('home/sign_up'), 'refresh');
		}
    }
    
    public function profile(){
     if($this->session->userdata('login') == true)//checking session
     {
      $data['profileData'] = $this->session->userdata('userProfile'); //getting data from session ans storing.
      var_dump($data['profileData']);
      //$this->load->view('profile',$data); //loading a view.
     }
     else
     {
       redirect('');
     }
    }
    
    public function logout($from = "") {
        //destroy sessions of specific userdata. We've done this for not removing the cart session
        $this->session->unset_userdata('admin_login');
        $this->session->unset_userdata('user_login');
        $this->session_destroy();
        redirect(site_url('home/login'), 'refresh');
    }

    public function session_destroy() {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('code');
        $this->session->unset_userdata('percent');
        if ($this->session->userdata('admin_login') == 1) {
            $this->session->unset_userdata('admin_login');
        }else {
            $this->session->unset_userdata('user_login');
        }
    }


    function getName($n) { 
    	$LowerCharacters = 'abcdefghijklmnopqrstuvwxyz'; 
    	$digits = '0123456789'; 
    	$UpperCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    	$Symbol = '$@#!&*'; 
    	$alternative=array($LowerCharacters,$digits,$UpperCharacters,$Symbol);
    	$randomString = ''; 
    
    	for ($i = 0; $i < $n; $i++) { 
    	    if($i<4)
    	    $characters=$alternative[$i];
    	    else
    	    $characters=$alternative[rand(10,99)%4];
    		$index = rand(0, strlen($characters) - 1); 
    		$randomString .= $characters[$index]; 
    	} 
    
    	//echo $randomString; 
    	return  $randomString; 
    } 

    function forgot_password($from = "") {
        $email = $this->input->post('email');
        $new_password = $this->getName(8);

        // Checking credential for admin
        $query = $this->db->get_where('users' , array('email' => $email));
        if ($query->num_rows() > 0)
        {
            $this->db->where('email' , $email);
            $this->db->update('users' , array('password' => sha1($new_password)));
            // send new password to user email
            $this->email_model->password_reset_email($new_password, $email);
            $this->session->set_flashdata('flash_message', get_phrase('please_check_your_email_for_new_password'));
            if ($from == 'backend') {
                redirect(site_url('login'), 'refresh');
            }else {
                redirect(site_url('home'), 'refresh');
            }
        }else {
            $this->session->set_flashdata('error_message', get_phrase('password_reset_failed'));
            if ($from == 'backend') {
                redirect(site_url('login'), 'refresh');
            }else {
                redirect(site_url('home'), 'refresh');
            }
        }
    }

    public function verify_email_address($verification_code = "") {
        $user_details = $this->db->get_where('users', array('verification_code' => $verification_code));
        if($user_details->num_rows() == 0) {
            $this->session->set_flashdata('error_message', get_phrase('email_duplication'));
        }else {
            $user_details = $user_details->row_array();
            $updater = array(
                'status' => 1
            );
            $this->db->where('id', $user_details['id']);
            $this->db->update('users', $updater);
            $this->session->set_flashdata('flash_message', get_phrase('congratulations').'!'.get_phrase('your_email_address_has_been_successfully_verified').'.');
        }
        redirect(site_url('home'), 'refresh');
    }
    
    public function UpradeToInstructor(){
        $userid=$this->session->userdata('user_id');
        if($userid){
            $q=$this->db->query("update users set role_id=2 where id=".$userid);
            //echo $this->db->last_query();exit;
            $this->session->set_flashdata('flash_message', get_phrase('welcome_instructor'));
            $this->session->set_userdata('role_id',2);
				$this->session->set_userdata('role', get_user_role('user_role', $userid));
            redirect(site_url('home'), 'refresh');
        }
        redirect(site_url('home'), 'refresh');
    }
        
}
