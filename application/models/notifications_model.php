<?php

/**
 * Description of users_oauth_model
 *
 * @author stiucsib86
 */
class notifications_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->TAG = "notification_model";

		$this->load->database();
		$this->load->config('tables/notifications', TRUE);
		$this->load->library('session');
		$this->load->library('user_library');

		//initialize db tables data
		$this->tables = array_merge($this->config->item('tables', 'tables/notifications'));

		// User must be logged in.
		if (!is_numeric($this->session->userdata('user_id'))) {
			throw new Exception("Error. User is not authorized.");
		}
	}

	public function get_notification($fields = FALSE, $options = FALSE) {

		if (!isset($fields['notification_id'])) {
			throw new Exception('Error. No Notification ID specified.');
		}

		$fields['user_id'] = $this->session->userdata('user_id');

		$this->_set_filters($fields);

		$query = $this->db->get($this->tables['notifications']['notifications']);
		$notification = $query->row_array();

		return $this->_format_notification($notification, $options);
	}

	public function get_notifications($fields = FALSE, $options = FALSE) {

		$fields['user_id'] = $this->session->userdata('user_id');

		$this->_set_filters($fields);

		$query = $this->db->get($this->tables['notifications']['notifications']);
		$notifications = $query->result_array();

		foreach ($notifications as $key => $notification) {
			$notifications[$key] = $this->_format_notification($notification, $options);
		}
		return $notifications;
	}

	public function create_notification($fields = FALSE, $options = FALSE) {

		if (!isset($fields['user_id'])) {
			throw new Exception("Error. No User Id specified.");
		}

		if (!isset($fields['message'])) {
			throw new Exception("Error. No message specified.");
		}

		$data['user_id'] = $fields['user_id'];
		$data['message'] = $fields['message'];

		$this->db->set('created_on', 'NOW()', FALSE);
		return $this->db->insert($this->tables['notifications']['notifications'], $data);
	}

	public function update_notification($fields = FALSE, $options = FALSE) {

		if (!isset($fields['notification_id'])) {
			throw new Exception('Error. No Notification ID specified.');
		}

		if (isset($fields['is_read'])) {
			$data['is_read'] = $fields['is_read'];
		}

		if (!isset($data)) {
			return FALSE;
		}

		// Exisiting entry exist.
		$this->db->where('notification_id', $fields['notification_id']);
		return $this->db->update($this->tables['notifications']['notifications'], $data);
	}

	public function delete_notification($fields = FALSE, $options = FALSE) {
		if (!isset($fields['notification_id']) || !is_numeric($fields['notification_id'])) {
			throw new Exception("Invalid Notification ID.");
		}

		$this->db->where('notification_id', $fields['notification_id']);
		$this->db->set('is_deleted', 1);
		return $this->db->update($this->tables['notifications']['notifications']);
	}

	private function _set_filters($fields = FALSE, $options = FALSE) {

		if (isset($fields['user_id'])) {
			// Lets set the user fb_uid too!
			// And this need to be done first, before the rest of the where clause.
			$_user = $this->user_library->get_user($fields);
			if (isset($_user['fb_uid']) && is_numeric($_user['fb_uid'])) {
				$_or_where[] = 'user_id = ' . $_user['fb_uid'];
			}
			if (is_numeric($fields['user_id'])) {
				$_or_where[] = 'user_id = ' . $fields['user_id'];
			}

			if (is_array($_or_where)) {
				$_or_where_text = '(' . implode(' OR ', $_or_where) . ')';
			}

			$this->db->where($_or_where_text, NULL, FALSE);
		}

		if (isset($fields['notification_id'])) {
			if (is_numeric($fields['notification_id'])) {
				$this->db->where('notification_id', $fields['notification_id']);
			}
		}

		if (isset($fields['is_read'])) {
			if (is_numeric($fields['is_read'])) {
				$this->db->where('is_read', $fields['is_read']);
			}
		}

		$this->db->where('is_deleted', 0);
	}

	private function _format_notification($fields = FALSE, $options = FALSE) {

		if (!$fields) {
			return false;
		}

		return $fields;
	}

}

?>