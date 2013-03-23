<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Description of restaurants
 *
 * @author stiucsib86
 */
class restaurant extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->config('upload_paths', TRUE);
		$this->load->library('ion_auth');
		$this->load->model('restaurant_model');
		$this->load->model('restaurant_branch_model');
		$this->load->model('restaurant_comment_model');
		$this->load->model('restaurant_menu_model');
		$this->load->model('restaurant_menu_category_model');
		$this->load->model('restaurant_promotion_model');
		$this->load->model('restaurant_statistics_model');

		//initialize upload paths
		$this->upload_paths = $this->config->item('upload_paths', 'upload_paths');

		// Merge all input parameters
		$this->_all_request_parameters = array_merge($this->input->get()? : array(), $this->args());

		if (!$this->ion_auth->is_admin()) {
			// If user is not admin, can only access his own restaurants
			$this->_all_request_parameters['user_id'] = $this->session->userdata('user_id');
		}
	}

	public function index_get() {
		// Get
		try {

			$options['access'] = isset($this->_all_request_parameters['access']) ? $this->_all_request_parameters['access'] : FALSE;
			$data = $this->restaurant_model->get_restaurant($this->_all_request_parameters, $options);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function index_post() {
		// Create
		$this->index_put();
	}

	public function index_put() {
		// Update 
		try {

			$options['access'] = isset($this->_all_request_parameters['access']) ? $this->_all_request_parameters['access'] : FALSE;
			$data = $this->restaurant_model->update_restaurant($this->_all_request_parameters, $options);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error while updating restaurant info.';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function index_delete() {
		// Delete
		try {

			$options['access'] = isset($this->_all_request_parameters['access']) ? $this->_all_request_parameters['access'] : FALSE;
			$options['access'] = 'manage';
			$data = $this->restaurant_model->delete_restaurant($this->_all_request_parameters, $options);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error while deleting restaurant.';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function image_post() {
		$this->image_put();
	}

	public function image_put() {
		try {

			$options['access'] = 'manage';

			// Settings
			$this->file_upload_config['upload_path'] = $this->upload_paths['restaurants']['picture'];
			$this->file_upload_config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->_init_file_upload();

			if (!isset($this->_all_request_parameters['restaurant_id']) || !is_numeric($this->_all_request_parameters['restaurant_id'])) {
				throw new Exception('Invalid Restaurant ID.');
			}

			$field_name = "restaurant_image";
			if ($this->upload->do_upload($field_name)) {

				$upload_data = $this->upload->data();
				$upload_data['picture'] = $upload_data['file_name'];
				$upload_data['restaurant_id'] = $this->_all_request_parameters['restaurant_id'];

				$data = $this->restaurant_model->update_restaurant_image($upload_data, $options);
				//$data = array('upload_data' => $this->upload->data());

				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error: ' . strip_tags($this->upload->display_errors());
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			// Delete image if uploaded.
			if (isset($upload_data) && isset($upload_data['full_path'])) {
				unlink($upload_data['full_path']);
			}

			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function image_external_post() {
		$this->image_external_put();
	}

	public function image_external_put() {
		$data = $this->restaurant_model->update_restaurant_image_from_url($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function image_delete() {
		try {

			if (!isset($this->_all_request_parameters['restaurant_id']) || !is_numeric($this->_all_request_parameters['restaurant_id'])) {
				throw new Exception('Invalid Restaurant ID.');
			}

			$data = $this->restaurant_model->delete_restaurant_image($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error: ' . strip_tags($this->upload->display_errors());
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {

			// Delete image if uploaded.
			if (isset($upload_data) && isset($upload_data['full_path'])) {
				unlink($upload_data['full_path']);
			}

			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function branches_get() {
		// Get
		try {

			$data = $this->restaurant_branch_model->get_branches($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function branches_post() {
		$this->branches_put();
	}

	public function branches_put() {
		// Get
		try {

			$data = $this->restaurant_branch_model->update_branches($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function new_branch_post() {

		// Get
		try {

			if (!isset($this->_all_request_parameters['restaurant_ids']) || !is_array($this->_all_request_parameters['restaurant_ids'])) {
				throw new Exception('Invalid Restaurant ID to create branch.');
			}

			$restaurant = $this->restaurant_model->update_restaurant($this->_all_request_parameters);

			$branches['restaurant_ids'] = $this->_all_request_parameters['restaurant_ids'];
			$branches['restaurant_ids'][] = $restaurant['restaurant_id'];

			$data = $this->restaurant_branch_model->update_branches($branches);

			if ($restaurant) {
				$this->response($restaurant, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function comment_get() {

		$data = $this->restaurant_comment_model->get_restaurant_comment($this->_all_request_parameters);

		if ($data) {
			$this->response($data, 200); // 200 being the HTTP response code
		} else {
			$error_response = array();
			$error_response['error'] = 'Restaurant comment could not be found';
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function comment_post() {

		$data = $this->restaurant_comment_model->post_restaurant_comment($this->_all_request_parameters);

		if ($data) {
			$this->response($data, 200); // 200 being the HTTP response code
		} else {
			$error_response = array();
			$error_response['error'] = 'Error while updating comment.';
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function comment_put() {

		$data = $this->restaurant_comment_model->update_restaurant_comment($this->_all_request_parameters);

		if ($data) {
			$this->response($data, 200); // 200 being the HTTP response code
		} else {
			$error_response = array();
			$error_response['error'] = 'Error while updating comment.';
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function comment_delete() {

		$data = $this->restaurant_comment_model->delete_restaurant_comment($this->_all_request_parameters);

		if ($data) {
			$this->response($data, 200); // 200 being the HTTP response code
		} else {
			$error_response = array();
			$error_response['error'] = 'Error while deleting comment.';
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function comments_get() {
		$data = $this->restaurant_comment_model->get_restaurant_comments($this->_all_request_parameters);

		if ($data) {
			$this->response($data, 200); // 200 being the HTTP response code
		} else {
			$error_response = array();
			$error_response['error'] = 'Restaurant comment(s) could not be found';
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function qr_get() {
		try {

			if (!isset($this->_all_request_parameters['restaurant_id']) || !is_numeric($this->_all_request_parameters['restaurant_id'])) {
				throw new Exception("Invalid Restaurant ID.");
			}
			
			$restaurant['restaurant_id'] = $this->_all_request_parameters['restaurant_id'];

			$this->load->library('ciqrcode');

			header("Content-Type: image/png");
			if (isset($this->_all_request_parameters['download'])) {
				header('Content-Disposition: attachment; filename="qr_code.png"');
			}

			$params['data'] = base_url('/restaurants/view/' . $restaurant['restaurant_id']);
			$this->ciqrcode->generate($params);

			exit();
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function menu_get() {
		$data = $this->restaurant_menu_model->get_restaurant_menu($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function menu_post() {
		$this->menu_put();
	}

	public function menu_put() {
		// Get
		try {

			$data = $this->restaurant_menu_model->update_restaurant_menu($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function menu_delete() {
		// Delete
		try {

			$data = $this->restaurant_menu_model->delete_restaurant_menu($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error while deleting restaurant.';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function menu_image_post() {
		$this->menu_image_put();
	}

	public function menu_image_put() {
		try {

			// Settings
			$this->file_upload_config['upload_path'] = $this->upload_paths['restaurants_menu']['picture'];
			$this->file_upload_config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->_init_file_upload();

			$field_name = "restaurant_menu_image";
			if ($this->upload->do_upload($field_name)) {

				$upload_data = $this->upload->data();
				$upload_data['menu_picture'] = $upload_data['file_name'];
				$upload_data = array_merge($this->_all_request_parameters, $upload_data);

				$data = $this->restaurant_menu_model->update_restaurant_menu_image($upload_data);

				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error: ' . strip_tags($this->upload->display_errors());
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {

			// Delete image if uploaded.
			if (isset($upload_data) && isset($upload_data['full_path'])) {
				if (!is_dir($upload_data['full_path'])) {
					unlink($upload_data['full_path']);
				}
			}

			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function menu_image_delete() {
		try {
			
			if (!isset($this->_all_request_parameters['restaurant_menu_id']) || !is_numeric($this->_all_request_parameters['restaurant_menu_id'])) {
				throw new Exception('Invalid Restaurant Menu ID.');
			}

			$data = $this->restaurant_menu_model->delete_restaurant_menu_image($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Error: ' . strip_tags($this->upload->display_errors());
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {

			// Delete image if uploaded.
			if (isset($upload_data) && isset($upload_data['full_path'])) {
				unlink($upload_data['full_path']);
			}

			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function menus_get() {
		$data = $this->restaurant_menu_model->get_restaurant_menus($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function menu_category_get() {
		$data = $this->restaurant_menu_category_model->get_restaurant_menu_category($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function menu_categories_get() {
		$data = $this->restaurant_menu_category_model->get_restaurant_menu_categories($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function menu_category_post() {
		$this->menu_category_put();
	}

	public function menu_category_put() {
		// Get
		try {

			$data = $this->restaurant_menu_category_model->update_restaurant_menu_category($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function menu_category_delete() {

		// Get
		try {

			$data = $this->restaurant_menu_category_model->delete_restaurant_menu_category($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function promotion_get() {

		// Get
		try {

			$data = $this->restaurant_promotion_model->get_restaurant_promotion($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Promotion could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function promotions_get() {

		// Get
		try {

			$data = $this->restaurant_promotion_model->get_restaurant_promotions($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Promotion(s) could not be found';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function promotion_post() {
		$this->promotion_put();
	}

	public function promotion_put() {

		// Get
		try {

			$data = $this->restaurant_promotion_model->update_restaurant_promotion($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Promotion could not be updated.';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function promotion_delete() {

		// Get
		try {

			$data = $this->restaurant_promotion_model->delete_restaurant_promotion($this->_all_request_parameters);

			if ($data) {
				$this->response($data, 200); // 200 being the HTTP response code
			} else {
				$error_response = array();
				$error_response['error'] = 'Restaurant could not be deleted.';
				$error_response['code'] = 500;
				$this->response($error_response, 500);
			}
		} catch (Exception $e) {
			$error_response = array();
			$error_response['error'] = '[Error] ' . $e->getMessage();
			$error_response['code'] = 500;
			$this->response($error_response, 500);
		}
	}

	public function claim_get() {

		$options['access'] = isset($this->_all_request_parameters['access']) ? $this->_all_request_parameters['access'] : '';
		$data = $this->restaurant_model->claim_restaurant($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	/**
	 * Get Restaurant Statistics
	 */
	public function statistics_get() {
		// Get
		$data = $this->restaurant_statistics_model->get_restaurant_statistics($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function admin_get() {
		// @TODO
	}

	public function admin_post() {
		$this->admin_put();
	}

	public function admin_put() {
		$data = $this->restaurant_model->update_restaurant_admin($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	public function admin_delete() {
		$data = $this->restaurant_model->delete_restaurant_admin($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}
	
	public function import_menu_post() {
		$data = $this->restaurant_menu_model->import_restaurant_menus($this->_all_request_parameters);
		$this->response($data, 200); // 200 being the HTTP response code
	}

	/**
	 * Function initialize Codeigniter File Upload
	 */
	private function _init_file_upload() {

		// Create folder if not exist.
		if (!file_exists($this->file_upload_config['upload_path'])) {
			mkdir($this->file_upload_config['upload_path'], 0777);
		}

		$this->load->library('upload', $this->file_upload_config);
		$this->upload->initialize($this->file_upload_config);
	}

}

?>
