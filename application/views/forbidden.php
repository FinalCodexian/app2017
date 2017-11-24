<?php
$data = array(
  "titulo"=>"Acceso restringido"
);
?>

<? $this->load->view("header", $data); ?>

<div id="sidebar"><? $this->load->view("sidebar"); ?></div>

<div id="wrapper">
  <div id="header"><? $this->load->view("menu_top", $data); ?></div>

  <div id="dinamico">


        <div class="ui very padded center aligned basic segment">

          <div class="ui info message">
          <h2 class="ui icon header">
            <i class="warning sign icon"></i>
            <div class="content">
              Acceso restringido
              <div class="sub header">Su c&oacute;digo de usuario no tiene acceso a &eacute;ste m&oacute;dulo</div>
            </div>
          </h2>
        </div>
        </div>


  </div>

</div>

<? $this->load->view("footer"); ?>
