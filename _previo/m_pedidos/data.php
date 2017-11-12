<?php
	session_start(); 

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

	require_once("../lib/conexion.php"); 
	$connect = @mssql_connect(cxServer,cxUsuario,cxPwd);
	$display_json = array(); $json_arr = array();

	switch($_POST["opcion"]):

	case 'almacenes': 
		$base = $_POST['base']; 
		@mssql_select_db($base);
		$agencia = $_POST["agencia"]; 
		$sql = "SELECT A1_CALMA AS codigo, A1_CDESCRI AS almacen FROM AL0001ALMA ".
			   " WHERE A1_CLOCALI='9000' AND NOT (LEFT(A1_CALMA,1)='S' OR LEFT(A1_CALMA,1)='W') " .
			   "ORDER BY A1_CALMA";

		$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
		if (mssql_num_rows($resultado)>0 ):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["codigo"] = htmlentities(stripslashes($row['codigo'])); 
				$json_arr["almacen"] = htmlentities(stripslashes(trim($row['almacen']))); 
				array_push($display_json, $json_arr);			
			endwhile; 	
		endif; 
		

		break;


	case "clientes":
		@mssql_select_db(cxBase);
		$buscar = strtoupper($_POST['buscar']); 
		$sql = "SELECT CL_CCODCLI AS codigo, CL_CNOMCLI as cliente, VEN.VE_CNOMBRE AS vendedor FROM FT0001CLIE " .
			   "LEFT JOIN FT0001VEND VEN ON VEN.VE_CCODIGO=CL_CVENDE " .
			   "WHERE CL_CNOMCLI LIKE '%".$buscar."%' OR CL_CCODCLI LIKE '%".$buscar."%' " .
			   " ORDER BY CL_CNOMCLI"; 


		$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
		if (mssql_num_rows($resultado)>0 ):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["codigo"] = htmlentities(stripslashes($row['codigo'])); 
				$json_arr["cliente"] = htmlentities(stripslashes(trim($row['cliente']))); 
				$json_arr["vendedor"] = htmlentities(stripslashes(trim($row['vendedor']))); 
				array_push($display_json, $json_arr);			
			endwhile; 	
		endif; 
		break;

