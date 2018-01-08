<?php
$sess = $this->uri->segment(3, 0);
$data = array(
  "titulo"=>"Ranking de ventas"
);

$this->load->view("header", $data);
$this->load->view("ventas/estilos.php");
?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">

    <div class="ui tiny secondary yellow segment contenedorResult">

      <div class="ui tiny form">

        <div class="ui centered stackable grid" style="padding-bottom: -20px !important; margin-bottom: -15px !important">

          <div class="six wide column field" style="padding-bottom:0px">
            <div class="two fields">
              <div class="field">
                <label>Fecha inicio</label>
                <div class="ui icon input">
                  <input id="fecInicio" type="text" class="datepicker" value="<?=date("01/m/Y");?>"><i class="calendar icon"></i>
                </div>
              </div>
              <div class="field">
                <label>Fecha final</label>
                <div class="ui icon input">
                  <input id="fecFinal" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="three wide column" style="padding-bottom:0px !important; margin-bottom: 0px !important">
            <button id="btnEjecutar" class="ui tiny fluid basic teal button">
              <i class="zmdi zmdi-check-all zmdi-hc-fw zmdi-hc-2x"></i><br>
              Buscar ventas
            </button>
          </div>

          <div class="three wide column" style="padding-bottom:0px">
            <button id="excel" class="ui tiny basic fluid green button">
              <i class="zmdi zmdi-download zmdi-hc-fw zmdi-hc-2x"></i><br>
              Descargar Excel
            </button>
            <form style="display:none" id="formExcel" enctype='application/json' action="< ?=site_url('excel/ventas_excel/venta_x_clientes');?>" method="post" target="_blank">
              <textarea name="contenido" rows="8" cols="80"></textarea>
            </form>
          </div>


        </div>

      </div>

    </div>

    <div class="ui tiny" id="resultado" style="margin:6px auto">

    <style media="screen">
    .xBusqueda { height: calc(100vh - 260px); width: 100%; padding: 0px; overflow-y: auto; border: 1px solid rgba(233, 233, 233, 0.66)}
    .dataTables_scrollBody { font-size: 12px}
    .dataTables_scrollBody th { text-align: center !important;}
    </style>

    <div class="xBusqueda">
      <table id="example" class="display compact dataTables_scrollBody" cellspacing="0" width="100%">
      </table>
    </div>



<style media="screen">
  .statistic div.label { font-weight: bold; font-size: 11px !important}
</style>
    <div class="ui center aligned segment" style="border-bottom: 2px solid rgba(5, 103, 0, 0.57)">
      <div class="ui mini two statistics">

        <div class="ui blue statistic">
          <div class="value" id="cantidad">0</div>
          <div class="label">UNIDADES</div>
        </div>

        <div class="ui green statistic">
          <div class="value" id="total">0</div>
          <div class="label">TOTAL US$</div>
        </div>

      </div>
    </div>

  </div>
</div>



<script type="text/javascript">
$(function(){

  $('.datepicker').mask('00/00/0000', {placeholder: "__/__/____"});

  $('.datepicker').pikaday({
    firstDay: 1,
    yearRange: [2010,2020],
    format: 'DD/MM/YYYY',
    theme: 'triangle-theme'
  });

  var $ajuste = $(window).height() - 310;

  var $busqueda = $('#example').DataTable({
    language: { url: '<?=base_url("/tools/datatables/Spanish.json");?>' },
    data:[],
    columns: [
      {title: "Vendedor", data: "VENDEDOR"},
      {title: "Cantidad",  width: "60", data: "CANTID", className: "dt-center", render: $.fn.dataTable.render.number(',', '.', 0, '', '')},
      {title: "Valor Venta<br>(US$)",  width: "80", data: "NETO", className: "dt-right", render: $.fn.dataTable.render.number(',', '.', 2, '', '')},
      {title: "IGV<br>(US$)",  width: "80", data: "IGV", className: "dt-right", render: $.fn.dataTable.render.number(',', '.', 2, '', '')},
      {title: "Precio Venta<br>(US$)",  width: "80", data: "TOTAL", className: "dt-right", render: $.fn.dataTable.render.number(',', '.', 2, '', '')}
    ],
    scrollY: $ajuste,
    scrollCollapse: true,
    paging: false,
    info: false,
    searching: false
  });


  $("#btnEjecutar").on("click",function(){
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: "<?=base_url('ventas/ranking')?>",
      data: {
        base: "<?=$this->session->userdata($sess)["base"];?>",
        concar: "<?=$this->session->userdata($sess)["concar"];?>",
        agencia: "<?=$this->session->userdata($sess)["agenciaId"];?>",
        inicio: $("#fecInicio").val(),
        final: $("#fecFinal").val(),
      },
      beforeSend: function(){
        $busqueda.clear().draw();
        $busqueda.draw().order( [[ 4, 'desc' ]] );
      }
    }).done(function(result){

      $busqueda.rows.add(result.results).draw();

      $("#total").html($.number(result.total, 2, '.', ','))
      $("#cantidad").html($.number(result.cantidad, 0, '.', ','))

      $(".xxx").on("click",function(e){
        e.preventDefault();
        $codUsuario = $(this).data("usuario");
        alert($codUsuario)

      })

    })
  })


})
</script>

<? $this->load->view("footer"); ?>
