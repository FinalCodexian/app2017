<?php
ini_set('date.timezone', 'America/Lima');
date_default_timezone_set( 'America/Lima' );
$sess = $this->uri->segment(3, 0);
if( !$this->session->userdata($sess) ):
  redirect(site_url(), "refresh");
  die();
endif;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="-1">
  <meta http-equiv="refresh" content="<?=$this->config->item('sess_expiration');?>" />
  <link rel='icon' type='image/png' href='<?=base_url("images/favicon.ico");?>'>

  <title>JCH - <?=$titulo?></title>
  <link rel="stylesheet" href="<?=base_url('tools/css/hamburgers.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/semantic/semantic.min.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/HoldOn/HoldOn.min.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/css/estilos.css');?>">

  <link rel="stylesheet" href="<?=base_url('tools/select2/dist/css/select2.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/select2/dist/css/select2-xLuis.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/pretty-checkbox/dist/pretty-checkbox.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/material-icon/material-design-iconic-font.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/material-icon/material-design-color-palette.min.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/handsontable/dist/pikaday/pikaday.css');?>">
  <link rel="stylesheet" href="<?=base_url('tools/jbox/jBox.css');?>">

  <link rel="stylesheet" href="<?=base_url('tools/datatables/jquery.dataTables.min.css');?>">

  <!-- <link rel="stylesheet" href="< ?=base_url('tools/pickadate/lib/themes/default.css');?>"> -->
  <!-- <link rel="stylesheet" href="< ?=base_url('tools/pickadate/lib/themes/classic.date.css');?>"> -->


  <script src="<?=base_url('tools/jquery/jquery.min.js');?>"></script>
  <script src="<?=base_url('tools/semantic/semantic.min.js');?>"></script>
  <script src="<?=base_url('tools/handsontable/dist/moment/moment.js');?>"></script>
  <script src="<?=base_url('tools/handsontable/dist/pikaday/pikaday.js');?>"></script>
  <script src="<?=base_url('tools/handsontable/dist/pikaday/pikaday.jquery.js');?>"></script>
  <script src="<?=base_url('tools/select2/dist/js/select2.full.js');?>"></script>
  <script src="<?=base_url('tools/select2/dist/js/i18n/es.js');?>"></script>
  <script src="<?=base_url('tools/HoldOn/HoldOn.min.js');?>"></script>
  <script src="<?=base_url('tools/jQuery-Mask-Plugin/dist/jquery.mask.min.js');?>"></script>
  <script src="<?=base_url('tools/countup.js/dist/countUp.min.js');?>"></script>
  <script src="<?=base_url('tools/highlight.js');?>"></script>
  <script src="<?=base_url('tools/jquery.hotkeys.js');?>"></script>
  <script src="<?=base_url('tools/jbox/jBox.min.js');?>"></script>

  <script src="<?=base_url('tools/datatables/jquery.dataTables.min.js');?>"></script>
  <script src="<?=base_url('tools/jquery.number.min.js');?>"></script>

  <!-- <script src="< ?=base_url('tools/pickadate/lib/picker.js');?>"></script>
  <script src="< ?=base_url('tools/pickadate/lib/picker.date.js');?>"></script>
  <script src="< ?=base_url('tools/pickadate/lib/translations/es_ES.js');?>"></script> -->

  <link rel="stylesheet" type="text/css" href="<?=base_url('/tools/handson/handsontable.full.min.css');?>">
  <script src="<?=base_url('/tools/handson/handsontable.full.min.js');?>"></script>

  <script src="<?=base_url('/tools/ResizeSensor.js');?>"></script>
  <script src="<?=base_url('/tools/Chart.js');?>"></script>

  <style media="screen">

  *, .dropdown, input, button, textarea, select, label, h1, h2, h3, h4, h5, h6, a {font-family: 'Roboto Condensed', sans-serif;}
  .alCielo {
    display: none;
    position: fixed;
    bottom: 10px;
    right: 10px;
    background-color: rgba(157, 157, 157, 0.4);
    padding: 0 10px;
    border-radius: 50%
  }
  </style>
</head>
<body>
