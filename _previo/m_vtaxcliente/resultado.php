<?php
	ini_set('memory_limit', '512M'); // para script consultas grandes
	set_time_limit(300); // 5 minutes reponse from server SQL querys

	require_once("../lib/conexion.php"); 
	$connect = mssql_connect(cxServer,cxUsuario,cxPwd);
	mssql_select_db(cxBase);

	$agencia = $_POST["agencia"]; 
	$buscar = utf8_decode($_POST["buscar"]); 
	$ruc = utf8_decode($_POST["ruc"]); 

	$RowNumber = 0; 
	$display_json = array(); $json_arr = array();

	switch($_POST["opcion"]):

		case "vtaxcliente": 

			$age = $agencia;
			$ruc = $_POST['ruc']; 
			$vend = $_POST['vendedor']; 
			$inicio = $_POST['inicio']; 
			$final = $_POST['final']; 
			$td = $_POST['tipo_doc']; 
			$estado = $_POST['estado']; 
			$codigo = $_POST['codigo']; 
			
			$dif = $_POST['solo_diferida']; 
			$dif_pend = $_POST['solo_diferida_pend']; 
			$pend = $_POST['solo_pendientes']; 

			$marca = $_POST['marca']; 
		
			$sql = "EXEC XL_VTACLIENTE_X '$age','$ruc','$vend','$inicio','$final','$td','$codigo','$dif','$dif_pend','$pend','$marca';"; 

			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
			
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 

				$json_arr["tipo_factura"] = ($res->F5_CTF=='05') ? 'diferida' : '';
				
				$json_arr["pedido"] = is_null($res->F5_CNROPED) ? '' : $res->F5_CNROPED; 
				$json_arr["pedido_usuario"] = is_null($res->USU_PED) ? '' : $res->USU_PED; 
				$json_arr["fecha_pedido"] = $res->PED_CREACION;

				$json_arr["tdoc"] = $res->TD;
				$json_arr["documento"] = trim(utf8_encode($res->DOCU));
				$json_arr["total_ft"] = number_format($res->TOTAL,2,'.',',');

				$tmp1 = trim($res->FECHA); 
				//$tmp2 = trim($res->CREACION); 
				//$fecha = $tmp1=='' ? $fecha : $tmp1; 
				//$fecha = $tmp2=='' ? $fecha : $tmp2; 	
				$json_arr["fecha"] = $tmp1; 

				$json_arr["forma_venta"] = trim(utf8_encode($res->FORMA));
				$json_arr["orden_compra"] = trim(utf8_encode($res->ORDEN));

				$json_arr["glosa"] = trim(utf8_encode($res->GLOSA));
				$json_arr["vendedor"] = trim(utf8_encode($res->VENDEDOR));

				$json_arr["vencimiento"]=$res->VENCE; 

				$json_arr["doc_estado"] = utf8_encode($res->ESTADO);
				$json_arr["ruc"] = trim(utf8_encode($res->RUC));
				$json_arr["cliente"] = str_replace('¥', 'Ñ', trim(utf8_encode($res->CLIENTE)));
				$json_arr["almacen"] = trim(utf8_encode($res->ALMACEN));
				
				$json_arr["almacen_tipo"]=$res->A1_CTIPO; 

				$json_arr["guia"] = is_null($res->GUIA) ? '' : $res->GUIA;
				$json_arr["guia_almacen"] = is_null($res->GUIA_ALMACEN) ? '' : $res->GUIA_ALMACEN;
				$json_arr["fecha_guia"]=$res->GUIA_FECHA; 
				$json_arr["atendido"] =	number_format($res->ATENDIDO,0,'.',',');

				$json_arr["agencia"] = is_null($res->AGE_NOM) ? '' : trim(utf8_encode($res->AGE_NOM));
				
				$json_arr["item_ft"] = $res->ITEM_FT;
				$json_arr["codigo"] = trim(utf8_encode($res->CODIGO));
				$json_arr["producto"] = trim(utf8_encode($res->PRODUCTO));
				$json_arr["cantidad"] =	number_format($res->CANT,0,'.',',');
				$json_arr["x_atender"] = number_format($res->X_ATENDER,0,'.',',');

				$json_arr["cod_moneda"] = trim(utf8_encode($res->MONEDA));

				$json_arr["precio"] = number_format($res->PRECIO,2,'.',',');
				$json_arr["igv"] = number_format($res->F6_NIGV,2,'.',',');

				if($json_arr["cod_moneda"]=='US'):
					$json_arr["subtotal"] = number_format(($res->F6_NIMPUS-$res->F6_NIGV),2,'.',',');
					$json_arr["total"] = number_format($res->F6_NIMPUS,2,'.',',');
				else:
					$json_arr["subtotal"] = number_format(($res->F6_NIMPMN-$res->F6_NIGV),2,'.',',');
					$json_arr["total"] = number_format($res->F6_NIMPMN,2,'.',',');
				endif; 

				$json_arr["tipo_cambio"] = number_format($res->TC,2,'.',',');
				
				/*
				$json_arr["precio_us"] = number_format($res->PRECIO_US,2,'.',',');
				$json_arr["subtotal_us"] = number_format($res->SUBTOTAL_US,2,'.',',');
				$json_arr["igv_us"] = number_format($res->IGV_US,2,'.',',');
				$json_arr["total_us"] = number_format($res->TOTAL_US,2,'.',',');
				*/


				array_push($display_json, $json_arr);
				++$RowNumber;
			endwhile;		
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 


		case "consulta":
			$sql = "SELECT CL_CCODCLI codigo, rtrim(ltrim(CL_CNOMCLI)) nombre FROM FT0001CLIE WHERE "
				."CL_CCODCLI LIKE '%".strtoupper($buscar)."%' OR upper(CL_CNOMCLI) LIKE '%".strtoupper($buscar)."%' "
			 	."ORDER BY rtrim(ltrim(CL_CNOMCLI)); ";

			$seccion = $_POST["seccion"]; 
			if($seccion){

				switch ($seccion) {
					case 'vendedor':
						$sql = "SELECT VE_CCODIGO as codigo, rtrim(ltrim(VE_CNOMBRE)) as nombre FROM FT0001VEND "
						."WHERE upper(VE_CNOMBRE) LIKE '%".strtoupper($buscar)."%' or upper(VE_CCODIGO) LIKE '%".strtoupper($buscar)."%' "
						."ORDER BY rtrim(ltrim(VE_CNOMBRE))"; 
						break;					

					case 'producto':
						$sql = "SELECT AR_CCODIGO AS codigo, ltrim(rtrim(AR_CDESCRI)) AS nombre FROM AL0001ARTI "
						."WHERE upper(AR_CDESCRI) LIKE '%".strtoupper($buscar)."%' or upper(AR_CCODIGO) LIKE '%".strtoupper($buscar)."%' "
						."ORDER BY rtrim(ltrim(AR_CDESCRI))"; 
						break;
				}


			}

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



		case "marcas":
			$sql = "SELECT TG_CCLAVE codigo, TG_CDESCRI marca FROM AL0001TABL WHERE TG_CCOD='V7' ORDER BY TG_CDESCRI";

			$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());
			if (mssql_num_rows($result)>0):
				while($res = mssql_fetch_object($result)): 
					$json_arr["codigo"] = trim($res->codigo);
					$json_arr["marca"] = utf8_encode(trim($res->marca));
					array_push($display_json, $json_arr);
					++$RowNumber;
				endwhile;
			endif; 
			mssql_free_result($result); // libera el recurso de consulta sql
			break; 



	endswitch; 


	$jsonWrite = json_encode($display_json); 
	print '{ "estado":"ok", "rows":' . $jsonWrite . ', "total": '.$RowNumber.', "sql": "'.$sql.'" }';

?>
