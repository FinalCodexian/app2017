<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_login extends CI_Model {

	public function EncriptaSoftcom($CCLAVE){
	  $cDecrip = "";
	  for($i=0;$i<=(strlen($CCLAVE)-1);++$i):
	    $cKey = ord(substr($CCLAVE, $i , 1));
	  $cDecrip = $cDecrip . chr($cKey + strlen($CCLAVE));
	  endfor;
	  return $cDecrip;
	}

	public function login($datos){

		$this->db->where("TU_ALIAS", $datos["usuario"]);
		$this->db->where("TU_PASSWO", $this->EncriptaSoftcom($datos["clave"]));
		$q = $this->db->get("UT0030");

		if($q->num_rows()>0){
			return true;
		}else{
			return false;
		}

	}

}
