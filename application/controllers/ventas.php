<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

  public function fnVentasxCliente(){
    $this->load->model('ventas/m_ventas');
    $result = $this->m_ventas->mVentasXcliente();

    $data = array();
    $docActual = "";

    foreach ($result as $row):
      if($docActual !== $row["TD"] . $row["DOCUMENTO"]):
        $data[] = array(
          "td" => $row["TD"],
          "documento" => $row["DOCUMENTO"]
        );
      endif;
      $docActual = $row["TD"] . $row["DOCUMENTO"];
    endforeach;

    foreach ($result as $row):
      foreach ($data as $ind => $item):
        if($item["documento"] == $row["documento"]):
          $data[$ind][] = $row["CODIGO"]; 
        endif;
      endforeach;
    endforeach;

    $datos = array(
      "total" => count($result),
      "data" => $data
    );


    echo json_encode($datos);

  }

}
