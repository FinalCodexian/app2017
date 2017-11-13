<?php
$data = array(
  "titulo"=>"Ventas por cliente"
);
?>

<? $this->load->view("header", $data); ?>

<link rel="stylesheet" href="<?=base_url('tools/select2/dist/css/select2.css');?>">
<link rel="stylesheet" href="<?=base_url('tools/select2/dist/css/select2-xLuis.css');?>">
<script type="text/javascript" src="<?=base_url('tools/select2/dist/js/select2.full.js');?>"></script>
<script type="text/javascript" src="<?=base_url('tools/select2/dist/js/i18n/es.js');?>"></script>
<link rel="stylesheet" href="<?=base_url('tools/pretty-checkbox/dist/pretty-checkbox.css');?>">
<link rel="stylesheet" href="<?=base_url('tools/mdi/css/materialdesignicons.css');?>">

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
                  <input placeholder="Fecha inicio" type="text" class="datepicker"><i class="calendar icon"></i>
                </div>
              </div>
              <div class="field">
                <label>Fecha final</label>
                <div class="ui icon input">
                  <input placeholder="Fecha final" type="text" class="datepicker"><i class="calendar icon"></i>
                </div>
              </div>
            </div>
            <div class="field">
              <select id="cboMarca" data-placeholder='- Todas las <b>marcas</b> -'></select>
            </div>
          </div>

          <div class="three wide column">

            <button class="ui tiny fluid primary labeled icon button" style="margin-bottom:4px">
              <i class="icon mdi mdi-flash mdi-18px"></i>
              Ejecutar reporte
            </button>

            <button class="ui tiny fluid green labeled icon button" disabled>
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
      data: function(params){ return { q: params.term }; },
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

  $("#btn").on('click',function(){
    $.post("<?=site_url('datos/fnListaSimple_Clientes')?>", {}, function(d){
      alert(d);
    })
  })

})
</script>

<? $this->load->view("footer"); ?>
