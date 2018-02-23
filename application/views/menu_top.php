<style media="screen">
  #top-menu .item, #top-menu label, #top-menu strong {font-family: "Roboto Condensed", sans-serif; font-size: 11px !important}
</style>

<div id="wrapper">
  <div id="header">

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

        <div class="item icon">
          <i class="alarm outline icon"></i>

        </div>

        <div class="item">
          <strong>Empresa:</strong>&nbsp;<?=$this->session->userdata($sess)["empresa"];?>
        </div>

        <div class="item">
          <strong>Agencia:</strong>&nbsp;<?=$this->session->userdata($sess)["agenciaNom"];?>
        </div>

      </div>
    </div>
  </div>


  <div id="dinamico">
