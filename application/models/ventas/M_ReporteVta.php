<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_reportevta extends CI_Model {

  public function datos($params){
    $dbLuis = $this->load->database('default', TRUE);
    $q = $dbLuis->query("EXEC VTA_REPORTEVTA '".$params["base"]."', '".$params["agencia"]."', '".$params["fecha"]."'");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

  public function redondeos($params){
    $dbLuis = $this->load->database('default', TRUE);
    $q = $dbLuis->query("EXEC VTA_REDONDEOS '".$params["base"]."', '".$params["agencia"]."', '".$params["fecha"]."'");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

  public function datos_resumen($params){
    $dbLuis = $this->load->database('default', TRUE);
    $q = $dbLuis->query("EXEC VTA_REPORTE_DATOS '".$params["base"]."', '".$params["agencia"]."', '".$params["fecha"]."', '".$params["concar"]."'; ");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

}
