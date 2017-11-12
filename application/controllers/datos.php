<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datos extends CI_Controller {
  public function fnListaSimple_Clientes(){
    $this->load->model('m_datos');
    $data['buscar'] = $this->input->get('q');
    $result =  $this->m_datos->listaSimple_Clientes($data);

    $dataRet = array();
    if($result <> FALSE):
      foreach ($result as $item) $dataRet[] = $item;
    endif;
    $datos = array(
      "total_count" => count($result),
      "results" => $dataRet
    );
    echo json_encode($datos);
  }

}
?>
