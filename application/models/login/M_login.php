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

	public function Empresas(){
		$dbLuis = $this->load->database("default", TRUE);
		$res = $dbLuis
		->select("RUC_EMPRESA, EMPRESA, ABREVIACION, DIRECCION")
		->from("T_EMPRESAS")
		->where("ESTADO","S")
		->order_by("ORDEN")
		->get();
		return ($res->num_rows()>0) ? array('items'=>$res->result_array()) : FALSE;
	}

	public function Bases($data){
		$empresa = $data["ruc"];
		$dbLuis = $this->load->database("default", TRUE);
		$res = $dbLuis
		->select("PERIODO, BASE, ESCRITURA")
		->from("T_BASES")
		->where("COD_EMPRESA", $empresa)
		->order_by("PERIODO", 'desc')
		->get();
		return ($res->num_rows()>0) ? array('items'=>$res->result_array()) : FALSE;
	}


	public function login($datos){

		$dbLuis = $this->load->database($datos["base"], TRUE);

		$dbLuis->select("TU_ALIAS alias, rtrim(TU_NOMUSU) nombre");
		$dbLuis->select("TU_NROALM almacen_id, RTRIM(A.A1_CDESCRI) almacen_nom");
		$dbLuis->select("TU_CCODAGE agencia_id, RTRIM(F.AG_CDESCRI) agencia_nom");
		$dbLuis->select("rtrim(V.VE_CCODIGO) vendedor,TU_TELEFONO telefono,TU_CORREO email");
		$dbLuis->select("RTRIM(TU_IMPRES) nivel, C.AC_CNOMCIA empresa, C.AC_CRUTCON concar");
		$dbLuis->from('UT0030');
		$dbLuis->join("FT0001AGEN F", 'F.AG_CCODAGE=TU_CCODAGE', 'left');
		$dbLuis->join("AL0001ALMA A", 'A.A1_CALMA=TU_NROALM', 'left');
		$dbLuis->join("FT0001VEND V", 'V.VE_CCODIGO=TU_ALIAS', 'left');
		$dbLuis->join("UT0060 S", 'S.UC_ALIAS=TU_ALIAS', 'left');
		$dbLuis->join("ALCIAS C", 'C.AC_CCIA=S.UC_CIA', 'left');
		$dbLuis->where("TU_ALIAS", $datos["usuario"]);
		$dbLuis->where("TU_PASSWO", $this->EncriptaSoftcom($datos["clave"]));
		$q = $dbLuis->get();

		/*
		Campo= TU_IMPRES
		MS = Master - acceso total
		SU = Supervisor - Administra TODO
		AD = Administrador - Administrada una o varias agencias / ciudad
		OP = Usuario / Asistente / Vendedor
		*/

		$row = $q->row();
		$resp = "";
		if (isset($row)):

			$log = $this->load->database("default", TRUE);
			$logAcceso = array(
				'USUARIO' => $datos["usuario"],
				'EMPRESA' => $row->empresa,
				'BASE' => $datos["base"],
				'IP' => $_SERVER['HTTP_HOST']

			);
			$log->insert('LOG_ACCESO', $logAcceso);


			$this->session->set_userdata(
				$datos["token"],
				array(
					"usuarioId" => $datos["usuario"],
					"usuarioNom" => $row->nombre,
					"almacenId" => $row->almacen_id,
					"almacenNom" => $row->almacen_nom,
					"agenciaId" => $row->agencia_id,
					"agenciaNom" => $row->agencia_nom,
					"vendedor" => $row->vendedor,
					"nivel" => $row->nivel,
					"base" => $datos["base"],
					"concar" => $row->concar,
					"empresa" => $row->empresa,
					"codeigniter_version" => CI_VERSION
				)
			);

			return true;
		else:
			return false;
		endif;

	}

}
