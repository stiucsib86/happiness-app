<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Gifting extends REST_Controller {


	public function __construct() {
		parent::__construct();
		$this->load->model('gifting_model');
		$this->load->model('notifications_model');
		$this->load->library('session');
		$this->_all_request_parameters = array_merge($this->input->get()? : array(), $this->args());
	}


	public function index_get() {
		try{
			if(!isset($this->_all_request_parameters["id"])) {
				$this->get_giftings();
			}
			$giftingId =  $this->_all_request_parameters["id"];
			$gifting = $this->gifting_model->get_gifting($giftingId);
			$this->response($gifting, 200);
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function get_giftings() {
		try{
			if(is_numeric($this->session->userdata("user_id"))){
				$user_id = $this->session->userdata("user_id");
			}
			$giftings = $this->gifting_model->get_giftings($user_id);
			$this->response($giftings, 200);
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function send_get() {

		$this->send_post();
	}

	public function send_post() {
		try{
			if(is_numeric($this->session->userdata("user_id"))){
				$this->_all_request_parameters["sender_fb_id"] = $this->session->userdata("user_id");
			}

			$gifting_id = $this->gifting_model->set_gifting_send($this->_all_request_parameters);
			$fields["user_id"] = $this->_all_request_parameters["receiver_fb_id"];
			$fields["message"] = "Hi! you have received a new gift! <a href='/dashboard/receive-gift/".$gifting_id."'>Click here</a> to check it out.";

			$this->notifications_model->create_notification($fields);
			$this->response($gifting_id, 200);

		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

	public function accept_get() {

		$this->accept_post();
	}

	public function accept_post() {
		try{
			if(is_numeric($this->session->userdata("user_id"))){
				$this->_all_request_parameters["receiver_fb_id"] = $this->session->userdata("user_id");
			}

			$gifting_id = $this->gifting_model->set_gifting_accept($this->_all_request_parameters);
			$this->response($gifting_id, 200);
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 404;
			$this->response($error_response, 404);
		}
	}

}