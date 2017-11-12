<?php
$root = "_clientes/"; 

?>

<script type="text/javascript">


	var $bloqueo_contenido = "<div><table><tr><td><img src='img/loading-boqueo.gif' align=absmiddle></td>"
	$bloqueo_contenido += "<td>Procesando la informaci&oacute;n..<br>Espere un momento</td></tr></table></div>"

	var $altoReporte = 340; 
	var $tabla_reporte = $("#reporte"); 
	var $tabla_resultado = $("#resultado"); 


	var myFeature = {
		init: function(){

			
			$(".fecha").datepicker({
				showButtonPanel: true,
				changeMonth: true,
				changeYear: false
			});  


			$tabla_resultado.tablesorter({
				theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
				widgetOptions: { stickyHeaders_attachTo : $tabla_resultado.parent() }
			});		

			$tabla_reporte.tablesorter({
				theme: 'metro-dark', widthFixed : true,	widgets: ['zebra','stickyHeaders'], 
				widgetOptions: { 
					stickyHeaders_attachTo : $tabla_reporte.parent(),
					stickyHeaders_zIndex: 8
				}
				
			});	

			myFeature.setup(); 
		}, 

		setup: function(){

			$("input[type='search']").keypress(function(event) {
				if ( event.which == 13 ) {
					$(this).parent().find("button.boton").click(); 
				 	$(this).blur(); 
				}
			});

		}
	}


	//$(function($){
	$(document).ready(function(){

		$("a.seccion").html("VENTAS POR CLIENTE").animate({opacity: 1},400);  // titulo de la seccion
		
		myFeature.init(); 

		$(".div-marca").block({
			overlayCSS: { 
				backgroundColor: '#f4f4f4',
	            opacity: .6
	        }, 
	        message:null
	    }); 
		$.post('m_vtaxcliente/resultado.php', {opcion: 'marcas'}, function(data) {
			var response = $.parseJSON(data);
			$.each(response.rows, function(index, item){ 
				$("#marca").append('<option value="'+item.codigo+'"">'+item.marca+'</option>')
			});
			$(".div-marca").unblock(); 
		});


		var $ventana_bloqueo = $("#miBloqueo"); 
		var $ventana_busqueda = $("#busqueda"); 

		$(".cerrarWrapper").click(function(e){
			$tabla_resultado.trigger('sortReset');
			$tabla_resultado.find('tbody').html('');
			$tabla_resultado.trigger("update"); 
			$ventana_busqueda.hide()
			$ventana_bloqueo.hide(); 			
		}); 


		$("input[type=checkbox]").ionCheckRadio();
		$("input[type=radio]").ionCheckRadio();

		var $inp_diferida = $("input[name=diferida]"); 
		var $inp_dif_pend = $("input[name=diferida_pend]"); 
		var $inp_pendient = $("input[name=pendientes]"); 

		$inp_diferida
		.on("change",function(event){
			$inp_dif_pend.prop('disabled', !$(this).is(":checked")); 
		})

		$inp_pendient.on("change",function(event) {
			$inp_diferida.prop('disabled', $(this).is(":checked")); 
			$inp_dif_pend.prop('disabled', !($inp_diferida.is(":checked") && !$inp_diferida.is(":disabled"))); 
		});


		$("button.boton").click(function(e){
			e.preventDefault();
			$input_actual = $(this).parent().find("input"); 
			$seccion = $input_actual.attr("name");

			var $pos = $input_actual.position(); 
			$ventana_busqueda.css({ 'top': $pos.top + 20, 'left': $pos.left + 10 });
			
			if(($input_actual.val()).length<4){ alert("Debe escribir como mínimo 4 caracteres"); return false; }
			$ventana_bloqueo.html($bloqueo_contenido); 
			$ventana_bloqueo.show(); 

			$.post('m_vtaxcliente/resultado.php', {opcion: 'consulta', buscar:$input_actual.val(), seccion:$seccion}, function(data) {

				var response = $.parseJSON(data);

				if(response.total==0){
					alert("No se encontraron coincidencias"); 
					$ventana_busqueda.hide(); 
					$ventana_bloqueo.hide();
					$input_actual.focus();
					$input_actual.select(); 
					return false; 
				}

				if(response.total==1){
					$.each(response.rows, function(index, item){ 
						$("div."+$seccion).find("label.codigo").html(item.codigo).addClass('seleccionado'); 
						$input_actual.val(item.nombre); 
						// $("#<?=$indice;?> div."+$seccion).find("label.descripcion").html(item.nombre).addClass('seleccionado'); 
					});
					$("div."+$seccion).find("button.borrar").prop("disabled",false); 
					$ventana_busqueda.hide(); 
					$ventana_bloqueo.hide();
					return false; 					
				}


				$.each(response.rows, function(index, item){ 					
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center><a class='detalle' seccion='"+$seccion+"' href='"+item.codigo+"' nombre='"+$.trim(item.nombre)+"''>"+item.codigo+"</a></td>"); 
					$fila.append("<td>"+$.trim(item.nombre)+"</td>"); 
					$fila.appendTo($tabla_resultado.find('tbody'));
				});


				$("a.detalle").on("click", document, function(e){
					e.preventDefault(); 
					$("div."+$seccion).find("label.codigo").html( $(this).attr("href") ).addClass('seleccionado'); 
					$("div."+$seccion).find("label.descripcion").html( $(this).attr("nombre") ).addClass('seleccionado'); 
					$input_actual.val($(this).attr("nombre")); 
					$("div."+$seccion).find("button.borrar").prop("disabled",false); 
					$ventana_busqueda.hide(); 
					$ventana_bloqueo.hide();
					return false; 
				})

				
				$ventana_bloqueo.html(''); 
				$ventana_busqueda.show(); 

				$tabla_resultado.parent().scrollTop(0); 
				$tabla_resultado.trigger("sortReset");
				$tabla_resultado.trigger("update"); 

			});

		})

		$("button.borrar").click(function(e){
			e.preventDefault();
			$input_actual = $(this).parent().find("input"); 
			$input_actual.val(''); 
			$(this).parent().find("label.codigo").html("[ TODOS ]").removeClass('seleccionado');
			$(this).prop("disabled", true); 
		})

		$("#fechas").click(function(e){
			if($(this).is(':checked')){
				$("input[name='fecha_inicio']").prop('disabled', false)
				$("input[name='fecha_final']").prop('disabled', false)
			}else{
				$("input[name='fecha_inicio']").prop('disabled', true)
				$("input[name='fecha_final']").prop('disabled', true)				
			}
		})



		$("button.excel").click(function(e){

			$ruc = $("div.cliente").find('label.codigo').html(); 
			$ruc = ($ruc=='[ TODOS ]') ? '' : $ruc; 

			$vendedor = $("div.vendedor").find('label.codigo').html(); 
			$vendedor = ($vendedor=='[ TODOS ]') ? '' : $vendedor; 

			$producto = $("div.producto").find('label.codigo').html(); 
			$producto = ($producto=='[ TODOS ]') ? '' : $producto; 		

			$tipo_doc = $("select[name='tipo_doc']").val(); 

			$inicio =$("input[name='fecha_inicio']").val(); 
			$final = $("input[name='fecha_final']").val(); 
			
			$ventana_bloqueo.html($bloqueo_contenido); 
			$ventana_bloqueo.show();


			var formData = {
				'resumen': ($("input[name=chk_resumen]").is(":checked") ? 'S' : ''),
				'agencia': '9000',
				'ruc': $ruc, 
				'vendedor': $vendedor,
				'inicio': $inicio,
				'final': $final,
				'tipo_doc': $tipo_doc,
				'codigo': $producto, 
				'solo_diferida': ($("input[name=solo_diferida]").is(":checked") ? 'S' : ''),
				'solo_diferida_pend': ($("input[name=solo_diferida_pend]").is(":checked") ? 'S' : ''),
				'solo_pendientes': ($("input[name=solo_pendientes]").is(":checked") ? 'S' : '')
			};

			$.ajax({
			  url: "m_vtaxcliente/excel.php",
			  type: "GET",
			  data: formData,
			  dataType: 'binary',
			  success: function(result) {
			    var url = URL.createObjectURL(result);
			    var $a = $('<a />', {
			      'href': url,
			      'download': 'JCH Llantas - Ventas por cliente.xls',
			      'text': "-"
			    }).hide().appendTo("body")[0].click();
			    setTimeout(function() {
			      URL.revokeObjectURL(url);
			    }, 10000);
			    $ventana_bloqueo.hide(); 
			  }
			});

		}); 


		$("button.ejecutar").click(function(e){

			$ruc = $("div.cliente").find('label.codigo').html(); 
			$ruc = ($ruc=='[ TODOS ]') ? '' : $ruc; 

			$vendedor = $("div.vendedor").find('label.codigo').html(); 
			$vendedor = ($vendedor=='[ TODOS ]') ? '' : $vendedor; 

			$producto = $("div.producto").find('label.codigo').html(); 
			$producto = ($producto=='[ TODOS ]') ? '' : $producto; 		

			$tipo_doc = $("select[name='tipo_doc']").val(); 
			$estado = $("select[name='estado']").val(); 

			$inicio =$("input[name='fecha_inicio']").val(); 
			$final = $("input[name='fecha_final']").val(); 
			
			$ventana_bloqueo.html($bloqueo_contenido); 
			$ventana_bloqueo.show(); 

			$respuesta = $("#respuesta"); 
			$respuesta.html(''); 
			$todo = ""; 

			$x_diferida = (!$inp_diferida.is(":disabled") && $inp_diferida.is(":checked")) ? 'S' : '';
			$x_dif_pend = (!$inp_dif_pend.is(":disabled") && $inp_dif_pend.is(":checked")) ? 'S' : '';
			$x_pendient = (!$inp_pendient.is(":disabled") && $inp_pendient.is(":checked")) ? 'S' : '';
			
			$marca = $("#marca option:selected").val(); 

			$.post('m_vtaxcliente/resultado.php', {
					opcion: 'vtaxcliente',
					agencia: '<?=$_COOKIE[$_POST['sub']."agencia_codigo"]?>',
					ruc: $ruc, 
					vendedor: $vendedor,
					inicio: $inicio,
					final: $final,
					tipo_doc: $tipo_doc,
					estado: $estado,
					codigo: $producto, 
					solo_diferida: $x_diferida,
					solo_diferida_pend: $x_dif_pend,
					solo_pendientes: $x_pendient,
					marca: $marca
				}, function(data) {

					var $ix_guia=0, $ix_fecha=1, $ix_cant=2, $ix_almacen=3;
					var $ix_codigo=4, $ix_producto=5, $ix_item=6; 

					var response = $.parseJSON(data);

					var $doc_actual = ""; 

					var $header = "<table class=vtaxcliente_resultado><thead><th>C&Oacute;DIGO</th><th>PRODUCTO</th>"
					$header += "<th>MON</th><th>CANT</th><th>X_ATENDER</th><th>PRECIO</th><th>VALOR PRECIO</th><th>IGV</th><th>PRECIO VENTA</th></thead><tbody>"; 

					var $guias_atiende = ""; 
					var $producto_ft_actual = ""; 

					var $arr_atenciones = new Array(); 
					$ultimo = ""; 


					$.each(response.rows, function(index, item){
						

						if($doc_actual != item.tdoc+"-"+item.documento){
							
							if(index>0){ 

								$todo += '</tbody></table>';

								if($arr_atenciones.length>0){
									
									$arr_atenciones.sort(); 
									$arr_atenciones.sort((function(index){
										return function(a, b){return (a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1));};
									})($ix_item)); 
									$arr_atenciones.sort((function(index){
										return function(a, b){return (a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1));};
									})($ix_fecha)); 

									$arr_atenciones.sort((function(index){
										return function(a, b){return (a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1));};
									})($ix_guia)); 

									$todo += "<h1 class=atenciones>Atenciones</h1><table class=atenciones><thead><th>Almac&eacute;n</th><th>Gu&iacute;a</th><th>Fecha</th><th>C&oacute;digo</th><th>Producto</th><th>Cantidad</th></thead><tbody>";
									$.each($arr_atenciones, function($ind,$dato) {
										$todo += "<tr>";
										$todo += "<td>"+$dato[$ix_almacen]+"</td>";
										$todo += "<td>"+$dato[$ix_guia]+"</td>";
										$todo += "<td>"+$dato[$ix_fecha]+"</td>";
										$todo += "<td>"+$dato[$ix_codigo]+"</td>";
										$todo += "<td>"+$dato[$ix_producto]+"</td>";
										$todo += "<td align=center>"+$dato[$ix_cant]+"</td>";
										$todo += "</tr>"; 
									});
									$todo += "</tbody></table>"; 

									$arr_atenciones = []; 
									$guias_atiende = ""; 
									$producto_ft_actual = ""; 

								}else{

									if($ultimo!=''){
										$todo += "<div class=sin_atencion>NO TIENE ATENCIONES</div>"
									}

								}

								$todo += '</section>'; 								
							}

							$todo += '<section class=seccion_documento><div class=omg>'
							$todo += '<table class="vtaxcliente_cabecera">'; 
							$todo += '<tr>';

							//$todo += '<td><label class=etiq>Documento:</label><strong>'+item.tdoc+' '+item.documento+'</strong> ('+item.fecha+') '+item.tipo_factura+' / TC: '+item.tipo_cambio+'</td>';
							$todo += '<td>';
							$todo += '<label class=lbl_contenedor>';
								$todo += '<label class=lbl_documento>'+item.tdoc+' '+item.documento+'</label> '
								$todo += '<label class=lbl_fecha>'+item.fecha+'</label>'
								$todo += ' &bull; IMPORTE: <label class="total_ft moneda_'+item.cod_moneda+'">' + item.cod_moneda + ' ' + item.total_ft + '</label>'
								if(item.tipo_factura!=''){
									$todo += ' &bull; <label class=lbl_diferida>DOCUMENTO DIFERIDO</label>'
								}
								// +item.tipo_factura
							$todo += '</label>';
							$todo += '</td>';

							if((item.forma_venta).indexOf("CONTADO")!=-1 || (item.forma_venta).indexOf("TARJETA")!=-1){
								$todo += '<td width=40%><label class=etiq>Cond. Pago:</label>'+item.forma_venta+'</td>'; 
							}else{
								$todo += '<td width=40%><label class=etiq>Cond. Pago:</label>'+item.forma_venta+'&emsp;Vencimiento: '+item.vencimiento+'</td>';
							}

							$todo += '</tr>';

							$todo += '<tr>';
							$todo += '<td><label class=etiq>Cliente:</label>'+item.ruc+' - '+item.cliente+'</td>';
							$todo += '<td><label class=etiq>Vendedor:</label>'+item.vendedor+'</td>';
							$todo += '</tr>';
							$todo += '</table>';

							$todo += '<table class="vtaxcliente_cabecera">'; 
							$todo += '<tr>';
							$todo += '<td width=30%><label class=etiq>Almac&eacute;n:</label>'+($.trim(item.tipo_factura)!='' ? '' : item.almacen)+'</td>';

							
							if($.trim(item.tipo_factura)!=''){
								$todo += '<td width=30%><label class=etiq>Gu&iacute;a Rem.:</label><i>&laquo;VER ATENCIONES&raquo;</i></td>'; 	
							}else{
								if(item.almacen_tipo=='C'){
									$todo += '<td width=30%><label class=etiq>Gu&iacute;a Rem.:</label><i>&laquo;REGULARIZACION DE CONSIGNACION&raquo;</i></td>'; 	
									
								}else{
									if(item.guia!=''){
 										$todo += '<td width=30%><label class=etiq>Gu&iacute;a Rem.:</label><label class=lbl_documento_guia><strong>'+item.guia+'</strong> <label class=lbl_fecha_guia>'+item.fecha_guia+'</label></label></td>'; 	
									}else{
										$todo += '<td width=30%><label class=etiq>Gu&iacute;a Rem.:</label><i>&laquo;PENDIENTE DE DESPACHO&raquo;</i></td>'; 	
									}
								}
							}
								

							$todo += '<td width=30%><label class=etiq>Pedido:</label>'+ (($.trim(item.pedido)!='') ? '<strong>'+item.pedido+'</strong> ('+item.fecha_pedido+') - ' + item.pedido_usuario : '<i>&laquo;SIN PEDIDO&raquo;</i>')+'</td>';
							$todo += '</tr>';
							$todo += '</table>';

							$todo += '<table class="vtaxcliente_cabecera">'; 
							$todo += '<tr>'; 

							$todo += '<td width=130px><label class=etiq>Estado:</label>'+ ((item.doc_estado=='V') ? 'VIGENTE' : 'ANULADO') +'</td>';

							$todo += '<td width=160px><label class=etiq>O/C:</label>'+ item.orden_compra+'</td>';
							$todo += '<td width=250px><label class=etiq>Glosa:</label>'+item.glosa+'</td>';
							$todo += '<td><label class=etiq>Agencia:</label>'+item.agencia+'</td>';							
							$todo += '</tr>';
							$todo += '</table>';

							$todo += $header; 
						}

						if($producto_ft_actual != item.documento + '-' + item.producto + "-" + item.item_ft){
							$todo += "<tr>"
							$todo += "<td width=80 align=center>"+item.codigo+"</td>";
							$todo += "<td>"+item.producto+"</td>";
							$todo += "<td width=40 align=center class=moneda_"+item.cod_moneda+">"+item.cod_moneda+"</td>";
							$todo += "<td width=50 align=center>"+item.cantidad+"</td>"

							if(item.x_atender==0){
								$todo += "<td width=40 align=center class=cero>0</td>"; 
							}else{
								$todo += "<td width=40 align=center class=x_atender>"+item.x_atender+"</td>"; 
							}
							
							$todo += "<td width=60 align=right class=moneda_"+item.cod_moneda+">"+item.precio+"</td>";
							$todo += "<td width=60 align=right class=moneda_"+item.cod_moneda+">"+item.subtotal+"</td>";
							$todo += "<td width=60 align=right class=moneda_"+item.cod_moneda+">"+item.igv+"</td>";
							$todo += "<td width=65 align=right class=moneda_"+item.cod_moneda+">"+item.total+"</td>";
							$todo += "</tr>";
							$producto_ft_actual = item.documento + '-' + item.producto + "-" + item.item_ft; 
						}

						if($.trim(item.tipo_factura)!='' && item.atendido!=0){
							$guias_atiende += item.guia + ' ' + item.codigo + ' ' + item.atendido + '<br>'; 

							$arr_atenciones.push([
								$.trim(item.guia), 
								item.fecha_guia, 
								item.atendido, 
								$.trim(item.guia_almacen),
								$.trim(item.codigo),
								$.trim(item.producto),
								item.item_ft
							]); 
						}

						$doc_actual = item.tdoc+"-"+item.documento; 
						$ultimo = $.trim(item.tipo_factura); 

					});

					$todo += '</tbody></table>';


					if($arr_atenciones.length>0){
						
						$arr_atenciones.sort(); 
						$arr_atenciones.sort((function(index){
							return function(a, b){return (a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1));};
						})($ix_item)); 						
						$arr_atenciones.sort((function(index){
							return function(a, b){return (a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1));};
						})($ix_fecha)); 
						$arr_atenciones.sort((function(index){
							return function(a, b){return (a[index] === b[index] ? 0 : (a[index] < b[index] ? -1 : 1));};
						})($ix_guia)); 

						$todo += "<h1 class=atenciones>Atenciones</h1><table class=atenciones><thead><th>Almac&eacute;n</th><th>Gu&iacute;a</th><th>Fecha</th><th>C&oacute;digo</th><th>Producto</th><th>Cantidad</th></thead><tbody>";
						$.each($arr_atenciones, function($ind,$dato) {
							$todo += "<tr>";
							$todo += "<td>"+$dato[$ix_almacen]+"</td>";
							$todo += "<td>"+$dato[$ix_guia]+"</td>";
							$todo += "<td>"+$dato[$ix_fecha]+"</td>";
							$todo += "<td>"+$dato[$ix_codigo]+"</td>";
							$todo += "<td>"+$dato[$ix_producto]+"</td>";
							$todo += "<td align=center>"+$dato[$ix_cant]+"</td>";
							$todo += "</tr>"; 
						});
						$todo += "</tbody></table>"; 

						$arr_atenciones = []; 
						$guias_atiende = ""; 
						$producto_ft_actual = ""; 
					}else{
						if($ultimo!=""){
							$todo += "<div class=sin_atencion>NO TIENE ATENCIONES</div>"
						}
					}
					$todo += '</div></section>'; 


					$respuesta.append( $todo ); 
				

					$('table.atenciones').each(function () {
					 	var table  = $(this); 
						var firstColumnBrakes = [];
						for(var i=1; i<=table.find('th').length; i++){
							if(i>=1 && i<=3){
						        var previous = null, cellToExtend = null, rowspan = 1;
						        table.find("td:nth-child(" + i + ")").each(function(index, e){
						            var jthis = $(this), content = jthis.text();
						            if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
						                jthis.addClass('hidden');
						                cellToExtend.attr("rowspan", (rowspan = rowspan+1));
						            }else{
						                if(i === 1) firstColumnBrakes.push(index);
						                rowspan = 1;
						                previous = content;
						                cellToExtend = jthis;
						            }
						        });
							}
						}
						$('td.hidden').remove();
					});


					$ventana_bloqueo.hide(); 
				}
			); 

		});

		$("input[type='search']").click(function(e) {
			e.preventDefault(); 
			$(this).select();
		});


	})

	</script>




	<style type="text/css">
		div.omg { padding-bottom: 6px; border: 1px solid #CBCBCB; border-radius: 0 5px 0 5px;}
		div.omg,label.lbl_contenedor,label.lbl_diferida, label.lbl_documento_guia {transition: all 0.15s;}
	

		div.omg:hover { border-color:#333; background-color: #F9F9F9}
		div.omg:hover label.lbl_contenedor, div.omg:hover label.lbl_documento_guia {background-color: #333; color: #efefef}
		div.omg:hover label.lbl_diferida { background-color: #06c}


		
		label.lbl_contenedor {background-color: #CBCBCB; border-radius: 0 0 5px 5px; padding: 6px 8px; margin-top: -12px}

		label.lbl_diferida { background-color: #333; padding: 2px 8px 2px 20px; border-radius: 4px;
			background-image: url("img/flag_yellow.png");
			background-repeat: no-repeat;
			background-position: 5px center; 
			background-size: 11px 11px; font-size: 10px; color: #efefef

		}

		label.lbl_documento, label.lbl_documento_guia { font-size: 11px; padding: 5px; font-weight: bold}
		label.lbl_fecha, label.lbl_fecha_guia { background-color: #E0E0E0; color: #333;  
			background-image: url("img/datepicker.png");
			background-repeat: no-repeat;
			background-position: 5px center; 
			background-size: 11px 11px; 
			border-radius: 4px; padding: 2px 4px 2px 20px; margin: 0 4px; font-size: 10px }

		label.lbl_documento_guia { background-color: #CBCBCB; border-radius: 4px}

		label.total_ft { background-color: #E0E0E0; border-radius: 5px; padding: 2px 5px; font-size: 11px; font-weight: bold; }

		.moneda_US { color: #236E02;}
		.moneda_MN { color: #06c;}

		
		div#busqueda { position: absolute; margin-top: 4px;  display: none;}
		div#busqueda .wrapper { height: 300px; width: 500px; z-index: 888}

		#miBloqueo { background-color: rgba(0,0,0, .6); z-index: 887; width: 100%; height: 100%; position: fixed; top: 0; left: 0;
			display: none}
		#miBloqueo div { color: #fff; margin: auto; font-size: 14px; padding: 20px}
		#miBloqueo div table tr td { vertical-align: middle; padding: 3px}

		.cerrarWrapper { position: absolute; top: -24px; right: 0; z-index: 889; 
			color: #f2f2f2; background-color: #06c; border-radius: 5px 5px 0 0; padding: 5px 8px; cursor: pointer; }

		input.fecha { padding: 3px; color: #06c; 
			margin-left: 0px; margin-right: 0px; 
			border:1px solid #E8E8E8; text-align: center; width: 75px}



		
		section {padding: 3px 0; vertical-align: middle;}

		.label-info-contenedor { display: inline-block; background-color: #EFEFEF; padding: 4px; border-radius: 4px}
		.label-info-contenedor .subtitulo {padding: 0 10px; width: 60px; display: inline-block; text-align: right;}
		.label-info-contenedor .codigo {
			min-width: 90px; text-align: center; background-color: #e2e2e2; border: 0 none; 
			border-radius: 4px 0 0 4px; display: inline-block; padding: 5px 2px
		}

		.label-info-contenedor .seleccionado { background-color: #06c; color: #fff;}

		.label-info-contenedor input[type='search'] { color: #06c; vertical-align: middle; margin: 0; 
			border:1px solid #E8E8E8; 
			padding: 4px 6px 4px 18px; margin-bottom: 2px; width: 180px;
			background-image: url('img/buscar.png');
			background-repeat: no-repeat; background-position: 4px 5px

		}

		.label-info-contenedor select { padding: 2px}

		.label-info-contenedor button.boton { margin: 0 0 2px 0 ; border:0 none; vertical-align: middle; padding: 4px 12px; 
			background-color: #06c; color: #fff; cursor: pointer; display: inline-block; border-radius: 4px}

		.label-info-contenedor button.borrar { margin: 0; line-height: 20px; border:1px solid #E8E8E8; border-left: 0 none; 
			background-color: #DA1515; color: #f2f2f2; cursor: pointer; border-radius: 4px; padding: 1px 8px}
		.label-info-contenedor button.borrar:disabled {background-color: #ccc; cursor: no-drop }




		.seccion_documento { margin: 4px auto 30px auto; padding: 5px; }
		/*.seccion_documento:hover { background-color:#F6F6F6}*/

		.vtaxcliente_cabecera { width: 100%; margin: 0 auto; border-collapse: collapse}
		.vtaxcliente_cabecera tr td { /*border: 1px solid #ddd; */ border-bottom: 0 none; padding: 3px}
		.vtaxcliente_cabecera tr td i { color: #939393}

		.vtaxcliente_cabecera label.etiq { color: #333; width: 70px; text-align: right; display: inline-block;
			padding-right: 4px; background-color: #F4F4F4; padding: 3px; border-radius: 3px; margin-right: 2px}

		.vtaxcliente_cabecera label.etiq2 { color: #333; display: inline-block;	padding: 0 6px}

		.vtaxcliente_resultado { width: 99%; margin: 0px auto; border-collapse: collapse; }
		.vtaxcliente_resultado thead th { border: 1px solid #ddd; text-align: center; font-size: 10px; 
			background-color:#EBEBEB; color: #333; font-weight: normal; padding: 4px 5px }
		.vtaxcliente_resultado tbody tr td { border: 1px solid #ddd; vertical-align: middle; 
			padding: 4px; border-collapse: collapse;}

		.vtaxcliente_resultado tbody tr:nth-child(odd) {background-color: #fff;}
		.vtaxcliente_resultado tbody tr:nth-child(even) {background-color: #f4f4f4;}


		#vtaxcliente_footer { position: fixed; bottom: 0; background-color: #06c; 
			margin: 10px; border-radius: 5px; padding: 10px; color: #f4f4f4}
		#vtaxcliente_footer input { border: 1px solid #3683D1; padding: 3px}


		h1.atenciones { margin: 10px auto 3px auto; text-align: center; font-size: 14px; font-weight: 100;}
		table.atenciones { margin: 0px auto 10px auto; border-collapse: collapse; }
		table.atenciones thead th, table.atenciones tbody td { border:1px solid #bbb;}
		table.atenciones thead th { padding: 4px 6px; background-color: #06c; color: #fff; font-size: 10px; text-transform: uppercase; font-weight: 100;}
		table.atenciones tbody td { padding: 2px 8px }

		table.atenciones tbody tr:nth-child(odd) {background-color: #fff;}
		table.atenciones tbody tr:nth-child(even) {background-color: #f4f4f4;}

		div.sin_atencion { margin: 5px auto; background-color: #FFFFDF; width: 400px; padding: 10px; color:#5F5F5F; font-size: 14px;
			text-align: center; font-style: italic;}

		td.cero { color: #D1D1D1}
		td.x_atender { background-color:#FFF6BD}

		td.hidden { display: none;}



		button.xboton { 
			margin: 0; border:0 none; border-left: 0 none; 
			background-color: #06c; color: #fff; cursor: pointer; border-radius: 5px; padding: 7px;  
			margin: 4px; width: 100px
		}
		button.xboton:hover { background-color: #004E9D}
		button.xboton img {display: block; height: 40px; margin: 4px auto}

		table.botonera tr td { vertical-align: top;}

		.al_cielo { opacity: .7}
		.al_cielo img { width: 40px;border-radius: 50%;  }

</style>


	<div id="miBloqueo"></div>

	<div style="margin:auto; text-align:center; background-color: #f8f8f8; padding: 14px 0">

		<table border="0" align="center" width="960px" style="margin:auto">
		<tr><td>
		<section>
			<div class="label-info-contenedor">
				<label style="padding: 0 10px">Emision:</label>
				<input class="fecha" name="fecha_inicio" type="text" value="<?=date('d');?>/<?=date('m');?>/<?=date('Y');?>" />
				<label style="padding: 0 7px">al:</label><input class="fecha" name="fecha_final" type="text" value="<?=date('d');?>/<?=date('m');?>/<?=date('Y');?>" />
			</div>
			<div class="label-info-contenedor">
				<label style="padding: 0 5px">TD:</label>
				<select name="tipo_doc">
					<option value="">[ TODOS ]</option>
					<option value="FT">FACTURA</option>
					<option value="BV">BOLETA DE VENTA</option>
					<option value="NC">NOTA DE CREDITO</option>
					<option value="ND">NOTA DE DEBITO</option>
				</select>
			</div>
		</section>		
		<section>
			<div class="label-info-contenedor cliente">
				<label class="subtitulo">Cliente:</label>
				<label class="codigo">[ TODOS ]</label><input name="cliente" type="search" placeholder="Razón social o RUC/DNI">
				<button class="boton">Buscar</button>
				<button class="borrar" disabled="disabled">&times;</button>
			</div>
		</section>
		
		<section>
			<div class="label-info-contenedor vendedor">
				<label class="subtitulo">Vendedor:</label>
				<label class="codigo">[ TODOS ]</label><input name="vendedor" type="search" placeholder="Código o nombre">
				<button class="boton">Buscar</button>
				<button class="borrar" disabled="disabled">&times;</button>
			</div>		
		</section>

		<section>
			<div class="label-info-contenedor producto">
				<label class="subtitulo">Producto:</label>
				<label class="codigo">[ TODOS ]</label><input name="producto" type="search" placeholder="Código o descripción">
				<button class="boton">Buscar</button>
				<button class="borrar" disabled="disabled">&times;</button>
			</div>
		</section>
		</td>
		<td>&nbsp;</td>
		<td style="vertical-align:top">

		<section>

			<table width="98%" style="margin:auto">
			<tr>
				<td align="left" style="border:1px solid #f2f2f2; padding: 5px; width:200px">


					<style type="text/css">
					label.subtitulo { margin: 0 0 8px 3px; font-style: italic; color: #666; display: inline-block; text-decoration: underline}
					div.div-marca { text-align: center; position: relative; padding: 10px }
					hr.separado { margin: 10px auto; width: 90%; border:0 none; border-bottom: 1px solid #bbb }
					</style>

					<div class="div-marca">
						<label>Marca: 
							<select id="marca">
								<option value="">[ TODAS ]</option>
							</select>
						</label>
					</div>

					<hr class="separado">
					<label class="subtitulo">Opciones de facturaci&oacute;n</label><br>
					<input type="checkbox" id="diferida" value="S" name="diferida" />
					<label for="diferida">SOLO DIFERIDAS</label>
					<br>
					<input type="checkbox" id="diferida_pend" value="S" disabled  name="diferida_pend"/>
					<label for="diferida_pend">SOLO DIFERIDOS POR ATENDER</label>
					<br>
					<input type="checkbox" id="pendientes" value="S" name="pendientes"/>
					<label for="pendientes">DOCUMENTO <u>POR DESPACHAR</u></label>
				</td>
				<td>
					<table class="botonera">
					<tr>
						<td align="left" style="padding-left:5px">
							<button class="xboton ejecutar"><img src="img/screen.png">A pantalla</button>
							
							<style type="text/css">
							.contx1 { width: 100px; text-align: left; margin-top: 5px}
							.contx { width: 140px; text-align: left; margin-top: 5px}
							</style>
							
							<!--div class="contx1">
								<input type="radio" name="resumen1" value="S" id="chk_opcion1" checked />
								<label for="chk_opcion1">Detallado</label><br>

								<input type="radio" name="resumen1" value="S" id="chk_opcion2" />
								<label for="chk_opcion2">Resumen</label>							
							</div-->
						</td>
						<!--td align="left">
							<button class="xboton excel"><img src="img/excel.png">Enviar a Excel</button>
							<div class="contx">
								<input type="radio" name="resumen" value="S" id="chk_detallado" checked />
								<label for="chk_detallado">Detallado</label><br>

								<input type="radio" name="resumen" value="S" id="chk_detallado1" />
								<label for="chk_detallado1">Resumen</label><br>

								<input type="radio" name="resumen" value="S" id="chk_resumen" />
								<label for="chk_resumen">Resumen (cabecera)</label>
							</div>
						</td-->
					</tr>
					</table>
				</td>
			</tr>
			</table>

		</section>
		</td>
		</tr>
		</table>

	</div>

	<div id="busqueda">
		<div class="cerrarWrapper">[ &times; ] Cerrar ventana</div>
		<div class="wrapper">
		<table id="resultado" class="tablesorter">
		<thead><tr><th class="mini">C&oacute;digo</th><th class="mini">Descripci&oacute;n</th></tr></thead>
		<tbody></tbody>
		</table>
		</div>
	</div>

	<div id="respuesta" style="margin:10px auto 100px auto"></div>

</div>

<script>

	$(window).scroll(function(){
		if( $(this).scrollTop() > 100 ){
			$('.al_cielo').fadeIn(200);
		} else {
			$('.al_cielo').fadeOut(200);
		}
	});

	$('.al_cielo').click(function(){
		$('body, html').animate({scrollTop: '0px'}, 350);
		return false; 
	});

	$("#vtaxlciente_filtro").bind("keyup change",function(){
		$filtro = $.trim($(this).val().toUpperCase()); 
		$("section.seccion_documento").hide(); 
		
		$('section.seccion_documento').removeHighlight()
		if($filtro.length>0){ 
			$('section.seccion_documento > :not(th)')
			.highlight( $filtro );
		}

		$("section.seccion_documento").each(function(index){ 
			if($(this).is(':contains("'+$filtro+'")')){ $(this).show(); }   
		}); 
	})
</script>

<div id="vtaxcliente_footer">
Filtro: <input type="text" size="30" id="vtaxlciente_filtro">
</div>

<a class="al_cielo" href="#" title="Al cielo"><img src="img/al_cielo.png"></a>

