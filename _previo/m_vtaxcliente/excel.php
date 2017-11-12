<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('America/Lima');
	require_once '../lib/PHPExcel.php';
	require_once("../lib/funciones.php"); 

	$conex = conexion();
	$connect = mssql_connect($conex["host"],$conex["user"],$conex["pwd"]);
	mssql_select_db($conex["base"]);

	$archivo = "SoftcomJCH - Ranking de Ventas.xls"; 


	$resumen = $_GET['resumen'];
	$age = $_GET['agencia'];

	$ruc = $_GET['ruc'];
	$vend = $_GET['vendedor'];
	$inicio = $_GET['inicio'];
	$final = $_GET['final'];
	$td = $_GET['tipo_doc'];

	$codigo = $_GET['codigo'];
	$dif = $_GET['solo_diferida'];
	$dif_pend = $_GET['solo_diferida_pend'];
	$pend = $_GET['solo_pendientes'];


	$sql = "EXEC XL_VTACLIENTE_X '$age','$ruc','$vend','$inicio','$final','$td','$codigo','$dif','$dif_pend','$pend';";
	$result = mssql_query($sql) or die("MSSQL error: " . mssql_get_last_message());

	$objPHPExcel = new PHPExcel();
	$head_row = array(
		'font' => array(
			'bold' => true, 
			'size' => 8
		),
		'alignment' => array(
			'wrap'       => true,
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)
	);


	$titulo = array(
		'font' => array(
			'bold' => true, 
			'size' => 10
		),
		'alignment' => array(
			'wrap'       => true,
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)
	);


	$stTabla = array(
	  'borders' => array(
	      'allborders' => array(
	          'style' => PHPExcel_Style_Border::BORDER_THIN
	      )
	  )
	);


	$stNumero = array(
		'font' => array(
			'bold' => true
		),
	  'borders' => array(
	      'allborders' => array(
	          'style' => PHPExcel_Style_Border::BORDER_THIN
	      )
	  )
	);


	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Luis Ventura")
								 ->setLastModifiedBy("JCH Llantas")
								 ->setTitle("Office 2003 XLS Document")
								 ->setSubject("Office 2003 XLS Document")
								 ->setDescription("Hoja de calculo generada desde PHP")
								 ->setKeywords("jch softcom office 2003 openxml php")
								 ->setCategory("Archivo de Exportacion");

	$objPHPExcel->getDefaultStyle()->getFont()
				->setName('Calibri')
				->setSize(10);

	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', "J.CH. COMERCIAL S.A.")
	            ->setCellValue('E1', date("d/m/Y"))
	            //->setCellValue('A2', "RANKING DE VENTAS POR VENDEDOR DEL ".$fecIni." AL ".$fecFin)
				->mergeCells('A2:E2')

	            ->setCellValue('A4', "VENDEDOR")
	            ->setCellValue('B4', "CANTIDAD")
	            ->setCellValue('C4', "VALOR VENTA US$")
	            ->setCellValue('D4', "I.G.V. US$")
	            ->setCellValue('E4', "PRECIO VENTA US$"); 

	$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); 
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($titulo);
	$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($head_row);

	$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
	$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(6);
	$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(20);

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(8);


    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 

	$rowCount = 5;


	if (mssql_num_rows($result)>0):
		while($res = mssql_fetch_object($result)): 

			$vendedor = utf8_encode($res->VENDEDOR); 
			$cantid = number_format($res->CANT,0);
			$neto = number_format($res->PRECIO,2,'.','');
			$igv = number_format($res->F6_NIGV,2,'.','');
			$total = number_format($res->F6_NIMPUS,2,'.','');

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $vendedor);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $cantid);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $neto);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $igv);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $total);

			$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight(15);

			$objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

			$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount.':E'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');

			$rowCount++;

		endwhile; 	
	endif; 
	mssql_close($connect); 

	$objPHPExcel->getActiveSheet()->getStyle('A1:E'.($rowCount-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A4:E'.($rowCount-1))->applyFromArray($stTabla);

	$objPHPExcel->getActiveSheet()->freezePane('A5'); // Freeze row :) easy!!

	$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, "=SUM(B5:B".($rowCount-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, "=SUM(C5:C".($rowCount-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, "=SUM(D5:D".($rowCount-1).")");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, "=SUM(E5:E".($rowCount-1).")");
	$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount.':E'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount.':E'.$rowCount)->applyFromArray($stNumero);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 	

	$objPHPExcel->getActiveSheet()->setTitle('Ranking por Vendedor');	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setSelectedCells('A3');  // Set selected cell
	$objPHPExcel->setActiveSheetIndex(0);	// Set active sheet index

	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$archivo.'"');
	header('Cache-Control: max-age=0');  

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	  
	$objPHPExcel->disconnectWorksheets();
	unset($objPHPExcel);

?>