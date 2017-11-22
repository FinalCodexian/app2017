<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function archClientes(){
		$this->load->view('archivos/clientes');
	}

	public function vtaClientes(){
		$this->load->view('ventas/ventasxcliente');
	}
	
}
