<?php
	header ("Content-type: text/html; charset=utf-8");
	$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'); 
	require_once 'PHPWord.php';
	require_once("conexion.php"); 
	$connect = mssql_connect(cxServer,cxUsuario,cxPwd);
	mssql_select_db(cxBase);

	function eliminarDir($carpeta){
		foreach(glob($carpeta . "/*") as $archivos_carpeta):
			if (is_dir($archivos_carpeta)):
				eliminarDir($archivos_carpeta);
			else:
				unlink($archivos_carpeta);
			endif; 
		endforeach; 
		rmdir($carpeta);
	}


 	$lst_guias = array(); 
 	$original = $_POST["guias"]; 
	$guias = split(',', $_POST["guias"]);

	foreach ($guias as $key => $value) {
		$x = split("[|]",$value); 
		array_push($lst_guias, $x[0]);
	}
 	$x_guias = join(',',$lst_guias); 

 	$original = str_replace("|", " (", $original); 
 	$original = str_replace(",", "), ", $original); 
 	$original = $original . ")"; 
 	

	$sql = "SELECT D.C6_CCODIGO codigo, D.C6_CDESCRI producto, S.C6_CSERIE serie, CONVERT(VARCHAR(10),SER.SR_DFECVEN,103) fecha, SUM(S.C6_NCANTID) cantidad " 
		."FROM AL0001MOVD D LEFT JOIN AL0001MOVC C ON D.C6_CALMA=C.C5_CALMA AND C.C5_CTD=D.C6_CTD AND C.C5_CNUMDOC=D.C6_CNUMDOC "
		."LEFT JOIN AL0001ASER S ON S.C6_CLOCALI=C.C5_CLOCALI AND S.C6_CALMA=C.C5_CALMA AND S.C6_CTD=C.C5_CTD AND S.C6_CNUMDOC=C.C5_CNUMDOC "
		."AND D.C6_CCODIGO=S.C6_CCODIGO AND S.C6_CITEM=D.C6_CITEM "
		."LEFT JOIN AL0001SERI SER ON SER.SR_CALMA=C.C5_CALMA AND SER.SR_CCODIGO=S.C6_CCODIGO AND SER.SR_CSERIE=S.C6_CSERIE "
		."WHERE C.C5_CTD='GS' AND C.C5_CCODMOV='22' AND C.C5_CNUMDOC IN ('". str_replace(",", "','", $x_guias) ."') "
		."GROUP BY D.C6_CCODIGO, D.C6_CDESCRI, S.C6_CSERIE, SER.SR_DFECVEN "
		."ORDER BY SER.SR_DFECVEN, s.C6_CSERIE, D.C6_CCODIGO, D.C6_CDESCRI"; 
	
	//echo $sql; 
	//die(); 

	$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());

	$unico = time(); 
	$zip = new ZipArchive();
	$comprimido = "Cartas_" . $unico . ".rar";
	$zip->open($comprimido, ZipArchive::CREATE); 

	mkdir($unico, 0777, true);
	chmod($unico, 0777);
	
	$lst_dua = $lst_fecha = $lst_producto = $lst_cantidad = array(); 

	if (mssql_num_rows($result)>0):
		while($res = mssql_fetch_object($result)):
			array_push($lst_dua, trim($res->serie) ); 
			array_push($lst_fecha, trim($res->fecha) ); 
			array_push($lst_producto, utf8_encode($res->producto) ); 
			array_push($lst_cantidad, (int) $res->cantidad); 
		endwhile;
	endif; 

	$dua_tm = ""; $duas_n = array(); 
	foreach($lst_dua as $p):
		if($dua_tm<>$p) array_push($duas_n, $p); 
		$dua_tm = $p; 
	endforeach; 

	
	$tmp_dua = $tmp_fecha = $tmp_producto = $tmp_cantidad = array(); 
	foreach ($duas_n as $dua):
		foreach($lst_dua as $k => $d):
			if($dua==$d):
				array_push($tmp_dua, $lst_dua[$k] ); 
				array_push($tmp_fecha, $lst_fecha[$k] ); 
				array_push($tmp_producto, $lst_producto[$k] ); 
				array_push($tmp_cantidad, (int) $lst_cantidad[$k] ); 
			endif; 
		endforeach; 
		++$duas; 

		$serie_actual = $dua; 
		$vencimiento = $tmp_fecha[0]; 
		$vencimiento_año = substr($tmp_fecha[0], -4, 4); 

		$PHPWord = new PHPWord();	
		$document = $PHPWord->loadTemplate('../m_carta/carta_membretada.docx');
		$document->setValue('{fecha}', date("d") . " de ". $meses[date("n")-1] . " del " . date("Y"));
		$document->setValue('{almacen}', $empresa);
		$document->setValue('{dua_año}', $vencimiento_año);
		$document->setValue('{dua}', $serie_actual);
		$document->setValue('{dua_fecha}', $vencimiento);
		$document->setValue('{chofer}', $personal);
		
		$document->setValue('{lista}', $original);

		$data = array('dua' => $tmp_dua, 'producto' => $tmp_producto, 'cant' => $tmp_cantidad);	
		$document->cloneRow('TBL1', $data);

		$tmp_file = $unico.'/Carta_'.$serie_actual.'_'.$unico.'.docx';
		$document->save($tmp_file);
        $zip->addFile($tmp_file);

		$tmp_dua = $tmp_fecha = $tmp_producto = $tmp_cantidad = array(); 

	endforeach; 

    $zip->close();
	eliminarDir($unico); 
	mssql_free_result($result);

	print "Archivos comprimidos ".$duas."<br><br>";
	print "Descargar:<br><a class=download href=lib/" . $comprimido . "><img src='img/download.png'><br>" . $comprimido . "</a><br>";
?>