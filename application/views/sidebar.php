<?php
$sess = $this->uri->segment(3, 0);
?>

<div class="ui fluid inverted blue vertical menu" id="menuSidebar">

  <div class="item">
    <h5 class="ui header">
      <i class="user icon"></i>
      <div class="content">
        <?=$this->session->userdata($sess)["usuarioNom"];?>
        <div class="sub header">Usuario del Sistema</div>
      </div>
    </h5>
  </div>

  <?php
  // armar menu desde base de datos
  $dbLuis = $this->load->database('XLUIS', TRUE);
  $dbLuis->select('padre.id padre_id, padre.DESCRIPCION padre, padre.ICONCLASS padre_class');
  $dbLuis->select('hijo.id hijo_id, hijo.DESCRIPCION hijo, hijo.ICONCLASS hijo_class, hijo.enlace enlace');
  $dbLuis->from('MENU padre');
  $dbLuis->join('MENU hijo', 'padre.ID=hijo.PADRE_ID', 'left');
  $dbLuis->where('padre.PADRE_ID', '0');
  $q = $dbLuis->get();

  $menuActual = "";
  if($q->num_rows()>0):
    foreach ($q->result() as $row):
      if($menuActual <> $row->padre):
        if($menuActual <> '') echo '</div></div>';
        echo '<div class="item">';
        echo '<div class="header">' . $row->padre . '</div>';
        echo '<div class="menu">';
      endif;

      $active = "";
      if($this->uri->segment(1, 0) . "/" . $this->uri->segment(2, 0) == trim($row->enlace)) $active = "active";

      if(trim($row->enlace) <> ''):
        if($active=="active"):
          echo '<a class="item '.$active.'" href="' . site_url(trim($row->enlace)).'/'.$sess . '">' . $row->hijo . '<i class="history icon"></i></a>';
        else:
          echo '<a class="item" href="' . site_url(trim($row->enlace)).'/'.$sess . '">' . $row->hijo . '<i class="history icon"></i></a>';
        endif;
      else:
        echo '<a class="item">' . $row->hijo . '<i class="history icon"></i></a>';
      endif;

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
