<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_importaciones extends CI_Model {

  public function listaSimple(){
    $dbLuis = $this->load->database("JCHS2018", TRUE);

    // $q = $dbLuis
    // ->select("RUC_EMPRESA CODIGO, EMPRESA USUARIO, ABREVIACION, DIRECCION")
    // ->from("T_EMPRESAS")
    // ->where("ESTADO","S")
    // ->order_by("ORDEN")
    // ->get();

    $query = "SELECT rtrim(ltrim(A.AR_CCODIGO)) CODIGO , RTRIM(LTRIM(A.AR_CDESCRI)) PRODUCTO, MARCA.TG_CDESCRI MARCA, MODELO.TG_CDESCRI MODELO,";
    $query .= "0 PEDIDO,0 PROFORMA,0 INVOICE,0 CONTENEDOR,0 VENTAS FROM al0001arti A ";
    $query .= "LEFT JOIN AL0001TABL MARCA ON MARCA.TG_CCOD='V7' AND MARCA.TG_CCLAVE=A.AR_CMARCA ";
    $query .= "LEFT JOIN AL0001TABL MODELO ON MODELO.TG_CCOD='39' AND MODELO.TG_CCLAVE=A.AR_CMODELO ";
    $query .= "LEFT JOIN FT0001LINE L ON A.AR_CLINEA=L.LI_CCODLIN ";
    $query .= "WHERE A.AR_CMARCA='DUN' ";
    $query .= "AND L.LI_COBSER2 LIKE '%NEUMA%' ";
    $query .= "ORDER BY A.AR_CCODIGO, A.AR_CDESCRI ";

    $q = $dbLuis->query($query);
    return ($q->num_rows()>0) ? $q->result_array() : FALSE;

  }
}
