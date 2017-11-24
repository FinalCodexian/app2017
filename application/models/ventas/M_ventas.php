<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_ventas extends CI_Model {

  public function mVentasXcliente($params){
    $dbLuis = $this->load->database('default', TRUE);
    $q = $dbLuis->query("EXEC XLUIS..VTA_VTAXCLIENTE  'JCHS2017', 'JCHC2017', '9000', '".$params["vendedor"]."', '', '', '', '".$params["inicio"]."', '".$params["final"]."', '".$params["opcion"]."';");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

}

?>
