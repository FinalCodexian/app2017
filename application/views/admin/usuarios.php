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
  .xForm { margin-left: 355px; font-size: 13px}
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

    var $ajuste = $(window).height() - 200;

    var $busqueda = $('#example').DataTable({
      language: { url: '<?=base_url("/tools/datatables/Spanish.json");?>' },
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

  <style media="screen">
  .xp-list { display: block; margin: 7px auto; font-size: 13px}
  </style>

  <div class="xForm">

    <div class="ui internally celled  grid">
      <div class="two column row">
        <div class="ten wide column">

          <h4 class="ui dividing header">Privilegios</h4>

          <div class="pretty p-icon p-curve p-smooth xp-list">
            <input value="S" type="checkbox" name="transito" />
            <div class="state p-primary">
              <i class="icon check"></i>
              <label>El usuario puede cambiar su contraseña de acceso</label>
            </div>
          </div>

          <div class="pretty p-icon p-curve p-smooth xp-list">
            <input value="S" type="checkbox" name="transito" />
            <div class="state p-primary">
              <i class="icon check"></i>
              <label>Puede cambiar sus datos generales</label>
            </div>
          </div>

          <div class="ui sub header">Nivel de usuario</div>
          <div class="pretty p-switch p-fill xp-list">
            <input type="radio" name="switch1"/>
            <div class="state p-primary">
              <label><strong>Operario</strong></label>
            </div>
          </div>
          Acceso al codigo su codigo de vendedor o los vendedores asignados a su usuario

          <div class="ui sub header" style="margin-top:12px">Vendedores asignados</div>
          <select multiple="" name="skills" class="ui fluid normal dropdown">
            <option value="">Skills</option>
            <option value="angular">Angular</option>
            <option value="css">CSS</option>
            <option value="design">Graphic Design</option>
            <option value="ember">Ember</option>
            <option value="html">HTML</option>
            <option value="ia">Information Architecture</option>
            <option value="javascript">Javascript</option>
          </select>


          <div class="ui divider"></div>

          <!--
          Campo= TU_IMPRES
      		MS = Master - acceso total
      		SU = Supervisor - Administra TODO
      		AD = Administrador - Administrada una o varias agencias / ciudad
      		OP = Usuario / Asistente / Vendedor
        -->

        <div class="pretty p-switch p-fill xp-list">
          <input type="radio" name="switch1"/>
          <div class="state p-primary">
            <label><strong>Supervisor</strong></label>
          </div>
        </div>
          Permite ver todos los codigos de las agencias asignadas




          <div class="ui divider"></div>

          <div class="pretty p-switch xp-list">
            <input type="radio" name="switch1" />
            <div class="state p-success">
              <label>Operario</label>
            </div>
          </div>
          Acceso al codigo de usuario/vendedor o los asignados de la agencia registrada por el sistema
          <div class="ui divider"></div>
          <div class="pretty p-switch xp-list">
            <input type="radio" name="switch1" />
            <div class="state p-success">
              <label>Operario</label>
            </div>
          </div>
          Acceso al codigo de usuario/vendedor o los asignados de la agencia registrada por el sistema


          <script type="text/javascript">
          $(function(){
            $('.dropdown').dropdown();
          })
          </script>

        </div>
        <div class="six wide column">

          <h4 class="ui dividing header">Accesos al Menú</h4>

          <div class="ui basic segment" style="padding-top: 0">


            <?php
            $sess = $this->uri->segment(3, 0);
            $dbLuis = $this->load->database('default', TRUE);
            $dbLuis->select('padre.id padre_id, padre.DESCRIPCION padre, padre.ICONCLASS padre_class');
            $dbLuis->select('hijo.id hijo_id, hijo.DESCRIPCION hijo, hijo.ICONCLASS hijo_class, hijo.enlace enlace');
            $dbLuis->select('opc.lectura, opc.escritura');
            $dbLuis->from('MENU padre');
            $dbLuis->join('MENU hijo', 'padre.ID=hijo.PADRE_ID', 'left');

            $user = $this->session->userdata($sess)["usuarioId"];
            $base = $this->session->userdata($sess)["base"];
            $dbLuis->join('MENU_USUARIO opc', 'opc.MENU=hijo.id AND opc.USUARIO=\''.$user.'\' AND opc.BASE=\''.$base.'\' ', 'left');

            $dbLuis->where('padre.PADRE_ID', '0');
            $dbLuis->order_by("padre.orden", "asc");
            $dbLuis->order_by("hijo.orden", "asc");

            $q = $dbLuis->get();
            $menuActual = "";
            $active = "";

            if($q->num_rows()>0):
              foreach ($q->result() as $row):
                if($menuActual <> $row->padre):
                  echo '<h5>' . $row->padre . '</h5>';
                endif;

                if($this->uri->segment(1, 0) . "/" . $this->uri->segment(2, 0) == trim($row->enlace)) $active = "active";
                ?>

                <div class="pretty p-switch p-fill xp-list">
                  <input type="checkbox" name="switch1" />
                  <div class="state p-success">
                    <label><?=$row->hijo;?></label>
                  </div>
                </div>

                <?php

                //
                // if(trim($row->enlace) <> '' &&  !is_null($row->lectura) && $row->lectura!=='N'):
                //   echo '<a class="item '.$active.'" href="' . site_url(trim($row->enlace)).'/'.$sess . '">' . $row->hijo . '<i class="' . $row->hijo_class . ' icon"></i></a><br>';
                // else:
                //   echo '<a class="item" href="' . site_url("forbidden/msg").'/'.$sess . '">' . $row->hijo . '<i class="' . $row->hijo_class . ' icon"></i></a><br>';
                // endif;

                $active  = "";

                $menuActual = $row->padre;
              endforeach;
              // echo '</div></div>';
            endif;

            ?>
          </div>


        </div>
      </div>
    </div>

  </div>
</div>

</div>

<? $this->load->view("footer"); ?>
