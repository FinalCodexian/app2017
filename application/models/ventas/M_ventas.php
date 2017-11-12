<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_ventas extends CI_Model {

  public function mVentasXcliente(){
    $dbLuis = $this->load->database('XLUIS', TRUE);
    $q = $dbLuis->query("EXEC VTA_VTAXCLIENTE 'JCHS2017', 'JCHC2017'");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

}

?>
