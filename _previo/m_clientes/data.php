<?php
	require_once("../lib/conexion.php"); 

	$connect = mssql_connect(cxServer,cxUsuario,cxPwd);
	mssql_select_db(cxBase);

	$buscar = utf8_decode($_POST["buscar"]); 
	$ruc = $_POST["ruc"]; 

	$RowNumber = 0; $display_json = array(); $json_arr = array();

	switch($_POST["opcion"]):

		case "consulta":  // Lista de resultado de busqueda por RUC o razon social
			$sql = "SELECT CL_CCODCLI codigo, CL_CNOMCLI nombre FROM FT0001CLIE WHERE "
				."CL_CCODCLI LIKE '%".$buscar."%' OR CL_CNOMCLI LIKE '%".$buscar."%' "
			 	."ORDER BY CL_CNOMCLI; ";
			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 
					$json_arr["codigo"] = trim($res->codigo);
					$json_arr["nombre"] = utf8_encode(trim($res->nombre));
					array_push($display_json, $json_arr);
					++$RowNumber;
				endwhile;
			endif;
			mssql_free_result($result); // libera el recurso de consulta sql
			break;



		case "detalle": // detalle para mostrar datos del cliente
			$sql = "SELECT CL_CHOST as linea_credito, CL_CCODCLI AS codigo, CL_CNOMCLI AS nombre, CL_CNUMRUC AS ruc, CL_CDIRCLI AS direccion, "
				."CL_CTELEFO AS telefono, CL_CNUMFAX AS fax, TCLI.TG_CDESCRI tipo_cliente, VEND.VE_CNOMBRE AS vendedor, "
				."FORMA.FV_CDESCRI forma_vta, PRECIO.TG_CDESCRI tipo_precio, CL_CFPERCP afecto, "
				."CASE WHEN CL_CUSUCRE = '' THEN '-'  ELSE CREA.TU_NOMUSU END AS usuario_creacion, "
				."CASE WHEN CL_DFECCRE IS NULL THEN '-' ELSE CONVERT(VARCHAR(10),CL_DFECCRE,103) END AS fec_creacion, "				
				."CASE WHEN CL_CUSUMOD = '' THEN '-'  ELSE MODI.TU_NOMUSU END AS usuario_modifica, "
				."CASE WHEN CL_DFECMOD IS NULL THEN '-' ELSE CONVERT(VARCHAR(10),CL_DFECMOD,103) END AS fec_modifica, "
				."CL_TCOMENT AS comentario, CL_CEMAIL email "
				."FROM FT0001CLIE "
				."LEFT OUTER JOIN AL0001TABL TCLI ON (TCLI.TG_CCOD='67' AND TCLI.TG_CCLAVE=CL_CTIPCLI) "
				."LEFT OUTER JOIN FT0001VEND VEND ON (VEND.VE_CCODIGO=CL_CVENDE) "
				."LEFT OUTER JOIN FT0001FORV FORMA ON (FORMA.FV_CCODIGO=CL_CTIPVTA) "
				."LEFT OUTER JOIN AL0001TABL PRECIO ON (PRECIO.TG_CCOD='23' AND PRECIO.TG_CCLAVE=CL_CTIPPRE) "
				."LEFT OUTER JOIN UT0030 CREA ON (CREA.TU_ALIAS=CL_CUSUCRE ) "
				."LEFT OUTER JOIN UT0030 MODI ON (MODI.TU_ALIAS=CL_CUSUMOD ) "
				."WHERE CL_CCODCLI= '$ruc';";
				
			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());			
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 
					$json_arr["linea_credito"] = number_format(trim($res->linea_credito),2);
					$json_arr["codigo"] = trim($res->codigo);

					$json_arr["nombre"] = utf8_encode(trim($res->nombre));
					$json_arr["ruc"] = $res->ruc;
					$json_arr["direccion"] = utf8_encode(trim($res->direccion));
					$json_arr["tipo_cliente"] = $res->tipo_cliente;
					$json_arr["vendedor"] = utf8_encode(trim($res->vendedor));
					$json_arr["forma_vta"] = trim($res->forma_vta);
					$json_arr["tipo_precio"] = $res->tipo_precio;
					$json_arr["usuario_creacion"] = utf8_encode($res->usuario_creacion);
					$json_arr["fec_creacion"] = $res->fec_creacion;
					$json_arr["usuario_modifica"] = utf8_encode($res->usuario_modifica);
					$json_arr["fec_modifica"] = $res->fec_modifica;
					$json_arr["telefono"] = $res->telefono;
					$json_arr["email"] = $res->email;
					
	
					// addins
					$json_arr["comentario"] = nl2br(utf8_encode($res->comentario));
	
					array_push($display_json, $json_arr);
					++$RowNumber; 
				endwhile;
			endif;
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 


		case "transportes":  // transportistas asignados
			$sql = "SELECT tr.TR_CCODIGO AS codigo, tr.TR_CRUC AS ruc, tr.TR_CNOMBRE AS transporte, tr.TR_CDIRECC AS direccion "
				."FROM FT0001CLIA a LEFT JOIN AL0001TRAN tr ON tr.TR_CCODIGO=a.AT_CAGETRA "
				."WHERE NOT tr.TR_CCODIGO IS NULL AND AT_CCODCLI='$ruc' "
				."ORDER BY tr.TR_CNOMBRE, tr.TR_CDIRECC"; 

			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());			
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 
					$json_arr["codigo"] = trim(utf8_encode($res->codigo));
					$json_arr["ruc"] = trim($res->ruc);
					$json_arr["transporte"] = trim(utf8_encode($res->transporte));
					$json_arr["direccion"] = trim(utf8_encode($res->direccion));
					
					array_push($display_json, $json_arr);
					++$RowNumber; 
				endwhile;
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 


		case "direcciones":
			$sql = "SELECT DE_CCODUBI AS codigo, DE_CDIRECC as direccion FROM FT0001CLID WHERE DE_CCODCLI='$ruc' ORDER BY DE_CCODUBI;"; 

			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());			
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 
				$json_arr["codigo"] = trim($res->codigo);
				$json_arr["direccion"] = trim(utf8_encode($res->direccion));
				array_push($display_json, $json_arr);
				++$RowNumber;
				endwhile;			
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 


		case "contactos": 

		$sql = "SELECT CC_CFLGAVA aval, CC_CNOMBRE + ' ' + CC_CAPELLI nombres, CC_CDIRECC direccion, CC_CCARGO cargo, "
			."CASE CC_CDOCIDE WHEN  '0' THEN 'SIN DOCUMENTO ' + CC_CNUMIDE		WHEN '1' THEN 'D.N.I. ' + CC_CNUMIDE "
			."WHEN '2' THEN 'CARNET FF.PP. ' + CC_CNUMIDE			WHEN '3' THEN 'CARNET FF.AA. ' + CC_CNUMIDE "
			."WHEN '4' THEN 'CARNET EXTRANJERIA ' + CC_CNUMIDE	WHEN '6' THEN 'R.U.C. ' + CC_CNUMIDE "
			."WHEN '7' THEN 'PASAPORTE ' + CC_CNUMIDE END docu, CC_CEMAIL correo, CC_CTELEFO telefono "
			."FROM FT0001CLIC WHERE CC_CCODCLI='$ruc'; "; 
			
			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());			
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 
				
					$json_arr["aval"] = $res->aval;
					$json_arr["nombres"] = $res->nombres;
					$json_arr["direccion"] = trim($res->direccion);
					$json_arr["cargo"] = $res->cargo;
					$json_arr["docu"] = trim($res->docu);
					$json_arr["correo"] = $res->correo;
					$json_arr["telefono"] = $res->telefono;
	
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
