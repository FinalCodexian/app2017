<?php
ini_set('max_execution_time', 300);

if(sizeof($redondeos)>0){ $xRedondeos = json_decode(json_encode($redondeos), true); }
$cabecera = json_decode(json_encode($resumen[0]), true);

$obs1 = 0;
$obs2 = 0;
$totSeccion_1 = 0; // FACTURACION
$totSeccion_2 = 0; // APLICACIONES
$totSeccion_3 = 0; // COBRANZAS

foreach ($data as $value):
  if($value["OPCION"] == "OP-1"):
    ++$totSeccion_1;
  endif;
  if($value["OPCION"] == "OP-2" && $value["FORMA_COD"] == "A"):
    ++$totSeccion_2;
  endif;
  if($value["OPCION"] == "OP-2" && $value["FORMA_COD"] <> "A"):
    ++$totSeccion_3;
  endif;
endforeach;

$efec["ventas"]["MN"] = 0;
$efec["ventas"]["US"] = 0;
$efec["cobranzas"]["MN"] = 0;
$efec["cobranzas"]["US"] = 0;
$efec["percepcion"]["MN"] = 0;
$efec["percepcion"]["US"] = 0;
$efec["redondeo"]["MN"] = 0;
$efec["redondeo"]["US"] = 0;
$efec["total"]["MN"] = 0;
$efec["total"]["US"] = 0;

$depo["ventas"]["MN"] = 0;
$depo["ventas"]["US"] = 0;
$depo["cobranzas"]["MN"] = 0;
$depo["cobranzas"]["US"] = 0;
$depo["percepcion"]["MN"] = 0;
$depo["percepcion"]["US"] = 0;
$depo["redondeo"]["MN"] = 0;
$depo["redondeo"]["US"] = 0;
$depo["total"]["MN"] = 0;
$depo["total"]["US"] = 0;

$cheq["ventas"]["MN"] = 0;
$cheq["ventas"]["US"] = 0;
$cheq["cobranzas"]["MN"] = 0;
$cheq["cobranzas"]["US"] = 0;
$cheq["percepcion"]["MN"] = 0;
$cheq["percepcion"]["US"] = 0;
$cheq["redondeo"]["MN"] = 0;
$cheq["redondeo"]["US"] = 0;
$cheq["total"]["MN"] = 0;
$cheq["total"]["US"] = 0;

$tarj["ventas"]["MN"] = 0;
$tarj["cobranzas"]["MN"] = 0;
$tarj["percepcion"]["MN"] = 0;
$tarj["redondeo"]["MN"] = 0;
$tarj["total"]["MN"] = 0;
$tarj["total"]["MN"] = 0;

?>

