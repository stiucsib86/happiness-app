<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load('main', 'english');
		$this->load->library('ion_auth');
		$this->load->library('user_library');
		
		$this->_all_request_parameters = array_merge($this->input->get()?:array(), $this->args());
	}

	public function index_get() {
		
		$users = $this->user_library->get_users($this->_all_request_parameters);
		
		if ($users) {
			$this->response($users, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'User(s) could not be found'), 404);
		}
	}
	
	public function id_get($params = FALSE) {
		
		if (is_numeric($params)) {
			$users = $this->user_library->get_user($params);
		} else {
			//$users = $this->user_library->get_user($this->_all_request_parameters);
		}
		
		if ($users) {
			$this->response($users, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'User(s) could not be found'), 404);
		}
		
	}

}

/* End of file users.php */
/* Location: ./application/controllers/api/users.php */