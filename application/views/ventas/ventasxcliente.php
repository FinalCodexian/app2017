<?php
$data = array(
  "titulo"=>"Ventas por cliente"
);
?>

<? $this->load->view("header", $data); ?>



<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">

    <div class="ui tiny secondary yellow segment contenedorResult">

      <div class="ui tiny form ">

        <div class="ui stackable grid">

          <div class="eight wide column">
            <div class="inline field">
              <select id="cboCliente" data-placeholder='- Todos los <b>clientes</b> -'></select>
            </div>
            <div class="field">
              <select id="cboVendedor" data-placeholder='- Todos los <b>vendedores</b> -'></select>
            </div>

            <div class="fields">
              <div class="eleven wide field">
                <select id="cboProducto" data-placeholder='- Todos los <b>productos</b> -'></select>
              </div>
              <div class="five wide right field">
                <select id="cboMarca" data-placeholder='- Todas las <b>marcas</b> -'></select>
              </div>
            </div>

          </div>

          <div class="five wide column field">
            <div class="two fields">
              <div class="field">
                <label>Fecha inicio</label>
                <div class="ui icon input">
                  <input id="fecInicio" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
                </div>
              </div>
              <div class="field">
                <label>Fecha final</label>
                <div class="ui icon input">
                  <input id="fecFinal" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
                </div>
              </div>
            </div>

            <style>
            .opciones { text-align: center;}
            .opciones label.header {
              font-size: 12px; font-weight: bold;
              text-align: left;
              border-bottom: 1px solid rgba(94, 94, 94, .6);
              padding-bottom: 3px; display: block;
              margin-bottom: 8px
            }
            </style>

            <div class="opciones">
              <label class="header">Opciones segun atenci&oacute;n</label>
              <div class="pretty p-icon p-round p-pulse">
                <input value="" type="radio" name="opcion" checked/>
                <div class="state p-primary">
                  <i class="icon check"></i>
                  <label>Todo</label>
                </div>
              </div>

              <div class="pretty p-icon p-round p-pulse">
                <input value="S" type="radio" name="opcion" />
                <div class="state p-primary">
                  <i class="icon check"></i>
                  <label>Sin gu&iacute;a de atenci&oacute;n</label>
                </div>
              </div>

            </div>

          </div>

          <div class="three wide column">

            <button id="btnEjecutar" class="ui tiny fluid basic teal  button" style="margin-bottom:4px">
              <i class="zmdi zmdi-check-all zmdi-hc-fw zmdi-hc-2x"></i><br>
              Buscar ventas
            </button>

            <button class="ui tiny basic fluid green button" disabled>
              <i class="zmdi zmdi-download zmdi-hc-fw zmdi-hc-2x"></i><br>
              Descargar a excel
            </button>

          </div>

        </div>

      </div>

    </div>

    <div class="ui tiny" id="resultado" style="margin:6px auto">
      #resultado
    </div>

  </div>

  <style>

  </style>
  <a href="#" class="alCielo"><i class="zmdi zmdi-chevron-up zmdi-hc-3x"></i></a>

  <div id="excedido" style="display:none">
    <div class="ui icon info message" style="width: 640px; margin:auto">
      <i class="spy icon"></i>
      <div class="content">
        <div class="header">Su reporte tiene demasiados documentos.. <span>0</span> para ser exactos</div>
        <p><i class="circular info icon"></i> Le recomendamos <u>descargarlo</u> para poder revisarlo</p>
      </div>
    </div>
  </div>

</div>

</div>
</div>

<script>
$(function(){

  $('.datepicker').mask('00/00/0000', {placeholder: "__/__/____"});

  $.fn.select2.defaults.set('theme', 'xLuis')
  $.fn.select2.defaults.set('language', 'es');
  $.fn.select2.defaults.set('allowClear', 'true');
  $.fn.select2.defaults.set('width', '100%');

  $('.combo').select2({
    minimumResultsForSearch: Infinity,
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo2
  });

  $('#cboMarca').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple');?>',
      data: function(params){ return { q: params.term, opcion: 'marcas' }; },
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo2
  });

  $('#cboCliente').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple');?>',
      data: function(params){ return {
        q: params.term,
        vendedor: "<?=$this->session->userdata('vendedor');?>",
        opcion: "clientes"
      }},
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo
  });

  $('#cboVendedor').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple');?>',
      data: function(params){ return { q: params.term, opcion: "vendedores" }; },
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo
  });

  if("<?=$this->session->userdata('vendedor');?>" !== ""){
    $("#cboVendedor")
    .select2("destroy")
    .select2({
      data: [{
        id: "<?=$this->session->userdata('vendedor');?>",
        text: "<?=$this->session->userdata('usuarioNom');?>"
      }],
      disabled: true
    });
  }

  $('#cboProducto').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple');?>',
      data: function(params){ return { q: params.term, opcion: "productos" }; },
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo
  });

  function formatRepo2 (repo) {
    if (repo.loading) return repo.text;
    return '<table class="markupSelect2"><tr><td class="descrip">'+repo.text+'</td></td></table>';
  }

  function formatRepo (repo) {
    if (repo.loading) return repo.text;
    return '<table class="markupSelect2"><tr><td class="codigo">'+repo.id+'</td><td class="descrip">'+repo.text+'</td></td></table>';
  }

  $('select').on('change', function (e) {
    $(".select2-selection__rendered").removeAttr('title');
  });

  $('.datepicker').pikaday({
    firstDay: 1,
    yearRange: [2010,2020],
    format: 'DD/MM/YYYY',
    theme: 'triangle-theme',
    onSelect: function(date) {
      console.log(this.getMoment().format('Do MMMM YYYY'));
    }
  });


  $("#btnEjecutar").on('click',function(){
    $resultado = $("#resultado");

    $.ajax({
      method: "POST",
      url: "<?=site_url('ventas/fnVentasxCliente')?>",
      cache: false,
      dataType: 'json',
      data: {
        vendedor: $("#cboVendedor").val(),
        cliente: $("#cboCliente").val(),
        producto: $("#cboProducto").val(),
        marca: $("#cboMarca").val(),
        inicio: $("#fecInicio").val(),
        final: $("#fecFinal").val(),
        opcion: $("[name='opcion']:checked").val()
      },
      beforeSend: function(){
        HoldOn.open({ theme:"sk-bounce" });
        $resultado.html("");
      }
    }).done(function($resp){
      if($resp.excedido == true){
        $("#excedido").find("span").html($resp.total);
        $resultado.html($("#excedido").html());
      }else{
        $resultado.html($resp.html);
      }
      HoldOn.close();
    });

  });


})
</script>

