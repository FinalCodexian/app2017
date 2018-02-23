<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	// seccion: Administracion
	public function adminUsuarios(){
		$this->load->view('admin/usuarios');
	}

	public function adminAlmacenes(){
		$this->load->view('admin/almacenes');
	}

	public function imporPedido(){
		$this->load->view('importaciones/pedido');
	}

	// seccion: Archivos
	public function archClientes(){
		$this->load->view('archivos/clientes');
	}

	// seccion: Ventas
	public function vtaClientes(){
		$this->load->view('ventas/ventasxcliente');
	}
	public function vtaRanking(){
		$this->load->view('ventas/ranking');
	}
	public function vtaReporteVta(){
		$this->load->view('ventas/reporteVta');
	}
	// seccion: Stock
	public function stockNeumaticos(){
		$this->load->view('stock/neumaticos');
	}


}
