<?php
$data = array(
  "titulo"=>"Stock: Neumaticos"
);
?>

<? $this->load->view("header", $data); ?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">

  <style media="screen">
  .xBusqueda { height: calc(100vh - 60px); width: 100%; padding: 10px; overflow-y: auto; }
  .xBusqueda .secc1 { }
  .xBusqueda .secc2 { margin-top: 10px; display: block;}
  .dataTables_scrollBody { font-size: 12px}
  </style>

  <div class="xBusqueda">
    <?php
    $ip = $_SERVER['REMOTE_ADDR'];
    echo "mi ip publico: " . $ip;
    ?>

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
      </table>
    </div>
  </div>

  <script type="text/javascript">
  $(function(){

    var $ajuste = $(window).height() - 200;

    var $busqueda = $('#example').DataTable({
      language: { url: '<?=base_url("/tools/datatables/Spanish.json");?>' },
      data:[],
      columns: [
        {title: "Codigo", data: "CODIGO", width: "40", className: "dt-center" },
        {title: "Usuario", data: "USUARIO" }
      ],
      scrollY: $ajuste,
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

  })
  </script>

</div>

</div>

<? $this->load->view("footer"); ?>
