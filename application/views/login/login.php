<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JCH - Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes" />

  <link rel="shortcut icon" href="<?=base_url('images/favicon.ico');?>">

  <link rel="stylesheet" type="text/css" href="<?=base_url('/tools/semantic/semantic.min.css')?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto+Condensed" />

  <script src="<?=base_url('tools/jquery/jquery.min.js')?>"></script>
  <script src="<?=base_url('/tools/semantic/semantic.min.js')?> "></script>
  <script src="<?=base_url('/tools/jquery-validation/dist/jquery.validate.min.js')?> "></script>

  <?php
  $this->load->view("login/css_login");
  ?>

</head>
<body>

  <div id="login-box" class="ui segment">

    <h2 class="ui icon header aligned center">
      <i class="users icon"></i>
      <div class="content">
        Acceso de Usuarios
        <div class="sub header">Ingreso de usuarios registrados</div>
      </div>
    </h2>

    <div class="ui negative small message hidden">
      <i class="close icon"></i>
      <div class="header">No se pudo acceder al sistema</div>
      <p>Revise si su código y clave son correctos</p>
    </div>

    <form id="formLogin" class="ui form" autocomplete="off" method="post">

      <div class="field">
        <label>Empresa</label>
        <select class="ui dropdown" required name="empresa" title="Seleccione una empresa">
          <option value="">Seleccione su empresa</option>
          <option value="JCHS2017">JCH COMERCIAL SA</option>
          <option value="CARS2017">EVOLUTION CAR SERVICE</option>
        </select>
      </div>

      <div class="field">
        <div class="two fields">
          <div class="field">
            <label>Codigo de usuario</label>
            <input type="text" name="usuario" required title="Ingrese su usuario" maxlength="5">
          </div>

          <div class="field">
            <label>Clave de acceso</label>
            <input type="password" name="clave" required title="Ingrese su clave" maxlength="8">
          </div>
        </div>

      </div>

      <input type="hidden" name="opcion" value="login">
      <input type="hidden" name="empresa_nom" value="">
      <button class="fluid tiny primary ui button">Ingresar</button>
    </form>

  </div>




  <script type="text/javascript">
  $(function () {

    var $formLogin = $("#formLogin");

    $formLogin.validate({
      submitHandler: function(form){

        $.ajax({
          type: 'post',
          url: '<?=site_url('login/ingresar');?>',
          data: $formLogin.serialize(),
          dataType: 'json',
          beforeSend: function () {
            $formLogin.parent().addClass("loading");
            $(".message.negative").slideUp("fast");
          },
          success: function (data) {
            console.log(data);
            if (data.status===true){
              window.location = "<?=site_url("/welcome/inicio");?>/"+data.md5;
            }else{
              $(".message.negative").slideDown("fast");
              $formLogin.parent().removeClass("loading");
            }
          }
        });

      }
    });

    $("#formLogin").on("submit",function(){
      return false;
    });

    $('[name="empresa"]').on("change", function () {
      $('[name="usuario"]').focus();
      $('[name="empresa_nom"]').val($("option:selected",this).text());
    });

    $(".close.icon").click(function () {
      $(this).parent().fadeOut(200);
    });

  });

  </script>

</body>
</html>
