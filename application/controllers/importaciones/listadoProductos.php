<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListadoProductos extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('importaciones/m_importaciones');
  }

  public function fnListaSimple(){
    $data['buscar'] = 'LUIS'; //$this->input->get('q');
    $data['opcion'] = 'usuarios'; //$this->input->get('opcion');
    $data['base'] = 'JCHS2018'; //$this->input->get('base');
    $result =  $this->m_importaciones->listaSimple($data);
    $dataRet = array();
    if($result <> FALSE) foreach ($result as $item) $dataRet[] = $item;
    $datos = array("total_count" => count($result),"data" => $dataRet);
    echo json_encode($datos);
  }

}
?>
