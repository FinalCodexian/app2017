<?php
ob_start();

if ($_POST){ // Si se envia el formulario
	require_once("lib/conexion.php");
	$sub = $_POST["sub"]; 
	$sql = "SELECT u.TU_ALIAS usuario_codigo, u.TU_NOMUSU usuario_nombre, u.TU_CCODAGE agencia_codigo, g.AG_CDESCRI agencia_nombre, " .
		"u.TU_NROALM almacen_codigo, a.A1_CDESCRI almacen_nombre, v.VE_CNOMBRE vendedor_nombre, " . 
		"AC_CRUC RUC,  AC_CRUTCON BASE_CONCAR FROM UT0030 u " .
		"LEFT JOIN ALCIAS s ON s.AC_CCIA=s.AC_CCIA LEFT JOIN FT0001AGEN g ON g.AG_CCODAGE=u.TU_CCODAGE LEFT JOIN AL0001ALMA a ON a.A1_CALMA=u.TU_NROALM " .
		"LEFT JOIN FT0001VEND v ON v.VE_CCODIGO=u.TU_ALIAS WHERE 1=1 "; 
	if($_POST["libre"]==""){ $sql .= "AND u.TU_ALIAS='".strtoupper($_POST["usuario"])."' AND u.TU_PASSWO='". DesencriptaSoftcom(strtoupper($_POST["pass"])) ."' ";}
	if($_POST["libre"]==true){ $sql.= "AND u.TU_ALIAS='SIST' ";}
	$connect = mssql_connect(cxServer,cxUsuario,cxPwd);
	mssql_select_db($_POST["base"]);
	$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());

	$RowNumber = 0; $display_json = array(); $json_arr = array();

	if (mssql_num_rows($resultado)==1){ 
		$res = mssql_fetch_object($resultado);
		setcookie($sub."usuario_codigo", trim($res->usuario_codigo), time()+(60*60*24*365));
		setcookie($sub."usuario_nombre", trim(utf8_encode($res->usuario_nombre)), time()+(60*60*24*365));
		setcookie($sub."agencia_codigo", $res->agencia_codigo, time()+(60*60*24*365));
		setcookie($sub."agencia_nombre", trim(utf8_encode($res->agencia_nombre)), time()+(60*60*24*365));
		setcookie($sub."almacen_codigo", $res->almacen_codigo, time()+(60*60*24*365));
		setcookie($sub."almacen_nombre", trim($res->almacen_nombre), time()+(60*60*24*365));
		setcookie($sub."vendedor_codigo", trim(utf8_encode($res->vendedor_nombre)), time()+(60*60*24*365));
		setcookie($sub."base", $_POST["base"], time()+(60*60*24*365));
		setcookie($sub."empresa_nombre", $_POST["empresa"], time()+(60*60*24*365));
	 	setcookie($sub."base_concar", $res->BASE_CONCAR, time()+(60*60*24*365));

	 	$json_arr["sub"] = $_POST["sub"];
		$json_arr["usuario_nombre"] = utf8_encode(trim($res->usuario_nombre));

		array_push($display_json, $json_arr);

		// return values
		$jsonWrite = json_encode($display_json); 
		print '{ "estado":"true", "rows":' . $jsonWrite . '}';
		die(); 


	}else{

		print '{ "estado":"false"}';
		die(); 


		$segundos = 5; 
		?>
        <html>
        <head>
        <title>JCH Llantas - ERROR DE AUTENTICACION</title>
        </head>
        <body>
        <style>
		*, body, html {font-family:Tahoma, Geneva, sans-serif; color: #666; font-size: 14px; line-height:24px }
		body {background-color:#eee}
		div {padding:20px; margin:40px auto; background-color:#fff; width:500px; text-align:center; letter-spacing:0.2px;
		position:relative}
		hr {border:0 none; border-bottom:1px solid #ccc; width:80%; margin:20px auto}
		a, a span {font-size:11px;  text-decoration:none; color:#06c}
		img { opacity: 1; position:absolute; width:200px; right:-60px; top:-20px}
		</style>
        <div>
        <img src="../dist/img/svg_2/warning.svg">
        <strong>Fallo de autenticaci&oacute;n!</strong><br>
        Ingrese su usuario y clave correctamente<hr>
        <a href="javascript:cerrar();">Cerrar ventana [&nbsp;<span id="contador"><?=$segundos;?></span>&nbsp;]</a>
        </div>
        <script>
        alert(9)
		var sec = <?=$segundos;?> -1; 
		var timer = setInterval(function() { 
		document.getElementById("contador").innerHTML = sec--;
		if (sec == -1) {
		clearInterval(timer);
		window.close();
		}}, 1000);		
		var cerrar = function(){  window.close(); }	
		</script>        
        </body></html>
        <?php
	}
	
}
ob_end_flush();
?>