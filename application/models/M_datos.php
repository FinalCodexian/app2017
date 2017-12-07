<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class M_datos extends CI_Model {

  public function listaSimple($datos){

    $buscar = $datos["buscar"];
    $opcion = $datos["opcion"];
    $dbLuis = $this->load->database($datos["base"], TRUE);
    switch($opcion):

      case 'usuarios':
      $dbLuis->select('RTRIM(TU_ALIAS) CODIGO, RTRIM(TU_NOMUSU) USUARIO, TU_CCODAGE AGENCIA_COD, RTRIM(A.AG_CDESCRI) AGENCIA');
      $dbLuis->from('UT0030');
      $dbLuis->join('FT0001AGEN A','A.AG_CCODAGE=TU_CCODAGE','left');
      $dbLuis->like('TU_NOMUSU', $buscar, 'both');
      $dbLuis->or_like('TU_ALIAS', $buscar, 'both');
      $dbLuis->order_by('TU_NOMUSU', 'ASC');
      break;

      case 'clientes':
      $dbLuis->select('rtrim(CL_CCODCLI) as id, rtrim(CL_CNOMCLI) AS text');
      $dbLuis->from('FT0001CLIE');
      $dbLuis->like('CL_CCODCLI', $buscar, 'both');
      $dbLuis->or_like('CL_CNOMCLI', $buscar, 'both');
      $dbLuis->order_by('CL_CNOMCLI', 'ASC');
      break;

      case "vendedores":
      $dbLuis->select('rtrim(VE_CCODIGO) as id, rtrim(VE_CNOMBRE) AS text');
      $dbLuis->from('FT0001VEND');
      $dbLuis->like('VE_CCODIGO', $buscar, 'both');
      $dbLuis->or_like('VE_CNOMBRE', $buscar, 'both');
      $dbLuis->order_by('VE_CNOMBRE', 'ASC');
      break;

      case "productos":
      $dbLuis->select('rtrim(AR_CCODIGO) as id, rtrim(AR_CDESCRI) AS text');
      $dbLuis->from('AL0001ARTI');
      $dbLuis->like('AR_CCODIGO', $buscar, 'both');
      $dbLuis->or_like('AR_CDESCRI', $buscar, 'both');
      $dbLuis->order_by('AR_CDESCRI', 'ASC');
      break;

      case "marcas":
      $dbLuis->select('rtrim(TG_CCLAVE) as id, rtrim(TG_CDESCRI) AS text');
      $dbLuis->from('AL0001TABL');
      $dbLuis->where('TG_CCOD', 'V7');
      $dbLuis->like('TG_CDESCRI', $buscar, 'both');
      $dbLuis->order_by('TG_CDESCRI', 'ASC');
      break;
    endswitch;

    $q = $dbLuis->get();
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;
  }


}

?>
