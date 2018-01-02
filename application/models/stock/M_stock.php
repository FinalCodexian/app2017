<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_stock extends CI_Model {


  public function listar(){
    $dbLuis = $this->load->database("JCHS2017", TRUE);
    $res = $dbLuis
    ->select("AR_CDESCRI, AR_CCODIGO")
    ->from("AL0001ARTI")
    ->like("AR_CDESCRI","ohtsu","both")
    ->get();

    if($res->num_rows()>0){
      foreach ($res->result() as $row) {
        $data[] = [
          'codigo' => $row->AR_CDESCRI,
          'numero' => 0
        ];
      }
      return array('items' => $data );
    }else{
      return FALSE;
    }

  }

}
