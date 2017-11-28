<?php
$sess = $this->uri->segment(3, 0);
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

            <button id="excel" class="ui tiny basic fluid green button">
              <i class="zmdi zmdi-download zmdi-hc-fw zmdi-hc-2x"></i><br>
              Descargar a excel
            </button>


            <script type="text/javascript">
            $(function(){
              $("#excel").on("click",function(){

                // var $columnas = [], datos = hot.getColHeader();
                // $.each(datos, function(k, v){
                //   $columnas.push({
                //     item:k,
                //     title: hot.getColHeader(k),
                //     type: hot.getDataType(0,k,0,k),
                //     ancho: hot.getCellMeta(0,k).ancho,
                //     className: hot.getCellMeta(0,k).className
                //   });
                // });


                $.ajax({
                  method: "POST",
                  url: "<?=site_url('ventas/fnVentasxCliente')?>",
                  cache: false,
                  //dataType: 'json',
                  data: {
                    vendedor: $("#cboVendedor").val(),
                    cliente: $("#cboCliente").val(),
                    producto: $("#cboProducto").val(),
                    marca: $("#cboMarca").val(),
                    inicio: $("#fecInicio").val(),
                    final: $("#fecFinal").val(),
                    opcion: $("[name='opcion']:checked").val(),

                    agencia: "<?=$this->session->userdata($sess)["agenciaId"];?>",
                    base: "<?=$this->session->userdata($sess)["base"];?>",
                    concar: "<?=$this->session->userdata($sess)["concar"];?>",

                    excel: 1
                  },
                  beforeSend: function(){
                    HoldOn.open({ theme:"sk-bounce" });
                  }
                })
                .done(function($resp){
//                  console.log($resp);

                  $('<form method=post target=_new action="<?=site_url('excel/ventas_excel/venta_x_clientes');?>"><textarea name=contenido>' + $resp + '</textarea></form>').appendTo('body').submit().remove();

                  HoldOn.close();

                })


              })

            });
            </script>
            <div id="x">x</div>

          </div>

        </div>

      </div>

    </div>


    <div class="ui tiny custom popup">

      <i class="circular red remove link icon" style="position:absolute; right:4px; top: 6px" onclick="javascript:$('#popover').popup('hide'); void(0)"></i>

      <div style="text-align:center; margin-bottom:10px">
        <div class="ui tiny statistic">
          <div class="value" id="total_documentos">0</div>
          <div class="label">Documentos</div>
        </div>
      </div>

      <div class="ui tiny fluid teal vertical menu cmdFiltro">
        <a class="disabled item cmdFiltro" data-type='FT'>Facturas<div class="ui disabled grey label" id="cont_FT">0</div></a>
        <a class="disabled item cmdFiltro" data-type='BV'>Boletas de venta<div class="ui disabled grey label" id="cont_BV">0</div></a>
        <a class="disabled item cmdFiltro" data-type='NC'>Notas de Credito<div class="ui disabled grey label" id="cont_NC">0</div></a>
        <a class="disabled item cmdFiltro" data-type='ND'>Notas de Debito<div class="ui disabled grey label" id="cont_ND">0</div></a>
      </div>

      <div class="two tiny basic ui buttons">
        <button class="ui button disabled cmdFiltro" data-type='GS'>
          Sin Gu&iacute;a de remisi&oacute;n
          <hr class="ui dividing " />
          <div class="ui grey label" id="cont_GS">0</div>
        </button>
        <button class="ui button disabled cmdFiltro" data-type='R'>
          Sin Nota de Cr&eacute;dito
          <hr class="ui dividing " />
          <div class="ui grey label" id="cont_R">0</div>
        </button>
      </div>

      <div class="ui icon fluid input" style="margin-bottom:10px; margin-top:20px">
        <input type="search" placeholder="Filtrar en resultado..." id="Filtro" disabled>
        <i class="inverted orange circular search icon"></i>
      </div>

    </div>

    <div class="ui tiny" id="resultado" style="margin:6px auto">

      <i class="circular inverted teal xpulse large link filter icon" id="popover"></i>

      <div id="datosJSON"></div>

    </div>

  </div>

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

  $.fn.select2.defaults.set('theme', 'xLuis')
  $.fn.select2.defaults.set('language', 'es');
  $.fn.select2.defaults.set('allowClear', 'true');
  $.fn.select2.defaults.set('width', '100%');


  $(".cmdFiltro").on("click",function(e){

    if($(this).hasClass("active")) $(this).removeClass("active"); else $(this).addClass("active");

    $filtro = $(this).data("type");

    $('.resultado').removeClass('temporal');
    $('.resultado').removeClass('x');

    var $filtros = [];
    $(".cmdFiltro").each(function(index){
      if($(this).hasClass("active")){
        if ($(this).data('type') == "FT") $(".resultado[data-type='FT']").addClass("temporal");
        if ($(this).data('type') == "BV") $(".resultado[data-type='BV']").addClass("temporal");
        if ($(this).data('type') == "NC") $(".resultado[data-type='NC']").addClass("temporal");
        if ($(this).data('type') == "ND") $(".resultado[data-type='ND']").addClass("temporal");

        // Sin GS / pendientes
        if ($(this).data('type') == "GS"){
          $('.resultado').removeClass('temporal');
          $('.resultado').removeClass('x');

          $(".resultado")
          .not("[data-type='NC']")
          .not("[data-type='ND']")
          .not("[data-pendiente!='S']")
          .addClass("temporal");

          if( $(".cmdFiltro[data-type='FT']").hasClass("active") && $(".cmdFiltro[data-type='BV']").hasClass("active") ){
          }else{
            if( $(".cmdFiltro[data-type='FT']").hasClass("active") ) $(".temporal[data-type!='FT']").removeClass("temporal");
            if( $(".cmdFiltro[data-type='BV']").hasClass("active") ) $(".temporal[data-type!='BV']").removeClass("temporal");
          }
        }

        // NC relacionada
        if ($(this).data('type') == "R"){
          $(".resultado").each(function(ind, elem){
            if( $(elem).data("notas") != '0' ) $(elem).addClass("x");
          });
        }

      }
    })

    $('.resultado').show();
    if( $(".resultado").length !==  $(".resultado:not(.temporal)").length ){
      $(".resultado:not(.temporal)").hide();
    }
    $(".x").hide();

  })





  if("<?=$this->session->userdata($sess)['nivel'];?>" !== "OP"){

    $('#cboVendedor').select2({
      minimumInputLength: 3,
      ajax: {
        url: '<?=site_url('datos/fnListaSimple');?>',
        data: function(params){ return { q: params.term, opcion: 'vendedores', base: "<?=$this->session->userdata($sess)['base'];?>" }; },
        dataType: 'json',
        delay: 850
      },

      escapeMarkup: function (markup) { return markup; },
      templateResult: formatRepo
    });

  }else{

    $.ajax({
      method: 'POST',
      url: '<?=site_url('ventas/fnVendedorAsignado');?>',
      data: {
        usuario: "<?=$this->session->userdata($sess)['usuarioId'];?>",
        base: "<?=$this->session->userdata($sess)['base'];?>"
      },
      cache: false,
      dataType: 'json',
      delay: 850
    }).done(function(response){

      $("#cboVendedor").select2({
        data: response.results,
        escapeMarkup: function (markup) { return markup; },
        templateResult: formatRepo,
        allowClear: false
      });

      $('#cboVendedor').val($('#cboVendedor option:first-child').val()).trigger('change');

    })

  }



  $("#Filtro").on("keyup change",function(){
    $filtro = $.trim($(this).val().toUpperCase());
    $(".cmdFiltro").removeClass("active");
    $(".resultado").hide();

    $('.resultado e').removeHighlight();
    if($filtro.length>0){
      $('.resultado e').highlight( $filtro );
    }

    $(".resultado").each(function(index){
      if( $(this).is(':contains("'+$filtro+'")') ){
        $(this).show();
      }
    });

  });

  var options = { useEasing: false, useGrouping: false, separator: ',', decimal: '.'},
  $total_documentos = new CountUp('total_documentos', 0, 0, 0, .5, options),
  $total_FT = new CountUp('cont_FT', 0, 0, 0, .5, options),
  $total_BV = new CountUp('cont_BV', 0, 0, 0, .5, options),
  $total_NC = new CountUp('cont_NC', 0, 0, 0, .5, options),
  $total_ND = new CountUp('cont_ND', 0, 0, 0, .5, options),
  $total_GS = new CountUp('cont_GS', 0, 0, 0, .5, options),
  $total_R = new CountUp('cont_R', 0, 0, 0, .5, options);

  $('#popover').popup({
    popup : $('.custom.popup'), on: 'click', inline: false,
    position: 'top left',
    closable: false,
    delay: {
      show: 0,
      hide: 800
    }
  }).on("mousemove", function(){
    //  $(this).css({'animation':'none'});
  });

  //$('#popover').popup('show');


  $(document).bind('keydown.f3', function(e){
    e.preventDefault();
    $('#popover').popup('toggle');
  });


  $('.datepicker').mask('00/00/0000', {placeholder: "__/__/____"});


  $('.combo').select2({
    minimumResultsForSearch: Infinity,
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo2
  });

  $('#cboMarca').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple');?>',
      data: function(params){ return { q: params.term, opcion: 'marcas', base: "<?=$this->session->userdata($sess)['base'];?>" }; },
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
        vendedor: "<?=$this->session->userdata($sess)['vendedor'];?>",
        base: "<?=$this->session->userdata($sess)['base'];?>",
        opcion: "clientes"
      }},
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo
  });


  $('#cboProducto').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple');?>',
      data: function(params){ return { q: params.term, opcion: "productos", base: "<?=$this->session->userdata($sess)['base'];?>" }; },
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
      //console.log(this.getMoment().format('Do MMMM YYYY'));
    }
  });


  $("#btnEjecutar").on('click',function(){
    $resultado = $("#datosJSON");

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
        opcion: $("[name='opcion']:checked").val(),

        agencia: "<?=$this->session->userdata($sess)["agenciaId"];?>",
        base: "<?=$this->session->userdata($sess)["base"];?>",
        concar: "<?=$this->session->userdata($sess)["concar"];?>"
      },
      beforeSend: function(){
        HoldOn.open({ theme:"sk-bounce" });
        $resultado.html("");
        $("#popover").hide();
        $('#popover').popup('hide');

        $("#Filtro").val("");
        $("#Filtro").prop("disabled",true);

        $total_documentos.reset();
        $total_FT.reset();
        $total_BV.reset();
        $total_NC.reset();
        $total_ND.reset();
        $total_GS.reset();
        $total_R.reset();

        $("#cont_FT").addClass("disabled").parent().addClass("disabled").removeClass("active");
        $("#cont_BV").addClass("disabled").parent().addClass("disabled").removeClass("active");
        $("#cont_NC").addClass("disabled").parent().addClass("disabled").removeClass("active");
        $("#cont_ND").addClass("disabled").parent().addClass("disabled").removeClass("active");
        $("#cont_GS").addClass("disabled").parent().addClass("disabled").removeClass("active");
        $("#cont_R").addClass("disabled").parent().addClass("disabled").removeClass("active");
      }
    }).done(function($resp){
      if($resp.excedido == true){
        $("#excedido").find("span").html($resp.total);
        $resultado.html($("#excedido").html());
      }else{
        $resultado.html($resp.html);
        $("#popover").show(350, function(){

          $('#popover').popup('show');

          $total_documentos.start(function() {
            $total_documentos.update($resp.total);
          });

          if($resp.total > 0){
            $("#Filtro").prop("disabled",false);
          }

          if($resp.totales.FT>0){
            $total_FT.start(function() {
              $total_FT.update($resp.totales.FT);
            });

            $("#cont_FT")
            .removeClass("disabled").addClass("active")
            .parent().removeClass("disabled");
          }

          if($resp.totales.BV>0){
            $("#cont_BV")
            .removeClass("disabled").addClass("active")
            .parent().removeClass("disabled");
            $total_BV.start(function() {
              $total_BV.update($resp.totales.BV);
            });
          }

          if($resp.totales.NC>0){
            $("#cont_NC")
            .removeClass("disabled").addClass("active")
            .parent().removeClass("disabled");
            $total_NC.start(function() {
              $total_NC.update($resp.totales.NC);
            });
          }

          if($resp.totales.ND>0){
            $("#cont_ND")
            .removeClass("disabled").addClass("active")
            .parent().removeClass("disabled");
            $total_ND.start(function() {
              $total_ND.update($resp.totales.ND);
            });
          }

          if($resp.tot_pendientes>0){
            $("#cont_GS")
            .removeClass("disabled").addClass("active")
            .parent().removeClass("disabled");
            $total_GS.start(function() {
              $total_GS.update($resp.tot_pendientes);
            });
          }

          if($resp.tot_notasNC>0){
            $("#cont_R")
            .removeClass("disabled").addClass("active")
            .parent().removeClass("disabled");
            $total_R.start(function() {
              $total_R.update($resp.tot_notasNC);
            });
          }


        });
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


  .highlight { text-decoration: underline !important; color: rgb(15, 79, 2) !important; background-color: rgba(255, 237, 70, 0.5)}


  #popover {display:snone; position: fixed; bottom: 20px; margin-left: 10px; z-index: 10}
  .popup {
    position: fixed !important; border-left: 2px solid rgb(255, 157, 66) !important
  }

  .xpulse {animation: xpulse 1s infinite;}
  .xpulse:hover { animation: none;}
  @-webkit-keyframes xpulse {
    0% { -webkit-box-shadow: 0 0 0 0 rgba(241, 174, 0, 0.72) }
    70% { -webkit-box-shadow: 0 0 0 10px rgba(121, 121, 121, 0) }
    100% { -webkit-box-shadow: 0 0 0 0 rgba(121, 121, 121, 0) }
  }

  .opciones { text-align: center;}
  .opciones label.header {
    font-size: 12px; font-weight: bold;
    text-align: left;
    border-bottom: 1px solid rgba(94, 94, 94, .6);
    padding-bottom: 3px; display: block;
    margin-bottom: 8px
  }

  .temporal {

  }
  .x {

  }
  </style>

  <? $this->load->view("footer"); ?>
