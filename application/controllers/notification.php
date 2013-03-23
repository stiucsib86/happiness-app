<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Notification extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load('main', 'english');
		$this->load->library('ion_auth');
		$this->load->model('notifications_model');

		$this->_all_request_parameters = array_merge($this->input->get()? : array(), $this->args());
	}

	public function index_get() {
		try {

			$notifications = $this->notifications_model->get_notification($this->_all_request_parameters);

			if ($notifications) {
				$this->response($notifications, 200); // 200 being the HTTP response code
			} else {
				$this->response(array('error' => 'Notification could not be found.'), 404);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function index_post() {
		try {

			$results = $this->notifications_model->update_notification($this->_all_request_parameters);

			if ($results) {
				$this->response($results, 200); // 200 being the HTTP response code
			} else {
				$this->response(array('error' => 'Error updating user info.'), 404);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function index_delete() {
		try {
			$user = $this->notifications_model->delete_notification($this->_all_request_parameters);

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

	public function create_sample_notification_get() {

		try {
			$this->_all_request_parameters['user_id'] = $this->session->userdata('user_id');
			$this->_all_request_parameters['message'] = "Lorem Ipsum";
			$result = $this->notifications_model->create_notification($this->_all_request_parameters);

			if ($result) {
				$this->response($result, 200); // 200 being the HTTP response code
			} else {
				$this->response(array('error' => 'Error creating notification.'), 404);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function delete_get() {
		$this->index_delete();
	}

	public function update_notification_get() {
		$this->index_post();
	}

	public function mark_as_read_get() {
		$this->_all_request_parameters['is_read'] = 1;
		$this->index_post();
	}

}

/* End of file user.php */
/* Location: ./application/controllers/api/user.php */