<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

  public function fnVentasxCliente(){
    $docActual = "";
    $itemActual = "";
    $contador = 0;
    $final = array();

    $params = [
      "vendedor" => $this->input->post("vendedor"),
      "inicio" => '',
      "final" => '',
      "opcion" => ''
    ];
    $this->load->model('ventas/m_ventas');
    $result = $this->m_ventas->mVentasXcliente($params);

    if($result !== FALSE):
      $data = array();
      foreach ($result as $row):
        $data[] = $row;
      endforeach;

      foreach ($data as $key => $value):

        if($docActual != $value["TD"].$value["DOCUMENTO"]):

          $final[$contador] = array(
            "cabecera" => array(
              "diferida" => ($value["DIF"]=='05' ? 'S' : 'N'),
              "td" => $value["TD"],
              "documento" => $value["DOCUMENTO"],
              "fecha" => $value["FECHA"],
              "glosa" => $value["GLOSA"],
              "referencia" => $value["REFERENCIA"],

              "pedido" => $value["PEDIDO"],
              "pedido_usuario" => $value["PEDIDO_USUARIO"],
              "pedido_fecha" => $value["PEDIDO_FECHA"],

              "almacen" => $value["ALMACEN"],
              "almacen2" => $value["ALMACEN2"],
              "tipo_almacen" => $value["TIPO_ALMACEN"],
              "ruc" =>  $value["RUC"],
              "cliente" => $value["CLIENTE"],
              "vendedor" => $value["VENDEDOR"],
              "tipo_cliente" => $value["TIPO_CLIENTE"],
              "forma_venta" => $value["FORMA_VENTA"],
              "moneda" => $value["MONEDA"],
              "tc" => $value["TC"]
            ),
            'detalle' => array(
              array(
                "item" => $value["ITEM"],
                "codigo" => $value["CODIGO"],
                "descrip" => $value["DESCRIP"],
                "cant" => $value["CANT"],
                "saldar" => $value["SALDAR"],
                "precio_mn" => $value["PRECIO_MN"],
                "precio_us" => $value["PRECIO_US"],
                "subtot_mn" => $value["SUBTOT_MN"],
                "subtot_us" => $value["SUBTOT_US"]
              )
            ),

            'tot_cantidad' => ($value["CODIGO"]=="" ? 0 : $value["CANT"]),
            'tot_MN' => ($value["DOCUMENTO"]=="" ? 0 : $value["SUBTOT_MN"]),
            'tot_US' => ($value["DOCUMENTO"]=="" ? 0 : $value["SUBTOT_US"]),

            'guias' => array(array(
              "guia" => $value["GUIA"],
              "guia_fecha" => $value["GUIA_FECHA"]
            )),
            'tot_guias' => ($value["GUIA"]=="" ? 0 : 1),

            'notas' => array(array(
              "id" => $value["NOTA_TD"].$value["NOTA_NUM"],
              "td" => $value["NOTA_TD"],
              "nota" => $value["NOTA_NUM"],
              "nota_fecha" => $value["NOTA_FECHA"]
            )),
            'tot_notas' => ($value["NOTA_NUM"]=="" ? 0 : 1)

          );
          ++$contador;

        else:

          if($itemActual != $value["ITEM"] . $value["CODIGO"]):
            array_push($final[$contador-1]['detalle'], array(
              "item" => $value["ITEM"],
              "codigo" => $value["CODIGO"],
              "descrip" => $value["DESCRIP"],
              "cant" => $value["CANT"],
              "saldar" => $value["SALDAR"],
              "precio_mn" => $value["PRECIO_MN"],
              "precio_us" => $value["PRECIO_US"],
              "subtot_mn" => $value["SUBTOT_MN"],
              "subtot_us" => $value["SUBTOT_US"]
            ));
            $final[$contador-1]['tot_cantidad'] = $final[$contador-1]['tot_cantidad'] + $value["CANT"];
            $final[$contador-1]['tot_MN'] = $final[$contador-1]['tot_MN'] + $value["SUBTOT_MN"];
            $final[$contador-1]['tot_US'] = $final[$contador-1]['tot_US'] + $value["SUBTOT_US"];

          endif;

          $existeGuia = 0;
          foreach ($final[$contador-1]['guias'] as $guia):
            if($value['GUIA'] == $guia['guia']) ++$existeGuia;
          endforeach;
          if($existeGuia == 0):
            array_push($final[$contador-1]['guias'], array(
              "guia" => $value["GUIA"],
              "guia_fecha" => $value["GUIA_FECHA"]
            ));
            $final[$contador-1]['tot_guias'] = ++$final[$contador-1]['tot_guias'];
          endif;

          $existeNota = 0;
          foreach ($final[$contador-1]['notas'] as $nota):
            if($value['NOTA_TD'].$value['NOTA_NUM'] == $nota['id']) ++$existeNota;
          endforeach;
          if($existeNota == 0):
            array_push($final[$contador-1]['notas'],
            array(
              "id" => $value["NOTA_TD"].$value["NOTA_NUM"],
              "td" => $value["NOTA_TD"],
              "nota" => $value["NOTA_NUM"],
              "nota_fecha" => $value["NOTA_FECHA"]
            )
          );
          $final[$contador-1]['tot_notas'] = ++$final[$contador-1]['tot_notas'];
        endif;


      endif;

      $itemActual = $value["ITEM"] . $value["CODIGO"];
      $docActual = $value["TD"].$value["DOCUMENTO"];

    endforeach;
  endif;

  /*
  $datos = array(
  "total" => $contador,
  "data" => $final
);
*/

if($contador>0):
  if($contador>3500):
    $datos = array(
      "excedido" => TRUE,
      "total" => $contador,
      "mensaje" => "Muy pesado :("
    );
  else:
    $datos = array(
      "excedido" => FALSE,
      "total" => $contador,
      "html" => $this->formatoReporte(["total"=>$contador,"data"=>$final])
    );
  endif;
else:
  $datos = array(
    "excedido" => FALSE,
    "total" => $contador,
    "data" => array()
  );
endif;

echo json_encode($datos);

}

