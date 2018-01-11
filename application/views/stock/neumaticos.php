<?php
$data = array(
  "titulo"=>"Stock: Neumaticos"
);

$this->load->view("header", $data);
?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">

  <style media="screen">
  .xBusqueda { height: calc(100vh - 60px); width: 100%; padding: 10px; overflow-y: auto;}
  .xBusqueda .secc1 { }
  .xBusqueda .secc2 { margin-top: 10px; display: block;}
  .dataTables_scrollBody { font-size: 12px}
  </style>

  <input type="text" name="" value="" id="x">
  <script type="text/javascript">

  $(function(){
    $('#x').pickadate({
      format: 'dd/mm/yyyy'
    });
  })
  </script>


  <div class="xBusqueda">

    <h4 class="ui header">
      <i class="cube icon"></i>
      <div class="content">
        <?=$data["titulo"];?>
        <div class="sub header">Existencias en tiempo real</div>
      </div>
    </h4>


    <style media="screen">
    table.tempo {
      border-collapse: separate; width: 100%
    }
    table.tempo td { border: 1px solid #ccc}
    </style>
    <div class="ui segment">
      <button type="button" class="ui teal tiny button" id="test">Prueba</button>
      <div class="ui segment" id="tempo">
      </div>
    </div>

    <script type="text/javascript">
    $(function(){
      $("#test").on("click",function(){

        $.ajax({
          type: "POST",
          dataType: 'json',
          url: "<?=base_url('stock/listar')?>",
          data: {
            opcion: "usuarios",
            base: "JCHS2017",
            q: $("#txtBuscar").val()
          },
          beforeSend: function(){
            $("#tempo").html("");
          }
        }).done(function(result){
          var $tabla = $("<table class=tempo></table>");
          $.each(result.data.items, function(i, item) {
            var $fila = $("<tr></tr>");
            $.each(item, function(j, valor) {
              $("<td></td>").html(valor).appendTo($fila);
            })
            $fila.appendTo($tabla);
          });

          $("#tempo").append($tabla);

        });


      })
    })
    </script>

    <style media="screen">
    .secc1 { background: ; padding: 0; margin: 0 auto; }
    .secc1 .grid { background: ; width: 96%; margin: 1px auto;}
    .secc1 .grid .row { background: ; margin: 0; padding: 5px; }
    .secc1 .grid .column { background: ; margin: 0}
    .secc1 .grey { font-weight: normal;}
    .secc1 .blue  { font-weight: normal;}
    .pretty { text-align: center; font-size: 12px; margin-top: 10px;}
    </style>

    <div class="secc1">


      <div class="ui grid">
        <div class="row">

          <div class="eleven wide column">

            <div class="ui mini fluid action input">

              <input type="text" placeholder="Buscar por código o descripción...">

              <div class="ui mini floating labeled icon dropdown button grey">
                <i class="filter icon"></i>
                <span class="text">Todas las líneas</span>
                <div class="menu">
                  <div class="header">
                    <i class="tags icon"></i>
                    Buscar por linea
                  </div>
                  <div class="divider"></div>
                  <div class="active item">Todas las líneas</div>
                  <div class="item">Línea neumáticos</div>
                  <div class="item">Línea motos</div>
                </div>
              </div>

              <div class="ui mini right labeled icon button blue" id="btnBuscar"><i class="search icon"></i>Buscar</div>

            </div>
          </div>


          <div class="two wide column">
            <div class="pretty p-icon p-curve p-smooth">
              <input value="S" type="checkbox" name="transito" />
              <div class="state p-primary">
                <i class="icon check"></i>
                <label>Transito</label>
              </div>
            </div>
          </div>

          <div class="two wide column">
            <div class="pretty p-icon p-curve p-smooth">
              <input value="S" type="checkbox" name="ceros" />
              <div class="state p-primary">
                <i class="icon check"></i>
                <label>Con ceros</label>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>

    <div class="secc2">
      <table id="example" class="display compact dataTables_scrollBody" cellspacing="0" width="100%">
      </table>
    </div>
  </div>

  <script type="text/javascript">
  $(function(){

    $(".dropdown").dropdown();

    var $ajuste = $(window).height() - 230;

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
