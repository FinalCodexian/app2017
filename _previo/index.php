<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" /> 
<meta http-equiv="Pragma" content="no-cache"> 
<meta http-equiv="Expires" content="-1">
<link rel='icon' type='image/png' href='favicon.png'>
<title>JCH Llantas - INTRANET 2017</title>

<link href="css/login.css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="js/jquery-1.12.1.js"></script>
<script language="javascript" type="text/javascript" src="js/funciones.js"></script>

<script type="text/javascript">
$(document).ready(function(){

	var $loadScripts = [
		'js/jquery.hotkeys.js',
		'js/jquery.validate.min.js',
		'js/jquery.backstretch.js',
		'js/login.js'
	];

	$.getScript($loadScripts, function(data, textStatus) {
		Login.init();
	});
});
</script>
</head>

<body class="login">

	<div class="wrapper">

		<div class="logo"><img src="img/JCH-logo.png"></div>

		<div class="content">
        	
        	<div id='mensaje' class="correcto"></div><div id='progreso'><div class="barra"></div></div>
            
			<form method="post" id="login-form" target="_self">
            
				<input type="hidden" name="empresa" value="" autocomplete="off" >
				<input type="hidden" name="pass" value="" autocomplete="off" >
				<input type="hidden" name="sub" value="" autocomplete="off">     
				<input type="hidden" name="usuario" value="" autocomplete="off">     
				<input type="hidden" name="clave" value="" autocomplete="off">
				<input type="hidden" name="base" value="" autocomplete="off">

				<div class="control-group">
					<h3 class="form-title">Ingrese a su cuenta</h3>

					<div class="div-icon">
						<i class="icon-build"></i>
						<select name="_base">
							<option value="JCHS2017" sub='jch_' selected>JCH COMERCIAL</option>
							<option value="CARS2017" sub='evo_'>EVOLUTION CAR SERVICE</option>
							<option value="SOFTCOMTOP" sub='top_'>TOP GARAGE</option>
							<option value="NEUS2017" sub='neu_'>NEUMACENTER</option>
						</select>
					</div>

					<div class="div-icon">
						<i class="icon-user"></i>
						<input name="_usuario" type="text" maxlength="5" autocomplete="off" placeholder="Usuario" required />
					</div>

					<div class="div-icon">
						<i class="icon-lock"></i>
						<input name="_clave" type="password" maxlength="8" autocomplete="off" placeholder="Contraseña" required />
					</div>

					<div class="div-icon" id="agencias"><i class="icon-gmap"></i><select name="agencia"></select></div>
					<div class="div-icon" id="listar"><input type="checkbox" name="libre"></div>

					<div class="form-actions"><button type="submit">Ingresar al sistema</button></div>
				</div>
			</form>


		</div> 

		<div class="pie">JCH Llantas - <a href="mailto:soporte.lima@jchllantas.com.pe">&Aacute;rea de Soporte T&eacute;cnico</a> &copy; <?=date("Y");?></div>
	</div>

</body>

</html>