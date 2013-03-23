<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Notifications extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load('main', 'english');
		$this->load->library('ion_auth');
		$this->load->model('notifications_model');

		$this->_all_request_parameters = array_merge($this->input->get()? : array(), $this->args());
	}

	public function index_get() {
		try {
			$notifications = $this->notifications_model->get_notifications($this->_all_request_parameters);
			$this->response($notifications, 200); // 200 being the HTTP response code
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function unread_get() {
		$this->_all_request_parameters['is_read'] = 0;
		$this->index_get();
	}

}

/* End of file user.php */
/* Location: ./application/controllers/api/user.php */