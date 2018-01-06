<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_ranking extends CI_Model {

  public function datos($params){
    $dbLuis = $this->load->database('default', TRUE);
    $q = $dbLuis->query("EXEC RANKING '".$params["base"]."', '".$params["concar"]."', '".$params["agencia"]."', '".$params["inicio"]."' , '".$params["final"]."' ");
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }

}
