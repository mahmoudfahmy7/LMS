<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function get_categories($param1 = "")
    {
        if ($param1 != "") {
            $this->db->where('id', $param1);
        }
        $this->db->where('parent', 0);
        return $this->db->get('category');
    }

    public function couponFiendBy(array $condition)
    {
        return $this->db->get_where('coupons', array($condition['key'] => $condition['value']))->result_array();
    }

    public function addCouponSession($code, $courseId)
    {
        $codeRow = $this->db->get_where('coupons', array('code' => $code))->result_array();//var_dump($codeRow);exit;
        if (count($codeRow) == 1) {
            if ($codeRow[0]['start'] <= strtotime(date("Y-m-d"))) {
                if ($codeRow[0]['type'] == 'public') {
                    $checkCourse = $this->db->get_where('coupons', array('course_id' => $courseId, 'end >=', strtotime(date("Y-m-d"))))->result_array();
                    if (count($checkCourse) > 0) {
                        return false;
                    } else {
                        $this->session->set_userdata('percent', $codeRow[0]['percent']);
                        $this->session->set_userdata('i_factor', $codeRow[0]['i_factor']);
                        $this->session->set_userdata('code', $code);
                        $this->session->set_userdata('type', $codeRow[0]['type']);
                        $this->session->set_userdata('course_id', $codeRow[0]['course_id']);
                        return true;
                    }
                } else {
                    if ($codeRow[0]['course_id'] == $courseId) {
                        if ($codeRow[0]['end'] != null)
                            if ($codeRow[0]['end'] < strtotime(date("Y-m-d")))
                                return false;

                        if ($codeRow[0]['expired_count'] != null)
                            if ($codeRow[0]['count_of_use'] >= $codeRow[0]['expired_count'])
                                return false;

                        $this->session->set_userdata('percent', $codeRow[0]['percent']);
                        $this->session->set_userdata('i_factor', $codeRow[0]['i_factor']);
                        $this->session->set_userdata('code', $code);
						$this->session->set_userdata('type', $codeRow[0]['type']);
						$this->session->set_userdata('course_id', $codeRow[0]['course_id']);
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }

    public function couponGetAll()
    {
        $this->db->order_by('start', 'desc');
        return $this->db->get('coupons');
    }

    public function couponGetAllByUserId()
    {
        if (!is_null($this->input->post('date_range'))) {
            $date = explode(' - ', $this->input->post('date_range'));
            $this->db->where('start >=', strtotime($date[1]));
            $this->db->where('end <=', strtotime($date[0]));
        }
        $this->db->order_by('start', 'desc');
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->get('coupons')->result_array();
    }

    private function couponData()
    {
        //$data['type'] = $this->input->post('type');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['course_id'] = $this->input->post('course_id');
        $data['code'] = $this->input->post('code');
        $data['url'] = $this->input->post('url');
        $data['percent'] = $this->input->post('percent');
        $data['i_factor'] = $this->input->post('i_factor');
        $date = explode(' - ', $this->input->post('expired_data'));
        $data['start'] = strtotime($date[0]);
        $data['end'] = strtotime($date[1]);
        $data['expired_count'] = $this->input->post('expired_count');
        $data['created_at'] = strtotime(date("Y-m-d"));
        //var_dump($data);exit;
        return $data;
    }

    public function couponAdd()
    {
        return $this->db->insert('coupons', $this->couponData());
    }

    public function promotionalAdd()
    {
        $data['course_id'] = $this->input->post('course_id');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['url'] = $this->input->post('url');
        $data['code'] = $this->input->post('code');

        return $this->db->insert('promotional', $data);
    }

    public function couponDelete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('coupons');
    }

    public function couponUpdate($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('coupons', $this->couponData());
    }

    public function couponUse($code)
    {
        $codeRow = $this->db->get_where('coupons', array('code' => $code))->row_array();
        $this->addAccounts($codeRow);

        $this->db->where('code', $code);
        return $this->db->update('coupons', array('count_of_use' => intval($codeRow['count_of_use']) + 1));
    }

    public function addAccounts($codeRow)
    {
        $course = $this->db->get_where('course', array('id' => $codeRow['course_id']))->row_array();
        $price = $course['price'] - ($course['price'] * $codeRow['percent'] / 100);

        if ($course['user_id'] == $codeRow['user_id'])
            $data['amount'] = $price - ($price * 30 / 100);
        else
            $data['amount'] = $price - ($price * 70 / 100);

        $data['course_id'] = $codeRow['course_id'];
        $data['user_id'] = $codeRow['user_id'];
        $data['date'] = strtotime(date("Y-m-d H:i:s"));
        return $this->db->insert('accounts', $data);
    }

    public function getAllAccounts()
    {
		/*$this->db->select('user_course_id, SUM(instructor_revenue) as total2');
		$this->db->where('instructor_payment_status',0);*/
		$this->db->select('user_course_id,SUM(instructor_revenue) as total, SUM(admin_revenue) as total1');
		$this->db->group_by('user_course_id');
		$query = $this->db->get('payment');
        return $query->result_array();
    }
	
	public function get_instructor_revenue_unpaid()
    {
		$this->db->select('user_course_id, SUM(instructor_revenue) as total2');
		$this->db->where('instructor_payment_status',0);
		$this->db->group_by('user_course_id');
		$query = $this->db->get('payment');
        return $query->result_array();
    }

    public function getAllAccountsByUserId($userId=0)
    {
		/*
        $query = $this->db->get_where('payment', array('user_course_id' => $userId));
        return $query->result_array();*/
		if($userId>0)
			$where= " where users.id=".$userId;
		$q=$this->db->query("SELECT 
							users.id,
							users.first_name,
							users.last_name, 
							users.payment_type, 
							users.payment_id, 
							sum(`amount`) total,
							(select 
									ifnull(sum(`instructor_revenue`),0) 
									from payment where payment.user_course_id=out_tbl.user_course_id and 
									`instructor_payment_status`=1)sumPaid ,
							(select 
									ifnull(sum(`instructor_revenue`),0) 
									from payment where payment.user_course_id=out_tbl.user_course_id) sumIPaid ,
							(select 
							ifnull(sum(`admin_revenue`),0) 
							from payment where payment.user_course_id=out_tbl.user_course_id  )sumAPaid 
									FROM `payment` out_tbl join users on users.id=out_tbl.user_course_id ".$where." group by users.id ");
		return $q->result();
		
    }

    public function accounts($course_id)
    {			
	//var_dump('accounts function');exit();
        $course = $this->db->get_where('course', array('id' => $course_id))->row_array();
        if ($course['discount_flag'] == 1)
            $price = $course['discounted_price'];
        else
            $price = $course['price'];
        if ($this->session->userdata('promotional')) {
            $promotional = $this->getPromotionalByCode($this->session->userdata('promotional'));
            if(is_array($promotional) && $course['user_id'] == $promotional['user_id'] ) {
                $data['amount'] = intval($price - ($price * 30 / 100));
            }else{
                $data['amount'] = intval($price - ($price * 70 / 100));
            }
        } else {
            $data['amount'] = intval($price / 2);
        }

        $data['course_id'] = $course_id;
        $data['user_id'] = $course['user_id'];
        $data['date'] = strtotime(date("Y-m-d H:i:s"));
        return $this->db->insert('accounts', $data);
    }

    public function getPromotionalByCode($code)
    {
        $this->db->where('code', $code);
        return $this->db->get('promotional')->row_array();
    }

    public function getCourseTitleById($id)
    {
        $this->db->where('id', $id);
        $this->db->select('title');
        return $this->db->get('course')->row_array()['title'];
    }
    
    public function getCoursePriceById($id)
    {
        $this->db->where('id', $id);
        $this->db->select('price');
        return $this->db->get('course')->row_array()['price'];
    }

    public function get_category_details_by_id($id)
    {
        return $this->db->get_where('category', array('id' => $id));
    }

    public function get_category_id($slug = "")
    {
        $category_details = $this->db->get_where('category', array('slug' => $slug))->row_array();
        return $category_details['id'];
    }

    public function add_category()
    {
        $data['code'] = html_escape($this->input->post('code'));
        $data['name'] = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug'] = slugify(html_escape($this->input->post('name')));
        if ($this->input->post('parent') == 0) {
            // Font awesome class adding
            if ($_POST['font_awesome_class'] != "") {
                $data['font_awesome_class'] = html_escape($this->input->post('font_awesome_class'));
            } else {
                $data['font_awesome_class'] = 'fas fa-chess';
            }

            // category thumbnail adding
            if (!file_exists('uploads/thumbnails/category_thumbnails')) {
                mkdir('uploads/thumbnails/category_thumbnails', 0777, true);
            }
            if ($_FILES['category_thumbnail']['name'] == "") {
                $data['thumbnail'] = 'category-thumbnail.png';
            } else {
                $data['thumbnail'] = md5(rand(10000000, 20000000)) . '.jpg';
                move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], 'uploads/thumbnails/category_thumbnails/' . $data['thumbnail']);
            }
        }
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $this->db->insert('category', $data);
    }

    public function addContact($data)
    {
        $this->db->insert('contact', $data);
    }


    public function getContact($slug)
    {
        $category_details = $this->db->get_where('contact', array('id' => $slug))->row_array();
        return $category_details;
    }

    public function getAllContacts()
    {
        return $this->db->select('*')
            ->from('contact')
            //->where('read', 1)
            ->get()->result();
        /*        $query = $this->db->query('SELECT * FROM contact ORDER BY id DESC;');
                return  $query->result();*/
    }

    public function getUnreadMsg()
    {
        return $this->db->select('*')
            ->from('contact')
            ->where('read', 0)
            ->get()->result();
    }

    public function updateSettings($key)
    {
        $data['value'] = $this->input->post('content_page');
        $this->db->where('key', $key);
        $this->db->update('frontend_settings', $data);
    }

    public function updateContact()
    {
        $data['value'] = $this->input->post('content_page');
        $this->db->where('key', 'contact');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('contact_info');
        $this->db->where('key', 'contact_info');
        $this->db->update('frontend_settings', $data);
    }

    public function getContentFromSetting($key)
    {
        return $this->db->get_where('frontend_settings', array('key' => $key));
    }

    public function updateMsgRead($id)
    {
        $this->db->query('UPDATE `contact` SET `read`=1 where `id`=' . $id);
    }

    public function deleteMsgContact($id)
    {
        $this->db->query('DELETE FROM `contact` WHERE `id`=' . $id);
    }


    public function edit_category($param1)
    {
        $data['name'] = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug'] = slugify(html_escape($this->input->post('name')));
        if ($this->input->post('parent') == 0) {
            // Font awesome class adding
            if ($_POST['font_awesome_class'] != "") {
                $data['font_awesome_class'] = html_escape($this->input->post('font_awesome_class'));
            } else {
                $data['font_awesome_class'] = 'fas fa-chess';
            }
            // category thumbnail adding
            if (!file_exists('uploads/category_thumbnails')) {
                mkdir('uploads/category_thumbnails', 0777, true);
            }
            if ($_FILES['category_thumbnail']['name'] != "") {
                $data['thumbnail'] = md5(rand(10000000, 20000000)) . '.jpg';
                move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], 'uploads/thumbnails/category_thumbnails/' . $data['thumbnail']);
            }
        }
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $this->db->where('id', $param1);
        $this->db->update('category', $data);
    }

    public function delete_category($category_id)
    {
        $this->db->where('id', $category_id);
        $this->db->delete('category');
    }

    public function get_sub_categories($parent_id = "")
    {
        return $this->db->get_where('category', array('parent' => $parent_id))->result_array();
    }

    public function enrol_history($course_id = "")
    {
        if ($course_id > 0) {
            return $this->db->get_where('enrol', array('course_id' => $course_id));
        } else {
            return $this->db->get('enrol');
        }
    }

    public function enrol_history_by_user_id($user_id = "")
    {
        return $this->db->get_where('enrol', array('user_id' => $user_id));
    }

    public function all_enrolled_student()
    {
        $this->db->select('user_id');
        $this->db->distinct('user_id');
        return $this->db->get('enrol');
    }

    public function enrol_history_by_date_range($timestamp_start = "", $timestamp_end = "")
    {
        $this->db->order_by('date_added', 'desc');
        $this->db->where('date_added >=', $timestamp_start);
        $this->db->where('date_added <=', $timestamp_end);
        return $this->db->get('enrol');
    }

    public function get_revenue_by_user_type($timestamp_start = "", $timestamp_end = "", $revenue_type = "")
    {
        $course_ids = array();
        $courses = array();
        $admin_details = $this->user_model->get_admin_details()->row_array();
        if ($revenue_type == 'admin_revenue') {
            $this->db->where('date_added >=', $timestamp_start);
            $this->db->where('date_added <=', $timestamp_end);
        } elseif ($revenue_type == 'instructor_revenue') {
            $this->db->where('user_id !=', $admin_details['id']);
            $this->db->select('id');
            $courses = $this->db->get('course')->result_array();
            foreach ($courses as $course) {
                if (!in_array($course['id'], $course_ids)) {
                    array_push($course_ids, $course['id']);
                }
            }
            if (sizeof($course_ids)) {
                $this->db->where_in('course_id', $course_ids);
            } else {
                return array();
            }
        }

        $this->db->order_by('date_added', 'desc');
        return $this->db->get('payment')->result_array();
    }

    public function getAllCourses()
    {
        return $this->db->select('id, title')->get('course')->result_array();
    }
	
	public function getAllCoursesNotFreeNotDiscount()
    {
        $this->db->where('discount_flag is null');
        $this->db->where('is_free_course is null');
        return $this->db->select('id, title ,price')->get('course')->result_array();
    }

    public function getAllCoursesForUser()
    {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->select('id, title')->get('course')->result_array();
    }
	
	public function getAllCoursesForUserNotFreeNotDiscount()
    {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('discount_flag is null');
        $this->db->where('is_free_course is null');
        return $this->db->select('id, title,price')->get('course')->result_array();
    }

    public function getAllPromotionalForUser()
    {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->get('promotional')->result_array();
    }


    public function get_instructor_revenue($where = '')
    {
        $course_ids = array();
        $courses = array();

        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->select('id');
        $courses = $this->db->get('course')->result_array();
        foreach ($courses as $course) {
            if (!in_array($course['id'], $course_ids)) {
                array_push($course_ids, $course['id']);
            }
        }
        if (sizeof($course_ids)) {
            $this->db->where_in('course_id', $course_ids);
        } else {
            return array();
        }

        $this->db->order_by('date_added', 'desc');
		
		if(strlen($where)>0)
			$this->db->where($where);
		
        return $this->db->get('payment')->result_array();
    }

    public function delete_payment_history($param1)
    {
        $this->db->where('id', $param1);
        $this->db->delete('payment');
    }

    public function delete_enrol_history($param1)
    {
        $this->db->where('id', $param1);
        $this->db->delete('enrol');
    }

    public function purchase_history($user_id)
    {
        if ($user_id > 0) {
            return $this->db->get_where('payment', array('user_id' => $user_id));
        } else {
            return $this->db->get('payment');
        }
    }

    public function get_payment_details_by_id($payment_id = "")
    {
        return $this->db->get_where('payment', array('id' => $payment_id))->row_array();
    }

    public function update_instructor_payment_status($payment_id = "")
    {
        $updater = array(
            'instructor_payment_status' => 1
        );
        $this->db->where('id', $payment_id);
        $this->db->update('payment', $updater);
    }

    public function update_system_settings()
    {
        $data['value'] = html_escape($this->input->post('system_name'));
        $this->db->where('key', 'system_name');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('system_title'));
        $this->db->where('key', 'system_title');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('author'));
        $this->db->where('key', 'author');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('slogan'));
        $this->db->where('key', 'slogan');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('language'));
        $this->db->where('key', 'language');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('text_align'));
        $this->db->where('key', 'text_align');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('system_email'));
        $this->db->where('key', 'system_email');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('address'));
        $this->db->where('key', 'address');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('phone'));
        $this->db->where('key', 'phone');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('youtube_api_key'));
        $this->db->where('key', 'youtube_api_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('vimeo_api_key'));
        $this->db->where('key', 'vimeo_api_key');
        $this->db->update('settings', $data);

        /*  $data['value'] = html_escape($this->input->post('purchase_code'));
          $this->db->where('key', 'purchase_code');
          $this->db->update('settings', $data);*/

        $data['value'] = html_escape($this->input->post('footer_text'));
        $this->db->where('key', 'footer_text');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('footer_link'));
        $this->db->where('key', 'footer_link');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('website_keywords'));
        $this->db->where('key', 'website_keywords');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('website_description'));
        $this->db->where('key', 'website_description');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('student_email_verification'));
        $this->db->where('key', 'student_email_verification');
        $this->db->update('settings', $data);
    }

    public function update_smtp_settings()
    {
        $data['value'] = html_escape($this->input->post('protocol'));
        $this->db->where('key', 'protocol');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_host'));
        $this->db->where('key', 'smtp_host');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_port'));
        $this->db->where('key', 'smtp_port');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_user'));
        $this->db->where('key', 'smtp_user');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_pass'));
        $this->db->where('key', 'smtp_pass');
        $this->db->update('settings', $data);
    }

    public function update_paypal_settings()
    {
        // update paypal keys
        $paypal_info = array();
        $paypal['active'] = $this->input->post('paypal_active');
        $paypal['mode'] = $this->input->post('paypal_mode');
        $paypal['sandbox_client_id'] = $this->input->post('sandbox_client_id');
        $paypal['production_client_id'] = $this->input->post('production_client_id');

        array_push($paypal_info, $paypal);

        $data['value'] = json_encode($paypal_info);
        $this->db->where('key', 'paypal');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('paypal_currency'));
        $this->db->where('key', 'paypal_currency');
        $this->db->update('settings', $data);
    }

    public function update_stripe_settings()
    {
        // update stripe keys
        $stripe_info = array();

        $stripe['active'] = $this->input->post('stripe_active');
        $stripe['testmode'] = $this->input->post('testmode');
        $stripe['public_key'] = $this->input->post('public_key');
        $stripe['secret_key'] = $this->input->post('secret_key');
        $stripe['public_live_key'] = $this->input->post('public_live_key');
        $stripe['secret_live_key'] = $this->input->post('secret_live_key');

        array_push($stripe_info, $stripe);

        $data['value'] = json_encode($stripe_info);
        $this->db->where('key', 'stripe_keys');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('stripe_currency'));
        $this->db->where('key', 'stripe_currency');
        $this->db->update('settings', $data);
    }

    public function update_system_currency()
    {
        $data['value'] = html_escape($this->input->post('system_currency'));
        $this->db->where('key', 'system_currency');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('currency_position'));
        $this->db->where('key', 'currency_position');
        $this->db->update('settings', $data);
    }

    public function update_instructor_settings()
    {
        $data['value'] = html_escape($this->input->post('allow_instructor'));
        $this->db->where('key', 'allow_instructor');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('instructor_revenue'));
        $this->db->where('key', 'instructor_revenue');
        $this->db->update('settings', $data);
    }

    public function get_lessons($type = "", $id = "")
    {
        $this->db->order_by("order", "asc");
        if ($type == "course") {
            return $this->db->get_where('lesson', array('course_id' => $id));
        } elseif ($type == "section") {
            return $this->db->get_where('lesson', array('section_id' => $id));
        } elseif ($type == "lesson") {
            return $this->db->get_where('lesson', array('id' => $id));
        } else {
            return $this->db->get('lesson');
        }
    }

    public function add_course($param1 = "")
    {
        $outcomes = $this->trim_and_return_json($this->input->post('outcomes'));
        $requirements = $this->trim_and_return_json($this->input->post('requirements'));

        $data['title'] = html_escape($this->input->post('title'));
        $data['short_description'] = $this->input->post('short_description');
        $data['description'] = $this->input->post('description');
        $data['outcomes'] = $outcomes;
        $data['language'] = $this->input->post('language_made_in');
        $data['sub_category_id'] = $this->input->post('sub_category_id');
        $category_details = $this->get_category_details_by_id($this->input->post('sub_category_id'))->row_array();
        $data['category_id'] = $category_details['parent'];
        $data['requirements'] = $requirements;
        $data['price'] = $this->input->post('price');
        $data['discount_flag'] = $this->input->post('discount_flag');
        $data['discounted_price'] = $this->input->post('discounted_price');
        $data['level'] = $this->input->post('level');
        $data['is_free_course'] = $this->input->post('is_free_course');
        
        
        
        $filename= '';

        $data['course_overview_provider'] = $this->input->post('course_overview_provider');

        if($this->input->post('course_overview_provider')=='html5'){
            if(isset($_FILES['course_overview_url']) && strlen($_FILES['course_overview_url']['name'])>4){
                $extArr=explode('.',$_FILES['course_overview_url']['name']);
                $ext=$extArr[sizeof($extArr)-1];
                if(strtolower($ext)!='mp4'){
                    $this->session->set_flashdata('error_message', get_phrase('invalid_attachment'));
                    redirect("user/course_form/course_edit/".$course_id,"refresh");
                }
                
                if (!file_exists('uploads/course')) {
                    mkdir('uploads/course', 0777, true);
                }
                $filename = md5(uniqid(rand(), true)).'.mp4';
                move_uploaded_file($_FILES['course_overview_url']['tmp_name'], 'uploads/course/' . $filename);
            }
        }
        else 
            $filename= $this->input->post('course_overview_url');
        
        if($filename=='') $data['course_overview_provider']='';
        $data['video_url']=$filename;
        
        
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['section'] = json_encode(array());
        $data['is_top_course'] = $this->input->post('is_top_course');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['meta_description'] = $this->input->post('meta_description');
        $data['meta_keywords'] = $this->input->post('meta_keywords');
        $admin_details = $this->user_model->get_admin_details()->row_array();
        if ($admin_details['id'] == $data['user_id']) {
            $data['is_admin'] = 1;
        } else {
            $data['is_admin'] = 0;
        }
        if ($param1 == "save_to_draft") {
            $data['status'] = 'draft';
        } else {
            if ($this->session->userdata('admin_login')) {
                $data['status'] = 'active';
            } else {
                $data['status'] = 'pending';
            }
        }
        $this->db->insert('course', $data);

        $course_id = $this->db->insert_id();
        // Create folder if does not exist
        if (!file_exists('uploads/thumbnails/course_thumbnails')) {
            mkdir('uploads/thumbnails/course_thumbnails', 0777, true);
        }

        
        
        // Upload different number of images according to activated theme. Data is taking from the config.json file
        $course_media_files = themeConfiguration(get_frontend_settings('theme'), 'course_media_files');
        foreach ($course_media_files as $course_media => $size) {
            if ($_FILES[$course_media]['name'] != "") {
				/*
				$max_height = 600;
$max_width = 600;
if ($_FILES[$course_media]['image_width']>$max_width || $_FILES[$course_media]['image_height']>$max_height)
{
				*/
        //        move_uploaded_file($_FILES[$course_media]['tmp_name'], 'uploads/thumbnails/course_thumbnails/' . $course_media . '_' . get_frontend_settings('theme') . '_' . $course_id . '.jpg');
                copy(getcwd().'/uploads/'.$this->input->post('thumbcourse_thumbnail'), getcwd().'/uploads/thumbnails/course_thumbnails/' . $course_media . '_' . get_frontend_settings('theme') . '_' . $course_id . '.jpg');
                /*var_dump($_POST);
                echo getcwd().'/uploads/'.$this->input->post('thumbcourse_thumbnail')."<br />".getcwd().'/uploads/thumbnails/course_thumbnails/' . $course_media . '_' . get_frontend_settings('theme') . '_' . $course_id . '.jpg';
                exit;*/
            }
        }

        if ($data['status'] == 'approved') {
            $this->session->set_flashdata('flash_message', get_phrase('course_added_successfully'));
        } elseif ($data['status'] == 'pending') {
            $this->session->set_flashdata('flash_message', get_phrase('course_added_successfully') . '. ' . get_phrase('please_wait_untill_Admin_approves_it'));
        } elseif ($data['status'] == 'draft') {
            $this->session->set_flashdata('flash_message', get_phrase('your_course_has_been_added_to_draft'));
        }

        return $course_id;
    }

    function trim_and_return_json($untrimmed_array)
    {
        $trimmed_array = array();
        if (sizeof($untrimmed_array) > 0) {
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    array_push($trimmed_array, $row);
                }
            }
        }
        return json_encode($trimmed_array);
    }

    public function update_course($course_id, $type = "")
    {
        $course_details = $this->get_course_by_id($course_id)->row_array();

        $outcomes = $this->trim_and_return_json($this->input->post('outcomes'));
        $requirements = $this->trim_and_return_json($this->input->post('requirements'));
        $data['title'] = $this->input->post('title');
        $data['short_description'] = $this->input->post('short_description');
        $data['description'] = $this->input->post('description');
        $data['outcomes'] = $outcomes;
        $data['language'] = $this->input->post('language_made_in');
        $data['sub_category_id'] = $this->input->post('sub_category_id');
        $category_details = $this->get_category_details_by_id($this->input->post('sub_category_id'))->row_array();
        $data['category_id'] = $category_details['parent'];
        $data['requirements'] = $requirements;
        $data['is_free_course'] = $this->input->post('is_free_course');
        $data['price'] = $this->input->post('price');
        $data['discount_flag'] = $this->input->post('discount_flag');
        $data['discounted_price'] = $this->input->post('discounted_price');
        $data['level'] = $this->input->post('level');
        
        $filename= '';

        $data['course_overview_provider'] = $this->input->post('course_overview_provider');

        if($this->input->post('course_overview_provider')=='html5'){
            if(isset($_FILES['course_overview_url']) && strlen($_FILES['course_overview_url']['name'])>4){
                $extArr=explode('.',$_FILES['course_overview_url']['name']);
                $ext=$extArr[sizeof($extArr)-1];
                if(strtolower($ext)!='mp4'){
                    $this->session->set_flashdata('error_message', get_phrase('invalid_attachment'));
                    redirect("user/course_form/course_edit/".$course_id,"refresh");
                }
                
                if (!file_exists('uploads/course')) {
                    mkdir('uploads/course', 0777, true);
                }
                $filename = md5(uniqid(rand(), true)).'.mp4';
                move_uploaded_file($_FILES['course_overview_url']['tmp_name'], 'uploads/course/' . $filename);
            }
        }
        else 
            $filename= $this->input->post('course_overview_url');
        
        if($filename=='') $data['course_overview_provider']='';
        $data['video_url']=$filename;

        $data['meta_description'] = $this->input->post('meta_description');
        $data['meta_keywords'] = $this->input->post('meta_keywords');
        $data['last_modified'] = strtotime(date('D, d-M-Y'));

        if ($this->input->post('is_top_course') != 1) {
            $data['is_top_course'] = 0;
        } else {
            $data['is_top_course'] = 1;
        }


        if ($type == "save_to_draft") {
            $data['status'] = 'draft';
        } else {
            if ($this->session->userdata('admin_login')) {
                $data['status'] = 'active';
            } else {
                $data['status'] = $course_details['status'];
            }
        }
        $this->db->where('id', $course_id);
        $this->db->update('course', $data);
        /*
        if($_POST){
            
        echo "<pre>";
        var_dump($_POST,$_FILES);
        var_dump($data);
        echo "</pre>";
        echo $this->db->last_query();
        exit;
        }*/
        // Upload different number of images according to activated theme. Data is taking from the config.json file
        $course_media_files = themeConfiguration(get_frontend_settings('theme'), 'course_media_files');
        foreach ($course_media_files as $course_media => $size) {
            if ($_FILES[$course_media]['name'] != "") {
                move_uploaded_file($_FILES[$course_media]['tmp_name'], 'uploads/thumbnails/course_thumbnails/' . $course_media . '_' . get_frontend_settings('theme') . '_' . $course_id . '.jpg');
            }
        }

        if ($data['status'] == 'active') {
            $this->session->set_flashdata('flash_message', get_phrase('course_updated_successfully'));
        } elseif ($data['status'] == 'pending') {
            $this->session->set_flashdata('flash_message', get_phrase('course_updated_successfully') . '. ' . get_phrase('please_wait_untill_Admin_approves_it'));
        } elseif ($data['status'] == 'draft') {
            $this->session->set_flashdata('flash_message', get_phrase('your_course_has_been_added_to_draft'));
        }
    }
	/*public function active_course($course_id){
		$data['status'] = 'active';
		$this->db->where('id', $course_id);
        $this->db->update('course', $data);
	}
	
	public function pending_course($course_id){
		$data['status'] = 'pending';
		$this->db->where('id', $course_id);
        $this->db->update('course', $data);
	}*/

    public function change_course_status($status = "", $course_id = "")
    {
        if ($status == 'active') {
            if ($this->session->userdata('admin_login') != true) {
                redirect(site_url('login'), 'refresh');
            }
        }
        $updater = array(
            'status' => $status
        );
        $this->db->where('id', $course_id);
        $this->db->update('course', $updater);
    }

    public function get_course_thumbnail_url($course_id, $type = 'course_thumbnail')
    {
        // Course media placeholder is coming from the theme config file. Which has all the placehoder for different images. Choose like course type.
        $course_media_placeholders = themeConfiguration(get_frontend_settings('theme'), 'course_media_placeholders');
        // if (file_exists('uploads/thumbnails/course_thumbnails/'.$type.'_'.get_frontend_settings('theme').'_'.$course_id.'.jpg')){
        //     return base_url().'uploads/thumbnails/course_thumbnails/'.$type.'_'.get_frontend_settings('theme').'_'.$course_id.'.jpg';
        // } elseif(file_exists('uploads/thumbnails/course_thumbnails/'.$course_id.'.jpg')){
        //     return base_url().'uploads/thumbnails/course_thumbnails/'.$course_id.'.jpg';
        // } else{
        //     return $course_media_placeholders[$type.'_placeholder'];
        // }
        if (file_exists('uploads/thumbnails/course_thumbnails/' . $type . '_' . get_frontend_settings('theme') . '_' . $course_id . '.jpg')) {
            return base_url() . 'uploads/thumbnails/course_thumbnails/' . $type . '_' . get_frontend_settings('theme') . '_' . $course_id . '.jpg';
        } else {
            return base_url() . $course_media_placeholders[$type . '_placeholder'];
        }
    }

    public function get_lesson_thumbnail_url($lesson_id)
    {

        if (file_exists('uploads/thumbnails/lesson_thumbnails/' . $lesson_id . '.jpg'))
            return base_url() . 'uploads/thumbnails/lesson_thumbnails/' . $lesson_id . '.jpg';
        else
            return base_url() . 'uploads/thumbnails/thumbnail.png';
    }

    public function get_my_courses_by_category_id($category_id)
    {
        $this->db->select('course_id');
        $course_lists_by_enrol = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id')))->result_array();
        $course_ids = array();
        foreach ($course_lists_by_enrol as $row) {
            if (!in_array($row['course_id'], $course_ids)) {
                array_push($course_ids, $row['course_id']);
            }
        }
        $this->db->where_in('id', $course_ids);
        $this->db->where('category_id', $category_id);
        return $this->db->get('course');
    }

    public function get_my_courses_by_search_string($search_string)
    {
        $this->db->select('course_id');
        $course_lists_by_enrol = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id')))->result_array();
        $course_ids = array();
        foreach ($course_lists_by_enrol as $row) {
            if (!in_array($row['course_id'], $course_ids)) {
                array_push($course_ids, $row['course_id']);
            }
        }
        $this->db->where_in('id', $course_ids);
        $this->db->like('title', $search_string);
        return $this->db->get('course');
    }

    public function get_courses_by_search_string($search_string)
    {
        $this->db->like('title', $search_string);
        $this->db->where('status', 'active');
        return $this->db->get('course');
    }


    public function get_course_by_id($course_id = "")
    {
        return $this->db->get_where('course', array('id' => $course_id));
    }

    public function delete_course($course_id)
    {
        $this->db->where('id', $course_id);
        $this->db->delete('course');
    }

    public function get_top_courses()
    {
		$q = $this->db->query('SELECT `id`, `title`, `short_description`, `description`, `outcomes`, `language`, `category_id`, `sub_category_id`, `section`, `requirements`, `price`, `discount_flag`, `discounted_price`, `level`, `user_id`, `thumbnail`, `video_url`, `date_added`, `last_modified`, `visibility`, `is_top_course`, `is_admin`, `status`, `course_overview_provider`, `meta_keywords`, `meta_description`, `is_free_course`,IFNULL(PERC,0)
FROM (SELECT `id`, `title`, `short_description`, `description`, `outcomes`, `language`, `category_id`, `sub_category_id`, `section`, `requirements`, `price`, `discount_flag`, `discounted_price`, `level`, `user_id`, `thumbnail`, `video_url`, `date_added`, `last_modified`, `visibility`, `is_top_course`, `is_admin`, `status`, `course_overview_provider`, `meta_keywords`, `meta_description`, `is_free_course`,
(
    SELECT ROUND(sum(rating)/count(1)) 
    FROM `rating`
	where course.id=rating.ratable_id
	group by ratable_id,ratable_type
) PERC FROM `course` )A
ORDER BY `PERC`  DESC
LIMIT 10');
		//var_dump($q->result());exit;
		return $q->result_array();
        //return $this->db->get_where('course', array('is_top_course' => 1, 'status' => 'active'));
    }

    public function get_default_category_id()
    {
        $categories = $this->get_categories()->result_array();
        foreach ($categories as $category) {
            return $category['id'];
        }
    }

    public function get_courses_by_user_id($param1 = "")
    {
        $courses['draft'] = $this->db->get_where('course', array('user_id' => $param1, 'status' => 'draft'));
        $courses['pending'] = $this->db->get_where('course', array('user_id' => $param1, 'status' => 'pending'));
        $courses['active'] = $this->db->get_where('course', array('user_id' => $param1, 'status' => 'active'));
        return $courses;
    }

    public function get_status_wise_courses($status = "")
    {
        if ($status != "") {
            $courses = $this->db->get_where('course', array('status' => $status));
        } else {
            $courses['draft'] = $this->db->get_where('course', array('status' => 'draft'));
            $courses['pending'] = $this->db->get_where('course', array('status' => 'pending'));
            $courses['active'] = $this->db->get_where('course', array('status' => 'active'));
        }
        return $courses;
    }

    public function get_status_wise_courses_for_instructor($status = "")
    {
        if ($status != "") {
            $this->db->where('status', $status);
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $courses = $this->db->get('course');
        } else {
            $this->db->where('status', 'draft');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $courses['draft'] = $this->db->get('course');

            $this->db->where('user_id', $this->session->userdata('user_id'));
            $this->db->where('status', 'draft');
            $courses['pending'] = $this->db->get('course');

            $this->db->where('status', 'draft');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $courses['active'] = $this->db->get_where('course');
        }
        return $courses;
    }

    public function get_default_sub_category_id($default_cateegory_id)
    {
        $sub_categories = $this->get_sub_categories($default_cateegory_id);
        foreach ($sub_categories as $sub_category) {
            return $sub_category['id'];
        }
    }

    public function get_instructor_wise_courses($instructor_id = "", $return_as = "")
    {
        $courses = $this->db->get_where('course', array('user_id' => $instructor_id));
        if ($return_as == 'simple_array') {
            $array = array();
            foreach ($courses->result_array() as $course) {
                if (!in_array($course['id'], $array)) {
                    array_push($array, $course['id']);
                }
            }
            return $array;
        } else {
            return $courses;
        }
    }
	public function getByLic($id=0,$where=''){
	    $str="
SELECT
	course.title,
	payment.`course_id`,
    payment.`payment_type`,
    payment.`amount`,
    payment.`date_added`,
    payment.`admin_revenue`,
    payment.`instructor_revenue`,
    payment.`instructor_payment_status`,
    payment.`user_course_id`,
    payment.`instructor_revenue_percentage`,
    payment.`instructor_payment_status`,
    enrol.discount_type,
    enrol.discount_code,
	users.first_name,last_name
FROM `payment` JOIN enrol
ON enrol.user_id=payment.`user_id`
AND enrol.course_id=payment.`course_id`
JOIN course
ON course.id=payment.course_id
JOIN users
ON payment.user_id=users.id
WHERE payment.course_id in (SELECT  `id`  FROM `course` WHERE `user_id`='".$id."')".(strlen($where)>0?' and '.$where:'');
//echo $str;
		$payment_histories = $this->db->query($str);
//echo $this->db->last_query();
return $payment_histories->result();
	}
    
	
	public function get_instructor_wise_payment_history($instructor_id = "")
    {
        $courses = $this->get_instructor_wise_courses($instructor_id, 'simple_array');
        if (sizeof($courses) > 0) {
            $this->db->where_in('course_id', $courses);
            return $this->db->get('payment')->result_array();
        } else {
            return array();
        }
    }

    public function add_section($course_id)
    {
        $data['title'] = html_escape($this->input->post('title'));
        $data['course_id'] = $course_id;
        $this->db->insert('section', $data);
        $section_id = $this->db->insert_id();

        $course_details = $this->get_course_by_id($course_id)->row_array();
        $previous_sections = json_decode($course_details['section']);

        if (sizeof($previous_sections) > 0) {
            array_push($previous_sections, $section_id);
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $course_id);
            $this->db->update('course', $updater);
        } else {
            $previous_sections = array();
            array_push($previous_sections, $section_id);
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $course_id);
            $this->db->update('course', $updater);
        }
    }

    public function edit_section($section_id)
    {
        $data['title'] = $this->input->post('title');
        $this->db->where('id', $section_id);
        $this->db->update('section', $data);
    }

    public function delete_section($course_id, $section_id)
    {
        $this->db->where('id', $section_id);
        $this->db->delete('section');

        $course_details = $this->get_course_by_id($course_id)->row_array();
        $previous_sections = json_decode($course_details['section']);

        if (sizeof($previous_sections) > 0) {
            $new_section = array();
            for ($i = 0; $i < sizeof($previous_sections); $i++) {
                if ($previous_sections[$i] != $section_id) {
                    array_push($new_section, $previous_sections[$i]);
                }
            }
            $updater['section'] = json_encode($new_section);
            $this->db->where('id', $course_id);
            $this->db->update('course', $updater);
        }
    }

    public function get_section($type_by, $id)
    {
        $this->db->order_by("order", "asc");
        if ($type_by == 'course') {
            return $this->db->get_where('section', array('course_id' => $id));
        } elseif ($type_by == 'section') {
            return $this->db->get_where('section', array('id' => $id));
        }
    }

    public function serialize_section($course_id, $serialization)
    {
        $updater = array(
            'section' => $serialization
        );
        $this->db->where('id', $course_id);
        $this->db->update('course', $updater);
    }

    public function add_lesson()
    {
        ini_set('memory_limit', '1024M');
        ini_set('post_max_size', '1024M');
        ini_set('upload_max_filesize', '1024M');
        $data['course_id'] = html_escape($this->input->post('course_id'));
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $lesson_type_array = explode('-', $this->input->post('lesson_type'));
        $lesson_type = $lesson_type_array[0];

        $data['attachment_type'] = $lesson_type_array[1];
        $data['lesson_type'] = $lesson_type;

        if ($lesson_type == 'video') {
            $lesson_provider = $this->input->post('lesson_provider');
            if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
                if ($this->input->post('video_url') == "" || $this->input->post('duration') == "") {
                    $this->session->set_flashdata('error_message', get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('video_url'));

                $duration_formatter = explode(':', $this->input->post('duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour . ':' . $min . ':' . $sec;

                $video_details = $this->video_model->getVideoDetails($data['video_url']);
                $data['video_type'] = $video_details['provider'];
            } elseif ($lesson_provider == 'html5') {
                if ($this->input->post('html5_duration') == "") {
                    $this->session->set_flashdata('error_message', get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
                }
                
                $fileName = $_FILES['html5_video_url']['name'];
                $tmp = explode('.', $fileName);
                $fileExtension = end($tmp);
                $uploadable_file = md5(uniqid(rand(), true));

                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['html5_video_url']['tmp_name'], 'uploads/lesson_files/' . $uploadable_file. '.' . $fileExtension);
                //echo 'uploads/lesson_files/' . $uploadable_file. '.' . $fileExtension;
                $data['video_type']='html5';
                $data['video_url']='uploads/lesson_files/' . $uploadable_file. '.' . $fileExtension;
                $json_translation=array();
                $files=$_FILES['html5_video_srt'];
                for($i=0;$i<sizeof($files);$i++){
                    $fileName = $files['name'][$i];
                    $tmp = explode('.', $fileName);
                    $fileLang=$tmp[0];
                    $fileExtension = end($tmp);
                    $uploadable_srt = $uploadable_file.''.$fileLang . '.' . $fileExtension;
                    if($fileLang=='')
                        continue;
                    $json_translation[$fileLang]=$uploadable_srt;
                    if (!file_exists('uploads/lesson_files')) {
                        mkdir('uploads/lesson_files', 0777, true);
                    }
                    move_uploaded_file($files['tmp_name'][$i], 'uploads/lesson_files/' . $uploadable_srt);
                    //chmod('uploads/lesson_files/' . $uploadable_srt, 0755);
                    //echo 'uploads/lesson_files/' .$uploadable_srt;
                }
                
                $data['attachment']=json_encode($json_translation);
                $duration_formatter = explode(':', $this->input->post('html5_duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour . ':' . $min . ':' . $sec;
                $data['video_type'] = 'html5';
                //var_dump($data);
            } else {
                $this->session->set_flashdata('error_message', get_phrase('invalid_lesson_provider'));
                redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
            }
        } else {
            if ($_FILES['attachment']['name'] == "") {
                $this->session->set_flashdata('error_message', get_phrase('invalid_attachment'));
                redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
            } else {
                $fileName = $_FILES['attachment']['name'];
                $tmp = explode('.', $fileName);
                $fileExtension = end($tmp);
                $uploadable_file = md5(uniqid(rand(), true)) . '.' . $fileExtension;
                $data['attachment'] = $uploadable_file;

                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/lesson_files/' . $uploadable_file);
            }
        }

        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = $this->input->post('summary');
		$data['preview'] = (isset($_POST['preview']))?true:false;
		//$data['preview'] = $this->input->post('preview');

        $this->db->insert('lesson', $data);
        $inserted_id = $this->db->insert_id();

        if ($_FILES['thumbnail']['name'] != "") {
            if (!file_exists('uploads/thumbnails/lesson_thumbnails')) {
                mkdir('uploads/thumbnails/lesson_thumbnails', 0777, true);
            }
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'uploads/thumbnails/lesson_thumbnails/' . $inserted_id . '.jpg');
        }
    }

    public function edit_lesson($lesson_id)
    {

        $previous_data = $this->db->get_where('lesson', array('id' => $lesson_id))->row_array();

        $data['course_id'] = html_escape($this->input->post('course_id'));
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $lesson_type_array = explode('-', $this->input->post('lesson_type'));
        $lesson_type = $lesson_type_array[0];

        $data['attachment_type'] = $lesson_type_array[1];
        $data['lesson_type'] = $lesson_type;

        if ($lesson_type == 'video') {
            $lesson_provider = $this->input->post('lesson_provider');
            if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
                if ($this->input->post('video_url') == "" || $this->input->post('duration') == "") {
                    $this->session->set_flashdata('error_message', get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('video_url'));

                $duration_formatter = explode(':', $this->input->post('duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour . ':' . $min . ':' . $sec;

                $video_details = $this->video_model->getVideoDetails($data['video_url']);
                $data['video_type'] = $video_details['provider'];
            } elseif ($lesson_provider == 'html5') {
                if ($this->input->post('html5_duration') == "") {
                    $this->session->set_flashdata('error_message', get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
                }
                
                if(isset($_FILES['html5_video_url'])){
                    $fileName = $_FILES['html5_video_url']['name'];
                    $tmp = explode('.', $fileName);
                    $fileExtension = end($tmp);
                    $uploadable_file = md5(uniqid(rand(), true));
    
                    if (!file_exists('uploads/lesson_files')) {
                        mkdir('uploads/lesson_files', 0777, true);
                    }
                    move_uploaded_file($_FILES['html5_video_url']['tmp_name'], 'uploads/lesson_files/' . $uploadable_file. '.' . $fileExtension);
                    $data['video_url']='uploads/lesson_files/' . $uploadable_file. '.' . $fileExtension;
                }
                //echo 'uploads/lesson_files/' . $uploadable_file. '.' . $fileExtension;
                $data['video_type']='html5';
                $json_translation=array();
                if(isset($_FILES['html5_video_srt'])){
                    $files=$_FILES['html5_video_srt'];
                    for($i=0;$i<sizeof($files);$i++){
                        $fileName = $files['name'][$i];
                        $tmp = explode('.', $fileName);
                        $fileLang=$tmp[0];
                        $fileExtension = end($tmp);
                        $uploadable_srt = $uploadable_file.''.$fileLang . '.' . $fileExtension;
                        if($fileLang=='')
                            continue;
                        $json_translation[$fileLang]=$uploadable_srt;
                        if (!file_exists('uploads/lesson_files')) {
                            mkdir('uploads/lesson_files', 0777, true);
                        }
                        move_uploaded_file($files['tmp_name'][$i], 'uploads/lesson_files/' . $uploadable_srt);
                        //chmod('uploads/lesson_files/' . $uploadable_srt, 0755);
                        //echo 'uploads/lesson_files/' .$uploadable_srt;
                    }
                    
                    //var_dump($json_translation); exit;
                    if(sizeof($json_translation)>0)
                    $data['attachment']=json_encode($json_translation);
                }

                $duration_formatter = explode(':', $this->input->post('html5_duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour . ':' . $min . ':' . $sec;
                $data['video_type'] = 'html5';

                if ($_FILES['thumbnail']['name'] != "") {
                    if (!file_exists('uploads/thumbnails/lesson_thumbnails')) {
                        mkdir('uploads/thumbnails/lesson_thumbnails', 0777, true);
                    }
                    move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'uploads/thumbnails/lesson_thumbnails/' . $lesson_id . '.jpg');
                }
            } else {
                $this->session->set_flashdata('error_message', get_phrase('invalid_lesson_provider'));
                redirect(site_url(strtolower($this->session->userdata('role')) . '/course_form/course_edit/' . $data['course_id']), 'refresh');
            }
            //$data['attachment'] = "";
        } else {
            if ($_FILES['attachment']['name'] != "") {
                // unlinking previous attachments
                if ($previous_data['attachment'] != "") {
                    unlink('uploads/lesson_files/' . $previous_data['attachment']);
                }

                $fileName = $_FILES['attachment']['name'];
                $tmp = explode('.', $fileName);
                $fileExtension = end($tmp);
                $uploadable_file = md5(uniqid(rand(), true)) . '.' . $fileExtension;
                $data['attachment'] = $uploadable_file;
                $data['video_type'] = "";
                $data['duration'] = "";
                $data['video_url'] = "";
                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/lesson_files/' . $uploadable_file);
            }
        }

        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = $this->input->post('summary');
		$data['preview'] = (isset($_POST['preview']))?true:false;

        $this->db->where('id', $lesson_id);
        $this->db->update('lesson', $data);
    }

    public function delete_lesson($lesson_id)
    {
        $this->db->where('id', $lesson_id);
        $this->db->delete('lesson');
    }

    public function update_frontend_settings()
    {
        $data['value'] = html_escape($this->input->post('banner_title'));
        $this->db->where('key', 'banner_title');
        $this->db->update('frontend_settings', $data);

        $data['value'] = html_escape($this->input->post('banner_sub_title'));
        $this->db->where('key', 'banner_sub_title');
        $this->db->update('frontend_settings', $data);


        $data['value'] = $this->input->post('about_us');
        $this->db->where('key', 'about_us');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('terms_and_condition');
        $this->db->where('key', 'terms_and_condition');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('privacy_policy');
        $this->db->where('key', 'privacy_policy');
        $this->db->update('frontend_settings', $data);
    }

    public function update_frontend_banner()
    {
        move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/system/home-banner.jpg');
    }

    public function update_light_logo()
    {
        move_uploaded_file($_FILES['light_logo']['tmp_name'], 'uploads/system/logo-light.png');
    }

    public function update_dark_logo()
    {
        move_uploaded_file($_FILES['dark_logo']['tmp_name'], 'uploads/system/logo-dark.png');
    }

    public function update_small_logo()
    {
        move_uploaded_file($_FILES['small_logo']['tmp_name'], 'uploads/system/logo-light-sm.png');
    }

    public function update_favicon()
    {
        move_uploaded_file($_FILES['favicon']['tmp_name'], 'uploads/system/favicon.png');
    }

    public function handleWishList($course_id,$state)
    {
        $wishlists = array();
        $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        
        if (!$this->session->userdata('wish_items')) {
            $this->session->set_userdata('wish_items', json_decode($user_details['wishlist']));
        }
        if ($user_details['wishlist'] == "") {
            array_push($wishlists, $course_id);
        } else {
            $wishlists = json_decode($user_details['wishlist']);
            if (in_array($course_id, $wishlists)) {
                $container = array();
                foreach ($wishlists as $key) {
                    if ($key != $course_id) {
                        array_push($container, $key);
                    }else if($state==1){
                        unset($wishlists[$key]);
                    }
                }
                $wishlists = $container;
                // $key = array_search($course_id, $wishlists);
                // unset($wishlists[$key]);
            } else {
                array_push($wishlists, $course_id);
            }
        }

        $updater['wishlist'] = json_encode($wishlists);
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('users', $updater);
        return $wishlists;
    }

    public function is_added_to_wishlist($course_id = "")
    {
        if ($this->session->userdata('user_login') == 1) {
            $wishlists = array();
            $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $wishlists = json_decode($user_details['wishlist']);
            if (in_array($course_id, $wishlists)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getWishLists()
    {
        $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        return json_decode($user_details['wishlist']);
    }

    public function get_latest_10_course()
    {
        $this->db->order_by("id", "desc");
        $this->db->limit('10');
        $this->db->where('status', 'active');
        return $this->db->get('course')->result_array();
    }


    /**
     * @param $catogryId
     * @return mixed
     */
    public function getCourseByCategory($categoryId)
    {
        $this->db->order_by("id", "desc");
        $this->db->where('status', 'active');
        $this->db->where('category_id', $categoryId);
        return $this->db->get('course')->result_array();
    }

    public function get_top_seller()
    {
        $this->db->select('course.*,COUNT(`course_id`) ac');
        $this->db->from('enrol');
        $this->db->join('course', 'enrol.course_id = course.id');
        $this->db->group_by('course_id');
        $this->db->order_by("ac", "desc");
        $this->db->limit(10);
        $query = $this->db->get();
        $resultsByGroup = $query->result_array();
        return $resultsByGroup;
    }


    public function get_recent_courses()
    {
        if ($this->session->userdata('user_login') == 1) {
            $this->db->select('course.*');
            $this->db->from('recent_courses');
            $this->db->join('course', 'recent_courses.course_id = course.id');
            $this->db->order_by("recent_courses.created_at", "desc");
            $this->db->limit(10);
            $query = $this->db->get();
            $resultsByGroup = $query->result_array();
            return $resultsByGroup;
        } else {
            return '';
        }

    }

    public function get_recommended_courses_by_your_search()
    {
        $latest_courses = $this->crud_model->get_recent_courses();
        $subCategories = array();
        foreach ($latest_courses as $latest_course) {
            if (!in_array($latest_course['sub_category_id'], $subCategories)) {
                array_push($subCategories, $latest_course['sub_category_id']);
            }
        }
        // $subCategories=array('2');
        $this->db->select('*');
        $this->db->from('course');
        $this->db->where_in('sub_category_id', $subCategories);
        $this->db->limit(10);
        $query = $this->db->get();
        $resultsByGroup = $query->result_array();
        return $resultsByGroup;
    }


    public function enrol_student($user_id)
    {


        $purchased_courses = $this->session->userdata('cart_items');
        foreach ($purchased_courses as $purchased_course) {
            $data['user_id'] = $user_id;
            $data['course_id'] = $purchased_course;
            $data['date_added'] = strtotime(date('D, d-M-Y'));
			
			if(isset($_SESSION['promotional'])){
				$data['discount_type'] = "promotional";
                $data['discount_code'] = $_SESSION['promotional'];
			}
			elseif(isset($_SESSION['code'])){
				$data['discount_type'] = "coupon";
                $data['discount_code'] = $_SESSION['code'];
			}
			
			if(isset($_SESSION['promotional']) && isset($_SESSION['code'])){
				$data['discount_type'] = "coupon";
                $data['discount_code'] = $_SESSION['code'];
			}
			
            $this->db->insert('enrol', $data);
            if ($this->session->userdata('code') != null) {
                $this->couponUse($this->session->userdata('code'));
            } else {
                $this->accounts($purchased_course);
            }
        }
    }

    public function enrol_a_student_manually()
    {
        $data['course_id'] = $this->input->post('course_id');
        $data['user_id'] = $this->input->post('user_id');
        if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
            $this->session->set_flashdata('error_message', get_phrase('student_has_already_been_enrolled_to_this_course'));
        } else {
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('enrol', $data);
            $this->session->set_flashdata('flash_message', get_phrase('student_has_been_enrolled_to_that_course'));
        }
    }

    public function enrol_to_free_course($course_id = "", $user_id = "")
    {
        $course_details = $this->get_course_by_id($course_id)->row_array();
        if ($course_details['is_free_course'] == 1) {
            $data['course_id'] = $course_id;
            $data['user_id'] = $user_id;
            if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
                $this->session->set_flashdata('error_message', get_phrase('student_has_already_been_enrolled_to_this_course'));
            } else {
                $data['date_added'] = strtotime(date('D, d-M-Y'));
                $this->db->insert('enrol', $data);
                $this->session->set_flashdata('flash_message', get_phrase('successfully_enrolled'));
            }
        } else {
            $this->session->set_flashdata('error_message', get_phrase('this_course_is_not_free_at_all'));
            redirect(site_url('home/course/' . slugify($course_details['title']) . '/' . $course_id), 'refresh');
        }

    }

    public function course_purchase($user_id, $method, $amount_paid)
    {
        $purchased_courses = $this->session->userdata('cart_items');
        foreach ($purchased_courses as $purchased_course) {
            $data['user_id'] = $user_id;
            $data['payment_type'] = $method;
            $data['course_id'] = $purchased_course;
            $course_details = $this->get_course_by_id($purchased_course)->row_array();
            if ($course_details['discount_flag'] == 1) {
                $data['amount'] = $course_details['discounted_price'];
            } else {
                $data['amount'] = $course_details['price'];
            }
			
			/*
            if (get_user_role('role_id', $course_details['user_id']) == 1) {
                $data['admin_revenue'] = $data['amount'];
                $data['instructor_revenue'] = 0;
                $data['instructor_payment_status'] = 1;
            } else {
                if (get_settings('allow_instructor') == 1) {
                    $instructor_revenue_percentage = get_settings('instructor_revenue');
                    $data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
                    $data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
                } else {
                    $data['instructor_revenue'] = 0;
                    $data['admin_revenue'] = $data['amount'];
                }
                $data['instructor_payment_status'] = 0;
            }
			*/
			$q=$this->db->query("select * from enrol where user_id='".$user_id."' and course_id='".$course_details['id']."'");
			$res=$q->result();
			
			$instructor_revenue_percentage=50; // No one make promosion
			$data['instructor_revenue'] = $data['admin_revenue'] = $data['amount']/2;
			$query=$this->db->query("SELECT `value` FROM `settings` WHERE `key`='instructor_revenue'");
			$instructor_revenue=$query->result();
			

			if(sizeof($res)>0){
				//var_dump($_SESSION);exit;
				if($res[0]->discount_type=='coupon'){
					$q1=$this->db->query("SELECT `role_id` FROM `users` WHERE `id` in (SELECT `user_id` FROM `coupons` WHERE `code`='".$res[0]->discount_code."')");
					$res1=$q1->result(); 
					
					$q2=$this->db->query("SELECT * FROM `coupons` WHERE `code`='".$res[0]->discount_code."'");
					$coupon_details=$q2->result();
					
					if($coupon_details[0]->i_factor==1){
						
						$data['amount'] = $course_details['price'] - ($course_details['price'] * $coupon_details[0]->percent / 100);
						if($res1[0]->role_id==2){// if instructor own the coupons
							$instructor_revenue_percentage=$instructor_revenue[0]->value;
						}
						else{// No one make coupons
							$instructor_revenue_percentage=100-$instructor_revenue[0]->value;
						}
						$data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
						$data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
					}
					else{
						$data['amount'] = $course_details['price'] -$coupon_details[0]->percent;
						if($res1[0]->role_id==2){// if instructor own the coupons
							$instructor_revenue_percentage=$instructor_revenue[0]->value;
						}
						else{// No one make coupons
							$instructor_revenue_percentage=100-$instructor_revenue[0]->value;
						}
						
						$data['instructor_revenue'] = ceil(($data['amount']-$coupon_details[0]->percent) * ($instructor_revenue_percentage / 100));
						$data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
						//var_dump($data,$instructor_revenue_percentage,$course_details['amount'],$coupon_details[0]->percent);exit;
					}
					
					//var_dump($this->session->userdata('type'));exit;
					if($this->session->userdata('type')=='specific'){

                        $this->session->unset_userdata('percent');
                        $this->session->unset_userdata('code');
                        $this->session->unset_userdata('type');
                        $this->session->unset_userdata('course_id');						
					}
							
				}
				elseif($res[0]->discount_type=='promotional'){
					$q1=$this->db->query("SELECT `role_id` FROM `users` WHERE `id` in (SELECT `user_id` FROM `promotional` WHERE `code`='".$res[0]->discount_code."')");
					$res1=$q1->result();
					if($res1[0]->role_id==2){// if instructor own the promotion
						$instructor_revenue_percentage=$instructor_revenue[0]->value;
					}
					else{// if admin own the promotion
						$instructor_revenue_percentage=100-$instructor_revenue[0]->value;
					}
					
					/*if($this->session->userdata('role_id')==2){
						$this->session->unset_userdata('promotional');
					}*/
					
					$data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
					$data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];	
				}
				if( isset($_SESSION['promotional']) && $res[0]->discount_type=='coupon' ){
					$q1=$this->db->query("SELECT `role_id` FROM `users` WHERE `id` in (SELECT `user_id` FROM `promotional` WHERE `code`='".$_SESSION['promotional']."')");
					$res1=$q1->result(); 
					//var_dump($res1);exit;
					$q2=$this->db->query("SELECT * FROM `coupons` WHERE `code`='".$res[0]->discount_code."'");
					$coupon_details=$q2->result();
					//var_dump($course_details['price']);exit;
					if($coupon_details[0]->i_factor==1){
						$data['amount'] = $course_details['price'] - ($course_details['price'] * $coupon_details[0]->percent / 100);
						
					}
					else{
						$data['amount'] = $course_details['price'] -$coupon_details[0]->percent;
					}
					
					if($res1[0]->role_id==2){// if instructor own the promotion
						$instructor_revenue_percentage=$instructor_revenue[0]->value;
					}
					else{// if admin own the promotion
						$instructor_revenue_percentage=100-$instructor_revenue[0]->value;
					}
					//var_dump($res1[0]->role_id,$instructor_revenue_percentage);exit;
					$data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
					$data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
					
					
					if($this->session->userdata('type')=='specific'){

                        $this->session->unset_userdata('percent');
                        $this->session->unset_userdata('code');
                        $this->session->unset_userdata('type');
                        $this->session->unset_userdata('course_id');						
					}
				}
				
			}
			$data['instructor_revenue_percentage'] = $instructor_revenue_percentage;
			$data['user_course_id'] = $course_details['user_id'];
            $data['date_added'] = strtotime(date('D, d-M-Y'));
			//var_dump($_SESSION);exit;
            $this->db->insert('payment', $data);
        }
    }

    public function get_default_lesson($section_id)
    {
        $this->db->order_by('order', "asc");
        $this->db->limit(1);
        $this->db->where('section_id', $section_id);
        return $this->db->get('lesson');
    }

    public function get_courses_by_wishlists()
    {
        $wishlists = $this->getWishLists();
        if (sizeof($wishlists) > 0) {
            $this->db->where_in('id', $wishlists);
            return $this->db->get('course')->result_array();
        } else {
            return array();
        }

    }


    public function get_courses_of_wishlists_by_search_string($search_string)
    {
        $wishlists = $this->getWishLists();
        if (sizeof($wishlists) > 0) {
            $this->db->where_in('id', $wishlists);
            $this->db->like('title', $search_string);
            return $this->db->get('course')->result_array();
        } else {
            return array();
        }
    }

    public function get_total_duration_of_lesson_by_course_id($course_id)
    {
        $total_duration = 0;
        $lessons = $this->crud_model->get_lessons('course', $course_id)->result_array();
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_type'] != "other") {
                $time_array = explode(':', $lesson['duration']);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }
        return gmdate("H:i:s", $total_duration) . ' ' . get_phrase('hours');
    }

    public function get_total_duration_of_lesson_by_section_id($section_id)
    {
        $total_duration = 0;
        $lessons = $this->crud_model->get_lessons('section', $section_id)->result_array();
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_type'] != 'other') {
                $time_array = explode(':', $lesson['duration']);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }
        return gmdate("H:i:s", $total_duration) . ' ' . get_phrase('hours');
    }

    public function rate($data)
    {
        if ($this->db->get_where('rating', array('user_id' => $data['user_id'], 'ratable_id' => $data['ratable_id'], 'ratable_type' => $data['ratable_type']))->num_rows() == 0) {
            $this->db->insert('rating', $data);
        } else {
            $checker = array('user_id' => $data['user_id'], 'ratable_id' => $data['ratable_id'], 'ratable_type' => $data['ratable_type']);
            $this->db->where($checker);
            $this->db->update('rating', $data);
        }
    }

    public function get_user_specific_rating($ratable_type = "", $ratable_id = "")
    {
        return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'user_id' => $this->session->userdata('user_id'), 'ratable_id' => $ratable_id))->row_array();
    }

    public function get_ratings($ratable_type = "", $ratable_id = "", $is_sum = false)
    {
        if ($is_sum) {
            $this->db->select_sum('rating');
            return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'ratable_id' => $ratable_id));

        } else {
            return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'ratable_id' => $ratable_id));
        }
    }

    public function get_instructor_wise_course_ratings($instructor_id = "", $ratable_type = "", $is_sum = false)
    {
        $course_ids = $this->get_instructor_wise_courses($instructor_id, 'simple_array');
        if ($is_sum) {
            $this->db->where('ratable_type', $ratable_type);
            $this->db->where_in('ratable_id', $course_ids);
            $this->db->select_sum('rating');
            return $this->db->get('rating');

        } else {
            $this->db->where('ratable_type', $ratable_type);
            $this->db->where_in('ratable_id', $course_ids);
            return $this->db->get('rating');
        }
    }

    public function get_percentage_of_specific_rating($rating = "", $ratable_type = "", $ratable_id = "")
    {
        $number_of_user_rated = $this->db->get_where('rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id' => $ratable_id
        ))->num_rows();

        $number_of_user_rated_the_specific_rating = $this->db->get_where('rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id' => $ratable_id,
            'rating' => $rating
        ))->num_rows();

        //return $number_of_user_rated.' '.$number_of_user_rated_the_specific_rating;
        if ($number_of_user_rated_the_specific_rating > 0) {
            $percentage = ($number_of_user_rated_the_specific_rating / $number_of_user_rated) * 100;
        } else {
            $percentage = 0;
        }
        return floor($percentage);
    }

    ////////private message//////
    function send_new_private_message()
    {
        $message = $this->input->post('message');
        $timestamp = strtotime(date("Y-m-d H:i:s"));

        $receiver = $this->input->post('receiver');
        $sender = $this->session->userdata('user_id');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->num_rows();
        $num2 = $this->db->get_where('message_thread', array('sender' => $receiver, 'receiver' => $sender))->num_rows();
        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender'] = $sender;
            $data_message_thread['receiver'] = $receiver;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($num1 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->row()->message_thread_code;
        if ($num2 > 0)
            $message_thread_code = $this->db->get_where('message_thread', array('sender' => $receiver, 'receiver' => $sender))->row()->message_thread_code;


        $data_message['message_thread_code'] = $message_thread_code;
        $data_message['message'] = $message;
        $data_message['sender'] = $sender;
        $data_message['timestamp'] = $timestamp;
        $this->db->insert('message', $data_message);

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code)
    {
        $message = html_escape($this->input->post('message'));
        $timestamp = strtotime(date("Y-m-d H:i:s"));
        $sender = $this->session->userdata('user_id');

        $data_message['message_thread_code'] = $message_thread_code;
        $data_message['message'] = $message;
        $data_message['sender'] = $sender;
        $data_message['timestamp'] = $timestamp;
        $this->db->insert('message', $data_message);
    }

    function mark_thread_messages_read($message_thread_code)
    {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code)
    {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('user_id');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
                $unread_message_counter++;
        }
        return $unread_message_counter;
    }

    public function get_last_message_by_message_thread_code($message_thread_code)
    {
        $this->db->order_by('message_id', 'desc');
        $this->db->limit(1);
        $this->db->where(array('message_thread_code' => $message_thread_code));
        return $this->db->get('message');
    }

    function curl_request($code = '')
    {
        /*
        $product_code = $code;

        $personal_token = "FkA9UyDiQT0YiKwYLK3ghyFNRVV9SeUn";
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $product_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer = 'bearer ' . $personal_token;
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:' . $product_code . '.json';
        $ch_verify = curl_init($verify_url . '?code=' . $product_code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        $response = json_decode($cinit_verify_data, true);

        if (count($response['verify-purchase']) > 0) {
            return true;
        } else {
            return false;
        }*/
        return true;
    }


    // version 1.3
    function get_currencies()
    {
        return $this->db->get('currency')->result_array();
    }

    function get_paypal_supported_currencies()
    {
        $this->db->where('paypal_supported', 1);
        return $this->db->get('currency')->result_array();
    }

    function get_stripe_supported_currencies()
    {
        $this->db->where('stripe_supported', 1);
        return $this->db->get('currency')->result_array();
    }

    // version 1.4
    function filter_course($selected_category_id = "", $selected_price = "", $selected_level = "", $selected_language = "", $selected_rating = "")
    {
        //echo $selected_category_id.' '.$selected_price.' '.$selected_level.' '.$selected_language.' '.$selected_rating;

        $course_ids = array();
        if ($selected_category_id != "all") {
            $category_details = $this->get_category_details_by_id($selected_category_id)->row_array();

            if ($category_details['parent'] > 0) {
                $this->db->where('sub_category_id', $selected_category_id);
            } else {
                $this->db->where('category_id', $selected_category_id);
            }
        }

        if ($selected_price != "all") {
            if ($selected_price == "paid") {
                $this->db->where('is_free_course', null);
            } elseif ($selected_price == "free") {
                $this->db->where('is_free_course', 1);
            }
        }

        if ($selected_level != "all") {
            $this->db->where('level', $selected_level);
        }

        if ($selected_language != "all") {
            $this->db->where('language', $selected_language);
        }
        $this->db->where('status', 'active');
        $courses = $this->db->get('course')->result_array();

        foreach ($courses as $course) {
            if ($selected_rating != "all") {
                $total_rating = $this->get_ratings('course', $course['id'], true)->row()->rating;
                $number_of_ratings = $this->get_ratings('course', $course['id'])->num_rows();
                if ($number_of_ratings > 0) {
                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                    if ($average_ceil_rating == $selected_rating) {
                        array_push($course_ids, $course['id']);
                    }
                }
            } else {
                array_push($course_ids, $course['id']);
            }
        }

        if (count($course_ids) > 0) {
            $this->db->where_in('id', $course_ids);
            return $this->db->get('course')->result_array();
        } else {
            return array();
        }
    }

    public function get_courses($category_id = "", $sub_category_id = "", $instructor_id = 0)
    {
        if ($category_id > 0 && $sub_category_id > 0 && $instructor_id > 0) {
            return $this->db->get_where('course', array('category_id' => $category_id, 'sub_category_id' => $sub_category_id, 'user_id' => $instructor_id));
        } elseif ($category_id > 0 && $sub_category_id > 0 && $instructor_id == 0) {
            return $this->db->get_where('course', array('category_id' => $category_id, 'sub_category_id' => $sub_category_id));
        } else {
            return $this->db->get('course');
        }
    }

    public function filter_course_for_backend($category_id, $instructor_id, $price, $status)
    {
        if ($category_id != "all") {
            $this->db->where('sub_category_id', $category_id);
        }

        if ($price != "all") {
            if ($price == "paid") {
                $this->db->where('is_free_course', null);
            } elseif ($price == "free") {
                $this->db->where('is_free_course', 1);
            }
        }

        if ($instructor_id != "all") {
            $this->db->where('user_id', $instructor_id);
        }

        if ($status != "all") {
            $this->db->where('status', $status);
        }
        return $this->db->get('course')->result_array();
    }

    public function sort_section($section_json)
    {
        $sections = json_decode($section_json);
        foreach ($sections as $key => $value) {
            $updater = array(
                'order' => $key + 1
            );
            $this->db->where('id', $value);
            $this->db->update('section', $updater);
        }
    }

    public function sort_lesson($lesson_json)
    {
        $lessons = json_decode($lesson_json);
        foreach ($lessons as $key => $value) {
            $updater = array(
                'order' => $key + 1
            );
            $this->db->where('id', $value);
            $this->db->update('lesson', $updater);
        }
    }

    public function sort_question($question_json)
    {
        $questions = json_decode($question_json);
        foreach ($questions as $key => $value) {
            $updater = array(
                'order' => $key + 1
            );
            $this->db->where('id', $value);
            $this->db->update('question', $updater);
        }
    }

    public function get_free_and_paid_courses($price_status = "", $instructor_id = "")
    {
        $this->db->where('status', 'active');
        if ($price_status == 'free') {
            $this->db->where('is_free_course', 1);
        } else {
            $this->db->where('is_free_course', null);
        }

        if ($instructor_id > 0) {
            $this->db->where('user_id', $instructor_id);
        }
        return $this->db->get('course');
    }

    // Adding quiz functionalities
    public function add_quiz($course_id = "")
    {
        $data['course_id'] = $course_id;
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $data['lesson_type'] = 'quiz';
        $data['duration'] = '00:00:00';
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = html_escape($this->input->post('summary'));
        $data['preview'] = (isset($_POST['preview']))?true:false;
        $this->db->insert('lesson', $data);
    }

    // updating quiz functionalities
    public function edit_quiz($lesson_id = "")
    {
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = html_escape($this->input->post('summary'));
        $data['preview'] = (isset($_POST['preview']))?true:false;
        $this->db->where('id', $lesson_id);
        $this->db->update('lesson', $data);
    }

    // Get quiz questions
    public function get_quiz_questions($quiz_id)
    {
        $this->db->order_by("order", "asc");
        $this->db->where('quiz_id', $quiz_id);
        return $this->db->get('question');
    }

    public function get_quiz_question_by_id($question_id)
    {
        $this->db->order_by("order", "asc");
        $this->db->where('id', $question_id);
        return $this->db->get('question');
    }

    // Add Quiz Questions
    public function add_quiz_questions($quiz_id)
    {
        $question_type = $this->input->post('question_type');
        if ($question_type == 'mcq') {
            $response = $this->add_multiple_choice_question($quiz_id);
            return $response;
        }
    }

    public function update_quiz_questions($question_id)
    {
        $question_type = $this->input->post('question_type');
        if ($question_type == 'mcq') {
            $response = $this->update_multiple_choice_question($question_id);
            return $response;
        }
    }

    // multiple_choice_question crud functions
    function add_multiple_choice_question($quiz_id)
    {
        if (sizeof($this->input->post('options')) != $this->input->post('number_of_options')) {
            return false;
        }
        foreach ($this->input->post('options') as $option) {
            if ($option == "") {
                return false;
            }
        }
        if (sizeof($this->input->post('correct_answers')) == 0) {
            // $correct_answers = [" "];
        } else {
            $correct_answers = $this->input->post('correct_answers');
        }
        $data['quiz_id'] = $quiz_id;
        $data['title'] = html_escape($this->input->post('title'));
        $data['number_of_options'] = html_escape($this->input->post('number_of_options'));
        $data['type'] = 'multiple_choice';
        $data['options'] = json_encode($this->input->post('options'));
        $data['correct_answers'] = json_encode($correct_answers);
        $this->db->insert('question', $data);
        return true;
    }

    // update multiple choice question
    function update_multiple_choice_question($question_id)
    {
        if (sizeof($this->input->post('options')) != $this->input->post('number_of_options')) {
            return false;
        }
        foreach ($this->input->post('options') as $option) {
            if ($option == "") {
                return false;
            }
        }

        if (sizeof($this->input->post('correct_answers')) == 0) {
            //  $correct_answers = [""];
        } else {
            $correct_answers = $this->input->post('correct_answers');
        }

        $data['title'] = html_escape($this->input->post('title'));
        $data['number_of_options'] = html_escape($this->input->post('number_of_options'));
        $data['type'] = 'multiple_choice';
        $data['options'] = json_encode($this->input->post('options'));
        $data['correct_answers'] = json_encode($correct_answers);
        $this->db->where('id', $question_id);
        $this->db->update('question', $data);
        return true;
    }

    function delete_quiz_question($question_id)
    {
        $this->db->where('id', $question_id);
        $this->db->delete('question');
        return true;
    }

    function get_application_details()
    {
        /*
        $purchase_code = get_settings('purchase_code');
        $returnable_array = array(
            'purchase_code_status' => get_phrase('not_found'),
            'support_expiry_date' => get_phrase('not_found'),
            'customer_name' => get_phrase('not_found')
        );

        $personal_token = "gC0J1ZpY53kRpynNe4g2rWT5s4MW56Zg";
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $purchase_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer = 'bearer ' . $personal_token;
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:' . $purchase_code . '.json';
        $ch_verify = curl_init($verify_url . '?code=' . $purchase_code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        $response = json_decode($cinit_verify_data, true);

        if (count($response['verify-purchase']) > 0) {

            //print_r($response);
            $item_name = $response['verify-purchase']['item_name'];
            $purchase_time = $response['verify-purchase']['created_at'];
            $customer = $response['verify-purchase']['buyer'];
            $licence_type = $response['verify-purchase']['licence'];
            $support_until = $response['verify-purchase']['supported_until'];
            $customer = $response['verify-purchase']['buyer'];

            $purchase_date = date("d M, Y", strtotime($purchase_time));

            $todays_timestamp = strtotime(date("d M, Y"));
            $support_expiry_timestamp = strtotime($support_until);

            $support_expiry_date = date("d M, Y", $support_expiry_timestamp);

            if ($todays_timestamp > $support_expiry_timestamp)
                $support_status = get_phrase('expired');
            else
                $support_status = get_phrase('valid');

            $returnable_array = array(
                'purchase_code_status' => $support_status,
                'support_expiry_date' => $support_expiry_date,
                'customer_name' => $customer
            );
        } else {
            $returnable_array = array(
                'purchase_code_status' => 'invalid',
                'support_expiry_date' => 'invalid',
                'customer_name' => 'invalid'
            );
        }
        */

        $returnable_array = array(
            'purchase_code_status' => get_phrase('expired'),
            'support_expiry_date' => date("d M, Y", strtotime("Wed Apr 08 20:40:09 +1000 2020")),
            'customer_name' => "MostafaOrabi"
        );

        return $returnable_array;
    }

    // Version 2.2 codes

    // This function is responsible for retreving all the language file from language folder
    function get_all_languages()
    {
        $language_files = array();
        $all_files = $this->get_list_of_language_files();
        foreach ($all_files as $file) {
            $info = pathinfo($file);
            if (isset($info['extension']) && strtolower($info['extension']) == 'json') {
                $file_name = explode('.json', $info['basename']);
                array_push($language_files, $file_name[0]);
            }
        }
        return $language_files;
    }

    // This function is responsible for showing all the installed themes
    function get_installed_themes($dir = APPPATH . '/views/frontend')
    {
        $result = array();
        $cdir = $files = preg_grep('/^([^.])/', scandir($dir));
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    array_push($result, $value);
                }
            }
        }
        return $result;
    }

    // This function is responsible for showing all the uninstalled themes inside themes folder
    function get_uninstalled_themes($dir = 'themes')
    {
        $result = array();
        $cdir = $files = preg_grep('/^([^.])/', scandir($dir));
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", "..", ".DS_Store"))) {
                array_push($result, $value);
            }
        }
        return $result;
    }

    // This function is responsible for retreving all the language file from language folder
    function get_list_of_language_files($dir = APPPATH . '/language', &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->get_list_of_directories_and_files($path, $results);
                $results[] = $path;
            }
        }
        return $results;
    }

    // This function is responsible for retreving all the files and folder
    function get_list_of_directories_and_files($dir = APPPATH, &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->get_list_of_directories_and_files($path, $results);
                $results[] = $path;
            }
        }
        return $results;
    }

    function remove_files_and_folders($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->remove_files_and_folders($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    function get_category_wise_courses($category_id = "")
    {
        $category_details = $this->get_category_details_by_id($category_id)->row_array();

        if ($category_details['parent'] > 0) {
            $this->db->where('sub_category_id', $category_id);
        } else {
            $this->db->where('category_id', $category_id);
        }
        $this->db->where('status', 'active');
        return $this->db->get('course');
    }

    function activate_theme($theme_to_active)
    {
        $data['value'] = $theme_to_active;
        $this->db->where('key', 'theme');
        $this->db->update('frontend_settings', $data);
    }

    // code of mark this lesson as completed
    function save_course_progress()
    {
        $lesson_id = $this->input->post('lesson_id');
        $progress = $this->input->post('progress');
        $user_id = $this->session->userdata('user_id');
        $crsres=$this->db->query("select id from lesson where course_id =(select course_id from lesson where id='".$lesson_id."')");
        //echo "select id from lesson where course_id =(select course_id from lesson where id='".$lesson_id."')";
        $lessons_arr=array();
        $progressl=0;
        foreach($crsres->result() as $row)
            array_push($lessons_arr,$row->id);
        $user_details = $this->user_model->get_all_user($user_id)->row_array();
        $watch_history = $user_details['watch_history'];
        
        $watch_history_array = array();
        if ($watch_history == '') {
            array_push($watch_history_array, array('lesson_id' => $lesson_id, 'progress' => $progress));
            $progressl=1;
        } else {
            $founder = false;
            $watch_history_array = json_decode($watch_history, true);
            for ($i = 0; $i < count($watch_history_array); $i++) {
                $watch_history_for_each_lesson = $watch_history_array[$i];
                if ($watch_history_for_each_lesson['lesson_id'] == $lesson_id) {
                    $watch_history_for_each_lesson['progress'] = $progress;
                    $watch_history_array[$i]['progress'] = $progress;
                    $founder = true;
                }
                if(in_array($watch_history_for_each_lesson['lesson_id'],$lessons_arr)){
                    $progressl++;
                }
            }
            if (!$founder) {
                array_push($watch_history_array, array('lesson_id' => $lesson_id, 'progress' => $progress));
                $progressl=1;
            }
        }
        $data['watch_history'] = json_encode($watch_history_array);
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        return ceil(($progressl/sizeof($lessons_arr))*100);
    }
    
    function getLastCert(){
        $this->db->select('*');
        $this->db->from('certificate');
        $this->db->order_by('pk_i_id','desc');
        $this->db->limit(1);
        $q=$this->db->get();
        return $q->result();
        
    }
    
    public function updateCert($dataArr)
    {
        $this->db->where('pk_i_id', $dataArr['pk_i_id']);
        $res=$this->db->update('certificate', $dataArr);
        
        if($res)
            return 1;
        return 0;
    }
    
    public function addCert($dataArr)
    {
        $res=$this->db->insert('certificate', $dataArr);
        
        if($res)
            return $this->db->insert_id();
        return 0;
    }
    
    
    public function addWedget($dataArr)
    {
        $res=$this->db->insert('widget', $dataArr);
        
        if($res)
            return $this->db->insert_id();
        return 0;
    }
    public function addUserCert($dataArr)
    {
        $res=$this->db->insert('user_certificate', $dataArr);
        if($res)
            return $this->db->insert_id();
        return 0;
    }
    function getCertUserCourse($user=0,$crs=0){
        $this->db->select('*');
        $this->db->from('user_certificate');
        $this->db->where('fk_i_user_id', $user);
        $this->db->where('fk_i_crs_id', $crs);
        $this->db->order_by('pk_i_id','desc');
        $this->db->limit(1);
        $q=$this->db->get();
        return $q->result();
        
    }
    
    function getwidget($id=0){
        $this->db->select('*');
        $this->db->from('widget');
        if($id>0)
            $this->db->where('pk_i_id', $id);
        $this->db->where('b_enabled', '1');
        $this->db->order_by('pk_i_id','desc');
        $q=$this->db->get();
        return $q->result();
        
    }
    public function updateWedget($dataArr)
    {
        $this->db->where('pk_i_id', $dataArr['pk_i_id']);
        $res=$this->db->update('widget', $dataArr);
        if($res)
            return 1;
        return 0;
    }
}
