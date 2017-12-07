<html>
<head>
  <title>Documento electronico</title>
</head>
<body>

  <?php

  $cabecera = json_decode(json_encode($data["cabecera"][0]), true);

  $this->load->library('numbertowords');
  $letra = new numbertowords();

  require './tools/vendor/autoload.php';
  use BigFish\PDF417\PDF417;
  use BigFish\PDF417\Renderers\ImageRenderer;
  use BigFish\PDF417\Renderers\SvgRenderer;

  ?>

  <style>
  * { font-family: sans-serif; font-size: 10px}

  table.header { width: 100%; border-collapse: collapse;}
  table.header td.numero { width: 150px; text-align:center; vertical-align: top; border: 1px solid black; padding: 5px 10px; font-size: 12px}
  table.header td.numero strong { font-size: 15px}
  table.header td span { font-size: 8px; font-weight: bold; margin-top: 5px; display: block;}

  table.cabecera { width: 100%; border-collapse: collapse; margin: 10px auto; border: 1px solid white}
  table.cabecera td {border-bottom: 1px solid white; padding: 3px 4px}
  table.cabecera td.label { font-weight: bold;}
  table.cabecera td.dato { }

  table.detalle { width: 100%; border-collapse: collapse;}
  table.detalle thead th {border: 1px solid black; font-size: 9px; padding: 4px 3px; background-color: #fafafa; text-align: center;}
  table.detalle td { border: 1px solid black; padding: 3px 5px}

  </style>

  <?php
  $dirFiscal = "";
  $dirFiscal = substr($base,0,3)=='JCH' ? "AV. NICOLAS ARRIOLA 2291 LIMA, LIMA, LA VICTORIA" : $dirFiscal;
  $dirFiscal = substr($base,0,3)=='CAR' ? "MZA. &Ntilde; LOTE. 14 ASO RAMON COPAJA TACNA, TACNA, ALTO DE LA ALIANZA" : $dirFiscal;
  ?>

  <table class="header">
    <tr>
      <td>
        <img src="./images/<?=substr($base,0,3);?>.bmp" height="90" alt="membrete" /><br>
        <span>DOMICILIO FISCAL: <?=$dirFiscal?></span>
      </td>
      <td class="numero">
        R.U.C. <?=$cabecera["EMPRESA_RUC"];?><br>
        <?=($cabecera["TD"]=='FT' ? "FACTURA<br>ELECTRONICA" : "BOLETA DE VENTA ELECTRONICA");?>
        <br><br>
        <strong><?=$serie;?>-<?=$numero;?></strong>
      </td>
    </tr>
  </table>


  <table class="cabecera">
    <tr>
      <td class="label" width='120px'><?=($cabecera["TD"]=='FT' ? "Nombre / Raz&oacute;n Social" : "Cliente");?>:</td>
      <td class="dato"><?=$cabecera["CLIENTE"];?></td>
      <td class="label" width='100px'><?=($cabecera["TD"]=='FT' ? "R.U.C." : "D.N.I.");?>:</td>
      <td class="dato" width='140px'><?=$cabecera["RUC"];?></td>
    </tr>

    <tr>
      <td class="label">Direcci&oacute;n:</td>
      <td class="dato" colspan="3"><?=$cabecera["DIRECCION"];?></td>
    </tr>

    <tr>
      <td class="label">Fecha Emisi&oacute;n:</td>
      <td class="dato"><?=$cabecera["FECHA"];?></td>
      <td class="label">Orden de Compra:</td>
      <td class="dato"><?=$cabecera["ORDEN"];?></td>
    </tr>
    <tr>
      <td class="label">Condici&oacute;n de Pago:</td>
      <td class="dato"><?=$cabecera["FORMA_DESCRIP"];?></td>
      <td class="label">Moneda:</td>
      <td class="dato"><?=$cabecera["MONEDA"];?></td>
    </tr>

    <tr>
      <td class="label">Vencimiento:</td>
      <td class="dato"><?=$cabecera["VENCE"];?></td>
      <td class="label">Almac&eacute;n:</td>
      <td class="dato"><?=$cabecera["ALMACEN"];?></td>
    </tr>
    <tr>
      <td class="label">Vendedor:</td>
      <td class="dato"><?=$cabecera["VENDEDOR"];?></td>
      <td class="label">Gu&iacute;a de Remisi&oacute;n:</td>
      <td class="dato">
        <?php
        $arrGuia = array();
        foreach ($data["guias"] as $gs) {
          $arrGuia[] = $gs["GUIA"];
        }
        echo implode($arrGuia, " / ");
        ?>
      </td>
    </tr>
  </table>


  <table class="detalle">
    <thead>
      <tr>
        <th>Item</th>
        <th>Descripci&oacute;n</th>
        <th>Und.</th>
        <th>Cantidad</th>
        <th>P. Unitario</th>
        <th>Descuento</th>
        <th>Importe</th>
      </tr>
    </thead>

    <tbody>
      <?php
      $total = 0;
      $igv = 0;
      foreach ($data["detalle"] as $value) {
        ?>
        <tr>
          <td align=center width=25><?=$value["ITEM"];?></td>
          <td><?=$value["PRODUCTO"];?></td>
          <td align=center width=20>UND</td>
          <td align=center width=35><?=intval($value["CANTIDAD"]);?></td>
          <td align=right width=50><?=number_format($value["PRECIO"],2,'.',',');?></td>
          <td align=right width=50>0.00</td>
          <td align=right width=50><?=number_format($value["SUBTOT"],2,'.',',');?></td>
        </tr>
        <?php
        $igv += $value["IGV"];
        $total += $value["SUBTOT"];
      }
      ?>

    </tbody>
  </table>

  <style media="screen">
  table.resumen { width: 100%; margin-top: 6px}
  table.resumen td.detalle { width: 200px}
  table.resumen td.letra { vertical-align: top;}
  table.resumen td.detalle table { width: 100%}
  table.resumen td.detalle table tr td { padding: 1px 4px}
  table.resumen td.detalle table tr td:first-child { font-weight: bold; }
  table.resumen td.detalle table tr td:last-child { text-align: right;}
  </style>



  <table class="resumen">
    <tr>
      <td class="letra">
        <?php
        if(strpos($total,".")){
          $partes = explode(".",$total);
          $xentero = $partes[0];
          $xextra = str_pad($partes[1], 2, "0", STR_PAD_RIGHT);
        }else{
          $xentero = $total;
          $xextra = "00";
        }
        $mon = $cabecera["MONEDA_COD"]=='US' ? 'DOLARES AMERICANOS' : 'SOLES';
        ?>
        <strong>SON:</strong> <?=$letra->to_word($xentero) . " CON " . $xextra . "/100 " . $mon;?>
      </td>

      <td class="detalle">
        <?php
        if($cabecera["FORMA_COD"]<>'O'){
          ?>
          <table>
            <tr><td>TOTAL GRAVADO</td><td><?=number_format($total - $igv,2,'.',',');?></td></tr>
            <tr><td>TOTAL EXONERADO</td><td>0.00</td></tr>
            <tr><td>TOTAL INAFECTO</td><td>0.00</td></tr>
            <tr><td>TOTAL I.G.V. ( 18% )</td><td><?=number_format($igv,2,'.',',');?></td></tr>
            <tr><td>TOTAL OP. GRATUITAS</td><td>0.00</td></tr>
            <tr><td></td><td></td></tr>
            <tr><td>IMPORTE TOTAL</td><td><?=number_format($total,2,'.',',');?></td></tr>
          </table>
          <?php
        }else{
          ?>
          <table>
            <tr><td>TOTAL GRAVADO</td><td>0.00</td></tr>
            <tr><td>TOTAL EXONERADO</td><td>0.00</td></tr>
            <tr><td>TOTAL INAFECTO</td><td>0.00</td></tr>
            <tr><td>TOTAL I.G.V. ( 18% )</td><td>0.00</td></tr>
            <tr><td>TOTAL OP. GRATUITAS</td><td><?=number_format($total,2,'.',',');?></td></tr>
            <tr><td></td><td></td></tr>
            <tr><td>IMPORTE TOTAL</td><td>0.00</td></tr>
          </table>
          <?php
        }
        ?>
      </td>
    </tr>
  </table>

  <style media="screen">
  table.escrito { width: 100%; margin: 30px auto 10px auto}
  table.escrito td { padding: 10px}
  table.escrito td.legal { width: 400px; border: 1px solid black}
  table.escrito td.separa { width: 5px}
  table.escrito td.adicional { border: 1px solid black}
  table.escrito td.adicional ul { margin: 0; padding-left: 12px}
  table.escrito td.adicional ul li { list-style: circle; padding: 3px 0}
  </style>

  <table class="escrito">
    <tr>
      <td class="legal"><i>
        Estimado cliente, se informa que la transferencia de la propiedad de los bienes adquiridos
        se realiza con la entrega de los mismos, la cual se sustentar&aacute; con la Gu&iacute;a de Remisi&oacute;n Remitente
        suscrito por el cliente, sus autorizados y/o su Transportista.
      </i>
    </td>

    <td class="separa"></td>

    <td class="adicional">
      <strong>Informaci&oacute;n Adicional:</strong>
      <ul>
        <?php
        echo $cabecera["ADD_1"]<>"" ? "<li>".$cabecera["ADD_1"]."</li>" : '';
        echo $cabecera["ADD_2"]<>"" ? "<li>".$cabecera["ADD_2"]."</li>" : '';
        echo $cabecera["ADD_3"]<>"" ? "<li>".$cabecera["ADD_3"]."</li>" : '';
        ?>
      </ul>
    </td>
  </tr>
</table>


<style media="screen">
table.barra { width: 100%;}
table.barra td { padding: 5px}
</style>

<table class="barra">
  <tr>
    <td><i>
      Autorizado como emisor electr&oacute;nico seg&uacute;n R.I. SUNAT N&deg; 018-005-0002794<br>
      Incorporado al R&eacute;gimen de Agentes de Retenci&oacute;n de I.G.V. (R.S. 395-2014)<br>
      No sujeto a retenci&oacute;n del 3.00% del I.G.V.</i>
      <br><br>
      Representaci&oacute;n impresa de la <?=($cabecera["TD"]=='FT' ? "Factura" : "Boleta de Venta");?> Electr&oacute;nica,
      consulte en https://sfe.bizlinks.com.pe
    </td>
    <td width="250px">
      <?php
      $arr_bar = array(
        'RUC' => $cabecera["EMPRESA_RUC"],
        'TD' => $cabecera["TDOC"],
        'SERIE' => $serie,
        'NUMERO' => $numero,
        'IGV' => $igv,
        'TOTAL' => $total,
        'FECHA' => substr($cabecera["FECHA"],6,4).'-'.substr($cabecera["FECHA"],3,2).'-'.substr($cabecera["FECHA"],0,2),
        'TIPO_ADQUIRIENTE' => $cabecera["TCLIENTE"],
        'RUC_ADQUIRIENTE' => $cabecera["RUC"],
        'HASH' => $cabecera["HASH"],
        'FIRMA' => $cabecera["FIRMA"]
      );
      $bar = (object) $arr_bar;

      try {
        $pdf417 = new PDF417();
        $barcode = $pdf417->encode(implode($arr_bar, '|') . '|');
        $renderer = new ImageRenderer(['format' => 'data-url']);
        $img = $renderer->render($barcode);
        $imgEncode = $img->encoded;
      } catch (Exception $e) {
        $imgEncode ='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
      }
      ?>
      <img src="<?=$imgEncode;?>" width="250px" height="80px" />
    </td>
  </tr>
  <tr>
    <td>
      C&oacute;digo de Seguridad (Hash): <?=$cabecera["HASH"];?>
    </td>
    <td align=right>
      RUC N&deg; <?=$cabecera["EMPRESA_RUC"];?>
    </td>
  </tr>
</table>


</body>
</html>
