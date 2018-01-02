<div class="ui mini top fixed menu" id="top-menu">

  <a class="item mobile-button">
    <div class="hamburger hamburger--vortex is-active">
      <div class="hamburger-box">
        <div class="hamburger-inner"></div>
      </div>
    </div>
  </a>

  <?php
  $sess = $this->uri->segment(3, 0);
  ?>

  <a class="item active xhelp" style="color:rgb(28, 80, 119)">
    <?=$titulo;?>&nbsp;&nbsp;&nbsp;&nbsp;
    <i class="icon small info circular"></i>
  </a>
  <div class="right menu">
    <div class="item">
      <strong>Empresa:</strong>&nbsp;<?=$this->session->userdata($sess)["empresa"];?>
    </div>

    <div class="item">
      <strong>Agencia:</strong>&nbsp;<?=$this->session->userdata($sess)["agenciaNom"];?>
    </div>

    <!--div class="ui dropdown item mnuOpciones">
      <i class="icon large setting"></i>
      <i class="dropdown icon"></i>
      <div class="menu">
        <a class="item">Administrar</a>
        <a class="item" href="< ?=site_url("/login/logout/".$sess);?>">Cerrar sesion</a>
      </div>
    </div-->

  </div>
</div>
