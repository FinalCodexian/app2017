<?php
$data = array(
  "titulo"=>"Importaciones: Pedido"
);
$this->load->view("header", $data);
$this->load->view("sidebar");
$this->load->view("menu_top");
?>


<style media="screen">
.handsontable { width: 100%; margin-bottom: 8px;}
#hot { width: 100% !important;
  border-bottom: 1px solid rgba(217, 217, 217, 0.52)
}
#hot * {font-size: 12px;}

.handsontable .htDimmed{
  color: #333;
  background-color: rgba(233, 233, 233, 0.56)

}

#hot span.colHeader {
  font-size: 11px;
  text-transform: uppercase;
}


::-webkit-scrollbar{
  width: 10px;
  height: 10px;
}

::-webkit-scrollbar-track {
  background: #e5e5e5;
}

::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: #acacac
}

::-webkit-scrollbar-thumb:hover {
  background: #949a9d;
}


.miniDato { font-size: 11px !important; text-align: center; padding: 0 8px !important}

.datoPedido { background-color: rgba(205, 255, 180, 0.64) !important}
.datoProforma { background-color: rgba(244, 255, 191, 0.77) !important}
.datoInvoice { background-color: rgba(183, 246, 238, 0.7) !important}
.datoContenedor { background-color: rgb(255, 209, 195) !important}

.precio_cero {color: rgba(200, 200, 200, 0.9); text-align: right;}
.cantidad_cero {color: rgba(200, 200, 200, 0.9) !important; text-align: center !important;}


