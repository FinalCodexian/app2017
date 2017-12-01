<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_info extends CI_Model {

  public function info($params){

    $dbDefault = $this->load->database($params['base'], TRUE);

    $dbDefault
    ->select('C5_CNUMDOC GUIA, CONVERT(VARCHAR(10),C5_DFECDOC,103) FECHA,  G.C5_CSITGUI ESTADO')
    ->select('RTRIM(G.C5_CCODMOV) + \' - \'+ RTRIM(M.TM_CDESCRI) MOVIMIENTO')
    ->select('C5_CALMA ALMACEN_COD, RTRIM(a.A1_CDESCRI) ALMACEN')
    ->select('RTRIM(G.C5_CCODCLI) RUC, LTRIM(RTRIM(g.C5_CNOMCLI)) CLIENTE')
    ->select('G.C5_CVENDE VENDEDOR_COD, RTRIM(V.VE_CNOMBRE) VENDEDOR')
    ->select('RTRIM(C5_CRFTDOC)+\' \'+RTRIM(C5_CRFNDOC) REFERENCIA, RTRIM(G.C5_CGLOSA3) GLOSA')
    ->select('RTRIM(C5_CAGETRA) TRANSPORTE_COD, RTRIM(T.TR_CNOMBRE) TRANSPORTE, RTRIM(T.TR_CDIRECC) TRANSPORTE_DIRECCION')
    ->select('LTRIM(RTRIM(C5_CDIRENV)) DESTINO')
    ->select('RTRIM(G.C5_CUSUCRE)+ \' - \'+RTRIM(U.TU_NOMUSU) USUARIO')
    ->select('CONVERT(CHAR(10), G.C5_DFECCRE, 103)+\'  \'+CONVERT(CHAR(8), G.C5_DFECCRE, 108) CREACION')
    ->from('AL0001MOVC G')
    ->join("UT0030 U", "U.TU_ALIAS=G.C5_CUSUCRE", "left")
    ->join("AL0001ALMA A", "A.A1_CALMA=G.C5_CALMA", "left")
    ->join("FT0001VEND V", "V.VE_CCODIGO=G.C5_CVENDE", "left")
    ->join("AL0001TRAN T", "T.TR_CCODIGO=C5_CAGETRA", "left")
    ->join("AL0001TABM M", "M.TM_CTIPMOV='S' AND M.TM_CCODMOV=G.C5_CCODMOV", "left")

    ->where("C5_CTD", "GS")
    ->where("C5_CNUMDOC", $params["dato"]);

    $cab = $dbDefault->get();

    $almacen = "";
    $cabecera = array();

    if($cab->num_rows()>0):
      $cabecera = $cab->result_array();
      foreach ($cab->result() as $row) {
        $almacen = $row->ALMACEN_COD;
      }
    endif;

    $dbDefault
    ->select('C6_NCANTID CANTIDAD, RTRIM(C6_CCODIGO) CODIGO, RTRIM(C6_CDESCRI) PRODUCTO')
    ->from('AL0001MOVD G')
    ->where("C6_CTD", "GS")
    ->where("C6_CALMA", $almacen)
    ->where("C6_CNUMDOC", $params["dato"]);
    $det = $dbDefault->get();

    $detalle = array();
    if($det->num_rows()>0):
      $detalle = $det->result_array();
    endif;

    return json_encode([
      'cabecera' => $cabecera,
      'detalle' => $detalle
    ]);

  }


}

?>
