<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

public function __construct(){
  parent::__construct();
  $this->load->model('login/m_login');
}

public function empresas(){
  $empresas = $this->m_login->Empresas();
  $dataRet = array();
  if($empresas <> FALSE) foreach ($empresas["items"] as $item) $dataRet[] = $item;
  $datos = array("total" => count($empresas["items"]),"items" => $dataRet);
  echo json_encode($datos);
}

public function bases(){
  $data["ruc"] = $this->input->post('empresa');
  $empresas = $this->m_login->Bases($data);
  $dataRet = array();
  if($empresas <> FALSE) foreach ($empresas["items"] as $item) $dataRet[] = $item;
  $datos = array("total" => count($empresas["items"]),"items" => $dataRet);
  echo json_encode($datos);
}


public function ingresar(){

  $token = md5(uniqid(rand(), true));

  $datos = [
    "usuario" => strtoupper($this->input->post("usuario")),
    "clave" => strtoupper($this->input->post("clave")),
    "base" => $this->input->post("base"),
    "empresa_nom" => $this->input->post("empresa_nom"),
    "token" => $token
  ];

  if($this->m_login->login($datos)){
    echo json_encode([
      "status" => true,
      "md5" => $token
    ]);
  }else{
    echo json_encode([
      "status" => false
    ]);
  }

}

public function logout(){
  $sess = $this->uri->segment(3, 0);
  $this->session->unset_userdata($sess);
  //$this->session->sess_destroy();
  $this->load->view('login/login');
}

}
?>
