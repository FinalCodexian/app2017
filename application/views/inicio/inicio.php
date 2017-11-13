<?php
$data = array(
  "titulo"=>"mi modulo"
);
?>

<? $this->load->view("header", $data); ?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">
    contenido...

    https://spigotdesign.com/hide-title-attribute-hover-dont-remove/
    https://pixinvent.com/bootstrap-admin-template/robust/html/ltr/vertical-menu-template/index.html
    http://demos.krajee.com/widget-details/select2#usage-templates
    https://lokesh-coder.github.io/pretty-checkbox/


    http://animista.net/play/text/tracking-out

  </div>

</div>

<? $this->load->view("footer"); ?>
