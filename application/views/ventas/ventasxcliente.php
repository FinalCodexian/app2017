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


<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">






    <div class="ui segment">

      <form class="ui small form">
        <div class="fields">
          <div class="four wide field">
            <label>Inicio</label>
            <div class="ui icon input"><input type="text" class="datepicker"><i class="calendar icon"></i></div>
          </div>
          <div class="four wide field">
            <label>Final</label>
            <div class="ui icon input"><input type="text" class="datepicker"><i class="calendar icon"></i></div>
          </div>

          <div class="four wide field">
            <label>Tipo de documento</label>
            <select class="combo" name="card[expire-month]">
              <option value=""></option>
              <option value="11">FACTURAS</option>
              <option value="12">BOLETAS DE VENTA</option>
              <option value="12">NOTAS DE CREDITO</option>
              <option value="12">NOTAS DE DEBITO</option>
            </select>
          </div>

          <div class="four wide field">
            <label>Marca</label>
            <select id="cboMarca"></select>
          </div>

          <!--div class="six wide field">
          <label>Marcas</label>
          <div class="two fields">
          <div class="field">
          <select class="ui fluid search dropdown" name="card[expire-month]">
          <option value="">Month</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
      </div>
      <div class="field">
      <input type="text" name="card[expire-year]" maxlength="4" placeholder="Year">
    </div>
  </div>
</div-->
</div>



<div class="two fields">
  <div class="field">
    <label>Cliente</label>
    <select id="cboClientes"></select>
  </div>
  <div class="field">
    <label>Vendedor</label>
    <select id="cboVendedor"></select>
  </div>
</div>

</form>



<button id="btn" class="ui mini button">Consultar</button>
</div>


<form class="ui form">
  <h4 class="ui dividing header">Shipping Information</h4>
  <div class="field">
    <label>Name</label>
    <div class="two fields">
      <div class="field">
        <input type="text" name="shipping[first-name]" placeholder="First Name">
      </div>
      <div class="field">
        <input type="text" name="shipping[last-name]" placeholder="Last Name">
      </div>
    </div>
  </div>
  <div class="field">
    <label>Billing Address</label>
    <div class="fields">
      <div class="twelve wide field">
        <input type="text" name="shipping[address]" placeholder="Street Address">
      </div>
      <div class="four wide field">
        <input type="text" name="shipping[address-2]" placeholder="Apt #">
      </div>
    </div>
  </div>
  <div class="two fields">
    <div class="field">
      <label>State</label>
      <select class="ui fluid dropdown">
        <option value="">State</option>
        <option value="AL">Alabama</option>
        <option value="AK">Alaska</option>
        <option value="AZ">Arizona</option>
        <option value="AR">Arkansas</option>
        <option value="CA">California</option>
        <option value="CO">Colorado</option>
        <option value="CT">Connecticut</option>
        <option value="DE">Delaware</option>
        <option value="DC">District Of Columbia</option>
        <option value="FL">Florida</option>
        <option value="GA">Georgia</option>
        <option value="HI">Hawaii</option>
        <option value="ID">Idaho</option>
        <option value="IL">Illinois</option>
        <option value="IN">Indiana</option>
        <option value="WY">Wyoming</option>
      </select>
    </div>
    <div class="field">
      <label>Country</label>
      <div class="ui fluid search selection dropdown">
        <input type="hidden" name="country">
        <i class="dropdown icon"></i>
        <div class="default text">Select Country</div>
        <div class="menu">
          <div class="item" data-value="af"><i class="af flag"></i>Afghanistan</div>
          <div class="item" data-value="ax"><i class="ax flag"></i>Aland Islands</div>
          <div class="item" data-value="al"><i class="al flag"></i>Albania</div>
          <div class="item" data-value="dz"><i class="dz flag"></i>Algeria</div>
          <div class="item" data-value="as"><i class="as flag"></i>American Samoa</div>
          <div class="item" data-value="ad"><i class="ad flag"></i>Andorra</div>
          <div class="item" data-value="va"><i class="va flag"></i>Vatican City</div>
          <div class="item" data-value="ve"><i class="ve flag"></i>Venezuela</div>
          <div class="item" data-value="vn"><i class="vn flag"></i>Vietnam</div>
          <div class="item" data-value="wf"><i class="wf flag"></i>Wallis and Futuna</div>
          <div class="item" data-value="eh"><i class="eh flag"></i>Western Sahara</div>
          <div class="item" data-value="ye"><i class="ye flag"></i>Yemen</div>
          <div class="item" data-value="zm"><i class="zm flag"></i>Zambia</div>
          <div class="item" data-value="zw"><i class="zw flag"></i>Zimbabwe</div>
        </div>
      </div>
    </div>
  </div>
  <h4 class="ui dividing header">Billing Information</h4>
  <div class="field">
    <label>Card Type</label>
    <div class="ui selection dropdown">
      <input type="hidden" name="card[type]">
      <div class="default text">Type</div>
      <i class="dropdown icon"></i>
      <div class="menu">
        <div class="item" data-value="visa">
          <i class="visa icon"></i>
          Visa
        </div>
        <div class="item" data-value="amex">
          <i class="amex icon"></i>
          American Express
        </div>
        <div class="item" data-value="discover">
          <i class="discover icon"></i>
          Discover
        </div>
      </div>
    </div>
  </div>
  <div class="fields">
    <div class="seven wide field">
      <label>Card Number</label>
      <input type="text" name="card[number]" maxlength="16" placeholder="Card #">
    </div>
    <div class="three wide field">
      <label>CVC</label>
      <input type="text" name="card[cvc]" maxlength="3" placeholder="CVC">
    </div>
    <div class="six wide field">
      <label>Expiration</label>
      <div class="two fields">
        <div class="field">
          <select class="ui fluid search dropdown" name="card[expire-month]">
            <option value="">Month</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
        </div>
        <div class="field">
          <input type="text" name="card[expire-year]" maxlength="4" placeholder="Year">
        </div>
      </div>
    </div>
  </div>
  <h4 class="ui dividing header">Receipt</h4>
  <div class="field">
    <label>Send Receipt To:</label>
    <div class="ui fluid multiple search selection dropdown">
      <input type="hidden" name="receipt">
      <i class="dropdown icon"></i>
      <div class="default text">Saved Contacts</div>
      <div class="menu">
        <div class="item" data-value="jenny" data-text="Jenny">
          <img class="ui mini avatar image" src="/images/avatar/small/jenny.jpg">
          Jenny Hess
        </div>
        <div class="item" data-value="elliot" data-text="Elliot">
          <img class="ui mini avatar image" src="/images/avatar/small/elliot.jpg">
          Elliot Fu
        </div>
        <div class="item" data-value="stevie" data-text="Stevie">
          <img class="ui mini avatar image" src="/images/avatar/small/stevie.jpg">
          Stevie Feliciano
        </div>
        <div class="item" data-value="christian" data-text="Christian">
          <img class="ui mini avatar image" src="/images/avatar/small/christian.jpg">
          Christian
        </div>
        <div class="item" data-value="matt" data-text="Matt">
          <img class="ui mini avatar image" src="/images/avatar/small/matt.jpg">
          Matt
        </div>
        <div class="item" data-value="justen" data-text="Justen">
          <img class="ui mini avatar image" src="/images/avatar/small/justen.jpg">
          Justen Kitsune
        </div>
      </div>
    </div>
  </div>
  <div class="ui segment">
    <div class="field">
      <div class="ui toggle checkbox">
        <input type="checkbox" name="gift" tabindex="0" class="hidden">
        <label>Do not include a receipt in the package</label>
      </div>
    </div>
  </div>
  <div class="ui button" tabindex="0">Submit Order</div>
