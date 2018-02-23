<?php
$data = array("titulo"=>"Reporte de Ventas");
$this->load->view("header", $data);
$this->load->view("sidebar");
$this->load->view("menu_top");
?>

<script type="text/javascript">
$(function(){

  $('.datepicker').pikaday({
    firstDay: 1,
    yearRange: [2010,2020],
    format: 'DD/MM/YYYY',
    theme: 'triangle-theme',
    onSelect: function(date) {
      //console.log(this.getMoment().format('Do MMMM YYYY'));
    }
  });



  var $tabla = $("#tbReporte");

  $("#xxx").on("click",function(){

    var $agencia = $("#agencia").val();
    var $fecha = $("#fecha").val();

    HoldOn.open({ theme:"sk-bounce" });

    $.ajax({
      dataType: 'json',
      type: 'POST',
      url: "<?=base_url("ventas/reportevta");?>",
      data: { opcion: 'OP-1', agencia: $agencia, fecha: $fecha },
      beforeSend: function(){
        $tabla.html(" ");
        $tabla.append("<tbody></tbody>");
      },
      success: function(d){

        $tabla.append( addCabecera("inicio") )

        var $docActual = ""

        $.each(d.data, function(index, item) {

          var $fila = $("<tr></tr>")

          $("<td class=cen></td>").html(item.TD + '/' + item.SERIE + item.DOC).appendTo($fila);

          if($docActual == item.TD + '/' + item.SERIE + item.DOC){
            $tabla.find('tbody tr:last').addClass("sameFT")
            $fila.addClass("sameFT")
          }

          $("<td class=cen></td>").html(item.RUC).appendTo($fila);

          $("<td></td>").html(item.CLIENTE).appendTo($fila);
          $("<td></td>").html(item.TIPO_CLIENTE).appendTo($fila);
          $("<td></td>").html(item.VENDEDOR).appendTo($fila);

          $("<td></td>").html(item.FORMA_VENTA).appendTo($fila);
          $("<td></td>").html(item.FORMA_PAGO).appendTo($fila);

          if(item.FORMA_COD != null){
            $("<td class=mon></td>").html(parseFloat(item.TCAM).toFixed(3) ).appendTo($fila);
          }else{
            $("<td></td>").appendTo($fila);
          }

          // Pago: PAGOS
          var $pago = 0
          if(item.FORMA_COD != null && item.FORMA_COD != 'F'){

            $pago = parseFloat(item.PAGO_IMP)
            if(item.TD=='NC'){ $pago = $pago * -1 }

            if (item.PAGO_MON=='MN') {
              $("<td class=mon></td>").html($pago.toFixed(2)).appendTo($fila);
              $("<td class=mon></td>").appendTo($fila);
            }
            if (item.PAGO_MON=='US') {
              $("<td class=mon></td>").appendTo($fila);
              $("<td class=mon></td>").html($pago.toFixed(2)).appendTo($fila);
            }
          }else {
            $("<td class=mon></td>").appendTo($fila);
            $("<td class=mon></td>").appendTo($fila);
          }

          // Pago: TARJETA DE CREDITO
          if(item.FORMA_COD != null && item.FORMA_COD == 'F'){
            if (item.PAGO_MON=='MN') $("<td class=mon></td>").html(parseFloat(item.PAGO_IMP).toFixed(2)).appendTo($fila);
            if (item.PAGO_MON=='US') $("<td class=mon></td>").html(parseFloat(item.PAGO_IMP).toFixed(2)).appendTo($fila);
          }else {
            $("<td class=mon></td>").appendTo($fila);
          }

          // Saldos por documento
          if($docActual !== item.TD + '/' + item.SERIE + item.DOC){
            $("<td class=mon></td>").html( (item.SALDO_MON=='MN' ? parseFloat(item.SALDO_IMPORTE).toFixed(2) : "") ).appendTo($fila);
            $("<td class=mon></td>").html( (item.SALDO_MON=='US' ? parseFloat(item.SALDO_IMPORTE).toFixed(2) : "") ).appendTo($fila);
          }else{
            $("<td class=mon></td>").appendTo($fila);
            $("<td class=mon></td>").appendTo($fila);
          }

          $("<td></td>").html(item.LIQUIDACION).appendTo($fila);
          $("<td class=cen></td>").html(item.REF_TD).appendTo($fila);
          $("<td></td>").html(item.REF_NUM).appendTo($fila);

          if(item.REF_TD=='NC'){
            $("<td></td>").html(item.REF_NC_APLICA).appendTo($fila);
          }else {
            $("<td></td>").html(item.BANCO).appendTo($fila);
          }

          $fila.appendTo($tabla.find("tbody"))

          $docActual = item.TD + '/' + item.SERIE + item.DOC
        });

        addFilaVacia()


        $.ajax({
          dataType: 'json',
          type: 'POST',
          url: "<?=base_url("ventas/reportevta");?>",
          data: { opcion: 'OP-2', agencia: $agencia, fecha: $fecha },
          success: function(d){
            addFilaVacia("COBRANZAS")
            $tabla.append( addCabecera("cobranzas") )
            $tabla.append($("<tbody></tbody>"))

            $.each(d.data, function(index, item) {

              if(item.FORMA_COD=='A'){

                var $fila = $("<tr></tr>")
                $("<td></td>").html(item.TD +"/" +item.DOC +'  ('+ item.REF_NC_APLICA + ')').appendTo($fila)
                $("<td class=cen></td>").html(item.RUC).appendTo($fila)
                $("<td></td>").html(item.CLIENTE).appendTo($fila)
                $("<td></td>").html(item.TIPO_CLIENTE).appendTo($fila)
                $("<td></td>").html(item.VENDEDOR).appendTo($fila)
                $("<td></td>").html(item.FORMA_VENTA).appendTo($fila)
                $("<td></td>").html(item.FORMA_PAGO).appendTo($fila)
                $("<td class=mon></td>").html(parseFloat(item.TCAM).toFixed(3) ).appendTo($fila);
                $("<td class=mon></td>").html( item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP).toFixed(2) : '').appendTo($fila);
                $("<td class=mon></td>").html( item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP).toFixed(2) : '').appendTo($fila);
                $("<td></td>").appendTo($fila)
                $("<td></td>").appendTo($fila)
                $("<td></td>").appendTo($fila)
                $("<td></td>").html(item.LIQUIDACION).appendTo($fila)
                $("<td class=cen></td>").html(item.REF_TD).appendTo($fila);
                $("<td></td>").html(item.REF_NUM).appendTo($fila);
                $("<td></td>").html(item.BANCO).appendTo($fila);

                $fila.appendTo($tabla.find("tbody").last())
              }
            });




            addFilaVacia()
            addFilaVacia("COBRANZAS")
            $tabla.append( addCabecera("cobranzas") )
            $tabla.append($("<tbody></tbody>"))

            $.each(d.data, function(index, item) {

              if(item.FORMA_COD!=='A'){

                var $fila = $("<tr></tr>")
                $("<td></td>").html(item.TD +"/" +item.DOC +'  ('+ item.REF_NC_APLICA + ')').appendTo($fila)
                $("<td class=cen></td>").html(item.RUC).appendTo($fila)
                $("<td></td>").html(item.CLIENTE).appendTo($fila)
                $("<td></td>").html(item.TIPO_CLIENTE).appendTo($fila)
                $("<td></td>").html(item.VENDEDOR).appendTo($fila)
                $("<td></td>").html(item.FORMA_VENTA).appendTo($fila)
                $("<td></td>").html(item.FORMA_PAGO).appendTo($fila)
                $("<td class=mon></td>").html(parseFloat(item.TCAM).toFixed(3) ).appendTo($fila);
                $("<td class=mon></td>").html( item.PAGO_MON=='MN' ? parseFloat(item.PAGO_IMP).toFixed(2) : '').appendTo($fila);
                $("<td class=mon></td>").html( item.PAGO_MON=='US' ? parseFloat(item.PAGO_IMP).toFixed(2) : '').appendTo($fila);
                $("<td></td>").appendTo($fila)
                $("<td></td>").appendTo($fila)
                $("<td></td>").appendTo($fila)
                $("<td></td>").html(item.LIQUIDACION).appendTo($fila)
                $("<td class=cen></td>").html(item.REF_TD).appendTo($fila);
                $("<td></td>").html(item.REF_NUM).appendTo($fila);
                $("<td></td>").html(item.BANCO).appendTo($fila);

                $fila.appendTo($tabla.find("tbody").last())
              }
            });


            HoldOn.close(); // Al final del ultimo Jedi.. no del ultimo AJAX :D

          }
        });



      }
    })
  })


  function addFilaVacia(titulo = ""){
    var $tr = $("<thead></thead>");
    $tabla.find("tr:eq(1)").find('td').each(function(ind) {
      if(ind==1){
        $tr.append("<th>"+titulo+"</th>")
      }else{
        $tr.append("<th>&nbsp;</th>")
      }
    });
    $tabla.append($tr)
  }

  function addCabecera($opcion = "inicio"){
    var $th = $("<thead></thead>");

    $th.append("<th>N° COMPROB.</th>");
    $th.append("<th>RUC/DNI</th>");
    $th.append("<th>CLIENTE</th>");
    $th.append("<th>T/CLIENTE</th>");

    switch ($opcion) {
      case "inicio":
      $th.append("<th>VENDEDOR</th>");
      break;

      case "cobranzas":
      $th.append("<th>COBRADOR</th>");
      break;

    }


    $th.append("<th>COND. VENTA</th>");

    $th.append("<th>(DIAS) FORMA PAGO</th>");
    $th.append("<th>T/C</th>");

    $th.append("<th>CONT S/</th>");
    $th.append("<th>CONT US$</th>");
    $th.append("<th>TARJETA S/</th>");
    $th.append("<th>CRED S/</th>");
    $th.append("<th>CRED US$</th>");
    $th.append("<th>N° INFORME COB.</th>");

    $th.append("<th>T/DOC</th>");
    $th.append("<th>N° DOC</th>");
    $th.append("<th>BANCO</th>");

    return $th;

  }

})
</script>

