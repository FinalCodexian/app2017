<?php
$data = array("titulo"=>"Reporte de Ventas");
$sess = $this->uri->segment(3, 0);
$this->load->view("header", $data);
$this->load->view("sidebar");
$this->load->view("menu_top");

?>

<style media="screen">
table.resumen { border-collapse: collapse; width: 95%; font-size: 11px !important; margin: auto !important; }
table.resumen thead tr th { text-align: center !important; color: #06c; padding: 5px 0}
table.resumen thead tr th.vacio, table.resumen tr td.vacio { border: 0 none}
table.resumen td, table.resumen th { border: 1px solid #999; padding: 1px 10px}
table.resumen td.separa {border: 0 none; height: 12px}
table.resumen td.grupo {text-align: center; font-weight: bold;}
table.resumen td.importe {text-align: right; padding-right: 12px; width: 120px}
table.resumen td.total {text-align: right; padding-right: 12px; font-weight: bold; background-color: rgba(214, 214, 214, 0.4)}
table.resumen td.total_des {text-align: right; padding-right: 12px; font-weight: bold; border: 0px none}

#exporta_1, #exporta_2, #btnReporte { font-weight: normal;}
#exporta_1, #exporta_2 {padding-bottom:10px}
#exporta_1 i, #exporta_2 i {margin-bottom: 7px !important}

</style>


<div class="ui divided grid" style="margin:0px auto">

  <div class="seven wide column">

    <h5 class="ui dividing header">
      Reporte de Ventas
    </h5>

    <form class="ui tiny form" autocomplete="off">
      <div class="field">
        <label>Agencia</label>
        <select class="ui dropdown" id="agencia"></select>
      </div>

      <div class="two fields">

        <div class="field">
          <label>Fecha</label>
          <div class="ui icon input">
            <input id="fecha" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
          </div>
        </div>

        <div class="field">
          <label>&nbsp;</label>
          <button type="button" class="ui tiny blue fluid button" id="btnReporte">Reporte</button>
        </div>
      </div>


      <div class="ui hidden divider"></div>

      <div class="ui disabled segment">

        <h6 class="ui horizontal divider header">
          <i class="check circle outline icon"></i>
          Datos generales
        </h6>


        <div class="field">

          <table class="ui definition table">
            <tbody>
              <tr>
                <td width="120px">Reporte</td>
                <td>......</td>
              </tr>
              <tr>
                <td>Día de la semana</td>
                <td>Jueves</td>
              </tr>
              <tr>
                <td>Responsable</td>
                <td>Jenny Venegas</td>
              </tr>
              <tr>
                <td>Administración</td>
                <td>Jenny Mamani Manchego</td>
              </tr>
            </tbody>
          </table>

        </div>

        <div class="two fields">
          <div class="field">
            <button type="button" id="exporta_1" class="ui tiny fluid teal button" disabled>
              <i class="zmdi zmdi-check-all zmdi-hc-fw zmdi-hc-2x"></i><br>
              Gerencia
            </button>
          </div>
          <div class="field">
            <button type="button" id="exporta_2" class="ui tiny fluid orange button" disabled>
              <i class="zmdi zmdi-check-all zmdi-hc-fw zmdi-hc-2x"></i><br>
              Finanzas y Contabilidad
            </button>
          </div>
        </div>
      </div>
    </form>

  </div>


  <div class="nine wide column">


    <div class="ui disabled segment">

      <table class="resumen">
        <thead>
          <tr>
            <th class="vacio"></th>
            <th class="vacio"></th>
            <th width=70>Soles ( S/ )</th>
            <th width=70>Dolares ( US$ )</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td rowspan="4" class="grupo">EFECTIVO</td>
            <td>Ventas</td><td class="importe" id="efec_ventas_MN">0.00</td><td class="importe" id="efec_ventas_US">0.00</td>
          </tr>
          <tr><td>Cobranzas</td><td class="importe" id="efec_cobran_MN">0.00</td><td class="importe" id="efec_cobran_US">0.00</td></tr>
          <tr><td>Percepciones</td><td class="importe" id="efec_percep_MN">0.00</td><td class="importe" id="efec_percep_US">0.00</td></tr>
          <tr><td>Redondeo (Sistema)</td><td class="importe" id="efec_redond_MN">0.00</td><td class="importe" id="efec_redond_US">0.00</td></tr>
          <tr><td class="vacio"></td><td class="total_des">Total EFECTIVO</td>
            <td class="total" id="efec_total_MN">0.00</td>
            <td class="total" id="efec_total_US">0.00</td>
          </tr>

          <tr><td class="separa"></td></tr>

          <tr>
            <td rowspan="4" class="grupo">DEPOSITO</td>
            <td>Ventas</td><td class="importe" id="depo_ventas_MN">0.00</td><td class="importe" id="depo_ventas_US">0.00</td>
          </tr>
          <tr><td>Cobranzas</td><td class="importe" id="depo_cobran_MN">0.00</td><td class="importe" id="depo_cobran_US">0.00</td></tr>
          <tr><td>Percepciones</td><td class="importe" id="depo_percep_MN">0.00</td><td class="importe" id="depo_percep_US">0.00</td></tr>
          <tr><td>Redondeo (Sistema)</td><td class="importe" id="depo_redond_MN">0.00</td><td class="importe" id="depo_redond_US">0.00</td></tr>
          <tr>
            <td class="vacio"></td><td class="total_des">Total DEPOSITO</td>
            <td class="total" id="depo_total_MN">0.00</td><td class="total" id="depo_total_US">0.00</td>
          </tr>

          <tr><td class="separa"></td></tr>

          <tr>
            <td rowspan="4" class="grupo">CHEQUE</td>
            <td>Ventas</td><td class="importe" id="cheq_ventas_MN">0.00</td><td class="importe" id="cheq_ventas_US">0.00</td>
          </tr>
          <tr><td>Cobranzas</td><td class="importe" id="cheq_cobran_MN">0.00</td><td class="importe" id="cheq_cobran_US">0.00</td></tr>
          <tr><td>Percepciones</td><td class="importe" id="cheq_percep_MN">0.00</td><td class="importe" id="cheq_percep_US">0.00</td></tr>
          <tr><td>Redondeo (Sistema)</td><td class="importe" id="cheq_redond_MN">0.00</td><td class="importe" id="cheq_redond_US">0.00</td></tr>
          <tr>
            <td class="vacio"></td><td class="total_des">Total CHEQUE</td>
            <td class="total" id="cheq_total_MN">0.00</td><td class="total" id="cheq_total_US">0.00</td>
          </tr>

          <tr><td class="separa"></td></tr>

          <tr>
            <td rowspan="4" class="grupo">TARJETA</td>
            <td>Ventas</td><td class="importe" id="tarj_ventas_MN">0.00</td><td class="importe" id="tarj_ventas_US">0.00</td>
          </tr>
          <tr><td>Cobranzas</td><td class="importe" id="tarj_cobran_MN">0.00</td><td class="importe" id="tarj_cobran_US">0.00</td></tr>
          <tr><td>Percepciones</td><td class="importe" id="tarj_percep_MN">0.00</td><td class="importe" id="tarj_percep_US">0.00</td></tr>
          <tr><td>Redondeo (Sistema)</td><td class="importe" id="tarj_redond_MN">0.00</td><td class="importe" id="tarj_redond_US">0.00</td></tr>
          <tr>
            <td class="vacio"></td><td class="total_des">Total TARJETA</td>
            <td class="total" id="tarj_total_MN">0.00</td><td class="total" id="tarj_total_US">0.00</td>
          </tr>

        </tbody>
      </table>

    </div>

  </div>

</div>



<div id="rrr"></div>

<form id="TheForm" method="post" action="<?=base_url('ventas/reporteVta');?>" target="TheWindow">
  <input type="hidden" name="agencia" value="something" />
  <input type="hidden" name="fecha" value="something" />
  <input type="hidden" name="base" value="something" />
  <input type="hidden" name="opcion" value="something" />
</form>


<script type="text/javascript">
$(function(){


  function openWindowWithPost() {
    var f = document.getElementById('TheForm');
    f.agencia.value = $("#agencia").val();
    f.fecha.value = $("#fecha").val();
    f.base.value = "<?=$this->session->userdata($sess)["base"];?>";
    f.opcion.value = "pdf";
    window.open('', 'TheWindow');
    f.submit();
  }

  $("#exporta").on("click",function(){
    openWindowWithPost();
  });

  $("#btnReporte").on("click",function(){

    HoldOn.open({ theme:"sk-bounce" });
    // $.ajax({
    //   type: 'POST',
    //   url: '< ?=base_url('ventas/reporteVta');?>',
    //   data: {
    //     opcion: 'resumen',
    //     agencia: $("#agencia").val(),
    //     fecha: $("#fecha").val(),
    //     base: "< ?=$this->session->userdata($sess)["base"];?>"
    //   },
    //   dataType: 'json',
    //   beforeSend: function(){
    //
    //     $(".importe, .total").html("0.00")
    //     $("#rrr").html("")
    //   }
    // });


    $.ajax({
      type: 'POST',
      url: '<?=base_url('ventas/reporteVta');?>',
      data: {
        opcion: 'resumen',
        agencia: $("#agencia").val(),
        fecha: $("#fecha").val(),
        base: "<?=$this->session->userdata($sess)["base"];?>"
      },
      dataType: 'json',
      beforeSend: function(){
        // HoldOn.open({ theme:"sk-bounce" });
        $(".importe, .total").html("0.00")
        $("#rrr").html("")
      },
      success: function(d){

        var obj_percepcion = { efectivo: 0, deposito: 0, cheque: 0, tarjeta: 0 }
        let $redondeo_MN = Object.assign({}, obj_percepcion);
        let $redondeo_US = Object.assign({}, obj_percepcion);

        var obj_secciones = { ventas: 0, cobranzas: 0, percepciones: 0 }
        let $efectivo_MN = Object.assign({}, obj_secciones);
        let $efectivo_US = Object.assign({}, obj_secciones);

        let $deposito_MN = Object.assign({}, obj_secciones);
        let $deposito_US = Object.assign({}, obj_secciones);

        let $cheque_MN = Object.assign({}, obj_secciones);
        let $cheque_US = Object.assign({}, obj_secciones);

        let $tarjeta_MN = Object.assign({}, obj_secciones);
        let $tarjeta_US = Object.assign({}, obj_secciones);



        $.each(d.redondeos, function(index, item) {
          // if(item.FORMA_COD=='R'){
          if(item.FORMA=='E'){
            $redondeo_MN.efectivo += (item.MONEDA=='MN' ? parseFloat(item.MN) : 0);
            $redondeo_US.efectivo += (item.MONEDA=='US' ? parseFloat(item.US) : 0);
          }
          if(item.FORMA=='B'){
            $redondeo_MN.deposito += (item.MONEDA=='MN' ? parseFloat(item.MN) : 0);
            $redondeo_US.deposito += (item.MONEDA=='US' ? parseFloat(item.US) : 0);
          }
          if(item.FORMA=='C'){
            $redondeo_MN.cheque += (item.MONEDA=='MN' ? parseFloat(item.MN) : 0);
            $redondeo_US.cheque += (item.MONEDA=='US' ? parseFloat(item.US) : 0);
          }
          if(item.FORMA=='F'){
            $redondeo_MN.tarjeta += (item.MONEDA=='MN' ? parseFloat(item.MN) : 0);
            $redondeo_US.tarjeta += (item.MONEDA=='US' ? parseFloat(item.US) : 0);
          }
          // }
        });

        $.each(d.data, function(index, item) {

          // SECCION 1: TOTALES EFECTIVO
          if(item.FORMA_COD=='E'){
            if(item.OPCION=='OP-1'){
              $efectivo_MN.ventas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $efectivo_US.ventas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2'){
              $efectivo_MN.cobranzas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $efectivo_US.cobranzas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2' && item.PERCEPCION_NUM!==''){
              $efectivo_MN.percepciones += (item.PAGO_MON=='MN' ? parseFloat(item.PERCEPCION_IMP) : 0);
              $efectivo_US.percepciones += (item.PAGO_MON=='US' ? parseFloat(item.PERCEPCION_IMP) : 0);
            }
          }

          // SECCION 1: TOTALES DEPOSITO
          if(item.FORMA_COD=='B'){
            if(item.OPCION=='OP-1'){
              $deposito_MN.ventas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $deposito_US.ventas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2'){
              $deposito_MN.cobranzas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $deposito_US.cobranzas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2' && item.PERCEPCION_NUM!==''){
              $deposito_MN.percepciones += (item.PAGO_MON=='MN' ? parseFloat(item.PERCEPCION_IMP) : 0);
              $deposito_US.percepciones += (item.PAGO_MON=='US' ? parseFloat(item.PERCEPCION_IMP) : 0);
            }
          }

          // SECCION 1: TOTALES CHEQUE
          if(item.FORMA_COD=='C'){
            if(item.OPCION=='OP-1'){
              $cheque_MN.ventas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $cheque_US.ventas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2'){
              $cheque_MN.cobranzas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $cheque_US.cobranzas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2' && item.PERCEPCION_NUM!==''){
              $cheque_MN.percepciones += (item.PAGO_MON=='MN' ? parseFloat(item.PERCEPCION_IMP) : 0);
              $cheque_US.percepciones += (item.PAGO_MON=='US' ? parseFloat(item.PERCEPCION_IMP) : 0);
            }
          }

          // SECCION 1: TOTALES TARJETA
          if(item.FORMA_COD=='F'){
            if(item.OPCION=='OP-1'){
              $tarjeta_MN.ventas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $tarjeta_US.ventas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2'){
              $tarjeta_MN.cobranzas += (item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP) : 0);
              $tarjeta_US.cobranzas += (item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP) : 0);
            }
            if(item.OPCION=='OP-2' && item.PERCEPCION_NUM!==''){
              $tarjeta_MN.percepciones += (item.PAGO_MON=='MN' ? parseFloat(item.PERCEPCION_IMP) : 0);
              $tarjeta_US.percepciones += (item.PAGO_MON=='US' ? parseFloat(item.PERCEPCION_IMP) : 0);
            }
          }


        });

        $("#efec_ventas_MN").html( $.number( $efectivo_MN.ventas, 2, '.', ',') );
        $("#efec_ventas_US").html( $.number( $efectivo_US.ventas, 2, '.', ',') );

        $("#efec_cobran_MN").html( $.number( $efectivo_MN.cobranzas, 2, '.', ',') );
        $("#efec_cobran_US").html( $.number( $efectivo_US.cobranzas, 2, '.', ',') );

        $("#efec_percep_MN").html( $.number( $efectivo_MN.percepciones, 2, '.', ',') );
        $("#efec_percep_US").html( $.number( $efectivo_US.percepciones, 2, '.', ',') );

        $("#efec_redond_MN").html( $.number( $redondeo_MN.efectivo, 2, '.', ',') );
        $("#efec_redond_US").html( $.number( $redondeo_US.efectivo, 2, '.', ',') );

        var $T_efectivo_MN = ($efectivo_MN.ventas + $efectivo_MN.cobranzas + $efectivo_MN.percepciones + $redondeo_MN.efectivo);
        var $T_efectivo_US = ($efectivo_US.ventas + $efectivo_US.cobranzas + $efectivo_US.percepciones + $redondeo_US.efectivo);
        $("#efec_total_MN").html( $.number( $T_efectivo_MN, 2, '.', ','))
        $("#efec_total_US").html( $.number( $T_efectivo_US, 2, '.', ','))


        $("#depo_ventas_MN").html( $.number( $deposito_MN.ventas, 2, '.', ',') );
        $("#depo_ventas_US").html( $.number( $deposito_US.ventas, 2, '.', ',') );

        $("#depo_cobran_MN").html( $.number( $deposito_MN.cobranzas, 2, '.', ',') );
        $("#depo_cobran_US").html( $.number( $deposito_US.cobranzas, 2, '.', ',') );

        $("#depo_percep_MN").html( $.number( $deposito_MN.percepciones, 2, '.', ',') );
        $("#depo_percep_US").html( $.number( $deposito_US.percepciones, 2, '.', ',') );

        $("#depo_redond_MN").html( $.number( $redondeo_MN.deposito, 2, '.', ',') );
        $("#depo_redond_US").html( $.number( $redondeo_US.deposito, 2, '.', ',') );

        var $T_deposito_MN = ($deposito_MN.ventas + $deposito_MN.cobranzas + $deposito_MN.percepciones + $redondeo_MN.deposito);
        var $T_deposito_US = ($deposito_US.ventas + $deposito_US.cobranzas + $deposito_US.percepciones + $redondeo_US.deposito);
        $("#depo_total_MN").html( $.number( $T_deposito_MN, 2, '.', ','))
        $("#depo_total_US").html( $.number( $T_deposito_US, 2, '.', ','))


        $("#cheq_ventas_MN").html( $.number( $cheque_MN.ventas, 2, '.', ',') );
        $("#cheq_ventas_US").html( $.number( $cheque_US.ventas, 2, '.', ',') );

        $("#cheq_cobran_MN").html( $.number( $cheque_MN.cobranzas, 2, '.', ',') );
        $("#cheq_cobran_US").html( $.number( $cheque_US.cobranzas, 2, '.', ',') );

        $("#cheq_percep_MN").html( $.number( $cheque_MN.percepciones, 2, '.', ',') );
        $("#cheq_percep_US").html( $.number( $cheque_US.percepciones, 2, '.', ',') );

        $("#cheq_redond_MN").html( $.number( $redondeo_MN.deposito, 2, '.', ',') );
        $("#cheq_redond_US").html( $.number( $redondeo_US.deposito, 2, '.', ',') );

        var $T_cheque_MN = ($cheque_MN.ventas + $cheque_MN.cobranzas + $cheque_MN.percepciones + $redondeo_MN.cheque);
        var $T_cheque_US = ($cheque_US.ventas + $cheque_US.cobranzas + $cheque_US.percepciones + $redondeo_US.cheque);
        $("#cheq_total_MN").html( $.number( $T_cheque_MN, 2, '.', ','))
        $("#cheq_total_US").html( $.number( $T_cheque_US, 2, '.', ','))


        $("#tarj_ventas_MN").html( $.number( $tarjeta_MN.ventas, 2, '.', ',') );
        $("#tarj_ventas_US").html( $.number( $tarjeta_US.ventas, 2, '.', ',') );

        $("#tarj_cobran_MN").html( $.number( $tarjeta_MN.cobranzas, 2, '.', ',') );
        $("#tarj_cobran_US").html( $.number( $tarjeta_US.cobranzas, 2, '.', ',') );

        $("#tarj_percep_MN").html( $.number( $tarjeta_MN.percepciones, 2, '.', ',') );
        $("#tarj_percep_US").html( $.number( $tarjeta_US.percepciones, 2, '.', ',') );

        $("#tarj_redond_MN").html( $.number( $redondeo_MN.tarjeta, 2, '.', ',') );
        $("#tarj_redond_US").html( $.number( $redondeo_US.tarjeta, 2, '.', ',') );

        var $T_tarjeta_MN = ($tarjeta_MN.ventas + $tarjeta_MN.cobranzas + $tarjeta_MN.percepciones + $redondeo_MN.tarjeta);
        var $T_tarjeta_US = ($tarjeta_US.ventas + $tarjeta_US.cobranzas + $tarjeta_US.percepciones + $redondeo_US.tarjeta);
        $("#tarj_total_MN").html( $.number( $T_tarjeta_MN, 2, '.', ','))
        $("#tarj_total_US").html( $.number( $T_tarjeta_US, 2, '.', ','))


        $("#exporta_1, #exporta_2").prop("disabled", false);
        $(".segment.disabled").removeClass('disabled').addClass("yellow");

        HoldOn.close();

      }
    })

  })


  HoldOn.open({ theme:"sk-bounce" });

  $('.datepicker').pikaday({
    firstDay: 1,
    yearRange: [2016,2025],
    format: 'DD/MM/YYYY',
    theme: 'triangle-theme',
    todayButton    : true
  });

  // combo Agencias
  $.ajax({
    url: '<?=base_url('datos/fnListaSimple');?>',
    data: { opcion: 'agencias', base: "<?=$this->session->userdata($sess)['base'];?>", buscar: '' },
    dataType: 'json',
    success: function(d){
      $.each(d.results, function(index, item) {
        $("<option>"+item.AGENCIA+"</option>").val(item.CODIGO).appendTo("#agencia")
      });

      $("#agencia").val('9100')
      $("#fecha").val('18/01/2018')
      HoldOn.close();
    }
  })


})
</script>




<?php
$this->load->view("footer");
?>