<html>
<head>
  <title><?=$cabecera["AGENCIA"];?>: <?=$cabecera["REPORTE"];?></title>
  <style>
  header {
    position: fixed; top: 0px; left: 0px; right: 0px; height: 50px; font-size: 11px; line-height: 14px
  }
  header table tr td { font-weight: bold; font-size: 11px}
  footer {
    position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; text-align: center; border-top: 1px solid #000;
    padding-top: 5px
  }

  * { font-family: "Calibri", sans-serif; font-size: 10px}
  body, html { width: 100% !important}
  body { margin-top: 65px}
  table#tbReporte { width: 100% !important; margin: 0 auto; border-collapse: collapse; page-break-before:auto;}
  table#tbReporte tr.cabecera {}
    table#tbReporte tr.cabecera td { font-weight: bold; padding: 6px 3px; font-size: 9px; text-align: center;}
    table#tbReporte tr td { font-size: 9px; border: 1px solid #222; padding: 3px 5px; cursor: default}
    table#tbReporte tbody td.mon { text-align: right; padding: 0 10px 0 15px}
    table#tbReporte tbody td.cen { text-align: center; }
    table#tbReporte tbody td.anulado { color: rgb(121, 15, 15)}
    table#tbReporte tbody tr:hover { background-color: rgb(197, 255, 177) !important}
    table#tbReporte tbody tr.sameFT { background-color: rgba(205, 244, 244, 0.66)}



    td.resaltar {
      background-color: yellow;
      border: 2px solid red !important;
      font-weight: bold; position: relative;
      background-image: url('./images/resaltar_contado.png');
      background-position: left center;
      background-repeat: no-repeat;
    }

    td.resaltar_cobranza {
      background-color: rgb(255, 188, 139);
      background-image: url('./images/resaltar_cobranza.png');
      background-position: left center;
      background-repeat: no-repeat;
    }

    hr.hrSepara { border: 0; border-bottom: 1px solid white}
    </style>
  </head>
  <body>
    <header>
      <table>
        <tr>
          <td><?=$cabecera["COMPANIA"];?></td>
        </tr>
        <tr>
          <td width='500px'>REPORTE DE VENTAS N&deg; <?=$cabecera["REPORTE"];?>
          </td>
          <td>Fecha: <?=strtolower($cabecera["DIA"]);?>, <?=$cabecera["DIA_NUM"];?> de <?=strtolower($cabecera["MES"]);?> del <?=$cabecera["ANIO"];?></td>
        </tr>
        <tr>
          <td>AGENCIA: <?=$cabecera["AGENCIA_COD"];?> - <?=$cabecera["AGENCIA"];?> (<?=$cabecera["CIUDAD"];?>)</td>
          <td>
            T/C COMPRA: <?=substr($cabecera["COMPRA"],0,5);?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            T/C VENTA: <?=substr($cabecera["VENTA"],0,5);?>
          </td>
        </tr>
      </table>
    </header>

    <footer>
      JCH Llantas - &Aacute;rea de Sistemas
    </footer>

    <table id="tbReporte">

      <?php
      // FACTURACION: inicio
      if($totSeccion_1>0):
        ?>
        <tr class="cabecera">
          <td>N° COMPROB.</td>
          <td>VENDEDOR</td>
          <td>RUC/DNI</td>
          <td>CLIENTE</td>
          <td>T/CLIENTE</td>
          <td>COND. VENTA</td>
          <td>FORMA PAGO</td>
          <td>T/C</td>
          <td>CONT S/</td>
          <td>CONT US$</td>
          <td>TARJETA S/</td>
          <td>CRED S/</td>
          <td>CRED US$</td>
          <td>N° INFORME COB.</td>
          <td>T/DOC</td>
          <td>N° DOC</td>
          <td>BANCO</td>
        </tr>
        <?php
        $docActual = "";


        // Redondeos: inicio
        if(sizeof($redondeos)>0){
        foreach ($xRedondeos as $value):
          if($value["MONEDA"]=='MN'):
            $efec["redondeo"]["MN"] += $value["FORMA"]=='E' ? $value["MN"] : 0;
            $depo["redondeo"]["MN"] += $value["FORMA"]=='B' ? $value["MN"] : 0;
            $cheq["redondeo"]["MN"] += $value["FORMA"]=='C' ? $value["MN"] : 0;
            $tarj["redondeo"]["MN"] += $value["FORMA"]=='F' ? $value["MN"] : 0;
          endif;
        endforeach;
        }
        // Redondeos: final

        foreach ($data as $value):

          // Totales: inicio
          if($value["ESTADO"]<>'A'):
            if($value["PAGO_MON"]=='MN'):
              if($value["FORMA_COD"]=='E'):
                if($value["OPCION"]=="OP-1"){ $efec["ventas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $efec["cobranzas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $efec["percepcion"]["MN"] += $value["PERCEPCION_IMP"]; }
              endif;

              if($value["FORMA_COD"]=='B'):
                if($value["OPCION"]=="OP-1"){ $depo["ventas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $depo["cobranzas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $depo["percepcion"]["MN"] += $value["PERCEPCION_IMP"]; }
              endif;

              if($value["FORMA_COD"]=='C'):
                if($value["OPCION"]=="OP-1"){ $cheq["ventas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $cheq["cobranzas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $cheq["percepcion"]["MN"] += $value["PERCEPCION_IMP"]; }
              endif;

              if($value["FORMA_COD"]=='F'):
                if($value["OPCION"]=="OP-1"){ $tarj["ventas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $tarj["cobranzas"]["MN"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $tarj["percepcion"]["MN"] += $value["PERCEPCION_IMP"]; }
              endif;
            endif;

            if($value["PAGO_MON"]=='US'):
              if($value["FORMA_COD"]=='E'):
                if($value["OPCION"]=="OP-1"){ $efec["ventas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $efec["cobranzas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $efec["percepcion"]["US"] += $value["PERCEPCION_IMP"]; }
              endif;

              if($value["FORMA_COD"]=='B'):
                if($value["OPCION"]=="OP-1"){ $depo["ventas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $depo["cobranzas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $depo["percepcion"]["US"] += $value["PERCEPCION_IMP"]; }
              endif;

              if($value["FORMA_COD"]=='C'):
                if($value["OPCION"]=="OP-1"){ $cheq["ventas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $cheq["cobranzas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $cheq["percepcion"]["US"] += $value["PERCEPCION_IMP"]; }
              endif;

              if($value["FORMA_COD"]=='F'):
                if($value["OPCION"]=="OP-1"){ $tarj["ventas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2"){ $tarj["cobranzas"]["US"] += $value["PAGO_IMP"]; }
                if($value["OPCION"]=="OP-2" && $value["PERCEPCION_NUM"]<>""){ $tarj["percepcion"]["US"] += $value["PERCEPCION_IMP"]; }
              endif;
            endif;

          endif;

          $efec["total"]["MN"] = $efec["ventas"]["MN"] + $efec["cobranzas"]["MN"] + $efec["percepcion"]["MN"] + $efec["redondeo"]["MN"];
          $efec["total"]["US"] = $efec["ventas"]["US"] + $efec["cobranzas"]["US"] + $efec["percepcion"]["US"] + $efec["redondeo"]["US"];

          $depo["total"]["MN"] = $depo["ventas"]["MN"] + $depo["cobranzas"]["MN"] + $depo["percepcion"]["MN"] + $depo["redondeo"]["MN"];
          $depo["total"]["US"] = $depo["ventas"]["US"] + $depo["cobranzas"]["US"] + $depo["percepcion"]["US"] + $depo["redondeo"]["US"];

          $cheq["total"]["MN"] = $cheq["ventas"]["MN"] + $cheq["cobranzas"]["MN"] + $cheq["percepcion"]["MN"] + $cheq["redondeo"]["MN"];
          $cheq["total"]["US"] = $cheq["ventas"]["US"] + $cheq["cobranzas"]["US"] + $cheq["percepcion"]["US"] + $cheq["redondeo"]["US"];

          $tarj["total"]["MN"] = $tarj["ventas"]["MN"] + $tarj["cobranzas"]["MN"] + $tarj["percepcion"]["MN"] + $tarj["redondeo"]["MN"];


          // Totales: final



          if($value["OPCION"] == "OP-1"):
            ?>
            <tr class="<?=$value["COUNT_PAGOS"] > 1 ? 'sameFT' : '';?>">

              <td class="cen"><?=$value["TD"];?>/<?=$value["SERIE"];?><?=$value["DOC"];?></td>

              <?php
              if($value["ESTADO"]<>'A'):

                $classResaltado = "";
                if($value["SALDO_IMPORTE"] <> 0 && (strpos($value["FORMA_VENTA"],'CONTADO') !== FALSE || strpos($value["FORMA_VENTA"],'TARJETA') !== FALSE)):
                  $classResaltado = "resaltar";
                endif;

                ?>
                <td><?=$value["VENDEDOR"];?></td>
                <td class="cen"><?=$value["RUC"];?></td>
                <td><?=$value["CLIENTE"];?></td>
                <td class="cen"><?=$value["TIPO_CLIENTE"];?></td>
                <td class="cen <?=$classResaltado;?>"><?=$value["FORMA_VENTA"];?></td>
                <?php
              else:
                ?>
                <td class=anulado>A&nbsp;&nbsp;N&nbsp;&nbsp;U&nbsp;&nbsp;L&nbsp;&nbsp;A&nbsp;&nbsp;D&nbsp;&nbsp;O</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php
              endif;


              ?>
              <td class="cen"><?=$value["FORMA_PAGO"];?></td>
              <td class="mon"><?=($value["TCAM"]<>0 ? number_format($value["TCAM"],3,'.',',') : '');?></td>

              <?php
              if($value["FORMA_COD"]<>'F' && $value["FORMA_COD"] <> null):

                $pago = $value["PAGO_IMP"];
                 if($value["TD"]=='NC') $pago = $pago * -1;
                 if($value["REF_TD"]=='PA') $pago = $pago * -1;

                if($value["PAGO_MON"]=='MN'){ echo "<td class=mon>" . number_format($pago,2,'.',',') . "</td><td></td>"; };
                if($value["PAGO_MON"]=='US'){ echo "<td></td><td class=mon>" . number_format($pago,2,'.',',') . "</td>"; };

              else:
                echo "<td></td>";
                echo "<td></td>";
              endif;
              ?>

              <?php
              if($value["FORMA_COD"]=='F' && $value["FORMA_COD"] <> null):
                if($value["PAGO_MON"]=='MN'){ echo "<td class=mon>" . number_format($value["PAGO_IMP"],2,'.',',') . "</td>"; };
                if($value["PAGO_MON"]=='US'){ echo "<td class=mon>" . number_format($value["PAGO_IMP"],2,'.',',') . "</td>"; };
              else:
                echo "<td></td>";
              endif;

              // Saldos por documento
              if($docActual <> $value["TD"] . $value["SERIE"] . $value["DOC"]):

                if($value["SALDO_IMPORTE"] <> 0 && $value["ESTADO"]<>'A'){

                  $classResaltado = "";
                  if( strpos($value["FORMA_VENTA"],'CONTADO') !== FALSE || strpos($value["FORMA_VENTA"],'TARJETA') !== FALSE ){
                    $classResaltado = "resaltar";
                    ++$obs1;
                  }

                  if($value["SALDO_MON"]=='MN') echo "<td class='mon ".$classResaltado."'>" . number_format($value["SALDO_IMPORTE"],2,'.',',') . "</td><td></td>";
                  if($value["SALDO_MON"]=='US') echo "<td></td><td class='mon ".$classResaltado."'>" . number_format($value["SALDO_IMPORTE"],2,'.',',') . "</td>";
                }else{
                  echo "<td></td>";
                  echo "<td></td>";
                }

              else:
                echo "<td></td>";
                echo "<td></td>";
              endif;

              ?>

              <td class="cen"><?=$value["LIQUIDACION"];?></td>
              <td class="cen"><?=$value["REF_TD"];?></td>
              <td><?=$value["REF_NUM"];?></td>

              <td><?=$value["REF_TD"]=='NC' ? $value["REF_NC_APLICA"] : $value["BANCO"];?></td>

            </tr>
            <?php

            $docActual = $value["TD"] . $value["SERIE"] . $value["DOC"];

          endif;
        endforeach;
      endif;
      // FACTURACION: final


      // APLICACIONES: inicio
      if($totSeccion_2>0):
        ?>
        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="cabecera"><td></td><td>APLICACIONES</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="cabecera">
          <td>N° COMPROB.</td>
          <td>COBRADOR</td>
          <td>RUC/DNI</td>
          <td>CLIENTE</td>
          <td>T/CLIENTE</td>
          <td>COND. VENTA</td>
          <td>(DIAS) FORMA PAGO</td>
          <td>T/C</td>
          <td>CONT S/</td>
          <td>CONT US$</td>
          <td>TARJETA S/</td>
          <td>CRED S/</td>
          <td>CRED US$</td>
          <td>N° INFORME COB.</td>
          <td>T/DOC</td>
          <td>N° DOC</td>
          <td>BANCO</td>
        </tr>

        <?php
        foreach ($data as $value):
          if($value["OPCION"] == "OP-2" && $value["FORMA_COD"] == "A"):
            ?>
            <tr>
              <td class="cen"><?=$value["TD"];?>/<?=$value["SERIE"];?><?=$value["DOC"];?></td>
              <td><?=$value["VENDEDOR"];?></td>
              <td class="cen"><?=$value["RUC"];?></td>
              <td><?=$value["CLIENTE"];?></td>
              <td class="cen"><?=$value["TIPO_CLIENTE"];?></td>
              <td class="cen"><?=$value["FORMA_VENTA"];?></td>
              <td class="cen"><?=$value["FORMA_PAGO"];?></td>
              <td class="mon"><?=($value["TCAM"]<>0 ? number_format($value["TCAM"],3,'.',',') : '');?></td>
              <td class="mon"><?=$value["PAGO_MON"]=='MN' && $value["FORMA_COD"]<>'F' ? number_format($value["PAGO_IMP"],2,'.',','): '';?></td>
              <td class="mon"><?=$value["PAGO_MON"]=='US' && $value["FORMA_COD"]<>'F' ? number_format($value["PAGO_IMP"],2,'.',','): '';?></td>
              <td class="mon"><?=$value["FORMA_COD"]=='F' ? number_format($value["PAGO_IMP"],2,'.',','): '';?></td>
              <td></td>
              <td></td>
              <td class="cen"><?=$value["LIQUIDACION"];?></td>
              <td class="cen"><?=$value["REF_TD"];?></td>
              <td><?=$value["REF_NUM"];?></td>
              <td><?=$value["REF_TD"]=='NC' ? $value["REF_NC_APLICA"] : $value["BANCO"];?></td>
            </tr>
            <?php


          endif;
        endforeach;
      endif;
      // APLICACIONES: Final


      // COBRANZAS: inicio
      if($totSeccion_3>0):
        ?>
        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="cabecera"><td>&nbsp;</td><td>COBRANZAS</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr class="cabecera">
          <td>N° COMPROB.</td>
          <td>COBRADOR</td>
          <td>RUC/DNI</td>
          <td>CLIENTE</td>
          <td>T/CLIENTE</td>
          <td>COND. VENTA</td>
          <td>(DIAS) FORMA PAGO</td>
          <td>T/C</td>
          <td>CONT S/</td>
          <td>CONT US$</td>
          <td>TARJETA S/</td>
          <td>CRED S/</td>
          <td>CRED US$</td>
          <td>N° INFORME COB.</td>
          <td>T/DOC</td>
          <td>N° DOC</td>
          <td>BANCO</td>
        </tr>
        <?php
        foreach ($data as $value):
          if($value["OPCION"] == "OP-2" && $value["FORMA_COD"] <> "A"):
            ?>
            <tr>
              <td class="cen"><?=$value["TD"];?>/<?=$value["SERIE"];?><?=$value["DOC"];?> (<?=$value["REF_NC_APLICA"];?>)</td>
              <td><?=$value["VENDEDOR"];?></td>
              <td class="cen"><?=$value["RUC"];?></td>
              <td><?=$value["CLIENTE"];?></td>
              <td class="cen"><?=$value["TIPO_CLIENTE"];?></td>

              <?php
              $classResaltado = "";
              if( strpos($value["FORMA_VENTA"],'CONTADO') !== FALSE || strpos($value["FORMA_VENTA"],'TARJETA') !== FALSE ){
                $classResaltado = "resaltar_cobranza";
                ++$obs2;
              }
              ?>
              <td class="cen <?=$classResaltado;?>"><?=$value["FORMA_VENTA"];?></td>

              <td class="cen">(<?=$value["DIAS"];?>) <?=$value["FORMA_PAGO"];?></td>
              <td class="mon"><?=($value["TCAM"]<>0 ? number_format($value["TCAM"],3,'.',',') : '');?></td>
              <td class="mon"><?=$value["PAGO_MON"]=='MN' && $value["FORMA_COD"]<>'F' ? number_format($value["PAGO_IMP"],2,'.',','): '';?></td>
              <td class="mon"><?=$value["PAGO_MON"]=='US' && $value["FORMA_COD"]<>'F' ? number_format($value["PAGO_IMP"],2,'.',','): '';?></td>
              <td class="mon"><?=$value["FORMA_COD"]=='F' ? number_format($value["PAGO_IMP"],2,'.',','): '';?></td>
              <td></td>
              <td></td>
              <td class="cen"><?=$value["LIQUIDACION"];?></td>
              <td class="cen"><?=$value["REF_TD"];?></td>
              <td><?=$value["REF_NUM"];?></td>
              <td><?=$value["REF_TD"]=='NC' ? $value["REF_NC_APLICA"] : $value["BANCO"];?></td>
            </tr>
            <?php

            // Percepciones: inicio
            if($value["PERCEPCION_NUM"] <> ""):
              ?>
              <tr>
                <td class="cen">CP/<?=$value["PERCEPCION_NUM"];?><br>(<?=$value["TD"];?>/<?=$value["SERIE"];?><?=$value["DOC"];?>)</td>
                <td><?=$value["VENDEDOR"];?></td>
                <td class="cen"><?=$value["RUC"];?></td>
                <td><?=$value["CLIENTE"];?></td>
                <td class="cen"><?=$value["TIPO_CLIENTE"];?></td>
                <td class="cen"><?=$value["FORMA_VENTA"];?></td>
                <td class="cen"><?=$value["FORMA_PAGO"];?></td>
                <td class="mon"><?=($value["TCAM"]<>0 ? number_format($value["TCAM"],3,'.',',') : '');?></td>
                <td class="mon"><?=$value["PAGO_MON"]=='MN' && $value["FORMA_COD"]<>'F' ? number_format($value["PERCEPCION_IMP"],2,'.',','): '';?></td>
                <td class="mon"><?=$value["PAGO_MON"]=='US' && $value["FORMA_COD"]<>'F' ? number_format($value["PERCEPCION_IMP"],2,'.',','): '';?></td>
                <td class="mon"><?=$value["FORMA_COD"]=='F' ? number_format($value["PERCEPCION_IMP"],2,'.',','): '';?></td>
                <td></td>
                <td></td>
                <td class="cen"><?=$value["LIQUIDACION"];?></td>
                <td class="cen"><?=$value["REF_TD"];?></td>
                <td><?=$value["REF_NUM"];?></td>
                <td><?=$value["REF_TD"]=='NC' ? $value["REF_NC_APLICA"] : $value["BANCO"];?></td>
              </tr>
              <?php
            endif;
            // Percepciones: final


          endif;
        endforeach;
      endif;
      // COBRANZAS: Final

      ?>
    </table>

    <hr class="hrSepara">

    <table style="margin-top:15px" border="0">
      <tr>
        <td width="500px" style="vertical-align: top">


        <style media="screen">
        table.tblTotales { width: 100%; border-collapse: collapse;}
        table.tblTotales tr.separa td { border: 0 none}
        table.tblTotales td { border: 1px solid #333; padding: 3px 5px}
        table.tblTotales td.grupo { text-align: center; font-weight: bold;}
        table.tblTotales td.subtitulo { text-align: right; width: 120px}
        table.tblTotales td.mon { text-align: right; width: 90px}

        table.tblTotales tr.total td:nth-child(1) { border: 0 none}
        table.tblTotales tr.total td.subtitulo { border: 0 none; font-weight: bold; }
        table.tblTotales tr.total td.mon { font-weight: bold; border:1px solid #333 !important; background-color: rgb(200, 200, 200)}

        table.tblTotales tr.cab td { border: 0 none; font-weight: bold; text-align: center;}
        table.tblTotales tr.cab td:nth-child(1) { text-align: right;}
        </style>
        <table class="tblTotales">
          <tr class="cab">
            <td></td>
            <td>RESUMEN</td>
            <td>S/</td>
            <td>US$</td>
          </tr>
          <tr>
            <td rowspan="4" class="grupo">EFECTIVO</td>
            <td class="subtitulo">Ventas</td>
            <td class="mon"><?=number_format($efec["ventas"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($efec["ventas"]["US"],2,'.',',');?></td>
          </tr>
          <tr>
            <td class="subtitulo">Cobranzas</td>
            <td class="mon"><?=number_format($efec["cobranzas"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($efec["cobranzas"]["US"],2,'.',',');?></td>
          </tr>
          <tr>"
            <td class="subtitulo">Percepciones</td>
            <td class="mon"><?=number_format($efec["percepcion"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($efec["percepcion"]["US"],2,'.',',');?></td>
          </tr>
          <tr>
            <td class="subtitulo">Redondeo (Sistemas)</td>
            <td class="mon"><?=number_format($efec["redondeo"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($efec["redondeo"]["US"],2,'.',',');?></td>
          </tr>
          <tr class="total">
            <td></td>
            <td class="subtitulo">Total EFECTIVO</td>
            <td class="mon"><?=number_format($efec["total"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($efec["total"]["US"],2,'.',',');?></td>
          </tr>

          <tr class="separa">
            <td></td><td></td><td></td><td></td>
          </tr>

          <tr>
            <td rowspan="4" class="grupo">DEPOSITO</td>
            <td class="subtitulo">Ventas</td>
            <td class="mon"><?=number_format($depo["ventas"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($depo["ventas"]["US"],2,'.',',');?></td>
          </tr>
          <tr>
            <td class="subtitulo">Cobranzas</td>
            <td class="mon"><?=number_format($depo["cobranzas"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($depo["cobranzas"]["US"],2,'.',',');?></td>
          </tr>
          <tr>"
            <td class="subtitulo">Percepciones</td>
            <td class="mon"><?=number_format($depo["percepcion"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($depo["percepcion"]["US"],2,'.',',');?></td>
          </tr>
          <tr>
            <td class="subtitulo">Redondeo (Sistemas)</td>
            <td class="mon"><?=number_format($depo["redondeo"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($depo["redondeo"]["US"],2,'.',',');?></td>
          </tr>
          <tr class="total">
            <td></td>
            <td class="subtitulo">Total DEPOSITO</td>
            <td class="mon"><?=number_format($depo["total"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($depo["total"]["US"],2,'.',',');?></td>
          </tr>

          <tr class="separa">
            <td></td><td></td><td></td><td></td>
          </tr>

          <tr>
            <td rowspan="4" class="grupo">CHEQUE</td>
            <td class="subtitulo">Ventas</td>
            <td class="mon"><?=number_format($cheq["ventas"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($cheq["ventas"]["US"],2,'.',',');?></td>
          </tr>
          <tr>
            <td class="subtitulo">Cobranzas</td>
            <td class="mon"><?=number_format($cheq["cobranzas"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($cheq["cobranzas"]["US"],2,'.',',');?></td>
          </tr>
          <tr>"
            <td class="subtitulo">Percepciones</td>
            <td class="mon"><?=number_format($cheq["percepcion"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($cheq["percepcion"]["US"],2,'.',',');?></td>
          </tr>
          <tr>
            <td class="subtitulo">Redondeo (Sistemas)</td>
            <td class="mon"><?=number_format($cheq["redondeo"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($cheq["redondeo"]["US"],2,'.',',');?></td>
          </tr>
          <tr class="total">
            <td></td>
            <td class="subtitulo">Total CHEQUE</td>
            <td class="mon"><?=number_format($cheq["total"]["MN"],2,'.',',');?></td>
            <td class="mon"><?=number_format($cheq["total"]["US"],2,'.',',');?></td>
          </tr>

          <tr class="separa">
            <td></td><td></td><td></td><td></td>
          </tr>

          <tr>
            <td rowspan="4" class="grupo">TARJETA</td>
            <td class="subtitulo">Ventas</td>
            <td class="mon"><?=number_format($tarj["ventas"]["MN"],2,'.',',');?></td>
            <td class="mon">0.00</td>
          </tr>
          <tr>
            <td class="subtitulo">Cobranzas</td>
            <td class="mon"><?=number_format($tarj["cobranzas"]["MN"],2,'.',',');?></td>
            <td class="mon">0.00</td>
          </tr>
          <tr>"
            <td class="subtitulo">Percepciones</td>
            <td class="mon"><?=number_format($tarj["percepcion"]["MN"],2,'.',',');?></td>
            <td class="mon">0.00</td>
          </tr>
          <tr>
            <td class="subtitulo">Redondeo (Sistemas)</td>
            <td class="mon"><?=number_format($tarj["redondeo"]["MN"],2,'.',',');?></td>
            <td class="mon">0.00</td>
          </tr>
          <tr class="total">
            <td></td>
            <td class="subtitulo">Total TARJETA</td>
            <td class="mon"><?=number_format($tarj["total"]["MN"],2,'.',',');?></td>
            <td class="mon">0.00</td>
          </tr>

        </table>

      </td>

      <td style="padding-left:50px"></td>

      <style>
      .res_hack { background-position: -1px 0px !important}
      </style>
      <td style="vertical-align: top">
        <table>
          <?php
          if($obs1>0){
            ?>
            <tr>
              <td class="resaltar res_hack">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td>Se han observado (<?=$obs1;?>) documentos CONTADO o TARJETA que no han sido cancelados en el día</td>
            </tr>
            <?php
          }

          if($obs2>0){
            ?>
            <tr>
              <td class="resaltar_cobranza" style="border:1px solid #333">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td>Se han observado (<?=$obs2;?>) cobranzas de documentos CONTADO o TARJETA</td>
            </tr>
            <?php
          }
          ?>
        </table>

        <style media="screen">
        table.firmas {
          width: 600px; margin-top: 140px
        }
        table.firmas td.separa { padding-left: 20px; border: 0 none}
        table.firmas td { border-top: 1px solid; text-align: center; padding: 4px}
        </style>
        <table class="firmas">
          <tr>
            <td>RESPONSABLE<br><strong><?=$cabecera["RESPON"];?></strong></td>
            <td class="separa"></td>
            <td>ADMINISTRACION<br><strong><?=$cabecera["ADMIN"];?></strong></td>
          </tr>
        </table>

      </td>


    </tr>
  </table>