<div class="field">
  <label>Fecha</label>
  <div class="ui icon input">
    <input id="fecha" type="text" class="datepicker" value="<?=date("d/m/Y");?>"><i class="calendar icon"></i>
  </div>
</div>

<select class="ui dropdown" id="agencia">
  <option value="0000">Tacna</option>
  <option value="1000">Arequipa</option>
  <option value="2000">Juliaca</option>
  <option value="3000">Cusco 1323</option>
  <option value="3100">Cusco H-11</option>
  <option value="3200">Cusco 1423</option>
  <option value="4000">Trujillo America</option>
  <option value="4100">Trujillo Pierola</option>
  <option value="5000">Chiclayo 1152</option>
  <option value="5100">Chiclayo 1139</option>
  <option value="6000">Piura</option>
  <option value="7000">Cajamarca</option>
  <option value="J000">Huancayo</option>
  <option value="9000">Lima Arriola 2291</option>
  <option value="9100" selected>Lima Canada 256</option>
  <option value="9200">Lima Alzamora</option>
  <option value="9300">Lima La Marina</option>
  <option value="9500">Lima Arriola 2298</option>
  <option value="9700">Lima Canada 635</option>
  <option value="9900">Lima Arriola 2265 (Motos)</option>
</select>

<button type="button" name="button" class="ui blue mini button" id="xxx">Reporte</button>


