<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_ventas extends CI_Model {

  public function mVendedoresAsignados($params){

    $listaFinal = array();
    $listaFinal[]  = $params['usuario'];

    $dbDefault = $this->load->database('default', TRUE);
    $dbDefault->select('rtrim(VENDEDOR) VENDEDOR');
    $dbDefault->from('USUARIO_VENDEDOR');
    $dbDefault->where("USUARIO", $params["usuario"]);
    $dbDefault->where("BASE", $params["base"]);
    $dbDefault->where("ESTADO", "V");
    $det = $dbDefault->get();
    if($det->num_rows()>0):
      foreach ($det->result() as $row) {
        $listaFinal[] = $row->VENDEDOR;
      }
    endif;

    $dbLuis = $this->load->database($params['base'], TRUE);
    $dbLuis->select('rtrim(VE_CCODIGO) id, RTRIM(VE_CNOMBRE) text');
    $dbLuis->where_in('VE_CCODIGO', $listaFinal);
    $dbLuis->from('FT0001VEND');
    $dbLuis->order_by('VE_CNOMBRE');
    $q = $dbLuis->get();

    if($q->num_rows()>0):
      echo json_encode([
        'total_count' => $q->num_rows(),
        'results' => $q->result_array()
      ]);
    endif;

  }




  public function mVentasXcliente($params){
    $dbLuis = $this->load->database('default', TRUE);
    $q = $dbLuis->query("EXEC VTA_VTAXCLIENTE  '".$params["base"]."', '".$params["concar"]."', '".$params["agencia"]."', '".
    $params["vendedor"]."', '".$params["cliente"]."', '".$params["marca"]."', '".$params["producto"]."', '".
    $params["inicio"]."', '".$params["final"]."', '".$params["opcion"]."';");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

}

?>
