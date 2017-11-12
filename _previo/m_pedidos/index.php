<!DOCTYPE html>
<html lang="en">
<head>


<?php
//setcookie("TestCookie", 'Lucho!');

//setcookie ("TestCookie", "", time() - 3600);

echo $_COOKIE["TestCookie"];
/*
session_start(); 

$_SESSION["usuario"] = $config["usuario_codigo"]; 
*/

$config["agencia_codigo"] = '9000'; 
$config["agencia_nombre"] = 'ARRIOLA 2291'; 
$config["almacen_codigo"] = '9001'; 
$config["almacen_nombre"] = 'LIM ARRIOLA 2291'; 
$config["costo"] = '500'; 

$config["usuario_codigo"] = 'LMVN'; 
$config["usuario_nombre"] = 'LUIS VENTURA NAQUIRA'; 


$fecha_sistema = getdate(); 
$fecha = date('j/m/y'); 

?>


	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes" /> 
	<meta http-equiv="Pragma" content="no-cache"> 
	<meta http-equiv="Expires" content="-1">

	<title>Pedido Standard (Descuentos)</title>
	<link rel="stylesheet" href="../../dist/css/select.css">
	<link rel="stylesheet" href="../../dist/css/jquery.qtip.min.css">
	<link rel="stylesheet" href="../../dist/css/theme.metro-dark.css">
	<link rel="stylesheet" href="../../dist/css/magnific-popup.css">
	<link rel="stylesheet" href="../../dist/css/animation.css">
	<link rel="stylesheet" href="../../dist/css/estilo.css">


	<script language="javascript" type="text/javascript" src="../../dist/js/jquery-1.12.1.js"></script>
	<script language="javascript" type="text/javascript" src="../../dist/js/jquery.tablesorter.js"></script>
	<script language="javascript" type="text/javascript" src="../../dist/js/jquery.tablesorter.widgets.js"></script>
	<script language="javascript" type="text/javascript" src="../../dist/js/jquery.magnific-popup.js"></script>
	<script language="javascript" type="text/javascript" src="../../dist/js/jquery.qtip.min.js"></script>




	<script type="text/javascript">
		$(function(){

			$(document)
			.ajaxStart(function(){ $("#loading").show();})
			.ajaxStop(function(){ $("#loading").hide(300);})


			var $cboAlmacen = $("#almacen"); 


			$('span.info').qtip({ // tooltip para iconos de ayuda (i)
				content: {
					attr: 'qContent',
			        title: function(event, api) { return $(this).attr('qTitle');}					
				},
				position: { my: 'center left', at: 'center right' },
			});

			$('span.ellipsis[qTitle]').qtip({
				content: {
					attr: 'qContent',
			        title: function(event, api) { return $(this).attr('qTitle');}					
				},		
				style: {classes:'qtip-dark' },
				position: { my: 'top left', at: 'bottom left' }
			});




			$.post('data.php', {opcion: 'almacenes', base:'JCHS2016', agencia: '9000'}, function(data, textStatus, xhr) {
				$.each($.parseJSON(data).rows, function(index, item) {
					$( "<option value='"+item.codigo+"'>"+item.codigo+" - "+ item.almacen +"</option>" )
					.appendTo( $cboAlmacen );
				})
				$cboAlmacen.val([]);
			});



			$('#btnCliente').magnificPopup({
				items: { src: '#popupCliente', type: 'inline' },
				preloader: true,
				removalDelay: 300,
				modal: true, 
				midClick: true, 
				mainClass: 'mfp-3d-unfold', 
				focus: '#buscarCliente', 
				callbacks: {
					beforeOpen: function(){
						$('#buscarCliente').val('');
						$('#tablaCliente').find('tbody').empty(); 
						$('#tablaCliente').trigger('updateAll'); 
					}
				}
			});


			$('#tablaProductos').tablesorter({
				theme: 'metro-dark',
				widthFixed : true,
				widgets: ['zebra', 'stickyHeaders'],
				widgetOptions: {
					stickyHeaders_attachTo: '.wrapper',
					stickyHeaders_includeCaption: true
				}
			});


			$('#tablaCliente').tablesorter({
				theme: 'metro-dark',
				widthFixed : true,
				widgets: ['zebra', 'stickyHeaders'],
				widgetOptions: {
					stickyHeaders_attachTo: '.wrapperCliente',
					stickyHeaders_offset: 0,
					stickyHeaders_includeCaption: true
				}
			});

			$(document).on('click', '.popup-modal-dismiss, .cerrarPopup', function (e) {
				e.preventDefault();
				$.magnificPopup.close();
			});		



			$(document).on('keypress', '#buscarCliente', function(event) {
				
				var code = (event.keyCode ? event.keyCode : event.which);
				var $buscar = $.trim( $(this).val().toUpperCase() );


				if(code == 13) { 

					$(this).blur(); // retirar el focus del input.. para evitar el doble ENTER 

					$("#tablaCliente").find('tbody').empty(); 
					$('#tablaCliente').trigger('updateAll'); 
					$('#tablaCliente').trigger('sortReset'); 


					$.post('data.php', {opcion: 'clientes', buscar: $buscar}, function(data, textStatus, xhr) {

						var resort = true; 

						$.each($.parseJSON(data).rows, function(index, item) {
							$fila = $('<tr></tr>')
							.append('<td align=center><a href="#" class="icon-seleccion">'+item.codigo+'</a></td>')
							.append('<td>'+item.cliente+'</td>')
							.append('<td>'+item.vendedor+'</td>')
							.appendTo( $("#tablaCliente").find('tbody') ); 
						});

						$('#tablaCliente').trigger('applyWidgets'); 
						$('#tablaCliente').trigger('updateAll'); 
						$('#tablaCliente').trigger('sortReset'); 
						
					});

				}
			});

		})
	</script>

