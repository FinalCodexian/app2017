<?php
$data = array(
  "titulo"=>"Administracion de Almacenes"
);

$this->load->view("header", $data);
?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>
  <div id="dinamico">

    <div class="ui basic segment">

      <h5 class="ui dividing header">Administracion</h5>

      <div class="ui form">
        <div class="field">
          <select>
            <option value="">Gender</option>
            <option value="1">Male</option>
            <option value="0">Female</option>
          </select>
        </div>
      </div>


      <div class="ui tiny form">

        <div class="fields">
          <div class="eight wide field">

            <div class="field">
              <select>
                <option value="">Gender</option>
                <option value="1">Male</option>
                <option value="0">Female</option>
              </select>
            </div>

            
            <div class="ui selection dropdown">
              <input type="hidden" name="gender">
              <i class="dropdown icon"></i>
              <div class="default text">Empresa</div>
              <div class="menu">
                <div class="item" data-value="1">Male</div>
                <div class="item" data-value="0">Female</div>
              </div>
            </div>
          </div>
          <div class="six wide field">

            <div class="ui selection dropdown">
              <input type="hidden" name="gender">
              <i class="dropdown icon"></i>
              <div class="default text">Año / Periodo</div>
              <div class="menu">
                <div class="item" data-value="1">Male</div>
                <div class="item" data-value="0">Female</div>
              </div>
            </div>

          </div>
          <div class="two wide field">
            <div class="ui tiny fluid button">Buscar</div>
          </div>
        </div>

      </div>


      <div class="ui celled grid">

        <div class="columns row">

          <div class="four wide column">

          <style media="screen">
          .tableDatos { height: calc(100vh - 280px)}
          </style>

          <table id="tblCiudad" class="display compact dataTables_scrollBody tableDatos" cellspacing="0">
          </table>

        </div>

        <div class="five wide column">
          <table id="tblAlmacen" class="display compact dataTables_scrollBody tableDatos" cellspacing="0">
          </table>
        </div>

        <div class="seven wide column">

          <h5 class="ui dividing header">Edicion</h5>

          <div class="ui tiny form">


            <div class="required inline field">
              <div class="ui checkbox">
                <input type="checkbox" tabindex="0" class="hidden">
                <label>I agree to the terms and conditions</label>
              </div>
            </div>


            <div class="fields">
              <div class="field">
                <label>Username</label>
                <input type="text" placeholder="Username">
              </div>
              <div class="field">
                <label>Password</label>
                <input type="password">
              </div>
            </div>
            <div class="equal width fields">
              <div class="field">
                <label>First name</label>
                <input type="text" placeholder="First Name">
              </div>
              <div class="field">
                <label>Middle name</label>
                <input type="text" placeholder="Middle Name">
              </div>
              <div class="field">
                <label>Last name</label>
                <input type="text" placeholder="Last Name">
              </div>
            </div>
          </div>


          <div class="ui success message">
            <div class="header">Could you check something!</div>
            <ul class="list">
              <li>That e-mail has been subscribed, but you have not yet clicked the verification link in your e-mail.</li>
            </ul>
          </div>


        </div>

        <script type="text/javascript">
        $('.dropdown').dropdown();
        $('.checkbox').checkbox();

        var $ajuste = $(window).height() - 230;

        $('#tblCiudad').DataTable({
          language: { url: '<?=base_url("/tools/datatables/Spanish.json");?>' },
          data:[],
          columns: [
            {title: "Ciudad", data: "CODIGO", width: "40", className: "dt-center" }
          ],
          scrollY: $ajuste,
          scrollCollapse: true,
          paging: false,
          info: false,
          searching: false
        });

        $('#tblAlmacen').DataTable({
          language: { url: '<?=base_url("/tools/datatables/Spanish.json");?>' },
          data:[],
          columns: [
            {title: "Almacen", data: "CODIGO", width: "40", className: "dt-center" }
          ],
          scrollY: $ajuste,
          scrollCollapse: true,
          paging: false,
          info: false,
          searching: false
        });

        </script>


      </div>
    </div>

  </div>


</div>
</div>


<? $this->load->view("footer"); ?>