<style media="screen">
table#tbReporte { margin: 5px auto; width: 100%; border-collapse: collapse;}
table#tbReporte thead th { border:1px solid #ccc; padding: 3px; font-size: 10px}
table#tbReporte tbody td { font-size: 11px; border: 1px solid #ccc; padding: 1px 5px; cursor: default;}
table#tbReporte tbody td.mon { text-align: right; padding: 0 10px 0 15px}
table#tbReporte tbody td.cen { text-align: center; }

table#tbReporte tbody tr:hover { background-color: rgb(197, 255, 177) !important}

table#tbReporte tbody tr.sameFT { background-color: rgba(205, 244, 244, 0.66)}
</style>


<table id="tbReporte">
  <thead>
    <th>N° COMPROB.</th>
    <th>RUC/DNI</th>
    <th>CLIENTE</th>

    <th>T/CLIENTE</th>
    <th>VENDEDOR</th>

    <th>COND. VENTA</th>

    <th>FORMA PAGO</th>
    <th>T/C</th>

    <th>CONT S/</th>
    <th>CONT US$</th>
    <th>TARJETA S/</th>
    <th>CRED S/</th>
    <th>CRED US$</th>
    <th>N° INFORME COB.</th>

    <th>T/DOC</th>
    <th>N° DOC</th>
    <th>BANCO</th>

  </thead>

  <tbody></tbody>

</table>

<?php
$this->load->view("footer");
?>