// ---------------------------------------------------------------------------------------------------------------------		
// ---------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------



	case 'detalle-consignacion':
		$base = $_POST['base']; 
		@mssql_select_db($base);
		$codigo = $_POST["codigo"]; 
		$agencia = $_POST["agencia"]; 

	    $sql = "SELECT alma.A1_CCODCLI AS ruc, clie.CL_CNOMCLI AS cliente, vend.VE_CNOMBRE AS vendedor, s.SK_NSKDIS cant " .
			   "FROM AL0001STOC s " .
			   "LEFT JOIN AL0001ALMA alma ON (s.SK_CALMA=alma.A1_CALMA) " .
			   "LEFT JOIN FT0001CLIE clie ON (clie.CL_CCODCLI=alma.A1_CCODCLI) " .
			   "LEFT JOIN FT0001VEND vend ON (vend.VE_CCODIGO=clie.CL_CVENDE) " .
			   "WHERE s.SK_CCODIGO='".$codigo."' AND alma.A1_CLOCALI='".$agencia."' AND s.SK_NSKDIS<>0 and ALMA.A1_CTIPO='C' " .
			   "ORDER BY alma.A1_CALMA";
			   
		//  s.SK_CCODIGO='".$codigo."' AND

		$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
		if (mssql_num_rows($resultado)>0 ):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["ruc"] = htmlentities(stripslashes($row['ruc'])); 
				$json_arr["cliente"] = htmlentities(stripslashes($row['cliente'])); 
				$json_arr["vendedor"] = htmlentities(stripslashes($row['vendedor'])); 
				$json_arr["stock"] = htmlentities(stripslashes($row['cant'])); 
				array_push($display_json, $json_arr);			
			endwhile; 	
		endif; 
		

		break;

	case "transito":
		@mssql_select_db(cxBase);

		$super = cxBase;
		$base = $_POST['base']; 
		$agencia = $_POST['agencia']; 
		$lineas = $_POST['lineas']; 
		$buscar = strtoupper($_POST['buscar']); 
		$tipo = $_POST["tipo"]; 

		$stmt=mssql_init("XL_SP_TRANSITO_FINAL", $connect);  
	
		mssql_bind($stmt, "@base", $base,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@agencia", $agencia,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@tipo", $tipo,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@linea", $lineas,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@busca", $buscar,SQLVARCHAR,FALSE,FALSE,61); 
		

		$resultado = @mssql_execute($stmt) or die("MSSQL error: " . mssql_get_last_message());
		if(mssql_num_rows($resultado)>0):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["codigo"] = htmlentities(stripslashes($row['cdg_prod'])); 
				$json_arr["stock"] = htmlentities(stripslashes($row['stock']));
				array_push($display_json, $json_arr);						
			endwhile; 
		endif; 
		break; 

	case "stock":
		@mssql_select_db(cxBase);

		$super = cxBase;
		$base = $_POST['base']; 
		$agencia = $_POST['agencia']; 
		$lineas = $_POST['lineas']; 
		$buscar = strtoupper($_POST['buscar']); 
		$ceros = $_POST['ceros']; 

		$stmt=mssql_init("XL_SP_STOCK_FINAL", $connect);  
		mssql_bind($stmt, "@super", $super,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@base", $base,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@agencia", $agencia,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@linea", $lineas,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@busca", $buscar,SQLVARCHAR,FALSE,FALSE,61); 
		mssql_bind($stmt, "@con_ceros", $ceros,SQLVARCHAR,FALSE,FALSE,61); 

		$resultado = @mssql_execute($stmt) or die("MSSQL error: " . mssql_get_last_message());
		if(mssql_num_rows($resultado)>0):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["codigo"] = htmlentities(stripslashes($row['cdg_prod'])); 
				$json_arr["producto"] = htmlentities(stripslashes($row['des_prod']));
				$json_arr["almacen"] = htmlentities(stripslashes($row['cdg_alm']));
				$json_arr["stock"] = htmlentities(stripslashes($row['stock']));
				array_push($display_json, $json_arr);						
			endwhile; 
		endif; 
		break; 


	case "empresas":
		@mssql_select_db(cxBase);
		$sql = "SELECT RUC, DESCRIP, ABREV, BASE FROM XL_EMPRESAS WHERE FLG_STOCK_RAPIDO='1' AND EST=1 ORDER BY ORDEN";
		$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
		if (mssql_num_rows($resultado)>0 ):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["empresa"] = htmlentities(stripslashes($row['DESCRIP'])); 
				$json_arr["base"] = htmlentities(stripslashes($row['BASE'])); 
				$json_arr["ruc"] = htmlentities(stripslashes($row['RUC'])); 
				array_push($display_json, $json_arr);			
			endwhile; 	
		endif; 
		break; 


	case "agencias":
		$base = $_POST["base"];
		@mssql_select_db($base);
		$sql = "SELECT a.AG_CCODAGE AS cod, a.AG_CDESCRI AS agencia FROM FT0001AGEN a ORDER BY a.AG_CCODAGE";
		$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
		if (mssql_num_rows($resultado)>0 ):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["agencia"] = htmlentities(stripslashes($row['agencia'])); 
				$json_arr["cod"] = htmlentities(stripslashes($row['cod'])); 
				array_push($display_json, $json_arr);			
			endwhile; 	
		endif; 
		break; 



	case "almacenes_X":
		$ruc = $_POST["ruc"];
		$base = $_POST["base"];
		$agencia = $_POST["agencia"];

		@mssql_select_db(cxBase);
		$sql = "SELECT cod_alm as cod, abr_alm as almacen FROM XL_ALMACEN WHERE " .
			   "id_emp='".$ruc."' AND base='".$base."' AND cod_agen='".$agencia."' " . 
			   "AND FLG_STOCK_RAPIDO ='1' ORDER BY orden";

		$resultado = @mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
		if (mssql_num_rows($resultado)>0 ):
			while (($row = mssql_fetch_array($resultado, MSSQL_BOTH))):
				$json_arr["cod"] = htmlentities(stripslashes($row['cod'])); 
				$json_arr["almacen"] = htmlentities(stripslashes($row['almacen'])); 
				array_push($display_json, $json_arr);			
			endwhile; 	
		endif; 
		break; 
		
	endswitch; 

	# Final para enviar en formato JSON
	$totalRegistros = mssql_num_rows($resultado); # total de registros por consulta
	@mssql_free_statement($resultado);	# liberar CONSULTA
	@mssql_free_result($resultado);	# liberar CONSULTA
	@mssql_close($connect); # liberar CONEXION

	$jsonWrite = json_encode($display_json); 

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
$totaltime = ceil($totaltime * 1000); 

	print '{ "rows":' . $jsonWrite . ', "total": '.$totalRegistros.', "tiempo": "'. $totaltime .' ms" } ';


?>