<style>

.datepicker {font-family: 'Roboto Condensed', sans-serif !important}
.contenedorResult { margin-bottom: 20px !important}
.resultado * { font-size: 12px; }
.resultado {margin: 5px auto 40px auto;
  border-top: 3px solid rgba(108, 108, 108, .1);
  border-bottom: 3px solid rgba(108, 108, 108, .1);
  border-radius: 5px;
  max-width: 850px;
  transition: all 0.3s
}
.resultado:hover{
  border-top: 3px solid rgba(108, 108, 108, .2);
  border-bottom: 3px solid rgba(108, 108, 108, .2);
}
.resultado table.cabecera {width: 100%; border-collapse: collapse; margin: 3px auto }
.resultado table.cabecera td {border: 1px solid #ccc; padding: 3px}
.resultado table.cabecera td.documento { width: 240px; font-size: 13px; font-weight: bold;}

.resultado label.label ~.nota {
  color: #333;
  font-size: 9px;
  padding: 1px 5px;
  border-radius: 3px;
  position: absolute;
  margin-left: 10px;
  border: 1px solid rgba(147, 147, 147, 0.57);
  background-color: yellow
}

.resultado label.label ~.green {
  color: green;
  padding: 3px 6px;
  border-radius: 3px;
  margin-right: 4px;
  display: inline-block;
  border: 1px solid green
}

.resultado label.label ~.orange {
  color: #f2711c;
  padding: 3px 6px;
  border-radius: 3px;
  margin-right: 4px;
  display: inline-block;
  border: 1px solid #f2711c
}

.resultado label.label ~.blue {
  color: #2185d0;
  padding: 3px 6px;
  border-radius: 3px;
  margin-right: 4px;
  display: inline-block;
  border: 1px solid #2185d0
}

.label.saldar {
  color: red;
  padding: 0px 7px;
  border-radius: 50%;
  display: inline-block;
  border: 1px solid red
}

.resultado label.label ~.red {
  color: red;
  padding: 3px 6px;
  border-radius: 3px;
  margin-right: 4px;
  display: inline-block;
  border: 1px solid red
}

.resultado label.label {
  font-size: 11px;
  color: #5d5d5d;
  font-weight: normal;
  background-color: #e8e8e8;
  padding: 3px 7px;
  border-radius: 3px;
  position: relative;
  margin-right: 10px;
  display: inline-block;
}

.resultado label.label:after {
  content: '';
  display: block;
  position: absolute;
  top: 7px;
  left: 100%;
  width: 0px;
  height: 0;
  border-color: transparent transparent transparent #e8e8e8;
  border-style: solid;
  border-width: 6px;
  }​


  .resultado table.cabecera td.fecha { text-align: left;}
  .resultado table.cabecera td.almacen { width: 350px; }


  .resultado table.detalle { width: 100%; border-collapse: collapse; margin: 3px auto}
  .resultado table.detalle thead th { border: 1px solid #ccc; background: rgb(241, 241, 241)}
  .resultado table.detalle td { border: 1px solid #ccc; padding: 0 4px}
  .resultado table.detalle td.cantidad { text-align: center; width: 65px}
  .resultado table.detalle td.codigo { text-align: center; width: 100px}
  .resultado table.detalle td.moneda { text-align: center; width: 60px}
  .resultado table.detalle td.precio { text-align: right; width: 90px; padding-right: 7px}
  .resultado table.detalle td.totCantidad { text-align: center; font-weight: bold; background: rgb(241, 241, 241)}
  .resultado table.detalle td.totPrecio { text-align: right;; font-weight: bold; padding-right: 7px; background: rgb(241, 241, 241)}
  .resultado table.detalle td.totVacio { border: 0 none}


  .resultado table.pie { width: 100%; border-collapse: collapse; margin: 3px auto}
  .resultado table.pie td { border: 1px solid #ccc; padding: 4px}
  </style>

  <? $this->load->view("footer"); ?>
