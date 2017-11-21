
<div class="ui wide inverted blue vertical menu">
  <?php
  $sess = $this->uri->segment(3, 0);
  ?>

  <div class="item"><h4>Menu Principal</h4></div>

  <div class="item">
    <h5 class="ui header">
      <i class="circular user icon"></i>
      <div class="content">
        <?=$this->session->userdata($sess)["usuarioNom"];?>
        <div class="sub header">Usuario del Sistema</div>
      </div>
    </h5>
  </div>


  <div class="item">
    <div class="header">Archivos</div>
    <div class="menu">
      <a class="item" href="<?=site_url('menu/archClientes')."/".$sess;?>">Consulta de Clientes</a>
    </div>
  </div>

  <div class="item">
    <div class="header">Ventas</div>
    <div class="menu">
      <a class="item" href="<?=site_url('menu/vtaClientes')."/".$sess;;?>">Ventas por Cliente</a>
      <a class="item">Ranking de Ventas</a>
      <a class="item">Record de Ventas</a>
    </div>
  </div>
  <div class="item">
    <div class="header">Stock</div>
    <div class="menu">
      <a class="item">Neumaticos</a>
      <a class="item">Motos</a>
    </div>
  </div>


</div>
