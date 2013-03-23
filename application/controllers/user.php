<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load('main', 'english');
		$this->load->library('ion_auth');
		$this->load->library('user_library');

		$this->_all_request_parameters = array_merge($this->input->get()? : array(), $this->args());
	}

	public function index_get() {

		$user = $this->user_library->get_user($this->_all_request_parameters);

		if ($user) {
			$this->response($user, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'User could not be found.'), 404);
		}
	}

	public function index_delete() {

		try {
			$user = $this->user_library->delete_user($this->_all_request_parameters);

			if ($user) {
				$this->response($user, 200); // 200 being the HTTP response code
			} else {
				$this->response(array('error' => 'User could not be deleted.'), 404);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

}

/* End of file user.php */
/* Location: ./application/controllers/api/user.php */