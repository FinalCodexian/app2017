<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( __DIR__  . "/dompdf/autoload.inc.php");
use Dompdf\Dompdf;

class Pdfgenerator {

  public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait", $footer = FALSE)
  {
    $dompdf = new DOMPDF();
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();
    if($footer==TRUE):

      $canvas = $dompdf->get_canvas();
      $h = $canvas->get_height() - 20;
      $w = $canvas->get_width() - 100;

      // $dompdf->get_canvas()->get_cpdf()->setEncryption('trees','frogs',array('copy'));

      $font = $dompdf->getFontMetrics()->get_font("helvetica", "normal");
      $dompdf->getCanvas()->page_text($w, 15, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0,0,0));
    endif;

    if ($stream) {
      $dompdf->stream($filename.".pdf", array("Attachment" => 0));
    } else {
      return $dompdf->output();
    }
  }
}
