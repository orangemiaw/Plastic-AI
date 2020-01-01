<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Req_detect_plastic extends Titan_Req_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->model('mod_app');
		$this->load->model('mod_upload');
		$this->load->model('mod_checking_log');
	}

	public function index() {
		# Proccess plastic waste image
		if(!empty($_FILES["image_file"]["name"])) {
			$upload = $this->mod_upload->image_file(sha1(rand(11111,99999)) . '-' . time(), 'image_file', IMG_UPLOAD_PATH);
			if($upload['status'] == false) {
				$this->session->set_flashdata('error', $upload['error']);
				$callback['location'] = 'detect_plastic';
				$this->ajax_output('', 400, $callback);
				return;
			}

			$image_path = IMG_UPLOAD_PATH.$upload['filename'];
			$res = $this->mod_app->detect_plastic($image_path);
			if($res) {
				$json = json_decode($res);
			} else {
				$this->session->set_flashdata('error', 'Failed to connect PlasticAI brain. Try again ...');
				$callback['location'] = 'detect_plastic';
				$this->ajax_output('', 400, $callback);
				return;
			}				
		} else {
			$this->session->set_flashdata('error', 'Plastic waste photo can\'t be emty');
			$callback['location'] = 'detect_plastic';
			$this->ajax_output('', 400, $callback);
			return;
		}

		$arr_fields = array(
			'user_id'				=> $this->user_id,
			'plastic_type'			=> $json->type,
			'plastic_percentage'	=> $json->percentage,
			'plastic_file'			=> $json->file,
			'time_used'				=> $json->time_used ? $json->time_used : NULL,
			'request_id'			=> $json->_id,
			'request_data'			=> json_encode($json, true),
			'plastic_status'		=> STATUS_ENABLE
		);

		$insert = $this->mod_checking_log->insert($arr_fields);
		if (!$insert) {
			$callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
			$this->ajax_output('', 503, $callback);
		} else {
			$this->session->set_flashdata('success', 'Plastic waste successfully proccessed');
			if (isset($this->role->{$this->parent_controller})) {
				$callback['location'] = 'detection_logs/detail/' . $json->_id;
			} else {
				$callback['location'] = 'dashboard';
			}

			$this->ajax_output('', 200, $callback);
		}
	}

	public function webcam() {
		$image_base64	= $this->input->post('image_base64');

		$this->form_validation->set_rules('image_base64', 'Image Base64', 'trim|required');

		# Proccess plastic waste image
		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
			$upload = $this->mod_upload->image_base64(sha1(rand(11111,99999)) . '_' . time(), $image_base64, IMG_UPLOAD_PATH);
			if($upload['status'] == false) {
				$this->session->set_flashdata('error', $upload['error']);
				$callback['location'] = 'detect_plastic';
				$this->ajax_output('', 400, $callback);
				return;
			}

			$image_path = IMG_UPLOAD_PATH.$upload['filename'];
			$res = $this->mod_app->detect_plastic($image_path);
			if($res) {
				$json = json_decode($res);
			} else {
				$this->session->set_flashdata('error', 'Failed to connect PlasticAI brain. Try again ...');
				$callback['location'] = 'detect_plastic';
				$this->ajax_output('', 400, $callback);
				return;
			}				
		}

		$arr_fields = array(
			'user_id'				=> $this->user_id,
			'plastic_type'			=> $json->type,
			'plastic_percentage'	=> $json->percentage,
			'plastic_file'			=> $json->file,
			'time_used'				=> $json->time_used ? $json->time_used : NULL,
			'request_id'			=> $json->_id,
			'request_data'			=> json_encode($json, true),
			'plastic_status'		=> STATUS_ENABLE
		);

		$insert = $this->mod_checking_log->insert($arr_fields);
		if (!$insert) {
			$callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
			$this->ajax_output('', 503, $callback);
		} else {
			$this->session->set_flashdata('success', 'Plastic waste successfully proccessed');
			if (isset($this->role->{$this->parent_controller})) {
				$callback['location'] = 'detection_logs/detail/' . $json->_id;
			} else {
				$callback['location'] = 'dashboard';
			}

			$this->ajax_output('', 200, $callback);
		}
	}
}