public function formatoReporte($datos){

  $retorno = "";

  if($datos["total"]):

    foreach ($datos["data"] as $key => $value):
      $retorno .= "<div class='resultado'>";

      $retorno .=  "<table class='cabecera'>";
      $retorno .=  "<tr>";
      $retorno .=  "<td class='documento'><label class='label'>Documento</label>" . $value["cabecera"]["td"] . " " . $value["cabecera"]["documento"];
      if($value["cabecera"]["diferida"]=='S') $retorno .=  "<a class='label nota'>&bull; DIFERIDA</a>";
      $retorno .=  "</td>";
      $retorno .=  "<td class='fecha'><label class='label'>Fec. Emisi&oacute;n</label>" . $value["cabecera"]["fecha"] . "</td>";
      $retorno .=  "<td><label class='label'>Cond. Pago</label>" . $value["cabecera"]["forma_venta"] . "</td>";
      $retorno .=  "</tr>";

      $retorno .=  "<tr>";
      $retorno .=  "<td colspan=3><label class='label'>Cliente</label> " . $value["cabecera"]["ruc"] . ' - ' . $value["cabecera"]["cliente"] . "</td>";
      $retorno .=  "</tr>";

      $retorno .=  "<tr>";
      $retorno .=  "<td colspan=2><label class='label'>Vendedor</label>" . $value["cabecera"]["vendedor"] . "</td>";

      if($value["cabecera"]["diferida"]=='S'):
        $retorno .=  "<td class='almacen'><label class='label'>Almac&eacute;n</label>" . $value["tot_guias"] . " gu&iacute;a(s) de atenci&oacute;n</td>";
      else:
        if(strpos($value["cabecera"]["almacen"], 'NO USAR')):
          $retorno .=  "<td class='almacen'><label class='label'>Almac&eacute;n</label>" . $value["cabecera"]["almacen2"] . "</td>";
        else:
          $retorno .=  "<td class='almacen'><label class='label'>Almac&eacute;n</label>" . $value["cabecera"]["almacen"] . "</td>";
        endif;
      endif;

      $retorno .=  "</tr>";

      $retorno .=  "<tr>";
      $retorno .=  "<td colspan=2><label class='label'>Glosa</label>". $value["cabecera"]["glosa"] ."</td>";
      $retorno .=  "<td><label class='label'>Pedido</label>". $value["cabecera"]["pedido"] ."</td>";
      $retorno .=  "</tr>";

      $retorno .=  "</table>";

      $retorno .=  "<table class='detalle'>";
      $retorno .=  "<thead><tr>";
      $retorno .=  "<th>Cantidad</th>";
      $retorno .=  "<th>Por atender</th>";
      $retorno .=  "<th>C&oacute;digo</th>";
      $retorno .=  "<th>Descripci&oacute;n</th>";
      $retorno .=  "<th>Moneda</th>";
      $retorno .=  "<th>P. Unitario</th>";
      $retorno .=  "<th>Sub Total</th>";
      $retorno .=  "</tr></thead>";

      foreach ($value["detalle"] as $det):
        $retorno .=  "<tr>";
        $retorno .=  "<td class='cantidad'>" . intval($det["cant"]) . "</td>";

        if(intval($det["saldar"])!==0){
          $retorno .=  "<td class='cantidad'><a class='label saldar'>" . intval($det["saldar"]) . "</a></td>";
        }else{
          $retorno .=  "<td class='cantidad'>0</td>";
        }

        $retorno .=  "<td class='codigo'>" . $det["codigo"] . "</td>";

        $retorno .=  "<td>" . $det["descrip"] ."</td>";
        $retorno .=  "<td class='moneda'>". $value["cabecera"]["moneda"] ."</td>";

        if($value["cabecera"]["moneda"] == "US"){
          $retorno .=  "<td class='precio'>". number_format($det["precio_us"], 2) ."</td>";
          $retorno .=  "<td class='precio'>". number_format($det["subtot_us"], 2) ."</td>";
        }

        if($value["cabecera"]["moneda"] == "MN"){
          $retorno .=  "<td class='precio'>". number_format($det["precio_mn"], 2) ."</td>";
          $retorno .=  "<td class='precio'>". number_format($det["subtot_mn"], 2) ."</td>";
        }

        $retorno .=  "</tr>";
      endforeach;

      $retorno .=  "<tr>";
      $retorno .=  "<td class='totCantidad'>" . intval($value["tot_cantidad"]) . "</td>";
      $retorno .=  "<td class='totVacio' colspan=5></td>";

      if($value["cabecera"]["moneda"] == "US") $retorno .=  "<td class='totPrecio'>" . number_format($value["tot_US"], 2) . "</td>";
      if($value["cabecera"]["moneda"] == "MN") $retorno .=  "<td class='totPrecio'>" . number_format($value["tot_MN"], 2) . "</td>";

      $retorno .=  "</tr>";
      $retorno .=  "</table>";


      $retorno .=  "<table class='pie'>";

      // guias de atencion
      if($value["tot_guias"] > 0):

        $tx = "";
        $g = 0;

        $tx =  "<td><label class='label'>Gu&iacute;as de atenci&oacute;n</label>";
        foreach($value["guias"] as $gitem){
          if(trim($gitem["guia"]) !== "SERVICIO"):
            $tx .=  "<a href='#' class='blue label'>" . $gitem["guia"] . ' (' . $gitem["guia_fecha"] . ")</a> ";
            ++$g;
          endif;
        }
        $tx .=  "</td>";

        if($g>0){ $retorno .= $tx; };

      else:
        if($value["cabecera"]["td"] == 'NC' OR $value["cabecera"]["td"] == 'ND'){
          $retorno .=  "<td><label class='label'>Sustento</label>Sustento.....</td>";
        }else{
          if($value["cabecera"]["tipo_almacen"] == "C"){
            $retorno .=  "<tr><td><label class='label'>Nota</label><a class='green label'> REGULARIZACION DE CONSIGNACION</a></td></tr>";
          }else{
            $retorno .=  "<tr><td><label class='label'>Aviso</label><a class='red label'> SIN GUIA DE ATENCION</a></td></tr>";
          }
        }
      endif;



      // notas relacionadas al documento actual
      if($value["tot_notas"] > 0){
        $retorno .=  "<tr>";
        $retorno .=  "<td><label class='label'>Notas NC/ND</label>";
        foreach($value["notas"] as $nitem){
          $retorno .=  "<a href='#' class='orange label'>" . $nitem["td"] . ' ' . $nitem["nota"] . ' (' . $nitem["nota_fecha"] . ")</a> ";
        }
        $retorno .=  "</tr>";
      }

      $retorno .=  "</table>";



      $retorno .=  "</div>";

    endforeach;

  endif;

  return $retorno;
}

}
