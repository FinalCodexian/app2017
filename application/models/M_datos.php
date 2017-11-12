<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_datos extends CI_Model {

  public function listaSimple_Clientes($datos){
    $dbLuis = $this->load->database('default', TRUE);

    $buscar = $datos["buscar"];

    $dbLuis->select('rtrim(CL_CCODCLI) as id, rtrim(CL_CNOMCLI) AS text');
    $dbLuis->from('FT0001CLIE');
    $dbLuis->like('CL_CCODCLI', $buscar, 'both');
    $dbLuis->or_like('CL_CNOMCLI', $buscar, 'both');
    $dbLuis->order_by('CL_CNOMCLI', 'ASC');
    $q = $dbLuis->get();
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

}

?>
