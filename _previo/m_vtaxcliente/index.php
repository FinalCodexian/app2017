
<script language="javascript">
$(function(){

	$(document).on('click', '.popup-modal-dismiss, .cerrarPopup', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});		
	
	
	var $tablaBuscar = $("#buscarCliente"); 
	
	$tablaBuscar.tablesorter({
		theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
		widgetOptions: { stickyHeaders_attachTo : $(".wrpBuscar"), stickyHeaders_zIndex: 9 }
	});


	$('#buscar-cliente').magnificPopup({
		items: { src: '#popup', type: 'inline' },
		preloader: true,
		removalDelay: 300,
		modal: true, 
		midClick: true, 
		mainClass: 'mfp-3d-unfold', 
		focus: '#buscar', 
		callbacks: {
			beforeOpen: function(){
				$('#buscar').val('');

				
				$tablaBuscar.find("tbody").html(" ");
				$tablaBuscar.trigger("updateAll");

				
				//$('#tablaCliente').find('tbody').empty(); 
				//$('#tablaCliente').trigger('updateAll'); 
			}
		}
	});	
	
	$("#buscar").click(function(e){
		e.preventDefault(); 
		
		$.ajax({
			type: "POST", url: "m_clientes/data.php", data: { opcion: 'transportes', ruc: "20318171701" },
			beforeSend: function(){
				$tablaBuscar.parent().block( $options_block );
				$tablaBuscar.trigger('sortReset');
				$tablaBuscar.find("tbody").html(" ");
			},
			success: function(d) {
				var response = $.parseJSON(d);
				$.each(response.rows, function(index, item){
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center>"+item.codigo+"</td>")
					$fila.append("<td>"+item.ruc+"</td>")
					$fila.append("<td>"+item.transporte+"</td>")
					$fila.append("<td>"+item.direccion+"</td>")
					$fila.appendTo($tablaBuscar);
				});	
				$tablaBuscar.trigger("update");
				$tablaBuscar.parent().unblock(); 
			}
		});
		
	})
})
</script>
<button id="buscar-cliente">abrir popup</button>

<style>
	#popup { 
		background: #FFF;
		padding: 15px;
		width: auto;

		max-width: 700px;
		margin: 10px auto; z-index:9999
	}
	a.popup-modal-dismiss {
		position: absolute; display: block;
		right: 0; top: 0;  
		padding: 7px 10px; 
		background-color: #eee; z-index: 100
	}
	.wrpBuscar { height:400px}

	button.cerrarPopup {
		position: absolute; top: 0px; right: 0px; margin: 4px; 
		padding: 8px 15px; background-color:#f8f8f8 ; border: 1px solid #ccc; 
		cursor:pointer
	}	
</style>

<div id="popup"  class="mfp-hide mfp-with-anim">

	<button class='cancelar cerrarPopup'>&times; Cerrar b&uacute;squeda</button>
    
	<input type="text" id="buscar">
    <div class="wrapper wrpBuscar">
    <table id="buscarCliente" class="tablesorter">
        <thead><tr>
            <th>RUC / DNI</th>
            <th>Nombre / Raz&oacute;n Social</th>
            <th>RUC / DNI</th>
            <th>RUC / DNI</th>
        </tr></thead>
        <tbody></tbody>
    </table>
	</div>
</div>