.ui.action.input .ui.dropdown:first-child {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.ui.action.input .ui.dropdown:last-child {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.ui.checkbox label { font-size: 12px}

</style>




<div class="ui mini form">
  <div class="ui celled width grid">
    <div class="two wide column">
      <button type="button" name="button" class="ui fluid icon labeled tiny button"><i class="add icon"></i>Nuevo</button>
    </div>
    <div class="two wide column">
      <button type="button" name="button" class="ui fluid icon labeled tiny button"><i class="search icon"></i>Buscar</button>
    </div>
    <div class="five wide column">

      <div class="ui fluid action input">

        <div class="ui fluid search dropdown selection" tabindex="0" id="cboMarcas">
          <select name="selection">
            <option value="">----- Select one -----</option>
            <option value="a">a</option>
          </select>
          <i class="dropdown icon"></i>
          <div class="default text">----- Select one -----</div>
          <div class="menu" tabindex="-1">
            <div class="item" data-value="a">a</div>
          </div>
        </div>

        <button class="ui tiny button" type="button" id="load">Listar</button>
      </div>

    </div>

    <div class="column">

    </div>
    <div class="two wide column">
      #Resultado
    </div>
  </div>
</div>



<script type="text/javascript">
$(function(){

  $("#cboMarcas.dropdown").dropdown({
    forceSelection: false
  });

  $('#showColumns.ui.dropdown').dropdown({
    direction: 'upward',
    action: 'nothing'
  });

  $('.ui.checkbox').checkbox();


})
</script>


<div id="hot"></div>

<input type="text" name="" value="" id="search">

<div class="ui tiny labeled dropdown icon button" id="showColumns">
  <i class="dropdown icon"></i>
  <span class="ui tiny header">Mostrar columnas</span>
  <div class="menu">
    <div class="scrolling menu">
      <!-- <div class="ui item checkbox" data-columna="-1">
      <input type="checkbox" name="item1" checked>
      <label>Num. Orden</label>
    </div> -->
    <div class="ui item checkbox" data-columna="0">
      <input type="checkbox" name="item2" checked>
      <label>Codigo</label>
    </div>
    <div class="ui item checkbox" data-columna="2,3,4,5,6">
      <input type="checkbox" name="item3" checked>
      <label>Seguimiento</label>
    </div>
    <div class="ui item checkbox" data-columna="7,8">
      <input type="checkbox" name="item4" checked>
      <label>Info - NEUMACENTER</label>
    </div>
    <div class="ui item checkbox" data-columna="9,10">
      <input type="checkbox" name="item5" checked>
      <label>Info - JCH Llantas</label>
    </div>

    <div class="ui item checkbox" data-columna="11,12">
      <input type="checkbox" name="item5" checked>
      <label>Info - EVOCAR</label>
    </div>

  </div>
</div>
</div>




<style media="screen">

.xchart {
  position: relative;
  height: 20px !important;
  width: 90px !important;
  overflow: hidden;
  background: red
}

.xchart canvas {
  max-height: 100% !important;
  max-width: 100% !important;
}

.description {
  font: 0.86em sans-serif;
}

</style>

<div id="info" class="ui tiny success message"></div>


<script type="text/javascript">

$(function(){

  var $textDefault = "<i class='info fitted icon'></i> Posicione el cursor del mouse sobre alguna columna de la tabla";
  $("#info").html($textDefault)

  function info(coords){
    var $mensaje = "";
    if(coords.row>=-1){
      switch (coords.col) {
        case 0: $mensaje = "¿Código?: Identificador único del producto"; break;
        case 1: $mensaje = "¿Descripción?: Medida del producto"; break;

        // Seguimiento
        case 2: $mensaje = "¿Seguimiento - Pedido?: Cantidad en pedidos anteriores por atender"; break;
        case 3: $mensaje = "¿Seguimiento - Producción?: Cantidad enviada a fábrica para su producción"; break;
        case 4: $mensaje = "¿Seguimiento - Trámite?: En proceso documentario antes de confirmar su despacho de puerto (PROFORMA / INVOICE)"; break;
        case 5: $mensaje = "¿Seguimiento - Tránsito?: En tránsito marítimo (PACKING LIST / B/L)"; break;
        case 6: $mensaje = "¿Seguimiento - Último tramo?: En espera de realizar el ingreso de la importación (CONTENEDOR)"; break;

        case 7: $mensaje = "¿Stock actual - NEUMA?: Existencias en tiempo real en NEUMACENTER"; break;
        case 8: $mensaje = "¿Stock actual - JCH?: Existencias en tiempo real en JCH COMERCIAL"; break;
        case 9: $mensaje = "¿Stock actual - EVO?: Existencias en tiempo real en EVOLUTION CAR SERVICES"; break;

      }
    }
    $mensaje = $mensaje.replace(/¿/g,"<strong style='text-transform:uppercase'>");
    $mensaje = $mensaje.replace(/\?/g,"</strong>");
    $("#info").html( $mensaje=="" ? $textDefault : $mensaje);
  }

  var container = document.getElementById('hot');
  var hot = new Handsontable(container, {

    afterOnCellMouseOver: function(event, coords){
      info(coords);
    },

    startRows: 3,
    rowHeaders: true,

    autoWrapRow: false,
    autoWrapCol: false,

    rowHeights: 24,

    fixedColumnsLeft: 2,
    columnSorting: true,
    sortIndicator: true,
    height: 420,
    fillHandle: false,
    stretchH: 'all',

    readOnly: true,

    nestedHeaders: [
      [
        '', '',
        {label: 'Seguimiento', colspan: 5},
        {label: '<i class="info blue icon"></i> NEUMACENTER', colspan: 2},
        {label: '<i class="info blue icon"></i> JCH LLANTAS', colspan: 2},
        {label: '<i class="info blue icon"></i> EVOCAR', colspan: 2},

        '<i class="edit blue icon"></i>', '<i class="info blue icon"></i>',
        {label: 'Pedido &nbsp; <i class="edit blue icon"></i>', colspan: 4},
        ''
      ],
      [
        'Código', 'Producto',
        'PED', 'PRO', 'TRM', 'TRS','FIN', // seguimiento
        'STCK', 'VTAS', // neumacenter
        'STCK', 'VTAS', // jch llantas
        'STCK', 'VTAS', // evocar

        'VOLUMEN', 'PRECIO FOB',
        'PRECIO', 'JCH', 'Muelle 7', 'Arica', // pedido
        'chart'
      ]
    ],

    columns:
    [
      { data: "CODIGO", className: 'miniDato'},
      { data: "PRODUCTO" },

      { data: "PEDIDO", className: 'datoPedido', renderer: cellCantidad},
      { data: "PROFORMA", className: 'datoProforma', renderer: cellCantidad},
      { data: "CONTENEDOR", className: 'datoContenedor', renderer: cellCantidad},
      { data: "PROFORMA", className: 'datoProforma', renderer: cellCantidad},
      { data: "INVOICE", className: 'datoInvoice', renderer: cellCantidad},

      // stocks & ventas
      { data: "PEDIDO2" }, // jch
      { data: "PEDIDOa" }, // jch

      { data: "PEDIDOb" }, // evocar
      { data: "PEDIDOc" }, // evocar

      { data: "PEDIDO2" }, // neuma
      { data: "PEDID2O" }, // neuma

      { data: "PE3DIDO" },  // precio FOB

      { data: "PED4IDO" }, // precio

      { data: "asdsd", type: 'numeric', format: '0,0.00', readOnly: false, renderer: cellPrecio },
      { data: "asdas11", type: 'numeric', readOnly: false, renderer: cellCantidad },
      { data: "PE344DIDO", type: 'numeric', readOnly: false, renderer: cellCantidad },
      { data: "PE42DIDO", type: 'numeric', readOnly: false, renderer: cellCantidad },


    ],

    afterChange: function (changes, source) {
      if (source === 'loadData') return;
      if (!changes) return;
      if(source === 'edit') processdata(changes);
    }

  });


  function processdata(change) {

    console.log(hot.getDataAtCell(change[0][0], 0));
    console.log(change);

    var $col = hot.getDataAtCol(15), sum = 0;
    $col.forEach(function(v,k){
      if($.isNumeric(v)) sum += v;
    });
    console.log(sum);
  }


  $("[data-columna]").on("change",function(){

    if($(this).data("columna") == "-1"){
      hot.updateSettings({
        rowHeaders: $(this).checkbox("is checked")
      });
      return;
    }

    var $columnas = [];

    $.each($("[data-columna]"),function(i, val){
      if( !$(this).checkbox("is checked") ){
        var str = String($(this).data("columna"));
        $.each(str.split(','), function(i, v){
          $columnas.push(v)
        })
      }
    })

    hot.updateSettings({
      hiddenColumns: {
        columns: $columnas,
        indicators: false
      }
    });

  })



  new ResizeSensor($('#dinamico'), function() {
    hot.render();
  });

  function cellPrecio (instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.NumericRenderer.apply(this, arguments);
    if(value === 0) $(td).addClass("precio_cero");
    return td;
  }

  function cellCantidad (instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.NumericRenderer.apply(this, arguments);
    if(value === 0) $(td).addClass("cantidad_cero");
    return td;
  }


  var $load = $('#load');
  var myData;

  $load.on('click', function(){
    $('#search').val("");
    $.ajax({
      url: '<?=base_url('importaciones/ListadoProductos/fnListaSimple');?>',
      type: 'GET',
      dataType: 'json',
      success: function(res) {
        myData = res.data;
        hot.loadData(res.data);
      }
    });
  });

  String.prototype.contains = function(it)
  {
    return this.indexOf(it) != -1;
  };

  $('#search').on('keyup', function (event) {
    if(myData == undefined) return false;

    var data = myData;
    var value = ('' + $(this).val()).toLowerCase();
    var searcharray = [];

    if (value) {
      $.each(data, function(i, v){
        var fruit = v.PRODUCTO.toLowerCase();
        var pattern = value.split(" ");
        var $totOpc = 0;

        pattern.forEach(function(c) {
          var regex = new RegExp(c, "i");
          if(regex.test(fruit)) $totOpc = $totOpc + 1;
        });

        if (pattern.length == $totOpc) searcharray.push(v);

      });
      hot.loadData(searcharray)
    }else{
      hot.loadData(data)
    }

  });


  $(".hamburger").removeClass("is-active");
  $("#wrapper").stop().animate({ paddingLeft: 0}, 150, function(){
    $("#sidebar").stop().animate({"width": "0"}, 100);
  });



})
</script>

<?php
$this->load->view("footer");
?>
