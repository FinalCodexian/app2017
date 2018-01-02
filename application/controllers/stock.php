<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('stock/m_stock');
  }

  public function listar(){
    $data['data'] = $this->m_stock->listar();
    echo json_encode($data);
  }

}
