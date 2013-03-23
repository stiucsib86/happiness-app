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
class restaurant_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->TAG = "restaurant_model";

		$this->load->database();
		$this->load->config('tables/restaurants', TRUE);
		$this->load->config('tables/settings', TRUE);
		$this->load->config('upload_paths', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->library('ion_auth');
		$this->load->library('user_library');
		$this->load->library('session');
		$this->load->model('favourites_model');
		$this->load->model('restaurant_branch_model');
		$this->load->model('restaurant_comment_model');
		$this->load->model('restaurant_opening_hour_model');
		$this->load->model('restaurant_promotion_model');
		$this->load->model('restaurant_recommendations_model');
		$this->load->model('restaurant_statistics_model');
		$this->load->model('xref_category_model');
		$this->load->model('xref_cuisine_model');
		$this->load->model('xref_location_model');
		$this->load->model('xref_price_group_model');

		// Load Amazon Configurations
		$this->load->spark('amazon-sdk/0.2.0');
		$this->load->config('awslib', TRUE);
		$this->storage = $this->config->item('storage', 'awslib');
		$this->s3 = $this->awslib->get_s3();
		// @TODO
		// Add SSL Cert and comment out the following.
		// For localhost http://stackoverflow.com/questions/11910697/amazon-s3-on-wamp-localhost-ssl-error
		//@$this->s3->disable_ssl_verification();
		//
		//initialize upload paths
		$this->upload_paths = $this->config->item('upload_paths', 'upload_paths');

		//initialize db tables data
		$this->tables = array_merge($this->config->item('tables', 'tables/restaurants'), $this->config->item('tables', 'tables/settings'));

		// Initialize pagination
		$this->pagination_config = array();
	}

	/**
	 * Get a restaurant detail.
	 *
	 * @param	array $fields
	 *
	 * @return	array
	 *
	 * */
	public function get_restaurant($fields = FALSE, $options = FALSE) {

		$this->_set_filters($fields, $options);

		$query = $this->db->get($this->tables['restaurants']['restaurants']);
		$_restaurant = $query->row_array();
		return $this->_format_restaurant($_restaurant, $options);
	}

	public function get_restaurants($fields = FALSE, $options = FALSE) {

		/*
		 * Notes by @stiucsib86.
		 * db->start_cache() is used so that we can run the same query for
		 * db->get, and db->count_all_results.
		 * Should disable this is count_all_results is not needed in the future,
		 * or when its no longer necessary
		 * to count for all results.
		 */

		//$this->db->start_cache();

		$this->_set_filters($fields, $options);

		$this->db->from($this->tables['restaurants']['restaurants']);

		//$this->db->stop_cache();

		$query = $this->db->get();
		$restaurants = $query->result_array();

		//$this->_count_all_results = $this->db->count_all_results();

		//$this->db->flush_cache();

		foreach ($restaurants as $key => $restaurant) {
			$restaurants[$key] = $this->_format_restaurant($restaurant, $options);
		}
		return $restaurants;
	}

	public function get_restaurant_unclaimed($fields = FALSE, $options = FALSE) {
		$fields['created_by'] = 0;
		$fields['is_approved'] = 'ANY';
		return $this->get_restaurant($fields, $options);
	}

	public function get_restaurants_unclaimed($fields = FALSE, $options = FALSE) {
		$fields['created_by'] = 0;
		$fields['is_approved'] = 'ANY';
		return $this->get_restaurants($fields, $options);
	}

	public function get_paging_info() {
		$_QUERY_STRING_ARR = $_GET;
		$_QUERY_STRING_ARR['page'] = $this->pagination_config['page'] + 1;
		$paging['next'] = base_url($_SERVER['PATH_INFO'] . '?' . http_build_query($_QUERY_STRING_ARR));
		$paging['current_page'] = $this->pagination_config['page'];
		$paging['count_all_results'] = $this->_count_all_results;

		if ($this->pagination_config['page'] * $this->pagination_config['limit'] < $paging['count_all_results']) {
			$paging['has_more_results'] = true;
		} else {
			$paging['has_more_results'] = false;
		}

		return $paging;
	}

	public function claim_restaurant($fields = FALSE, $options = FALSE) {

		if (!$this->ion_auth->logged_in() || !is_numeric($this->session->userdata('user_id'))) {
			throw new Exception("Invalid user permission.");
		}

		if (!isset($fields['restaurant_id']) || !is_numeric($fields['restaurant_id'])) {
			throw new Exception("Invalid Restaurant ID.");
		}

		$_fields['restaurant_id'] = $fields['restaurant_id'];
		$_restaurant = $this->get_restaurant_unclaimed($_fields, $options);

		if ($_restaurant && $_restaurant['created_by']) {
			throw new Exception('Error. This restaurant has been claimed.');
		}

		$data['created_by'] = $this->session->userdata('user_id');
		$this->db->where('restaurant_id', $fields['restaurant_id']);
		return $this->db->update($this->tables['restaurants']['restaurants'], $data);
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @param	array $fields
	 *
	 * @return	bool
	 *
	 * */
	public function update_restaurant($fields = FALSE, $options = FALSE) {

		if (!$fields) {
			throw new Exception("Invalid input parameters");
		}

		if (!isset($options['access']) || !$options['access'] = 'manage') {
			throw new Exception('You have no permission to update this restaurant.');
			// @TODO add permission checking.
		}

		if (isset($fields['restaurant_id'])) {
			if (!is_numeric($fields['restaurant_id'])) {
				throw new Exception("Invalid Restaurant ID");
			}
		}

		$data = array();
		if (isset($fields['name'])) {
			$data['name'] = ($fields['name']);
		}
		if (isset($fields['address'])) {
			$data['address'] = ($fields['address']);
		}
		if (isset($fields['postal_code'])) {
			$data['postal_code'] = ($fields['postal_code']);
		}
		if (isset($fields['website'])) {
			$data['website'] = ($fields['website']);
		}
		if (isset($fields['contact_number'])) {
			$data['contact_number'] = ($fields['contact_number']);
		}
		if (isset($fields['description'])) {
			$data['description'] = ($fields['description']);
		}
		if (isset($fields['price_range'])) {
			if (isset($fields['price_range']['price_group_id']) && is_numeric($fields['price_range']['price_group_id'])) {
				$data['price_range'] = ($fields['price_range']['price_group_id']);
			}
		}

		if (isset($fields['cuisine'])) {
			if (isset($fields['cuisine']['cuisine_id']) && is_numeric($fields['cuisine']['cuisine_id'])) {
				$data['cuisine'] = ($fields['cuisine']['cuisine_id']);
			}
		}
		if (isset($fields['location'])) {
			if (isset($fields['location']['location_id']) && is_numeric($fields['location']['location_id'])) {
				$data['location'] = ($fields['location']['location_id']);
			}
		}
		if (isset($fields['reservation_policy'])) {
			$data['reservation_policy'] = ($fields['reservation_policy']);
		}
		if (isset($fields['is_saved'])) {
			$data['is_saved'] = ($fields['is_saved']);
		}

		// Update restaurant Administrators
		if (isset($fields['admins'])) {
			if (!is_array($fields['admins'])) {
				$fields['admins'] = array($fields['admins']);
			}
			foreach ($fields['admins'] as $admin) {
				if (!isset($admin['restaurant_id'])) {
					$admin['restaurant_id'] = $fields['restaurant_id'];
				}
				$this->update_restaurant_admin($admin);
			}
		}

		// Approve Restaurant
		if (isset($fields['is_approved'])) {
			if ($this->ion_auth->is_admin()) {
				$data['is_approved'] = ($fields['is_approved']) ? 1 : 0;
			}
		}

		if (!isset($fields['restaurant_id'])) {
			// If user is not admin, user_id must be the user himself
			if (!$this->ion_auth->is_admin()) {
				$this->db->set('created_by', $this->session->userdata('user_id'));
			}
			// Create on
			$this->db->set('created_on', 'NOW()', FALSE);
			// Restaurant Name
			$data['name'] = isset($data['name']) ? $data['name'] : '';

			$this->db->insert($this->tables['restaurants']['restaurants'], $data);
			$restaurant['restaurant_id'] = $this->db->insert_id();
			if (is_numeric($restaurant['restaurant_id'])) {
				return $this->get_restaurant($restaurant, $options);
			}
			return false;
		} else {
			$this->db->set('is_deleted', 0);
			$this->db->where('restaurant_id', $fields['restaurant_id']);
			return $this->db->update($this->tables['restaurants']['restaurants'], $data);
		}
	}

	public function update_restaurant_admin($fields = FALSE) {

		if (!$fields) {
			throw new Exception("Invalid input parameters");
		}

		if (isset($fields['restaurant_id'])) {
			if (!is_numeric($fields['restaurant_id'])) {
				throw new Exception("Invalid Restaurant ID");
			}
		}

		// Update Admin
		if (isset($fields['user_id'])) {
			$data['user_id'] = ($fields['user_id']);
		}
		if (isset($fields['first_name'])) {
			$data['first_name'] = ($fields['first_name']);
		}
		if (isset($fields['last_name'])) {
			$data['last_name'] = ($fields['last_name']);
		}
		if (isset($fields['email'])) {
			$data['email'] = ($fields['email']);
		}
		if (isset($fields['contact_number'])) {
			$data['contact_number'] = ($fields['contact_number']);
		}

		if (!(isset($data) && is_array($data) && count($data) > 0)) {
			return false;
		}

		if (!isset($fields['restaurant_admins_id']) || !is_numeric($fields['restaurant_admins_id'])) {
			$data['restaurant_id'] = $fields['restaurant_id'];
			if ($this->db->insert($this->tables['restaurants']['admins'], $data)) {
				$_return_data['restaurant_admins_id'] = $this->db->insert_id();
				return $_return_data;
			}
		} else {
			$this->db->where('restaurant_admins_id', $fields['restaurant_admins_id']);
			if ($this->db->update($this->tables['restaurants']['admins'], $data)) {
				$_return_data['restaurant_admins_id'] = $fields['restaurant_admins_id'];
				return $_return_data;
			}
		}

		return false;
	}

	public function delete_restaurant_admin($fields = FALSE) {

		if (isset($fields['restaurant_admins_id'])) {
			if (!is_numeric($fields['restaurant_admins_id'])) {
				throw new Exception("Invalid Restaurant Admin ID");
			}
		}

		$this->db->where('restaurant_admins_id', $fields['restaurant_admins_id']);
		return $this->db->delete($this->tables['restaurants']['admins']);
	}

	public function update_restaurant_image($fields = FALSE, $options = FALSE) {

		if (!isset($fields['restaurant_id']) || !is_numeric($fields['restaurant_id'])) {
			throw new Exception("Invalid Restaurant ID");
		}

		if (!isset($fields['full_path']) || !file_exists($fields['full_path'])) {
			throw new Exception("Invalid Restaurant picture");
		}

		$options['access'] = 'manage';

		if (!$this->_has_manage_permission($fields, $options)) {
			throw new Exception("Invalid Permission.");
		}

		$this->db->where('restaurant_id', $fields['restaurant_id']);
		$query = $this->db->get($this->tables['restaurants']['restaurants']);
		$restaurant = $query->row_array();

		if (!$restaurant) {
			throw new Exception('No restaurant found.');
		}

		// Resize uploaded image, if image is less than 2MB.
		// Server cannot resize image bigger than 2MB.
		if (filesize($fields['full_path']) < 2097152) {
			$this->_resize_and_crop_image($fields['full_path']);
		}

		if ($this->storage['enable_s3']) {
			// ---- Store on Amazon S3 ----
			// Delete existing S3 object
			$this->_update_restaurant_image_remove_from_storage($restaurant);
			// Upload image to S3
			$new_name = $this->_update_restaurant_image_upload_to_storage($fields, $options);
		} else {
			// ---- Store on server ----
			// Delete old picture
			$existing_picture = ($fields['file_path'] . $restaurant['picture']);
			if (file_exists($existing_picture) && !is_dir($existing_picture)) {
				unlink($existing_picture);
			}

			//Rename Uploaded Picture
			$new_name = $fields['restaurant_id'] . '_' . $fields['file_name'];
			$new_full_path = $fields['file_path'] . $new_name;
			rename($fields['full_path'], $new_full_path);
		}

		// Update Database
		$data['picture'] = $new_name;
		$this->db->where('restaurant_id', $fields['restaurant_id']);
		return $this->db->update($this->tables['restaurants']['restaurants'], $data);
	}

	public function update_restaurant_image_from_url($fields = FALSE, $options = FALSE) {
		if (!isset($fields['restaurant_id'])) {
			throw new Exception('Invalid Restaurant ID.');
		}

		if (!isset($fields['url'])) {
			throw new Exception('Invalid image url.');
		}

		try {
			$image_data = file_get_contents($fields['url']);
			$fields['file_name'] = basename($fields['url']);
			$fields['file_path'] = $this->upload_paths['restaurants']['picture'];
			$fields['full_path'] = $fields['file_path'] . $fields['file_name'];
			file_put_contents($fields['full_path'], $image_data);

			return $this->update_restaurant_image($fields, $options);
		} catch (Exception $e) {

		}
	}

	/**
	 * Upload image to online storage and return the file URL
	 *
	 * @param array $fields
	 * @param array $options
	 * @throws Exception
	 */
	private function _update_restaurant_image_upload_to_storage($fields = FALSE, $options = FALSE) {

		if (!isset($fields['full_path']) || !file_exists($fields['full_path'])) {
			throw new Exception("Invalid Restaurant picture");
		}

		if (!isset($fields['file_name'])) {
			$fields['file_name'] = basename($fields['full_path']);
		}

		$fields['restaurant_image_s3_object_name'] = $this->_get_restaurant_image_bucket_name($fields);

		$result = $this->s3->create_object($this->storage['bucket'], $fields['restaurant_image_s3_object_name'], array(
			'fileUpload' => $fields['full_path'],
			'acl' => AmazonS3::ACL_PUBLIC
		));
		if ($result->isOK()) {
			$file_url = $this->s3->get_object_url($this->storage['bucket'], $fields['restaurant_image_s3_object_name']);
		}

		// Delete uploaded file on server.
		if (file_exists($fields['full_path']) && !is_dir($fields['full_path'])) {
			unlink($fields['full_path']);
		}

		return $file_url ? $file_url : '';
	}

	private function _update_restaurant_image_remove_from_storage($fields = FALSE, $options = FALSE) {

		if (!isset($fields['restaurant_id']) || !is_numeric($fields['restaurant_id'])) {
			return FALSE;
		}

		if (!isset($fields['picture'])) {
			return FALSE;
		}

		$fields['file_name'] = basename($fields['picture']);
		$fields['restaurant_image_s3_object_name'] = $this->_get_restaurant_image_bucket_name($fields);

		$result = $this->s3->delete_object($this->storage['bucket'], $fields['restaurant_image_s3_object_name']);

		return $result->isOK() ? TRUE : FALSE;
	}

	private function _get_restaurant_image_bucket_name($fields = FALSE) {

		if (!isset($fields['restaurant_id']) || !is_numeric($fields['restaurant_id'])) {
			throw new Exception('Invalid Restaurant ID.');
		}

		return $this->upload_paths['restaurants']['picture_bucket_folder'] . $fields['restaurant_id'] . '/' . $fields['file_name'];
	}

	/**
	 * Delete a restaurant
	 *
	 * @param	array $fields
	 *
	 * @return	bool
	 *
	 * */
	public function delete_restaurant($fields = FALSE, $options = FALSE) {

		if (!isset($fields['restaurant_id']) || !is_numeric($fields['restaurant_id'])) {
			throw new Exception("Invalid Restaurant ID.");
		}

		if (!$this->_has_manage_permission($fields, $options)) {
			throw new Exception("Invalid Permission.");
		}

		$this->db->where('restaurant_id', $fields['restaurant_id']);
		$this->db->set('is_deleted', 1);
		return $this->db->update($this->tables['restaurants']['restaurants']);
	}

	/**
	 * Delete a restaurant image
	 *
	 * @param	array $fields
	 *
	 * @return	bool
	 *
	 * */
	public function delete_restaurant_image($fields = FALSE) {

		if (!isset($fields['restaurant_id']) || !is_numeric($fields['restaurant_id'])) {
			throw new Exception("Invalid Restaurant ID");
		}

		$this->db->where('restaurant_id', $fields['restaurant_id']);
		$query = $this->db->get($this->tables['restaurants']['restaurants']);
		$restaurant = $query->row_array();

		if (!$restaurant) {
			throw new Exception('No restaurant found.');
		}

		// Delete Image
		if ($this->storage['enable_s3']) {
			$this->_update_restaurant_image_remove_from_storage($restaurant);
		} else {
			if (file_exists($this->upload_paths['restaurants']['picture'] . $restaurant['picture'])) {
				unlink($this->upload_paths['restaurants']['picture'] . $restaurant['picture']);
			}
		}

		// Update Database
		$data['picture'] = '';
		$this->db->where('restaurant_id', $fields['restaurant_id']);
		return $this->db->update($this->tables['restaurants']['restaurants'], $data);
	}

	private function _set_filters($fields = FALSE, $options = FALSE) {

		foreach ($fields as $key => $value) {
			if ($value === 'undefined') {
				unset($fields[$key]);
			}
		}

		/*
		 * When managing restaurant,
		 * return only users' restaurant if user is not admin
		 */
		if (isset($options['access']) && $options['access'] == 'manage') {
			// @TODO, need to check on the permission for this.
			if (isset($fields['created_by']) && is_numeric($fields['created_by'])) {
				$this->db->where('created_by', $fields['created_by']);
			} else if (!$this->ion_auth->is_admin()) {
				$this->db->where('created_by', $this->session->userdata('user_id'));
			}
		}

		/*
		 * Return only approved restaurant if user is not admin or owner.
		 */
		if (!$this->ion_auth->is_admin()) {
			if ((isset($options['access']) && $options['access'] == 'manage')) {
				$this->db->where('is_saved', '1');
			} else {
				// Public Access
				if (!isset($fields['is_approved']) || $fields['is_approved'] != 'ANY') {
					$_query_string = '(is_approved = 1 OR created_by = 0)';
					$this->db->where($_query_string);
				} else if (is_numeric($fields['is_approved'])) {
					$this->db->where('is_approved', $fields['is_approved']);
				}
			}
		}

		if (isset($fields['is_approved']) && $fields['is_approved'] == '1') {
			$this->db->where('is_approved', '1');
		} else if (isset($fields['is_approved']) && $fields['is_approved'] == '0') {
			$this->db->where('is_approved', '0');
		}

		if (isset($fields['not_by_admin']) && $fields['not_by_admin'] == 1) {
			$this->db->where('created_by !=', '0');
		}

		/*
		 * Filter for a single restaurant
		 */
		if (isset($fields['restaurant_id'])) {
			if (is_numeric($fields['restaurant_id'])) {
				$this->db->where('restaurant_id', $fields['restaurant_id']);
			}
		}
		if (isset($fields['restaurant_id_not'])) {
			if (is_numeric($fields['restaurant_id_not'])) {
				$this->db->where('restaurant_id !=', $fields['restaurant_id_not']);
			}
		}
		if (isset($fields['name']) && $fields['name'] != 'undefined') {
			$this->db->like('name', $fields['name']);
		}
		if (isset($fields['address'])) {
			$this->db->where('address', $fields['address']);
		}
		if (isset($fields['postal_code'])) {
			$this->db->where('postal_code', $fields['postal_code']);
		}

		/*
		 * Filters for multiple restaurant
		 */
		if (isset($fields['restaurant_ids'])) {
			if (is_numeric($fields['restaurant_ids'])) {
				$fields['restaurant_ids'] = array($fields['restaurant_ids']);
			}
			foreach ($fields['restaurant_ids'] as $restaurant_id) {
				if (is_numeric($restaurant_id)) {
					$this->db->or_where('restaurant_id', $restaurant_id);
				}
			}
		}

		/*
		 * Keywords
		 */
		$_restaurant_fields = array('name', 'address', 'postal_code', 'postal_code', 'website', 'contact_number');
		if (isset($fields['keywords']) && trim($fields['keywords']) != FALSE) {
			$_query_string = '';
			foreach ($_restaurant_fields as $_r_field) {
				if ($_query_string) {
					$_query_string .= ' OR ';
				}
				$_query_string .= $_r_field . ' LIKE "%' . mysql_real_escape_string($fields['keywords']) . '%"';
			}
			$this->db->where('(' . $_query_string . ')');
		}

		if (isset($fields['cuisine_id']) && is_numeric($fields['cuisine_id'])) {
			$this->db->where('cuisine', $fields['cuisine_id']);
		}

		if (isset($fields['location_id']) && is_numeric($fields['location_id'])) {
			$this->db->where('location', $fields['location_id']);
		}

		if (isset($fields['price_group_id']) && is_numeric($fields['price_group_id'])) {
			$this->db->where('price_range', $fields['price_group_id']);
		}

		/*
		 * Array filters
		 */
		$_or_where_strings = array();
		if (isset($fields['cuisine_ids'])) {
			if (is_string($fields['cuisine_ids']) && strpos($fields['cuisine_ids'], ',')) {
				$fields['cuisine_ids'] = explode(',', $fields['cuisine_ids']);
			}
			if (!is_array($fields['cuisine_ids'])) {
				$fields['cuisine_ids'] = array($fields['cuisine_ids']);
			}
			foreach ($fields['cuisine_ids'] as $cuisine_id) {
				if (is_numeric($cuisine_id)) {
					$_or_where_strings[] = ('cuisine = ' . $cuisine_id);
				}
			}
		}
		if (isset($fields['location_ids'])) {
			if (is_string($fields['location_ids']) && strpos($fields['location_ids'], ',')) {
				$fields['location_ids'] = explode(',', $fields['location_ids']);
			}
			if (!is_array($fields['location_ids'])) {
				$fields['location_ids'] = array($fields['location_ids']);
			}
			foreach ($fields['location_ids'] as $location_id) {
				if (is_numeric($location_id)) {
					$_or_where_strings[] = ('location = ' . $location_id);
				}
			}
		}
		if (isset($fields['price_group_ids'])) {
			if (is_string($fields['price_group_ids']) && strpos($fields['price_group_ids'], ',')) {
				$fields['price_group_ids'] = explode(',', $fields['price_group_ids']);
			}
			if (!is_array($fields['price_group_ids'])) {
				$fields['price_group_ids'] = array($fields['price_group_ids']);
			}
			foreach ($fields['price_group_ids'] as $price_group_id) {
				if (is_numeric($price_group_id)) {
					$_or_where_strings[] = ('price_range = ' . $price_group_id);
				}
			}
		}

		if (is_array($_or_where_strings) && sizeof($_or_where_strings)) {
			$_or_where_strings = '(' . implode(' OR ', $_or_where_strings) . ')';
			$this->db->where($_or_where_strings);
		}

		/*
		 * Important: Check if is not deleted.
		 */
		if (!isset($fields['is_deleted'])) {
			$this->db->where('is_deleted', 0);
		}

		$this->_set_pagination($fields, $options);
	}

	private function _set_pagination($fields = FALSE, $options = FALSE) {

		if (!isset($this->pagination_config)) {
			$this->pagination_config = array();
		}

		$this->pagination_config['limit'] = 10;

		if (!isset($fields['page']) || !is_numeric($fields['page'])) {
			$this->pagination_config['page'] = 1;
		} else {
			$this->pagination_config['page'] = $fields['page'];
		}

		if (isset($fields['limit']) && !is_numeric($fields['limit'])) {
			$this->pagination_config['limit'] = $fields['limit'];
		}

		$this->pagination_config['offset'] = ($this->pagination_config['page'] - 1) * $this->pagination_config['limit'];

		$this->db->limit($this->pagination_config['limit'], $this->pagination_config['offset']);
	}

	private function _format_restaurant($restaurant = FALSE, $options = FALSE) {

		if (!$restaurant) {
			return $restaurant;
		}

		if (!is_numeric($restaurant['restaurant_id'])) {
			return $restaurant;
		}

		if (isset($options['access']) && $options['access'] == 'manage') {
			$options['details'] = 'SIMPLE';
		}

		if (!isset($options['display_fields'])) {
			$options['display_fields'] = array();
		}

		// Fields display options.
		if (isset($options['details']) && $options['details'] == 'XSIMPLE') {
			$_display_fields = array('admins', '_permissions');
		} else if (isset($options['details']) && $options['details'] == 'SIMPLE') {
			$_display_fields = array('admins', '_permissions', 'opening_hours', 'category', 'cuisine', 'location', 'price_range');
		} else {
			// Default. Show all fields.
			// Temporary disabled 'branches' field.
			$_display_fields = array('admins', '_permissions', 'opening_hours', 'category', 'cuisine', 'location', 'price_range', 'statistics', 'promotion', 'comments', 'recommendations');
		}

		$_display_fields = array_unique(array_merge($_display_fields, $options['display_fields']));

		$_restaurant['restaurant_id'] = $restaurant['restaurant_id'];
		$_restaurant['name'] = $restaurant['name'];
		$_restaurant['url'] = base_url('/restaurants/view/' . $restaurant['restaurant_id']);
		$_restaurant['created_on_display_text'] = Date('d F Y', strtotime($restaurant['created_on']));
		$_restaurant['address'] = $restaurant['address'];
		$_restaurant['postal_code'] = $restaurant['postal_code'];
		$_restaurant['website'] = $restaurant['website'];
		$_restaurant['contact_number'] = $restaurant['contact_number'];
		$_restaurant['nearest_mrt'] = $restaurant['nearest_mrt'];
		$_restaurant['reservation_policy'] = $restaurant['reservation_policy'];
		$_restaurant['created_by'] = $restaurant['created_by'];
		$_restaurant['created_on'] = $restaurant['created_on'];
		$_restaurant['updated_on'] = $restaurant['updated_on'];
		$_restaurant['description'] = $restaurant['description'];
		$_restaurant['picture'] = $restaurant['picture'];
		// Get Restaurant Image Full Path
		if (isset($restaurant['picture']) && !empty($restaurant['picture'])) {
			if ($this->storage['enable_s3']) {
				$_restaurant['picture_full_url'] = $restaurant['picture'];
			} else {
				$_restaurant['picture_full_url'] = base_url($this->upload_paths['restaurants']['picture_url'] . $restaurant['picture']);
			}
		}
		// Add Creator as Administrator, if not already added
		if (isset($restaurant['created_by']) && is_numeric($restaurant['created_by'])) {
			$restaurant['created_by_user'] = $this->user_library->get_user(array('user_id' => $restaurant['created_by']));
		}
		// Get Permissions
		if (in_array('_permissions', $_display_fields)) {
			$_restaurant['_permissions'] = $this->_format_restaurant_permissions($restaurant, $options);
		}
		$_restaurant['is_approved'] = $restaurant['is_approved'];


		// Optional fields to display
		// Get Restaurant Opening Hours
		if (in_array('opening_hours', $_display_fields)) {
			$opening_hours = $this->restaurant_opening_hour_model->get_restaurant_opening_hours($restaurant);
			if (isset($opening_hours)) {
				$_restaurant['opening_hours'] = $opening_hours;
			}
		}

		// Get Restaurant Category
		if (in_array('category', $_display_fields)) {
			if (isset($restaurant['category']) && is_numeric($restaurant['category'])) {
				$_fields['category_id'] = $restaurant['category'];
				$_restaurant['category'] = $this->xref_category_model->get_category($_fields);
				if (!$_restaurant['category']) {
					$_restaurant['category'] = (object) $_restaurant['category'];
				}
			}
		}

		// Get Restaurant Cuisine
		if (in_array('cuisine', $_display_fields)) {
			if (isset($restaurant['cuisine']) && is_numeric($restaurant['cuisine'])) {
				$_fields['cuisine_id'] = $restaurant['cuisine'];
				$_restaurant['cuisine'] = $this->xref_cuisine_model->get_cuisine($_fields);
				if (!$_restaurant['cuisine']) {
					$_restaurant['cuisine'] = (object) $_restaurant['cuisine'];
				}
			}
		}

		// Get Restaurant Location
		if (in_array('location', $_display_fields)) {
			if (isset($restaurant['location']) && is_numeric($restaurant['location'])) {
				$_fields['location_id'] = $restaurant['location'];
				$_restaurant['location'] = $this->xref_location_model->get_location($_fields);
				if (!$_restaurant['location']) {
					$_restaurant['location'] = (array) $_restaurant['location'];
				}
			}
		}
		if (isset($_restaurant['location']) && isset($_restaurant['location']['location_name'])) {
			$_restaurant['nearest_mrt'] = $_restaurant['location']['location_name'];
		}

		// Get Restaurant Price Range
		if (in_array('price_range', $_display_fields)) {
			if (isset($restaurant['price_range']) && is_numeric($restaurant['price_range'])) {
				$_fields['price_group_id'] = $restaurant['price_range'];
				$_restaurant['price_range'] = $this->xref_price_group_model->get_price_group($_fields);
				if (!$_restaurant['price_range']) {
					$_restaurant['price_range'] = (object) $_restaurant['price_range'];
				}
			}
		}

		// Get Restaurant Branches
		if (in_array('branches', $_display_fields)) {
			$options['details'] = 'SIMPLE';
			$_restaurant['branches'] = $this->restaurant_branch_model->get_branches($restaurant);
			$_restaurant['branches']['restaurant_details'] = $this->get_restaurants($_restaurant['branches'], $options);
		}

		// Get Restaurant Statistics
		if (in_array('statistics', $_display_fields)) {
			$_restaurant['statistics'] = $this->restaurant_statistics_model->get_restaurant_statistics($restaurant);
		}

		// Get Restaurant Administrators
		if (in_array('admins', $_display_fields)) {
			$this->db->where('restaurant_id', $restaurant['restaurant_id']);
			$query = $this->db->get($this->tables['restaurants']['admins']);
			foreach ($query->result_array() as $user_array) {
				$_restaurant['admins'][] = $user_array;
			}
		}

		// Promotion
		if (in_array('promotion', $_display_fields)) {
			$_restaurant['promotion'] = $this->restaurant_promotion_model->get_restaurant_promotions_ongoing($restaurant, $options);
		}

		// Get Restaurant Comments
		if (in_array('comments', $_display_fields)) {
			$_restaurant['comments'] = $this->restaurant_comment_model->get_restaurant_comments($restaurant);
		}

		// Get Restaurant Recommendations
		if (in_array('recommendations', $_display_fields)) {
			$_rec_fields['restaurant_id'] = $restaurant['restaurant_id'];
			$_restaurant['recommendations_summary_display_text'] = '';
			$_restaurant['recommendations'] = $this->restaurant_recommendations_model->get_restaurant_recommendations_summarized($_rec_fields);
			if (is_array($_restaurant['recommendations'])) {
				$count = 0;
				foreach ($_restaurant['recommendations'] as $_recommendation) {
					$count++;
					if (isset($_recommendation['sender_user']) && isset($_recommendation['sender_user']['first_name'])) {
						if ($_restaurant['recommendations_summary_display_text'] != '') {
							$_restaurant['recommendations_summary_display_text'] .= ', ';
						}
						$_restaurant['recommendations_summary_display_text'] .= $_recommendation['sender_user']['first_name'];
					}
					if (strlen($_restaurant['recommendations_summary_display_text']) > 10) {
						$_remaining_count = (sizeof($_restaurant['recommendations']) - $count);
						if ($_remaining_count > 0) {
							$_restaurant['recommendations_summary_display_text'] .= '... and ' . $_remaining_count . ' more';
						}
						break;
					}
				}
			}
		}

		// Get Restaurant User Details
		$user_session_rel_data = array();
		if ($this->ion_auth->logged_in() && is_numeric($this->session->userdata('user_id'))) {
			// Check whether user favourited this restaurant
			$fav_field['restaurant_id'] = $restaurant['restaurant_id'];
			$fav_field['user_id'] = $this->session->userdata('user_id');
			$user_session_rel_data['favourite'] = $this->favourites_model->get_favourite($fav_field);
		}
		$_restaurant['user_session_rel_data'] = $user_session_rel_data;

		return $_restaurant;
	}

	private function _format_restaurant_permissions($restaurant = FALSE, $options = FALSE) {

		// By default, everyone has view permission.
		$_permissions = array('VIEW');

		if ($this->ion_auth->is_admin()) {
			$_permissions[] = 'UPDATE';
			$_permissions[] = 'DELETE';
		}

		if ($this->ion_auth->logged_in()) {
			$current_user_id = $this->session->userdata('user_id');
			if ($current_user_id == $restaurant['created_by']) {
				$_permissions[] = 'UPDATE';
				$_permissions[] = 'DELETE';
			}
		}

		if ($this->ion_auth->logged_in()) {
			$current_user_id = $this->session->userdata('user_id');
			if (is_array($restaurant) && isset($restaurant['admins'])) {
				foreach ($restaurant['admins'] as $admin) {
					if (isset($admin['user_id']) && is_numeric($admin['user_id']) && $admin['user_id'] > 0) {
						if ($admin['user_id'] == $current_user_id) {
							$_permissions[] = 'UPDATE';
							$_permissions[] = 'DELETE';
						}
					}
				}
			}
		}

		return array_unique($_permissions);
	}

	private function _resize_and_crop_image($image_full_path = FALSE) {

		if (!file_exists($image_full_path)) {
			throw new Exception('Invalid image file.');
		}
		$config["source_image"] = $image_full_path;
		$config['new_image'] = $image_full_path;
		$config["width"] = 500;
		$config["height"] = 371;
		$config["dynamic_output"] = FALSE; // always save as cache

		$this->load->library('image_lib');
		$this->image_lib->initialize($config);
		$this->image_lib->fit();

		return TRUE;
	}

	/**
	 * Check if current user has permission to manage the restaurant.
	 *
	 * @param type $restaurant
	 * @param type $options
	 * @return boolean
	 */
	private function _has_manage_permission($restaurant = FALSE, $options = FALSE) {

		$options['details'] = 'XSIMPLE';
		$_restaurant = $this->get_restaurant($restaurant, $options);

		if (!isset($_restaurant['_permissions'])) {
			throw new Exception("Invalid permission.");
		}

		$_permissions = $_restaurant['_permissions'];
		if (in_array('UDPATE', $_permissions)) {
			return TRUE;
		}

		if (in_array('DELETE', $_permissions)) {
			return TRUE;
		}

		return FALSE;
	}

}

?>
