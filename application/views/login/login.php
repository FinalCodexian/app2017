<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JCH Llantas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="shortcut icon" href="<?=base_url('images/favicon.ico');?>">
  <link rel="stylesheet" type="text/css" href="<?=base_url('/tools/semantic/semantic.min.css')?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto+Condensed" />
  <script src="<?=base_url('tools/jquery/jquery.min.js')?>"></script>
  <script src="<?=base_url('/tools/semantic/semantic.min.js')?> "></script>
  <script src="<?=base_url('/tools/jquery-validation/dist/jquery.validate.min.js')?> "></script>
  <?php $this->load->view("login/css_login");?>
</head>
<body>

<style media="screen">
#paso-1 { /* --- */}
#paso-2 { display: none}
#paso-3 { display: none}
/*.pass { background: rgba(68, 148, 31, 0.35)}*/
/*.active { background: rgba(10, 34, 108, 0.5)}*/
</style>

<div id="login-box" class="ui segment">

  <h3 class="ui icon header aligned center">
    <i class="user circular icon"></i>
    <div class="content">Acceso de Usuarios</div>
  </h3>

  <div id="paso-1" class="active">
    <form class="ui form" autocomplete="off" method="post">
      <div class="field">
        <select class="ui dropdown" required name="empresa">
          <option value="" disabled selected>Seleccione una empresa</option>
        </select>
      </div>
      <div class="error"></div>
    </form>
  </div>

  <div id="paso-2">
    <form class="ui form" autocomplete="off" method="post">
      <div class="field">
        <select class="ui dropdown" required name="base">
          <option value="" disabled selected>Seleccione un periodo/año</option>
        </select>
      </div>
      <div class="error"></div>
    </form>
  </div>

  <div id="paso-3">
    <form class="ui form" autocomplete="off" method="post">
      <div class="field">
        <div class="two fields">
          <div class="field">
            <label>Codigo de usuario</label>
            <input type="text" name="usuario" maxlength="5" required>
          </div>
          <div class="field">
            <label>Clave de acceso</label>
            <input type="password" name="clave" maxlength="8" required>
          </div>
        </div>
      </div>
      <div class="error"></div>

      <input type="hidden" name="base" value="">
      <button type="submit" name="empresa_nom" style="display:none"></button>
    </form>
  </div>

  <div class="ui negative small message hidden">
    <i class="close icon"></i>
    <div class="header">No se pudo acceder al sistema</div>
    <p>Revise si su código y clave son correctos</p>
  </div>

  <button id="btn" class="fluid tiny green ui right labeled icon button">
    <i class="right arrow icon"></i>
    Continuar
  </button>

  <div class="ui tiny blue bottom attached progress" id="progreso" data-value=1>
    <div class="bar"></div>
  </div>

</div>





<script type="text/javascript">
$(function () {

  var $paso_1 = $("#paso-1");
  var $paso_2 = $("#paso-2");
  var $paso_3 = $("#paso-3");

  $("#btn").on("click",function(){
    $form  = $("form", $("div.active"))
    $form.submit();
  })

  $('#progreso').progress({
    showActivity: false,
    total: 3
  });

  $.ajax({
    url: "<?=site_url('login/empresas');?>",
    type: "POST",
    dataType: 'json',
    beforeSend: function(){ $paso_1.addClass("loading");}
  }).done(function(resp){
    if(resp.total>0){
      $.each(resp.items, function(i, item) {
        $("<option></option>").val(item.RUC_EMPRESA).text(item.EMPRESA).appendTo("[name='empresa']")
      });
      $paso_1.removeClass("loading");

    }
  })

  $("form", $paso_1).validate({
    errorLabelContainer: $("div.error", $("form", $paso_1)),
    messages: { empresa: "<i class='building outline circular icon'></i>Debe seleccionar una empresa para continuar" },
    submitHandler: function(form){

      $.ajax({
        type: 'post',
        url: '<?=site_url('login/bases');?>',
        data: $("form", $paso_1).serialize(),
        dataType: 'json'
      }).done(function(resp){
        $paso_1.addClass("pass");
        $paso_1.removeClass("active");
        $paso_2.addClass("active");
        $("[name='empresa']", $paso_1).prop("disabled", true);

        if(resp.total>0){
          $.each(resp.items, function(i, item) {
            $("<option></option>").val(item.BASE).text(item.PERIODO).appendTo("[name='base']")
          });
        }
        $paso_2.show(300);
        $('#progreso').progress('increment');
      });

    }
  });


  $("form", $paso_2).validate({
    errorLabelContainer: $("div.error", $("form", $paso_2)),
    messages: { base: "<i class='checked calendar circular icon'></i>Debe seleccionar un un periodo continuar" },
    submitHandler: function(form){
      $paso_2.addClass("pass");
      $paso_2.removeClass("active");
      $paso_3.addClass("active");
      $("[name='base']", $paso_2).prop("disabled", true);
      $paso_3.show(300);
      $('#progreso').progress('increment');
    }
  });


  $("form", $paso_3).validate({
    errorLabelContainer: $("div.error", $("form", $paso_3)),
    messages: {
      usuario: "<i class='user circular icon'></i>Debe ingresar su codigo de usuario",
      clave: "<i class='lock circular icon'></i>Debe digitar una contraseña correcta",
    },
    submitHandler: function(form){

      $.ajax({
        type: 'post',
        url: '<?=site_url('login/ingresar');?>',
        data: $("form", $paso_3).serialize(),
        dataType: 'json',
        beforeSend: function () {
          $paso_3.addClass("loading");
          $(".message.negative").slideUp("fast");
        },
        success: function (data) {
          console.log(data);
          if (data.status===true){

            $("[name='usuario']", $paso_3).prop("disabled", true);
            $("[name='clave']", $paso_3).prop("disabled", true);
            $("#btn").prop("disabled", true);

            $('#progreso').progress({
              percent: 100,
              onSuccess: function(){
                window.location = "<?=site_url("/welcome/inicio");?>/"+data.md5;
              }
            })

          }else{
            $(".message.negative").slideDown("fast");
            $paso_3.removeClass("loading");
          }
        }
      });

    }
  });

  $("form").on("submit",function(){
    return false;
  });

  $('[name="base"]', $paso_2).on("change", function () {
    $('[name="base"]', $paso_3).val($("option:selected",this).val());
  });

  $('[name="empresa"]').on("change", function () {
    $('[name="empresa_nom"]').val($("option:selected",this).text());
  });



  $(".close.icon").click(function () {
    $(this).parent().fadeOut(200);
  });

});

</script>

</body>
</html>
