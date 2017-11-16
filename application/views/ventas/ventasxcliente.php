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

    <div class="ui tiny segment">

      <div class="ui tiny form">

        <div class="ui stackable grid">

          <div class="eight wide column">
            <div class="inline field">
              <select id="cboClientes" data-placeholder='- Todos los <b>clientes</b> -'></select>
            </div>
            <div class="field">
              <select id="cboVendedor" data-placeholder='- Todos los <b>vendedores</b> -'></select>
            </div>
            <div class="field">
              <select id="cboProducto" data-placeholder='- Todos los <b>productos</b> -'></select>
            </div>
          </div>

          <div class="five wide column field">
            <div class="two fields">
              <div class="field">
                <label>Fecha inicio</label>
                <div class="ui icon input">
                  <input placeholder="Fecha inicio" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
                </div>
              </div>
              <div class="field">
                <label>Fecha final</label>
                <div class="ui icon input">
                  <input placeholder="Fecha final" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
                </div>
              </div>
            </div>
            <div class="field">
              <select id="cboMarca" data-placeholder='- Todas las <b>marcas</b> -'></select>
            </div>
          </div>

          <div class="three wide column">

            <button id="btnEjecutar" class="ui tiny fluid basic teal labeled icon button" style="margin-bottom:4px">
              <i class="icon mdi mdi-flash mdi-18px"></i>
              Buscar ventas
            </button>

            <button class="ui tiny basic fluid green labeled icon button" disabled>
              <i class="icon mdi mdi-file-excel mdi-18px"></i>
              Exportar a excel
            </button>

          </div>

        </div>

      </div>

    </div>

    <div class="ui tiny segment" id="resultado" style="margin:6px auto">
      #resultado
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

  $('.combo').select2({
    minimumResultsForSearch: Infinity,
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo2
  });

  $('#cboMarca').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple_Clientes');?>',
      data: function(params){ return { q: params.term }; },
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo
  });

  $('#cboClientes').select2({
    minimumInputLength: 3,
    ajax: {
      url: '<?=site_url('datos/fnListaSimple_Clientes');?>',
      data: function(params){ return {
        q: params.term,
        vendedor: "<?=$this->session->userdata('vendedor');?>"
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
      url: '<?=site_url('datos/fnListaSimple_Clientes');?>',
      data: function(params){ return { q: params.term }; },
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
      url: '<?=site_url('datos/fnListaSimple_Clientes');?>',
      data: function(params){ return { q: params.term }; },
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
    HoldOn.open({ theme:"sk-bounce" });

    $resultado.html("");
    $.post("<?=site_url('ventas/fnVentasxCliente')?>", {
      vendedor: "vendedor"
    },
    function(data){
      var $doc = "";
      var $detalle = "";
      var myJSON = JSON.parse(data);

      $.each(myJSON.data, function(i, item){

        $div = $("<div class='ui basic segment resultado'></div>")
        $tabla = $("<table class='cabecera'></table>");

        $pie = $("<table class='pie'></table>");

        $fila = $("<tr></tr>").appendTo($tabla);
        $td = $("<td class='documento' colspan=2><label class='ui blue right pointing label'>Documento</label><label class='ui basic label'>"+ item.cabecera.td + " " + item.cabecera.documento + "</label> <label class='ui basic label'><i class='calendar icon'></i>"+ item.cabecera.fecha +"</label></td>").appendTo($fila);
        //$td = $("<td class='fecha'><i class='calendar icon'></i>"+ item.cabecera.fecha +"</td>").appendTo($fila);
        $td = $("<td><label class='ui right pointing label'>Cond. Pago</label>"+ item.cabecera.forma_venta +"</td>").appendTo($fila);

        $fila = $("<tr></tr>").appendTo($tabla);
        $td = $("<td colspan=3><label class='ui right pointing label'>Cliente</label> "+ item.cabecera.ruc + ' - ' + item.cabecera.cliente + "</td>").appendTo($fila);

        $fila = $("<tr></tr>").appendTo($tabla);
        $td = $("<td colspan=2><label class='ui right pointing label'>Vendedor</label>"+ item.cabecera.vendedor + "</td>").appendTo($fila);
        if(item.cabecera.almacen.indexOf("NO USAR") > 0){
          $td = $("<td class='almacen'><label class='ui right pointing label'>Almacen</label>"+ item.cabecera.almacen2 + "</td>").appendTo($fila);
        }else{
          $td = $("<td class='almacen'><label class='ui right pointing label'>Almacen</label>"+ item.cabecera.almacen + "</td>").appendTo($fila);
        }

        $fila = $("<tr></tr>").appendTo($tabla);
        $td = $("<td colspan=2><label class='ui right pointing label'>Glosa</label> Glosa....</td>").appendTo($fila);
        $td = $("<td><label class='ui right pointing label'>Pedido</label> Pedido....</td>").appendTo($fila);


        // Detalle del documento


        $detalle = $("<table class='detalle'></table>");
        $("<thead></thead>")
        .append("<tr></tr>")
        .append("<th>Cantidad</th>")
        .append("<th>Por saldar</th>")
        .append("<th>Codigo</th>")
        .append("<th>Descripcion</th>")
        .append("<th>Moneda</th>")
        .append("<th>P. Unitario</th>")
        .append("<th>Sub Total</th>")
        .appendTo($detalle);

        $.each(item.detalle, function(di, ditem){
          $detFila = $("<tr></tr>").appendTo($detalle);
          $td = $("<td class='cantidad'>"+ parseInt(ditem.cant) + "</td>").appendTo($detFila);

          if(parseInt(ditem.saldar)>0){
            $td = $("<td class='cantidad'><a class='ui red circular label'>" + ditem.saldar + "</a></td>").appendTo($detFila);
          }else{
            $td = $("<td class='cantidad'>0</td>").appendTo($detFila);
          }
          $td = $("<td class='codigo'>"+ ditem.codigo + "</td>").appendTo($detFila);
          $td = $("<td>"+ ditem.descrip +"</td>").appendTo($detFila);

          $td = $("<td class='moneda'>"+ item.cabecera.moneda +"</td>").appendTo($detFila);

          if(item.cabecera.moneda == "US"){
            $td = $("<td class='precio'>"+ parseFloat(ditem.precio_us).toFixed(2) +"</td>").appendTo($detFila);
            $td = $("<td class='precio'>"+ parseFloat(ditem.subtot_us).toFixed(2) +"</td>").appendTo($detFila);
          }

          if(item.cabecera.moneda == "MN"){
            $td = $("<td class='precio'>"+ parseFloat(ditem.precio_mn).toFixed(2) +"</td>").appendTo($detFila);
            $td = $("<td class='precio'>"+ parseFloat(ditem.subtot_mn).toFixed(2) +"</td>").appendTo($detFila);
          }

        });

        // subtotales del detalle
        $detFila = $("<tr></tr>").appendTo($detalle);
        $td = $("<td class='totCantidad'>"+ parseFloat(item.tot_cantidad) +"</td>").appendTo($detFila);
        $td = $("<td class='totVacio'></td>").appendTo($detFila);
        $td = $("<td class='totVacio'></td>").appendTo($detFila);
        $td = $("<td class='totVacio'></td>").appendTo($detFila);
        $td = $("<td class='totVacio'></td>").appendTo($detFila);
        $td = $("<td class='totVacio'></td>").appendTo($detFila);

        if(item.cabecera.moneda == "US"){
          $td = $("<td class='totPrecio'>"+ parseFloat(item.tot_US).toFixed(2) +"</td>").appendTo($detFila);
        }
        if(item.cabecera.moneda == "MN"){
          $td = $("<td class='totPrecio'>"+ parseFloat(item.tot_MN).toFixed(2) +"</td>").appendTo($detFila);
        }


        $filaPie = $("<tr></tr>").appendTo($pie);
        if(item.tot_guias > 0){
          $detGuias = $("<td><label class='ui right pointing label'>Guias de atencion</label></td>").appendTo($filaPie);
          $.each(item.guias, function(di, gitem){
            $td = $("<a class='ui basic blue medium label'>"+ gitem.guia +' ('+ gitem.guia_fecha + ")</a>").appendTo($detGuias);
          })
        }else{
          if(item.cabecera.td == 'NC' || item.cabecera.td == 'ND'){
            $td = $("<td><label class='ui right pointing label'>Sustento</label>Sustento.....</td>").appendTo($filaPie);
          }else{
            if(item.cabecera.tipo_almacen == "C"){
              $td = $("<td><label class='ui right pointing label'>Nota</label>REGULARIZACION DE CONSIGNACION</td>").appendTo($filaPie);
            }else{
              $td = $("<td><label class='ui right pointing label'>Nota</label><label class='ui red label'>SIN GUIA DE ATENCION</label></td>").appendTo($filaPie);
            }
          }
        }

        if(item.tot_notas > 0){
          $filaPie = $("<tr></tr>").appendTo($pie);
          $detNotas = $("<td><label class='ui right pointing label'>Notas NC/ND</label></td>").appendTo($filaPie);
          $.each(item.notas, function(di, nitem){
            $td = $("<a class='ui basic orange medium label'>"+ nitem.td + ' ' + nitem.nota +' ('+ nitem.nota_fecha + ")</a>").appendTo($detNotas);
          })
        }


        $div.append($tabla);
        $div.append($detalle);
        $div.append($pie);
        $resultado.append($div);
      });

      HoldOn.close();

    });
  });


})
</script>

<style>
.resultado * { font-size: 12px; }

.resultado table.cabecera {width: 100%; border-collapse: collapse; margin: 3px auto }
.resultado table.cabecera td {border: 1px solid #ccc; padding: 3px}
.resultado table.cabecera td.documento { width: 220px}
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
