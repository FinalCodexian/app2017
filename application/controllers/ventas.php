<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

  public function fnVentasxCliente(){
    $this->load->model('ventas/m_ventas');
    $result = $this->m_ventas->mVentasXcliente();

    $data = array();
    foreach ($result as $item) {
      //$item["desplegar"] = "a";
      //$d["opciones"] = '<div align="center"><img src="'.base_url().'/tools/img/edit.png" onclick=editar_descuento("'.$d["DEScod_descu"].'") style="cursor:pointer;"> <img src="'.base_url().'/tools/img/delete.png" onclick=eliminar_descuento("'.$d["DEScod_descu"].'") style="cursor:pointer;"></div>';
      $data[] = $item;
    }

    $datos = array(
      "total" => count($result),
      "data" => $data
    );

    echo json_encode($datos);

  }

}
