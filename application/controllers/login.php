<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('login/m_login');
  }

  public function ingresar(){

    $datos = [
      "usuario" => strtoupper($this->input->post("usuario")),
      "clave" => strtoupper($this->input->post("clave")),
      "base" => $this->input->post("empresa"),
      "empresa_nom" => $this->input->post("empresa_nom")
    ];

    if($this->m_login->login($datos)){
      $this->session->set_userdata($datos);
    }else{
      echo "error";
    }

  }

  public function logout(){
    $this->session->sess_destroy();
    $this->load->view('login/login');
  }

}
?>
