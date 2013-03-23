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

		$this->_all_request_parameters = array_merge($this->input->get()? : array(), $this->args());
	}

	public function index_get() {

		if (isset($this->_all_request_parameters['user_ids'])) {
			if (is_array($this->_all_request_parameters['user_ids'])) {
				// Do nothing
			} elseif (strstr($this->_all_request_parameters['user_ids'], '[')) {
				//is an JSON array
				$this->_all_request_parameters['user_ids'] = json_decode($this->_all_request_parameters['user_ids']);
			} elseif (strstr($this->_all_request_parameters['user_ids'], ',')) {
				// might be comma separated
				$this->_all_request_parameters['user_ids'] = explode(',', $this->_all_request_parameters['user_ids']);
			}
		}

		//		$users = array();
		//		foreach ($this->_all_request_parameters['user_ids'] as $user_id) {
		//			if (is_numeric($user_id)) {
		//				unset($_fields);
		//				$_fields['user_id'] = $user_id;
		//				$users[] = $this->user_library->get_user($_fields);
		//			}
		//		}

		$users = $this->user_library->get_users($this->_all_request_parameters);

		if ($users) {
			$this->response($users, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'User(s) could not be found'), 404);
		}
	}

}

/* End of file users.php */
/* Location: ./application/controllers/api/users.php */