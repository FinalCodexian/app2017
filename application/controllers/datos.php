<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datos extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('m_datos');
  }

  public function fnListaSimple(){
    $data['buscar'] = $this->input->get('q');
    $data['opcion'] = $this->input->get('opcion');
    $result =  $this->m_datos->listaSimple($data);
    $dataRet = array();
    if($result <> FALSE) foreach ($result as $item) $dataRet[] = $item;
    $datos = array("total_count" => count($result),"results" => $dataRet);
    echo json_encode($datos);
  }

}
?>