<style id="css">

	/* wrapper of table 2 */
	.wrapper {
		position: relative;
		height: 250px; 
		overflow-x: auto;
		padding: 0px; 
	}

	/* Magnific popup */
	#popup {
		background: #FFF;
		padding: 10px;
		width: auto;

		max-width: 700px;
		margin: 10px auto;
	}
	a.popup-modal-dismiss {
		position: absolute; display: block;
		right: 0; top: 0;  
		padding: 7px 10px; 
		background-color: #eee; z-index: 999
	}

	span.dato {  
	margin-bottom: 2px;
	padding: 4px 8px;
	text-transform: uppercase;
	background-color: #e4e4e4;
	}



	button { 
		padding: 10px 14px; cursor: pointer; border: none; background-color: #06c; background-repeat: no-repeat;
		background-position: center center; 

	}

	button[disabled], button[disabled]:hover {background-color: #ccc; color: #808080; border-color: #a0a0a0; box-shadow: none; cursor: default;}


	button.text:hover {
		box-shadow: inset 0 0 0 0 rgba(0,0,0,.16), 0 0 15px 0 rgba(0,0,0,.15);
		transform: translateY(-3px);
		/*-webkit-transform: scale(1.15);*/
	}
	button.img:hover {border-radius:50%; box-shadow: 0 0 0 3px rgba(0,0,0,.03)}

	button[data='buscar']:hover {background-color: #004e9b;}
	button[data='añadir']:hover {background-color: #004e9b;}
	button[data='finalizar']:hover {background-color: #138022;}
	button[data='cancelar']:hover {background-color: #B60C0C;}


	button.text, button.img {
		width: 24px; height: 22px; line-height: 22px; margin:0px; padding:0px; display: inline-block; position: relative;
	}

	button.text { 
		width: auto; height: auto; 
		color: #eee; 
		margin: 2px; padding: 10px;
		transition-duration: 0.2s; box-shadow: inset 0 0 0 50px rgba(255,255,255,.1); border-radius:4px;
	}
	button.img {
		top: -1px;
		vertical-align: middle; 
		border-radius:4px;
		transition-duration: 0.15s;
	}

	button.img[data='buscar'] { 
		background-image:url('../../dist/img/svg_2/interface.svg');
		background-position: center center; 
		background-size: 14px; 
	}


	button.text[data='añadir'] {
		background-image:url('../../dist/img/svg_2/cross.svg');
		background-position: 10px center; 
		background-size: 20px; padding-left: 37px 	
	}

	button.text[data='finalizar'] {
		background-image:url('../../dist/img/svg_2/diskette.svg');
		background-position: 10px center; 
		background-color: #1FA732; 
		background-size: 20px; padding-left: 37px; 	
	}

	button.text[data='cancelar'] {
		background-image:url('../../dist/img/svg_2/exit.svg');
		background-position: 10px center; 
		background-color: #E31717; 
		background-size: 20px; padding-left: 37px 	
	}



	.ellipsis {
		display: inline-block;
		vertical-align: middle;
		height: 14px; 
		text-overflow: ellipsis;
		white-space: nowrap;
		overflow: hidden;
	}

	.ellipsis[data-width='100%']  { width: 100%} 
	.ellipsis[data-width='50px'] { width: 50px} 
	.ellipsis[data-width='60px'] { width: 60px} 
	.ellipsis[data-width='70px'] { width: 70px} 
	.ellipsis[data-width='80px'] { width: 80px}
	.ellipsis[data-width='90px'] { width: 90px}
	.ellipsis[data-width='100px'] { width: 100px}
	.ellipsis[data-width='120px'] { width: 120px}
	.ellipsis[data-width='150px'] { width: 150px}
	.ellipsis[data-width='200px'] { width: 200px}
	.ellipsis[data-width='250px'] { width: 250px}
	.ellipsis[data-width='300px'] { width: 300px}
	.ellipsis[data-width='350px'] { width: 350px}
	.ellipsis[data-width='400px'] { width: 400px}
	.ellipsis[data-width='450px'] { width: 450px}
	.ellipsis[data-width='500px'] { width: 500px}
	.ellipsis[data-width='550px'] { width: 550px}
	.ellipsis[data-width='600px'] { width: 600px}



	input[type='text'] {border: 1px solid #ccc; padding: 2px; height: 20px}
	textarea {border: 1px solid #ccc; padding: 3px}
	input[type="text"][required]:focus, textarea:focus { border-color: #06c; }




	i {color: #666}
	span.tab {width: 20px; display: inline-block;}
	* {text-rendering: optimizeLegibility; outline: none;}


	.button {
		display: inline-block;
		font-size: 11px; padding: 0; margin: 0; 
		border: 1px solid #bbb;
		padding: 0; height: 22px;  

		background: #f3f3f3; /* Old browsers */
		background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #e5e5e5)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* IE10+ */
		background: linear-gradient(to bottom, #ffffff 0%, #e5e5e5 100%); /* W3C */
	}


	/*#loading { position: absolute; display: none;}*/
	/*loading10*/

	#loading10 .shaft1{
		-webkit-animation-delay:0.1s;
	}
	#loading10 .shaft2{
		-webkit-animation-delay:0.2s;
	}
	#loading10 .shaft3{
		-webkit-animation-delay:0.3s;
	}
	#loading10 .shaft4{
		-webkit-animation-delay:0.4s;
	}
	#loading10 .shaft5{
		-webkit-animation-delay:0.5s;
	}
	#loading10 .shaft6{
		-webkit-animation-delay:0.6s;
	}
	#loading10 .shaft7{
		-webkit-animation-delay:0.7s;
	}


	@-webkit-keyframes loading10{
		0%{
			-webkit-transform:translateX(-6px);
			background-color: #333;
		}
		50%{
			-webkit-transform:translateX(6px);
			background-color: #fff;
		}
		100%{
			-webkit-transform:translateX(-6px);
			background-color: #333;

		}
	}

	#loading {display: inline-block; position: fixed; background-image: url('../../dist/img/bg.png'); 
	bottom: 0; left: 0; height: 100%; width: 100%; 
	background-color:rgba(0, 0, 0, 0.1); 
	z-index: 99999
	}

#loading .contenedor {background-color: #333; position: absolute; 
	bottom: 40px; left: 30px; 
	height: 45px;
	width: 450px; 
	box-shadow: 0 0 20px 10px rgba(0,0,0,.08); border-radius:5px;
}

#loading .mensaje {position: absolute; top: 5px; color: #333; 
	font-size: 12px; color: #F2F2F2; 
	margin: 11px 0 0 95px }

	#loading10{
		position:absolute; 
		top:20px; left: 20px;
		width: 80px;
		height: 10px; 
	}
	#loading10>div{
		top: 0px; 
		position: relative;
		float: left;
		width: 6px;
		height: 6px;
		border-radius: 100%;
		margin:0px 1px 0px 1px;
		background-color: #333;
		-webkit-animation:loading10 1s ease-in-out infinite;
		animation:loading10 1s ease-in-out infinite;
	}
	
	a.icon-seleccion {display: inline-block; padding:0 6px 0 22px; background-color: #06c;
		background-image: url("../../dist/img/svg_2/arrows.svg"); background-size: 12px; background-repeat: no-repeat;
		background-position: 4px center; color: #eee; border-radius: 3px; margin: 0 4px;}

	</style>

</head>
<body>



	<div class="content">

		<div id="loading">
			<div class="contenedor">
				<div class="mensaje">Espere un momento..</div>
				<div id="loading10">
					<div class="shaft1"></div>
					<div class="shaft2"></div>
					<div class="shaft3"></div>
					<div class="shaft4"></div>
					<div class="shaft5"></div>
					<div class="shaft6"></div>
					<div class="shaft7"></div>
				</div>
			</div>
		</div>


		<div style="padding:10px; display: none;">
			agencia: <?=$config["agencia_codigo"];?> - <?=$config["agencia_nombre"];?><br>
			almacen: <?=$config["almacen_codigo"];?> - <?=$config["almacen_nombre"];?><br>
			costo: <?=$config["costo"];?><br>
			usuario: <?=$config["usuario_codigo"];?> - <?=$config["usuario_nombre"];?><br>
			fecha sistema: <?=$fecha;?>
		</div>

		<style type="text/css">
			#footer { position: fixed; width: 100%; bottom: 0px; height: 30px;  background-color: #333; 
				z-index: 100; display:none}
		</style>
		<div id="footer">
		footer!
		</div>


		<div class="formulario">

			<style type="text/css">

				div.formulario { border: 1px solid #eee; width: 900px; 
					padding: 20px; padding-bottom: 5px;  
					margin: 20px auto; 
					border-radius: 4px;
					box-shadow: 0 0 30px 0 rgba(0,0,0, .1);
					position: relative; background-color:#F6F6F6
				}

				.tabla { width: 100%; border-collapse:collapse; border: 1px solid #cecece;}
				.tabla td { border-bottom: 1px solid #cecece; border-right: 1px solid #cecece;
					vertical-align: middle; 
					padding-top: 5px; padding-bottom: 4px}
					.icono-user {color: #F00; transform: scale(10%,10%),}

					.form-titulo { position: absolute; background-color: #06c; top: 0; left: 0; padding: 10px 20px; font-size: 14px;
						color: #eee; display: none;}

						label.etiqueta {min-width: 60px; display: inline-block; text-align: right; padding-left: 4px; padding-right: 4px }

						textarea {
							resize: none;
						}
					</style>

					<div class="form-titulo">
						Pedido Standar (Descuento de Precios)
					</div>

					<table class="tabla" cellpadding="5px">
						<tr>	
							<td colspan="2">
								<label class="etiqueta">Almac&eacute;n: </label>
								<div class="button custom-select">
									<select id="almacen"></select>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<label class="etiqueta">Cliente: </label> <span class='dato ellipsis' data-width='550px'
 								qTitle="dasd" qContent="">algun cliente donde podemos ver algo mas</span>
								<button id="btnCliente" class="img" data="buscar"></button>

								<span class='tab'></span>
								<label class="etiqueta">RUC:</label> <span class='dato ellipsis' data-width='100px'>20318171701</span>
							</td>
						</tr>


						<tr>
							<td colspan="2">
								<label class="etiqueta">Dir. Fiscal:</label>
								<span class='dato ellipsis' data-width='250px'>DIRECCION  otra cosa mas para aumtnerar DEL CLIENTE DONDE PODREMOS MOSTRAR CON ELLIPSIS</span>

								<span class='tab'></span>
								<label class="etiqueta">Vendedor:</label> <span class='dato ellipsis' data-width='150px'>FIORELLA LUIS YAÑEZ VALENZUELA</span>
								<button class="img" data="buscar"></button>

								<span class='tab'></span>
								<label class="etiqueta">Tipo Cliente:</label> <span class='dato ellipsis' data-width='120px'>distribuidor</span>

							</td>
						</tr>

						<tr>
							<td colspan="2">

								<label class="etiqueta">Moneda:</label>
								<div class="button custom-select">
									<select id="moneda">
										<option>US - DOLARES</option>
										<option>MN - SOLES</option>
									</select>
								</div>

								<span class='tab'></span>
								<label class="etiqueta">Forma Venta:</label> <span class='dato ellipsis' data-width='150px'>CONTADO.</span>
								<button class="img" data="buscar"></button>

								<span class='tab'></span>
								<label class="etiqueta">Vcmto:</label> <span class='dato ellipsis' data-width='60px'>00/00/0000</span>

								<span class='tab'></span>
								<label class="etiqueta">Nro. O/C:</label><input required='' type='text' size="20" value="">

							</td>
						</tr>

						<tr>
							<td>
								<label class="etiqueta">Dir. Entrega: </label> <span class='dato ellipsis' data-width='500px'>algun cliente donde podemos ver algo mas</span>
								<button class="img" data="buscar"></button>
							</td>
							<td rowspan="2">
								<label style="padding:2px; display:inline-block">Glosa:</label><br>
								<textarea rows="3" cols="35"></textarea>
							</td>
						</tr>

						<tr>
							<td>
								<label class="etiqueta">Agencia de Transporte: </label><span class='dato ellipsis' data-width='450px'>algun cliente donde podemos ver algo mas</span>
								<button class="img" data="buscar"></button>
							</td>
						</tr>

						<tr>
							<td colspan="2" style="padding:0">

								<div class="wrapper">
									<table id="tablaProductos" class="tablesorter">
										<thead>
											<tr>
												<th data-sorter="false">C&oacute;digo</th>
												<th data-sorter="false">Producto</th>
												<th data-sorter="false">P. Unitario</th>
												<th data-sorter="false">Cant.</th>
												<th data-sorter="false" class="descuento">% Dscto</th>
												<th data-sorter="false" class="descuento">Importe</th>
												<th data-sorter="false">Total</th>
												<th data-sorter="false">Opc.</th>

											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>

							</td>
						</tr>

					</table>


					<style type="text/css">
						div.controlBox {padding:5px; text-align: center; text-align: center;}
						div.resumen { position: relative;display: inline-block; background-color: #eee; padding: 13px 10px; margin: 0px 10px;
							top: 1px;
						}
						button.img { vertical-align: middle;}
					</style>

					<div class="controlBox">
						<button class="text" data='añadir'>Añadir producto</button>

						<div class="resumen">
							Items: _____
							<span class='tab'></span>
							Cant: _____
							<span class='tab'></span>
							Valor. Vta: __________
							<span class='tab'></span>
							IGV: _______
							<span class='tab'></span>
							TOTAL: __________
						</div>

						<button class="text" data='finalizar'>Finalizar</button>
						<button class="text" data='cancelar'>Cancelar</button>
					</div>

					
				</div>

			</div>


			<style type="text/css">
				.tablesorter-wrapper {
					position: relative;
					padding: 0px; 
					height: 350px; 
					overflow-x: auto;
				
				}
				#popupCliente { 
					background-color: #fff; 
					margin: auto; 
					width: 800px; position: relative;
				}

				.wrapperCliente {height: 350px}

				#popupCliente button.cancelar {
					position: absolute; top: 0px; right: 0px; margin: 4px; 
					padding: 8px 15px; background-color:#f8f8f8 ; border: 1px solid #ccc
				}
				span.info {
					width: 20px; height: 17px; display: inline-block;
					vertical-align:middle; position: absolute; 
					margin-left: -24px; 
					margin-top: 3px; 
					border-radius: 2px; 
					background-image: url('../dist/img/svg_2/logo.svg');
					background-color: #f8f8f8; 
					background-size: 12px; background-repeat: no-repeat; background-position: center center;
					cursor: help;
				}
				input.inputInfo { padding: 1px 26px 1px 4px}



			</style>

			<div id="popupCliente"  class="mfp-hide mfp-with-anim">
				<button class='cancelar cerrarPopup'>&times; Cerrar b&uacute;squeda</button>
				<div style="padding: 14px">

					<label>
						Buscar por Raz&oacute;n Social o RUC: 
						<input type="text" size="30" id="buscarCliente" class="inputInfo">
						<span class='info' qTitle='B&uacute;squeda de cliente' qContent='Debe ingresar por lo menos <strong>3 caracteres</strong>'></span>
					</label>
					
				</div>
				<div class="tablesorter-wrapper wrapperCliente">
					<table id="tablaCliente" class="tablesorter">
						<thead>
							<tr>
								<th width="120px">RUC</th>
								<th>Cliente</th>
								<th>Vendedor</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>

		</body>
		</html>