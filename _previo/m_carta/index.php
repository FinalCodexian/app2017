<script language="javascript">
$(function(){
	
	$("a.seccion").html("CARTA PARA ADUANERA").animate({opacity: 1},400);  // titulo de la seccion
	$(".fecha").datepicker({showButtonPanel: true});  

	$tablaGuias = $("#tabla-guias"); 

	$tablaGuias.tablesorter({
		theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
		widgetOptions: { stickyHeaders_attachTo : $(".wrpGuias"), stickyHeaders_zIndex: 1 }
	});	

	$.blockUI($options_block); 


	$.post('m_carta/data.php', {opcion: 'almacenes'}, function(data) {
		var response = $.parseJSON(data);
		if(response.total>0){
			$.each(response.rows, function(index, item){
				$('<option>')
					.attr('razon',item.razon)
					.val(item.cod_almacen)
					.text(item.cod_almacen + ' - ' + item.almacen)
					.appendTo("#almacenes");
			});
		}

		$.post('m_carta/data.php', {opcion: 'personal'}, function(data) {
			var response = $.parseJSON(data);
			if(response.total>0){
				$.each(response.rows, function(index, item){
					$('<option>')
						.val(item.dni)
						.attr('nombre',item.nombre)
						.text(item.dni + ' - ' + item.nombre)
						.appendTo("#personal");
				});
			}
			$.unblockUI();
		}); 
	});
	
	$("#almacenes").on("change",function(){
		var $opcion = $('option:selected', this); 
		$("#empresa").html( $opcion.attr("razon") ); 
	})

	$("#generar").click(function(e){
		var arr = new Array();
		//var arrFecha = new Array();
		var $sum = 0, $tot = 0; 
		$("input").each(function() {
			if( this.checked ){
				arr.push( $(this).val() + "|" + $(this).attr('data-fecha') ); 
				//arrFecha.push( $(this).attr('data-fecha') ); 
				$tot += 1; 
			}
		});
		if($tot==0){
			alert("Debe seleccionar al menos una guia para continuar");
			return false; 
		}

		$guias = arr.join(); 
		//$fechas = arrFecha.join(); 

		var $personal = $('#personal option:selected').val(); 
		var $nombre = $('#personal option:selected').attr('nombre'); 
		if($personal==''){
			alert("Seleccione un personal SCTR");
			$('#personal').focus();
			return false; 
		}

		$.blockUI($options_block); 
		$("#respuesta").html(""); 
		$.post("lib/carta_genera.php",{
			empresa: $("#empresa").html(), 
			personal: $nombre + '  DNI: ' + $personal,
			guias: $guias

		},function(data){
			$("#respuesta").html(data); 
			$.unblockUI();
		})
	}); 

	$("#ejecuta").click(function(e){
		e.preventDefault(); 

		var $almacen = $('#almacenes option:selected').val(); 
		if($almacen==''){
			alert("Seleccione un almacen para realizar la consulta");
			$('#almacenes').focus();
			return false; 
		}

		$.blockUI($options_block); 
		
		$("#respuesta").html(""); 
		$("#sumGuias, #sumProductos").html('0'); 
		$tablaGuias.trigger('sortReset');
		$tablaGuias.find('tbody').html('');
		$tablaGuias.trigger("update"); 

		$.post('m_carta/data.php', {
			opcion: 'guias',
			inicio: $("#inicio").val(),
			final: $("#final").val(),
			almacen: $almacen
		}, function(data) {
			var response = $.parseJSON(data);
			if(response.total>0){
				$.each(response.rows, function(index, item){
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center><input type=checkbox value='"+item.guia+"' data-fecha='"+item.fecha+"'></td>"); 
					$fila.append("<td align=center>"+item.guia+"</td>"); 
					$fila.append("<td align=center>"+item.fecha+"</td>"); 
					$fila.append("<td align=center>"+parseInt(item.total)+"</td>"); 					
					$fila.appendTo($tablaGuias.find('tbody'));
				});
				$tablaGuias.parent().scrollTop(0); 
				$tablaGuias.trigger("sortReset");
				$tablaGuias.trigger("update"); 
				$.unblockUI();

				funciones(); 
			}else{

				alert("Sin informacion para mostrar"); 
				$.unblockUI();
			}

		});		
	})

	var funciones = function(){
		
		$("input[type=checkbox]").on("click",function(){
			var $sum = 0, $tot = 0; 
			$("input").each(function() {
				$row = $(this).parent().parent(); 
				if( this.checked ){
					$row.addClass('seleccionado')
					$sum += parseInt( $(this).parent().parent().find("td:eq(3)").html() ); 
					$tot += 1; 
				}else{
					$row.removeClass('seleccionado')
				}
			});
			$("#sumGuias").html( $tot ); 
			$("#sumProductos").html( $sum ); 
		})
	}


})
</script>

<style type="text/css">
	.wrpGuias { height:500px; width:100% }
	.tablaDatos { width: 100%; margin: 5px auto; border: 1px solid #f2f2f2; padding: 6px : }
	#almacenes { width: 200px}
	.tablaDatos span { color: #06c}
	.tablaDatos tr td { vertical-align: top}

	td.etiqueta, td.dato { padding: 3px;}
	td.etiqueta { text-align: right; width: 70px}
	td.dato {color: #06c}
	td.dato select { color: #06c;}
	#personal { width: 300px}
	h2 { font-size: 16px; font-weight: normal; color: #68A794; margin: 5px 0 10px 0}

	tr.seleccionado td {background-color: #06c !important; color: #Fff}

	a.download { background-color: #f2f2f2; display: inline-block; text-align: center; border-radius: 5px; padding: 10px;
		color: #333; text-decoration: none; cursor: pointer; margin: 4px auto; width: 130}
	a.download:hover { text-decoration: underline;}
	a.download img { width: 100px; display: inline-block; margin: 5px auto 10px auto}
	
</style>

<div class="contenedor-general">
<select id="almacenes"><option value="" selected>- Seleccione un almacen -</option></select>
<input type="text" id='inicio' class="fecha" value="<?=date('d');?>/<?=date('m');?>/<?=date('Y');?>">
<input type="text" id='final' class="fecha" value="<?=date('d');?>/<?=date('m');?>/<?=date('Y');?>">
<button id="ejecuta">Ejecutar</button>

<table class="tablaDatos">
<tr>
	<td width="300px">
		<div class="wrapper wrpGuias">
		<table id="tabla-guias" class="tablesorter">
		    <thead>
		    	<tr>
		    		<th width="45">Sel.</th>
		    		<th>Guia</th>
		    		<th width="70">Fecha</th>
		    		<th width="60">Productos</th>
		    	</tr>
		    </thead>
		    <tbody></tbody>
		</table>
		</div>    
	</td>
	<td align="center" width="200px" style="line-height:20px">
		<h2>Datos previos</h2>
		<table width="100%">
			<tr><td class="etiqueta">Empresa:</td><td class="dato" id='empresa'></td></tr>
			<tr><td class="etiqueta">Personal:</td>
			<td class="dato">
				<select id="personal"><option value="">- Seleccione un personal STCR -</option></select>
			</td>
			</tr>
		</table>

		Total de guias seleccionadas: <span id='sumGuias'>0</span><br>
		Total de productos: <span id='sumProductos'>0</span>

		<br><br>
		<button id="generar">Generar formato de cartas</button>
	</td>


	<td width="200px" align="center">
		<h2>Resultado</h2>
		<div id="respuesta"></div>
	</td>

</tr>
</table>
</div>