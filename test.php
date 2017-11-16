<style>* {font-size: 12px; font-family: "Calibri"}</style>

<?php

include("data.php");

$docActual = "";
$itemActual = "";
$contador = 0;
$final = array();

foreach ($data as $key => $value):

  if($docActual != $value["TD"].$value["DOCUMENTO"]):

    $final[$contador] = array(
      "cabecera" => array(
        "diferida" => ($value["F5_CTF"]=='05' ? 'S' : 'N'),
        "td" => $value["TD"],
        "documento" => $value["DOCUMENTO"],
        "fecha" => $value["FECHA"],
        "almacen" => $value["ALMACEN"],
        "almacen2" => $value["ALMACEN2"],
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

echo "<pre>";
print_r($final);

?>
