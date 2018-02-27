<?php
ini_set('max_execution_time', 300);

$totSeccion_1 = 0; // FACTURACION
$totSeccion_2 = 0; // APLICACIONES
$totSeccion_3 = 0; // COBRANZAS

$cabecera = json_decode(json_encode($data), true);
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

?>

<html>
<head>
  <title>Reporte de Ventas</title>

  <style>
  header { position: fixed; top: 0px; left: 0px; right: 0px; background-color: red; height: 50px; color: white; }
  footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; text-align: center; border-top: 1px solid #000;
  padding-top: 5px}

  * { font-family: "Calibri", sans-serif; font-size: 10px}
  body, html { width: 100% !important}
  body { margin-top: 60px}
  table#tbReporte { width: 100% !important; margin: 5px auto; border-collapse: collapse;}
  table#tbReporte tr.cabecera {}
    table#tbReporte tr.cabecera td { font-weight: bold; padding: 6px 3px; font-size: 9px; text-align: center;}
    table#tbReporte tr td { font-size: 9px; border: 1px solid #222; padding: 3px 5px; cursor: default}
    table#tbReporte tbody td.mon { text-align: right; padding: 0 10px 0 15px}
    table#tbReporte tbody td.cen { text-align: center; }
    table#tbReporte tbody td.anulado { color: rgb(121, 15, 15)}
    table#tbReporte tbody tr:hover { background-color: rgb(197, 255, 177) !important}
    table#tbReporte tbody tr.sameFT { background-color: rgba(205, 244, 244, 0.66)}

    </style>
  </head>
  <body>
    <header>on each page</header>

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
          <td>VENDEDOR / COBRADOR</td>
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
        $docActual = "";

        foreach ($data as $value):
          if($value["OPCION"] == "OP-1"):
            ?>
            <tr class="<?=$value["COUNT_PAGOS"] > 1 ? 'sameFT' : '';?>">

              <td class="cen"><?=$value["TD"];?>/<?=$value["SERIE"];?><?=$value["DOC"];?></td>

              <?php
              if($value["ESTADO"]<>'A'):
                ?>
                <td><?=$value["VENDEDOR"];?></td>
                <td class="cen"><?=$value["RUC"];?></td>
                <td><?=$value["CLIENTE"];?></td>
                <td class="cen"><?=$value["TIPO_CLIENTE"];?></td>
                <td class="cen"><?=$value["FORMA_VENTA"];?></td>
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

                if($value["SALDO_IMPORTE"] > 0){
                  if($value["SALDO_MON"]=='MN') echo "<td class=mon>" . number_format($value["SALDO_IMPORTE"],2,'.',',') . "</td><td></td>";
                  if($value["SALDO_MON"]=='US') echo "<td></td><td class=mon>" . number_format($value["SALDO_IMPORTE"],2,'.',',') . "</td>";
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
          <td>VENDEDOR / COBRADOR</td>
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
          <td>VENDEDOR / COBRADOR</td>
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
              <td class="cen"><?=$value["FORMA_VENTA"];?></td>
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
