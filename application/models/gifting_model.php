<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of restaurant_model
 *
 * @author stiucsib86
 */
class gifting_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->TAG = "gifting_model";
		$this->load->database();
		$this->load->config('tables/gifting', TRUE);
		$this->load->library('session');

		//initialize db tables data
		$this->tables = array_merge($this->config->item('tables', 'tables/gifting'));

	}

	public function get_gifting($fields = FALSE, $options = FALSE) {
		$query = $this->db->query("SELECT * FROM `gifting` WHERE `gifting_id`=$fields");
		$_gifting = $query->row_array();
		return $_gifting;
	}

	public function get_giftings($user_id) {
		$query = $this->db->query("SELECT * FROM `gifting` WHERE `sender_fb_id`=$user_id OR `receiver_fb_id` = $user_id");
		$_giftings = $query->result();
		return $_giftings;
	}

	public function set_gifting_send($fields = FALSE, $options = FALSE) {

		$gifting_obj = array();

		if(isset($fields["sender_fb_id"])){
			$gifting_obj["sender_fb_id"] = $fields["sender_fb_id"];
		}

		if(isset($fields["receiver_fb_id"])){
			$gifting_obj["receiver_fb_id"] = $fields["receiver_fb_id"];
		}

		if(isset($fields["gifting_url"])){
			$gifting_obj["gifting_url"] = $fields["gifting_url"];
		}

		$gifting_obj["status"] = 'sent';
		$this->db->insert($this->tables['gifting']['gifting'], $gifting_obj);
		return $this->db->insert_id();
	}

	public function set_gifting_accept($fields = FALSE, $options = FALSE) {

		$data = array();

		if(isset($fields['receiver_fb_id'])){
			$this->db->where('receiver_fb_id',$fields['receiver_fb_id']);
		} else{
			throw new Exception("Error: no user object found");
		}

		if(isset($fields['gifting_id'])){
			$this->db->where('gifting_id',$fields['gifting_id']);
		} else{
			throw new Exception("Error: no gifting ID found");
		}

		$data["status"] = "accepted";
		$data["thankyou_note"] = $fields["thankyou_note"];

		$this->db->update('gifting',$data);
		return $this->db->affected_rows();
	}
}

?>
