<?php
$data = array("titulo"=>"Reporte de Ventas");
$sess = $this->uri->segment(3, 0);
$this->load->view("header", $data);
$this->load->view("sidebar");
$this->load->view("menu_top");


// $this->load->view("_pdf/formato_ReporteVta");

?>


<div class="ui form">
  <div class="ui fields">
    <div class="field">
      <div class="ui icon input">
        <input id="fecha" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
      </div>
    </div>
    <div class="field">
      <select class="ui dropdown" id="agencia"></select>
    </div>

    <div class="field">
      <button type="button" name="button" class="ui tiny blue button" id="exporta">Reporte</button>
    </div>
  </div>
</div>

<div id="rrr"></div>

<form id="TheForm" method="post" action="<?=base_url('ventas/reporteVta');?>" target="TheWindow">
<input type="hidden" name="agencia" value="something" />
<input type="hidden" name="fecha" value="something" />
<input type="hidden" name="base" value="something" />
</form>


<script type="text/javascript">
$(function(){

  function openWindowWithPost() {
    var f = document.getElementById('TheForm');
    f.agencia.value = $("#agencia").val();
    f.fecha.value = $("#fecha").val();
    f.base.value = "<?=$this->session->userdata($sess)["base"];?>";
    window.open('', 'TheWindow');
    f.submit();
  }

  $("#exporta").on("click",function(){
    openWindowWithPost()
    return;


    $.ajax({
      type: 'POST',
      url: '<?=base_url('ventas/reporteVta');?>',
      data: {
        agencia: $("#agencia").val(),
        fecha: $("#fecha").val(),
        base: "<?=$this->session->userdata($sess)["base"];?>"
      },
      dataType: 'html',
      beforeSend: function(){
        HoldOn.open({ theme:"sk-bounce" });
        $("#rrr").html("")
      },
      success: function(d){
        $("#rrr").html( d )
        HoldOn.close();

      }
    })
  })

  HoldOn.open({ theme:"sk-bounce" });

  $('.datepicker').pikaday({
    firstDay: 1,
    yearRange: [2010,2020],
    format: 'DD/MM/YYYY',
    theme: 'triangle-theme'
  });

  // combo Agencias
  $.ajax({
    url: '<?=base_url('datos/fnListaSimple');?>',
    data: { opcion: 'agencias', base: "<?=$this->session->userdata($sess)['base'];?>", buscar: '' },
    dataType: 'json',
    success: function(d){
      $.each(d.results, function(index, item) {
        $("<option>"+item.AGENCIA+"</option>").val(item.CODIGO).appendTo("#agencia")
      });

      $("#agencia").val('9100')
      $("#fecha").val('05/02/2018')
      HoldOn.close();
    }
  })


})
</script>




<?php
$this->load->view("footer");
?>
