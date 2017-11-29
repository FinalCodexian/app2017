<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_excel extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->library('excel');

    $this->bordes = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('rgb' => '666666')
        )
      ),
      'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      )
    );

    $this->tabla_header = array(
      'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '333333'),
        'size'  => 8,
        'name'  => 'Calibri'
      ),
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
    );

    $this->celda = array(
      'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 9,
        'name'  => 'Calibri'
      ),
      'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ),
    );

    $this->align_center = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    );

    $this->titulo = array(
      'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 14,
        'name'  => 'Calibri'
      ),
    );

  }



  public function venta_x_clientes(){
    ob_start();
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getActiveSheet()->SetCellValue("B2", "Reporte: Ventas por cliente");
    $objPHPExcel->getActiveSheet()->getStyle("B2")->applyFromArray($this->titulo);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(6);
    $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(6);

    $objPHPExcel->getActiveSheet()->setShowGridlines(false);



    $cabecera = array(
      ["titulo" => "VENDEDOR","ancho" => 30],
      ["titulo" => "DIF","ancho" => 5, "align" => "C"],
      ["titulo" => "TD","ancho" => 7, "align" => "C"],
      ["titulo" => "DOC","ancho" => 12, "align" => "C"],
      ["titulo" => "EMISION","ancho" => 10, "align" => "C"],
      ["titulo" => "RUC","ancho" => 13, "align" => "C"],
      ["titulo" => "RAZON SOCIAL","ancho" => 40],
      ["titulo" => "TIPO CLIENTE","ancho" => 20],
      ["titulo" => "CONDICION","ancho" => 20],
      ["titulo" => "CANT","ancho" => 7, "align" => "C"],
      ["titulo" => "CODIGO","ancho" => 10, "align" => "C"],
      ["titulo" => "PRODUCTO","ancho" => 40],
      ["titulo" => "X_SALDAR","ancho" => 7, "align" => "C"],

      ["titulo" => "MONEDA","ancho" => 7, "align" => "C"],
      ["titulo" => "PRECIO","ancho" => 10, "tipo" => "precio"],
      ["titulo" => "TOTAL","ancho" => 10, "tipo" => "precio"],
      ["titulo" => "TC","ancho" => 7, "tipo" => "precio"],
      ["titulo" => "PRECIO_US","ancho" => 10, "tipo" => "precio"],
      ["titulo" => "TOTAL_US","ancho" => 10, "tipo" => "precio"],

    );

    $linea = 4;


    foreach ($cabecera as $key => $value) {
      $letraCol = PHPExcel_Cell::stringFromColumnIndex($key+1);
      $objPHPExcel->getActiveSheet()->getRowDimension($linea)->setRowHeight(20);
      $objPHPExcel->getActiveSheet()->SetCellValue($letraCol.$linea, $value["titulo"]);
      $objPHPExcel->getActiveSheet()->getStyle($letraCol.$linea)
      ->applyFromArray($this->bordes)
      ->applyFromArray($this->tabla_header);
    }
    ++$linea;

    $objPHPExcel->getActiveSheet()->freezePane('A'. $linea);

    $contenido = json_decode($_POST["contenido"]);

    foreach ($contenido->data as $row){
      foreach ($row->detalle as $det){
        // $FF = "precio_us";
        foreach ($cabecera as $col => $cab){
          switch ($col){
            case 0: $valor = $row->cabecera->vendedor; break;
            case 1: $valor = ($row->cabecera->diferida=='S' ? 'S' : ' '); break;
            case 2: $valor = $row->cabecera->td; break;
            case 3: $valor = $row->cabecera->documento; break;
            case 4: $valor = $row->cabecera->fecha; break;
            case 5: $valor = $row->cabecera->ruc; break;
            case 6: $valor = $row->cabecera->cliente; break;
            case 7: $valor = $row->cabecera->tipo_cliente; break;
            case 8: $valor = $row->cabecera->forma_venta; break;
            case 9: $valor = $det->cant; break;
            case 10: $valor = $det->codigo; break;
            case 11: $valor = $det->descrip; break;
            case 12: $valor = $det->saldar; break;
            case 13: $valor = $row->cabecera->moneda; break;

            case 14: $valor = ($row->cabecera->moneda=='MN' ? $det->precio_mn : $det->precio_us); break;
            case 15: $valor = ($row->cabecera->moneda=='MN' ? $det->subtot_mn : $det->subtot_us); break;

            case 16: $valor = $row->cabecera->tc; break;

            case 17: $valor = $det->precio_us; break;
            case 18: $valor = $det->subtot_us; break;

            default: $valor = " "; break;
          }

          $let = PHPExcel_Cell::stringFromColumnIndex($col + 1);
          $celda  = $let . $linea;

          $objPHPExcel->getActiveSheet()->SetCellValue($celda, $valor);
          $objPHPExcel->getActiveSheet()->getStyle($celda)->applyFromArray($this->bordes)->applyFromArray($this->celda);
          if(isset($cabecera[$col]["ancho"])) $objPHPExcel->getActiveSheet()->getColumnDimension($let)->setWidth($cabecera[$col]["ancho"]);
          if(isset($cabecera[$col]["align"]) && $cabecera[$col]["align"]=="C") $objPHPExcel->getActiveSheet()->getStyle($celda)->applyFromArray($this->align_center);

          if(isset($cabecera[$col]["tipo"]) && $cabecera[$col]["tipo"]=="precio"){
            $objPHPExcel->getActiveSheet()->getStyle($celda)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
          }

        }

        ++$linea;
      }
    }


    $objPHPExcel->getActiveSheet()->setSelectedCell('A1');



    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    ob_end_clean();
    $objWriter->save('php://output');
  }




  public function otro(){
    //echo $_POST["contenido"];
    $contenido = json_decode($_POST["contenido"]);
    // var_dump($contenido);
    // die();

    // $this->load->helper('download');
    $spreadsheet = new PHPExcel();
    $spreadsheet->setActiveSheetIndex(0);



    $linea = 2;

    foreach ($cabecera as $key => $value) {
      $letraCol = PHPExcel_Cell::stringFromColumnIndex($key+1);
      $spreadsheet->getActiveSheet()->SetCellValue($letraCol.$linea, $value["titulo"]);
      // $spreadsheet->getActiveSheet()->getStyle($letraCol.$linea)
      // ->applyFromArray($this->bordes)
      // ->applyFromArray($this->tabla_header);
    }

    // $contenido = json_decode($_POST["contenido"]);
    // foreach ($contenido->data as $key => $value){
    //   $spreadsheet->getActiveSheet()->SetCellValue('B' . $linea, $value->cabecera->documento);
    //   $spreadsheet->getActiveSheet()->getStyle('B' . $linea)->applyFromArray($this->bordes);
    //   ++$linea;
    // }

    // header('Content-Type: application/vnd.ms-excel');
    // header('Content-Disposition: attachment;filename="your_name.xlsx"');
    // header('Cache-Control: max-age=0');

    $objWriter = new PHPExcel_Writer_Excel2007($spreadsheet);
    $objWriter->save('php://output');
    //    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
    // $objWriter->save('php://output');
    die();





    ob_start();
    /* start: contenido */

    $linea = 5;
    $linInicio = $linea;
    $colFinal = 0;
    // $columnas = $_POST["columnas"];

    $titulo ="RECORD DE VENTAS - MES DE OCTUBRE 2017";
    $subtit = "OFICINA AREQUIPA";

    $bordes = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('rgb' => '666666')
        )
      )
    );

    $tabla_header = array(
      'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '333333'),
        'size'  => 8,
        'name'  => 'Calibri'
      ),
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    );

    $tabla_detalle = array(
      'font'  => array(
        'bold'  => false,
        'size'  => 9,
        'name'  => 'Calibri'
      )
    );

    $css_titulo = array(
      'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 14,
        'name'  => 'Calibri'
      ),
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    );

    $css_subtit = array(
      'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 12,
        'name'  => 'Calibri'
      ),
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    );

    $domingo = array(
      'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '666666'),
        'size'  => 9,
        'name'  => 'Calibri'
      ),
      'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'e4e4e4')
      )
    );

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(2);

    // foreach ($columnas as $key => $value){
    //   $letraCol = PHPExcel_Cell::stringFromColumnIndex($key+1);
    //
    //   if(isset($value["ancho"])):
    //     $spreadsheet->getActiveSheet()->getColumnDimension($letraCol)->setWidth($value["ancho"]);
    //   else:
    //     $spreadsheet->getActiveSheet()->getColumnDimension($letraCol)->setAutoSize(true);
    //   endif;
    //
    //   $spreadsheet->getActiveSheet()->SetCellValue($letraCol.$linea, $value["title"]);
    //   $spreadsheet->getActiveSheet()->getStyle($letraCol.$linea)
    //   ->applyFromArray($bordes)
    //   ->applyFromArray($tabla_header);
    //   $colFinal = $key;
    // };

    $spreadsheet->getActiveSheet()->getRowDimension($linea)->setRowHeight(23);
    $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(4);
    $spreadsheet->getActiveSheet()->getRowDimension(4)->setRowHeight(4);

    ++$linea;

    $spreadsheet->getActiveSheet()->SetCellValue("B2", $titulo);
    $spreadsheet->getActiveSheet()->getStyle("B2")->applyFromArray($css_titulo);

    $spreadsheet->getActiveSheet()->SetCellValue("B3", $subtit);
    $spreadsheet->getActiveSheet()->getStyle("B3")->applyFromArray($css_subtit);

    $spreadsheet->getActiveSheet()->mergeCells('B2:'.PHPExcel_Cell::stringFromColumnIndex($colFinal+1).'2');
    $spreadsheet->getActiveSheet()->mergeCells('B3:'.PHPExcel_Cell::stringFromColumnIndex($colFinal+1).'3');

    $spreadsheet->getActiveSheet()->setSelectedCell('A1');
    $spreadsheet->getActiveSheet()->freezePane('A'.$linea);
    $spreadsheet->getActiveSheet()->setShowGridlines(false);

    // $contenido = $_POST["contenido"];
    // foreach ($contenido as $key => $value):
    //   foreach ($columnas as $ck => $cv):
    //     $letraCol = PHPExcel_Cell::stringFromColumnIndex($ck+1);
    //
    //     $spreadsheet->getActiveSheet()->SetCellValue($letraCol.$linea, $value[$ck]);
    //     $spreadsheet->getActiveSheet()->getRowDimension($linea)->setRowHeight(15);
    //
    //
    //     if(isset($cv["className"]) && $cv["className"]=="htCenter"):
    //       $spreadsheet->getActiveSheet()->getStyle($letraCol.$linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     endif;
    //
    //     if(isset($cv["type"]) && $cv["type"]=="numeric"):
    //       $spreadsheet->getActiveSheet()->getStyle($letraCol.$linea)->getNumberFormat()->setFormatCode('#,##0.00');
    //     endif;
    //
    //     if($cv["title"]=="FECHA"):
    //       list($day,$month,$year) = explode('/', $value[$ck]);
    //       $fecha = strtotime($year.'/'.$month.'/'.$day);
    //       $dia = date("l", $fecha);
    //       $dia = strtolower($dia);
    //
    //       $spreadsheet->getActiveSheet()->SetCellValue($letraCol.$linea, $value[$ck]);
    //
    //       if($dia=="sunday"):
    //         $letraFinal = PHPExcel_Cell::stringFromColumnIndex($colFinal+1);
    //         $spreadsheet->getActiveSheet()->getStyle($letraCol.$linea.':'.$letraFinal.$linea)->applyFromArray($domingo);
    //       endif;
    //     endif;
    //
    //
    //
    //   endforeach;
    //   ++$linea;
    // endforeach;


    $spreadsheet->getActiveSheet()->getStyle('B'.($linInicio+1).':'.PHPExcel_Cell::stringFromColumnIndex($colFinal+1).($linea-1))->applyFromArray($bordes);
    $spreadsheet->getActiveSheet()->getStyle('B'.($linInicio+1).':'.PHPExcel_Cell::stringFromColumnIndex($colFinal+1).($linea-1))->applyFromArray($tabla_detalle);
    $spreadsheet->getActiveSheet()->getStyle('A1:Z150')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $spreadsheet->getActiveSheet()->setSelectedCell('A1');
    /* end: contenido */

    $objWriter = new PHPExcel_Writer_Excel2007($spreadsheet);
    $objWriter->save('php://output');

    $xlsData = ob_get_contents();
    ob_end_clean();

    $response =  array(
      'status' => 'ok',
      'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
    );
    die(json_encode($response));

  }

}
