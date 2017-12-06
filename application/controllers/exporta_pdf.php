<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exporta_pdf extends CI_Controller{

  public function generar() {

    $this->load->model('ventas/m_documento');

    $this->load->library('encrypt');
    $key = $this->config->item('encryption_key');

    $base = $this->uri->segment(3);
    $td = $this->uri->segment(4);
    $serie  = $this->uri->segment(5);

    $numero = $this->uri->segment(6);
    $numero = strtr($numero, array('.' => '+', '-' => '=', '~' => '/'));
    $numero = $this->encrypt->decode($numero, $key);

    if($numero=="" || strlen($numero)>7) die();

    $params = [
      "base" => $base,
      "td" => $td,
      "serie" => $serie,
      "numero" => $numero
    ];

    $datos = $this->m_documento->documento($params);
    if($datos<>false){
      $params["data"] = $datos;
      $html = $this->load->view('_pdf/formato_factura', $params, true);
      $this->load->library('pdfgenerator');
      $filename = 'Documento_' . $td .'_' . $serie . $numero ;
      $this->pdfgenerator->generate($html, $filename, true, 'A4', 'portrait');
    }


  }

}
