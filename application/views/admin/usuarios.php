<?php
$data = array(
  "titulo"=>"Administracion de Usuarios"
);
?>

<? $this->load->view("header", $data); ?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">

  <style media="screen">
  .xBusqueda { height: calc(100vh - 60px); position: fixed; width: 350px; padding: 10px; overflow-y: auto; border-right: 2px solid rgba(150, 150, 150, 0.2);}
  .xBusqueda .secc1 { }
  .xBusqueda .secc2 { margin-top: 10px; display: block;}
  .xForm { background: green; margin-left: 355px}
  .dataTables_scrollBody { font-size: 12px}
  </style>

  <div class="xBusqueda">

    <h4 class="ui header">
      <i class="settings icon"></i>
      <div class="content">
        Administrar usuarios
        <div class="sub header">Modificar accesos y privilegios</div>
      </div>
    </h4>

    <div class="secc1">
      <div class="ui mini fluid action input">
        <input type="text" placeholder="Buscar usuario..." id="txtBuscar">
        <div class="ui mini right labeled icon button" id="btnBuscar"><i class="search icon"></i>Buscar</div>
      </div>
    </div>

    <div class="secc2">
      <table id="example" class="display compact dataTables_scrollBody" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Agencia</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <script type="text/javascript">
  $(function(){

    var $ajuste = 200;
    var calcDataTableHeight = function() {
      return $(window).height() - $ajuste;
    };

    var $busqueda = $('#example').DataTable({
      // language: { url: '< ?=base_url("/tools/datatables/Spanish.json");?>' },
      data:[],
      columns: [
        { "data": "CODIGO",  "width": "25%", "className": "dt-center" },
        { "data": "USUARIO" }
      ],

      columnDefs: [
        {
          targets: 0,
          render: function ( data, type, row, meta ) {
            if(type === 'display') data = '<a class=xxx href=javascript:void() data-usuario='+data+'>' + data + '</a>';
            return data;
          }
        }
      ],
      rowCallback: function (row, data) {},
      scrollY: calcDataTableHeight,
      scrollCollapse: true,
      paging: false,
      info: false,
      searching: false
    });

    $("#btnBuscar").on("click",function(){
      $.ajax({
        dataType: 'json',
        url: "<?=base_url('datos/fnListaSimple')?>",
        data: {
          opcion: "usuarios",
          base: "JCHS2017",
          q: $("#txtBuscar").val()
        }
      }).done(function(result){

        $busqueda.clear().draw();
        $busqueda.draw().order( [[ 1, 'asc' ]] );
        $busqueda.rows.add(result.results).draw();

        $(".xxx").on("click",function(e){
          e.preventDefault();
          $codUsuario = $(this).data("usuario");
          alert($codUsuario)

        })

      })
    })

    $(window).resize(function() {
      $('.dataTables_scrollBody').css('height', calcDataTableHeight);
    });

  })
  </script>

  <div class="xForm">
    xForm
  </div>
</div>

</div>

<? $this->load->view("footer"); ?>
