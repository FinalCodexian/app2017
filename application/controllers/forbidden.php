<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forbidden extends CI_Controller {


	public function __construct(){
		parent::__construct();
		//$this->load->model('m_datos');
	}

	public function msg(){

		$this->load->view('forbidden');
	}

}
?>
