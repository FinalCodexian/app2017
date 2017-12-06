<?php
$data = array(
  "titulo"=>"Inicio"
);
?>

<? $this->load->view("header", $data); ?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">

  </div>

</div>

<? $this->load->view("footer"); ?>
