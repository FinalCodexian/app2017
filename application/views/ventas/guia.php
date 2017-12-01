<?
if(!isset($data)) exit();

$todo = json_decode($data, true);
?>

<style media="screen">
.xInfo { height: 98%; padding: 0; margin: 0; }
.xInfo .resultado .cabecera  { margin-top: 10px}
.xInfo .resultado { border: 0 none; margin-bottom: 20px}
.xInfo .resultado .label.transporte { color: rgb(26, 116, 154)}
.xInfo .xFooter { background: red; }
.xInfo .ui.header .content { font-size: 18px}
.xInfo .ui.header .content .sub.header { font-size: 12px}
</style>
<div class='xInfo'>
  <div class='resultado'>

    <?
    if(sizeof($todo["cabecera"])>1):
      ?>
      <div class="ui negative icon message">
        <i class="warning icon"></i>
        <div class="content">
          <div class="header">Este numero de Gu&iacute;a de Remisi&oacute;n tiene dos registros en almacenes distintos</div>
          <p>Informar al &aacute;rea correspondiente para su revisi&oacute;n</p>
        </div>
      </div>
      <?php
    endif;

    foreach ($todo["cabecera"] as $value):

      if($value["ESTADO"]=='A'){
        ?>
        <div class="ui info icon message">
          <i class="info icon"></i>
          <div class="content">
            <div class="header">Esta Gu&iacute;a de Remisi&oacute;n se encuentra ANULADA</div>
            <p>Al momento de consultar este documento, se anul&oacute; en el sistema <i class="frown icon"></i></p>
          </div>
        </div>
        <?php
      }

      ?>

      <div class="ui grid">
        <div class="eight wide column">
          <h2 class="ui header">
            <div class="content">
              Gu&iacute;a <?=$value["GUIA"];?> <span>(<?=$value["FECHA"];?>)</span>
              <div class="sub header">Almac&eacute;n: <b><?=$value["ALMACEN_COD"];?> - <?=$value["ALMACEN"];?></b></div>
            </div>
          </h2>
        </div>
        <div class="right aligned eight wide column">
          <h2 class="ui header">
            <div class="content">
              Ref. <?=$value["REFERENCIA"];?>
              <div class="sub header">Vendedor: <b><?=$value["VENDEDOR"];?></b></div>
            </div>
          </h2>
        </div>
      </div>

      <table class='cabecera'>
        <tr><td colspan="3"><label class="label">Cliente</label> <?=$value["CLIENTE"];?></td></tr>
        <tr><td colspan="3"><label class="label">Destino</label> <?=$value["DESTINO"];?></td></tr>
        <?
        if($value["TRANSPORTE"]<>''){
          ?>
          <tr><td colspan="3"><label class='label transporte'>Agencia de transporte</label> <?=$value["TRANSPORTE"];?></td></tr>
          <tr><td colspan="3"><label class='label transporte'>Direcci&oacute;n de agencia</label> <?=$value["TRANSPORTE_DIRECCION"];?></td></tr>
          <?php
        }
        ?>
      </table>
      <?php
    endforeach;

    ?>
    <table class="detalle">
      <thead><tr><th>C&oacute;digo</th><th>Descripci&oacute;n</th><th>Cantidad</th></tr></thead>
      <tbody>
        <?php
        $totDet = 0;
        foreach ($todo["detalle"] as $value):
          $totDet = $totDet + $value["CANTIDAD"];
          ?>
          <tr>
            <td align=center><?=$value["CODIGO"];?></td>
            <td ><?=$value["PRODUCTO"];?></td>
            <td align=center><?=intval($value["CANTIDAD"]);?></td>
          </tr>
          <?php
        endforeach;
        ?>
        <tr>
          <td class='totVacio' colspan=2></td>
          <td class='totCantidad'><?=intval($totDet);?></td>
        </tr>
      </tbody>
    </table>

    <div class="ui hidden divider"></div>

    <?php
    foreach ($todo["cabecera"] as $value):
      ?>
      <table class='cabecera'>
        <tr>
          <td><label class="label">Glosa</label> <?=$value["GLOSA"];?></td>
          <td><label class="label">Movimiento</label> <?=$value["MOVIMIENTO"];?></td>
        </tr>
        <tr>
          <td><label class='label'>Usuario</label> <?=$value["USUARIO"];?></td>
          <td><label class='label'>Fec. Emisi&oacute;n</label> <?=$value["CREACION"];?></td>
        </tr>
      </table>
      <?php
    endforeach;
    ?>
  </div>
</div>
