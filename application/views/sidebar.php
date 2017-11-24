<?php
$sess = $this->uri->segment(3, 0);
?>

<div class="ui fluid inverted blue vertical menu" id="menuSidebar">

  <div class="item">
    <h5 class="ui header">
      <i class="street view icon"></i>
      <div class="content">
        <?=$this->session->userdata($sess)["usuarioNom"];?>
        <div class="sub header">
          Usuario del Sistema
        </div>
      </div>
    </h5>

    <div class="two ui mini labels">
      <a class="ui label" href="<?=site_url("/login/logout/".$sess);?>">
        <i class="power icon"></i>
        Cerrar sesi&oacute;n
      </a>
      <a class="ui label">
        <i class="setting icon"></i>
        Configuraci&oacute;n
      </a>
    </div>

  </div>

  <?php
  // armar menu desde base de datos
  $dbLuis = $this->load->database('default', TRUE);
  $dbLuis->select('padre.id padre_id, padre.DESCRIPCION padre, padre.ICONCLASS padre_class');
  $dbLuis->select('hijo.id hijo_id, hijo.DESCRIPCION hijo, hijo.ICONCLASS hijo_class, hijo.enlace enlace');
  $dbLuis->select('opc.lectura, opc.escritura');
  $dbLuis->from('MENU padre');
  $dbLuis->join('MENU hijo', 'padre.ID=hijo.PADRE_ID', 'left');

  $user = $this->session->userdata($sess)["usuarioId"];
  $base = $this->session->userdata($sess)["base"];
  $dbLuis->join('MENU_USUARIO opc', 'opc.MENU=hijo.id AND opc.USUARIO=\''.$user.'\' AND opc.BASE=\''.$base.'\' ', 'left');

  $dbLuis->where('padre.PADRE_ID', '0');
  $dbLuis->order_by("padre.orden", "asc");
  $dbLuis->order_by("hijo.orden", "asc");

  $q = $dbLuis->get();
  // echo $dbLuis->last_query();
  $menuActual = "";
  $active = "";

  if($q->num_rows()>0):
    foreach ($q->result() as $row):
      if($menuActual <> $row->padre):
        if($menuActual <> '') echo '</div></div>';
        echo '<div class="item">';
        echo '<div class="header">' . $row->padre . '</div>';
        echo '<div class="menu">';
      endif;

      if($this->uri->segment(1, 0) . "/" . $this->uri->segment(2, 0) == trim($row->enlace)) $active = "active";

      if(trim($row->enlace) <> '' &&  !is_null($row->lectura) && $row->lectura!=='N'):

        echo '<a class="item '.$active.'" href="' . site_url(trim($row->enlace)).'/'.$sess . '">' . $row->hijo . '<i class="' . $row->hijo_class . ' icon"></i></a>';
      else:
        echo '<a class="item" href="' . site_url("forbidden/msg").'/'.$sess . '">' . $row->hijo . '<i class="' . $row->hijo_class . ' icon"></i></a>';
      endif;

      $active  = "";

      $menuActual = $row->padre;
    endforeach;
    echo '</div></div>';
  endif;

  ?>

</div>


<style>
#menuSidebar .item>div.header { color: white !important; font-weight: normal;}
#menuSidebar a.active { background-color: rgba(0, 0, 0, .3) !important; color: white !important; font-weight: normal;}
</style>
