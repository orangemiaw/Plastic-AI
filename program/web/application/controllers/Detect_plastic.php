<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detect_plastic extends Titan_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$layout = array(
			'page'            => 'forms/detect-plastic',
			'title'           => 'Detect Plastic Using Image Upload',
		);
		$this->layout->view($layout);
	}

	public function webcam() {
		$layout = array(
			'page'            => 'forms/detect-plastic-webcam',
			'title'           => 'Detect Plastic using Webcam',
		);
		$this->layout->view($layout);
	}
}