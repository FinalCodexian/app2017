<?php
	require_once("../lib/conexion.php"); 
	$connect = mssql_connect(cxServer,cxUsuario,cxPwd);
	mssql_select_db(cxBase);

	$agencia = $_SESSION["agencia"]; 
	$buscar = utf8_decode($_POST["buscar"]); 
	$ruc = utf8_decode($_POST["ruc"]); 

	$RowNumber = 0; 
	$display_json = array(); $json_arr = array();

	switch($_POST["opcion"]):

		case "almacenes":
			$sql = "SELECT A1_CALMA cod_almacen, rtrim(ltrim(A1_CDESCRI)) almacen, ".
			"A1_CCODCLI ruc, A1_CDESCR2 razon FROM al0001alma WHERE A1_CTIPO='N' AND A1_CCODCLI<>'' ORDER BY A1_CDESCRI"; 
			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());

			if (mssql_num_rows($result)>0):
			while($res = mssql_fetch_object($result)): 
				$json_arr["cod_almacen"] = trim($res->cod_almacen);
				$json_arr["almacen"] = utf8_encode($res->almacen);
				$json_arr["ruc"] = trim($res->ruc);
				$json_arr["razon"] = utf8_encode($res->razon);
				array_push($display_json, $json_arr);
				++$RowNumber;
			endwhile;		
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 

		case "personal":
			$sql = "SELECT DNI, NOMBRE FROM XL_CHOFERES WHERE ESTADO='1' ORDER BY NOMBRE"; 
			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());

			if (mssql_num_rows($result)>0):
			while($res = mssql_fetch_object($result)): 
				$json_arr["dni"] = trim($res->DNI);
				$json_arr["nombre"] = utf8_encode($res->NOMBRE);
				array_push($display_json, $json_arr);
				++$RowNumber;
			endwhile;		
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 


		case "guias":
			$sql = "SELECT mc.C5_CNUMDOC guia, convert(VARCHAR(10),mc.C5_DFECDOC,103) fecha, sum(md.C6_NCANTID) total FROM AL0001MOVC mc " .
				"LEFT JOIN AL0001MOVD md ON (md.C6_CALMA=mc.C5_CALMA AND md.C6_CTD=mc.C5_CTD AND mc.C5_CNUMDOC=md.C6_CNUMDOC) " .
				"WHERE 1=1 " .
				"AND CONVERT(DATE,mc.C5_DFECDOC,103)>=CONVERT(DATE,'".$inicio."',103) " .
				"AND CONVERT(DATE,mc.C5_DFECDOC,103)<=CONVERT(DATE,'".$final."',103) " .
				"AND C5_CSITGUI<>'A' AND C5_CSITGUI<>'2' AND C5_CCODMOV='22' AND mc.C5_CTD='GS' AND C5_CALMA='".$almacen."' " .
				"GROUP BY mc.C5_CNUMDOC, mc.C5_DFECDOC " .
				"ORDER BY  mc.C5_CNUMDOC, mc.C5_DFECDOC "; 			

			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());

			if (mssql_num_rows($result)>0):
			while($res = mssql_fetch_object($result)): 
				$json_arr["guia"] = trim($res->guia);
				$json_arr["fecha"] = $res->fecha;
				$json_arr["total"] = $res->total;
				array_push($display_json, $json_arr);
				++$RowNumber;
			endwhile;		
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 




		case "vtaxcliente": 
		
			$agencia = $_POST['agencia']; 
			$ruc = $_POST['ruc']; 
			$vendedor = $_POST['vendedor']; 
			$inicio = $_POST['inicio']; 
			$final = $_POST['final']; 
			$tipo_doc = $_POST['tipo_doc']; 
			$estado = $_POST['estado']; 
			$codigo = $_POST['codigo']; 
			$solo_diferida = $_POST['solo_diferida']; 
		
			//$sql = "EXEC XL_VTACLIENTE_2016  '".$agencia."', '".$ruc."', '".$vendedor."', '".$inicio."', '".$final."', '".$tipo_doc."', '".$estado."', '".$codigo."', ''; "; 
			
			$sql = "EXEC XL_VTACLIENTE_2016 '9000', '', 'JFQM', '28/06/2016', '29/06/2016', '', '', '', ''; "; 
			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
			
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 

				$json_arr["tipo_factura"] = $res->TIPO_FACTURA;
				
				array_push($display_json, $json_arr);
				++$RowNumber;
			endwhile;		
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 

	endswitch; 


	$jsonWrite = json_encode($display_json); 
	print '{ "estado":"ok", "rows":' . $jsonWrite . ', "total": '.$RowNumber.' }';

?>