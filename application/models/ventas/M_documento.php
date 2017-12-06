<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_documento  extends CI_Model {

  public function documento($params){
    $dbDefault = $this->load->database($params['base'], TRUE);

    $dbDefault
    ->select('rtrim(F.F5_CFIRMDIG) FIRMA, rtrim(F.F5_CHFIRMDIG) HASH')
    ->select('rtrim(CLIE.CL_CDOCIDE) TCLIENTE')
    ->select('substring(TDOC.TG_CDESCRI,39,2) TDOC')
    ->select('rtrim(AL.AC_CRUC) EMPRESA_RUC')
    ->select('F.F5_CTD TD, F.F5_CNUMSER SERIE, F.F5_CNUMDOC NUMERO, CONVERT(VARCHAR(10),F.F5_DFECDOC,103) FECHA')
    ->select('RTRIM(F.F5_CRUC) RUC, RTRIM(F.F5_CNOMBRE) CLIENTE, RTRIM(F.F5_CDIRECC) DIRECCION, RTRIM(ALMA.A1_CDESCRI) ALMACEN')
    ->select('RTRIM(F.F5_CFORVEN) FORMA_COD, RTRIM(FV.FV_CDESCRI) FORMA_DESCRIP, CONVERT(VARCHAR(10),DATEADD(day , FV.FV_NDIASV, F.F5_DFECDOC),103) VENCE')
    ->select('RTRIM(V.VE_CNOMBRE) VENDEDOR, F.F5_CCODMON MONEDA_COD, CASE F.F5_CCODMON WHEN \'US\' THEN \'USD - US Dolar\' WHEN \'MN\' THEN \'PEN - Sol\' ELSE F.F5_CCODMON END MONEDA')
    ->select('F.F5_CNUMORD ORDEN, \'USUARIO: \'+F.F5_CUSUCRE+\' (\'+CONVERT(VARCHAR(5),F.F5_DFECCRE,103)+\'  \'+CONVERT(VARCHAR(8),F.F5_DFECCRE,108)+\')\' ADD_1')
    ->select('IIF(F.F5_CTF=\'05\',\'FACTURA DIFERIDA\',\'\') ADD_2, IIF(ALMA.A1_CTIPO=\'C\',\'REGULARIZACION DE CONSIGNACION\',\'\') ADD_3')
    ->from('(SELECT * FROM FT0001ACUC UNION ALL SELECT * FROM FT0001FACC) F')
    ->join('FT0001FORV FV','FV.FV_CCODIGO=F.F5_CFORVEN','left')
    ->join('FT0001VEND V','V ON V.VE_CCODIGO=F.F5_CVENDE','left')
    ->join('AL0001ALMA ALMA','ALMA.A1_CALMA=F.F5_CALMA','left')
    ->join('AL0001TABL TDOC','TDOC.TG_CCOD=\'04\' AND TDOC.TG_CCLAVE=F.F5_CTD','left')
    ->join('FT0001CLIE CLIE','CLIE.CL_CCODCLI=F.F5_CCODCLI','left')
    ->join('ALCIAS AL','1=1','left')

    ->where('F.F5_CTD', $params['td'])
    ->where('F.F5_CNUMSER', $params['serie'])
    ->where('F.F5_CNUMDOC', $params['numero']);
    $query = $dbDefault->get();


    $dbDefault
    ->select('F6_CITEM ITEM, F6_CCODIGO CODIGO, RTRIM(F6_CDESCRI) PRODUCTO, F6_NCANTID CANTIDAD')
    ->select('F6_NPRECIO PRECIO, F6_NIGV IGV, IIF(C.F5_CCODMON=\'MN\', F6_NIMPMN, F6_NIMPUS) SUBTOT')
    ->from('(SELECT * FROM FT0001ACUD UNION ALL SELECT * FROM FT0001FACD) D')
    ->join('(SELECT * FROM FT0001ACUC UNION ALL SELECT * FROM FT0001FACC) C','C.F5_CCODAGE=D.F6_CCODAGE AND C.F5_CTD=D.F6_CTD AND C.F5_CNUMSER=D.F6_CNUMSER AND C.F5_CNUMDOC=D.F6_CNUMDOC','left')
    ->where('F6_CTD', $params['td'])
    ->where('F6_CNUMSER', $params['serie'])
    ->where('F6_CNUMDOC', $params['numero'])
    ->order_by('D.F6_CITEM','asc');
    $query2 = $dbDefault->get();



    $dbDefault
    ->select('rtrim(C5_CNUMDOC) GUIA')
    ->from('AL0001MOVC')
    ->where('C5_CSITGUI !=', 'A')
    ->where('C5_CRFNDOC', $params['serie'] . $params['numero'])
    ->order_by('C5_CNUMDOC','asc');
    $query3 = $dbDefault->get();


    if($query->num_rows()>0){
      $data = [
        "cabecera" => $query->result_array(),
        "detalle"  => $query2->result_array(),
        "guias"    => $query3->result_array()
      ];
      return $data;
    }else{
      return false;
    }


  }

}
