<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_stock extends CI_Model {

  public function listar(){
    $dbLuis = $this->load->database("JCHS2018", TRUE);
    $res = $dbLuis
    ->select("AR_CDESCRI, AR_CCODIGO, L.LI_CDESLIN, L.LI_COBSER2")
    ->join("FT0001LINE L", "L.LI_CCODLIN=A.AR_CLINEA", "left")
    ->from("AL0001ARTI A")
    ->like("AR_CDESCRI","DUNLOP","both")
    ->get();

    if($res->num_rows()>0){
      foreach ($res->result() as $row):

        $lista = $this->Parseo($row->AR_CDESCRI, $row->LI_COBSER2);

        $data[] = [
          'codigo' => trim($row->AR_CCODIGO),
          'descripcion' => $row->AR_CDESCRI,
          'medida' => $lista['medida'],
          'ancho' => $lista['ancho'],
          'alto' => $lista['alto'],
          'aro' => $lista['aro'],
          'pr' =>  $lista['pr'],
          'liss' =>  $lista['liss'],
          'ind_carga' =>  $lista['i_carga'],
          'ind_velocidad' =>  $lista['i_velocidad'],
          'general' => $lista['general'],
          'orden' => $lista['general'] .'-'. $lista['aro'] .'-'. $lista['alto'] .'-'. $lista['ancho'] .'-'. $lista['pr'] .'-'. trim($row->AR_CDESCRI)
        ];

      endforeach;

      $arr_order = array();
      foreach ($data as $key => $row) $arr_order[$key] = $row['orden'];
      array_multisort($arr_order, SORT_ASC, $data);

      return array('items' => $data );

    }else{
      return FALSE;
    }

  }


  function Parseo($cadena, $linea){
    $cadena = strtoupper(trim($cadena));
    $parse = trim($cadena);
    $parse = substr($parse, 0, strpos($parse, ' '));
    $parse = str_replace('X', '/', $parse);
    $parse = preg_replace('/[^0-9,.\/]+/i', '', $parse);
    $parse = $parse == '/' ? '' : $parse;
    $parse = $parse == '/.' ? '' : $parse;
    $parse = $parse == '.' ? '' : $parse;

    $medida = $parse;

    $ancho  = strpos($parse, '/') <> 0 ? explode('/', $parse)[0] : $parse;
    if($ancho < 10) $ancho = $ancho / 10 ;  // hack ancho

    $alto  = strpos($parse, '/') <> 0 ? explode('/', $parse)[1] : '';
    $alto = ($alto == '' && $ancho <> '') ? '80' : $alto;

    $aro = explode(' ', $cadena)[1];
    $aro = $ancho == '' ? '' : $aro;
    $aro = $ancho == 0 ? 999 : $aro;
    $aro = preg_replace('/[^0-9,.]+/i', '', $aro);
    if($aro < 10) $aro = $aro / 10 ;  // hack aro

    $general = substr($linea, 0, 4);
    $general = trim($linea) == '' ? '99-Z' : $general;

    if(strpos($linea, 'OTRO') <> 0 || $general == '99-Z'):
      $medida = '';
      $alto = '';
      $aro = '';
      $ancho = $cadena;
    endif;

    $pliegues = '';

    if(strpos($cadena, "PR") && strpos($linea, 'NEUMA') > 0):
      $pliegues = strpos(explode(' ', $cadena)[2], 'PR') > 0 ? explode(' ', $cadena)[2] : $pliegues;
      $pliegues = strpos(explode(' ', $cadena)[3], 'PR') > 0 ? explode(' ', $cadena)[3] : $pliegues;
      $pliegues = str_replace("PR", "", $pliegues);
      if( !is_numeric($pliegues) ) $pliegues = "";
    endif;


    $liss = "";

    $cats = explode(" ", $cadena);
    foreach($cats as $cat) {
      if( strpos($cat, 'PR') == 0 ):
        $ini = substr($cat, 0, 2);
        $fin = substr($cat, -1);
        if( is_numeric($ini) && !is_numeric($fin) && strpos($linea, 'NEUMA') > 0) $liss = $cat;
      endif;
    }

    $carga = "";
    $velocidad = "";

    if($liss<>""):
      $carga = preg_replace('/[^0-9,\/.]+/i', '', $liss);
      $velocidad = preg_replace('/[^A-Z,.]+/i', '', $liss);
    endif;

    return $respuesta = array(
      'medida' => trim($medida),
      'ancho' => trim($ancho),
      'alto' => trim($alto),
      'aro' => trim($aro),
      'pr' => $pliegues,
      'liss' => $liss,
      'i_carga' => $carga,
      'i_velocidad' => $velocidad,
      'general' => trim($general)
    );

  }


}