</form>



</div>

</div>

</div>

<script>
$(function(){

  function formatRepo2 (repo) {
    if (repo.loading) return repo.text;
    return '<table class="markupSelect2"><tr><td class="descrip">'+repo.text+'</td></td></table>';
  }

  $('.combo').select2({
    theme: 'xLuis',
    width: '100%',
    allowClear: true,
    placeholder: '- Todos -',
    minimumResultsForSearch: Infinity,
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo2
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


  $('#cboMarca').select2({
    theme: "xLuis",
    width: '100%',
    allowClear: true,
    minimumInputLength: 3,
    placeholder: 'Buscar cliente...',
    language: "es",
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
    theme: "xLuis",
    width: '100%',
    allowClear: true,
    minimumInputLength: 3,
    placeholder: 'Buscar cliente...',
    language: "es",
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
    theme: "xLuis",
    width: '100%',
    allowClear: true,
    minimumInputLength: 3,
    placeholder: 'Buscar cliente...',
    language: "es",
    ajax: {
      url: '<?=site_url('datos/fnListaSimple_Clientes');?>',
      data: function(params){ return { q: params.term }; },
      dataType: 'json',
      delay: 850
    },
    escapeMarkup: function (markup) { return markup; },
    templateResult: formatRepo
  });

  $('#cboClientes').on('select2:unselect', function (e) {
    $("#select2-cboClientes-container").removeAttr('title');
  });

  function formatRepo (repo) {
    if (repo.loading) return repo.text;
    return '<table class="markupSelect2"><tr><td class="codigo">'+repo.id+'</td><td class="descrip">'+repo.text+'</td></td></table>';
  }

})
</script>

<? $this->load->view("footer"); ?>
