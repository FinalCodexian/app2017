<?php
$sub = $_POST["sub"]; 
$data = "m_clientes/data.php"; 
?>

<style type="text/css">
	table.contenido { width:100%; height:100%}
	table.contenido td {vertical-align:top}
</style>


<script language="javascript">
$(function(){
	$("a.seccion").html("CLIENTES").animate({opacity: 1},400);  // titulo de la seccion

	$("#seccion-buscar").altoContenido({contenedor: $(window), ajuste: 70});
	$(".wrpBuscar").altoContenido({contenedor: $(window), ajuste: 140});
	
	var $tablaBuscar = $("#buscarCliente"); 
	var $tablaDirecciones = $("#tabla-direcciones"); 
	var $tablaTransportes = $("#tabla-transportes"); 
	var $tablaContactos = $("#tabla-contactos"); 
	
	var $valido = $('#formBuscar').validate({
	errorClass: 'error', 
	rules: {
		buscar: {required: true, minlength: 3}
	},
	submitHandler: function(form) {

		$.ajax({
			type: "POST",
			url: "<?=$data;?>",
			data: $(form).serialize(),
			beforeSend: function(){
				$("input").blur();  // focus a ningun input!!
				$("#seccion-buscar").block( $options_block );
				$tablaBuscar.trigger('sortReset');
				$tablaBuscar.find("tbody").html(" ");
			},
			success: function(d) {
				var response = $.parseJSON(d);
				var $ruc = ""; 
				$.each(response.rows, function(index, item){
					$ruc = item.codigo; 
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center><a class=vv href=#>"+item.codigo+"</a></td>"); 
					$fila.append("<td>"+item.nombre+"</td>"); 
					$fila.appendTo($tablaBuscar);
				});	
				$tablaBuscar.trigger("update");
				$('#seccion-buscar').unblock(); 

				if(response.total==1){
					$("#tab1").prop("checked", "checked")
					$('#dinamico').animate({ scrollTop: 0 }, 850)
					
					$(".tab").block( $options_block );
					setDetalles($ruc);
					setDirecciones($ruc);
					setTransportes($ruc);
					setContactos($ruc);					
					
				}
				
			}
		});

	},
	errorPlacement: function(error,element) {return true;}
	})


	$tablaBuscar.tablesorter({
		theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
		widgetOptions: { stickyHeaders_attachTo : $(".wrpBuscar"), stickyHeaders_zIndex: 9 }
	});
		
	$tablaDirecciones.tablesorter({
		theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
		widgetOptions: { stickyHeaders_attachTo : $(".wrpDirecciones"), stickyHeaders_zIndex: 9 }
	});	

	$tablaTransportes.tablesorter({
		theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
		widgetOptions: { stickyHeaders_attachTo : $(".wrpTransportes"), stickyHeaders_zIndex: 9 }
	});
		
	$tablaContactos.tablesorter({
		theme: 'metro-dark', widthFixed : true, widgets: ['zebra','stickyHeaders'], 
		widgetOptions: { stickyHeaders_attachTo : $(".wrpContactos"), stickyHeaders_zIndex: 9 }
	});	

	
	
	// Detalles del cliente x codigo de cliente (RUC)
	$tablaBuscar.on("click","a",function(e){
		$("#tab1").prop("checked", "checked")
		$('#dinamico').animate({ scrollTop: 0 }, 850); 
		var $ruc = $(this).text(); 

		$(".tab").block( $options_block );
		setDetalles($ruc);
		setDirecciones($ruc);
		setTransportes($ruc);
		setContactos($ruc);

	})
	
	
	var setContactos = function($ruc){
		$.ajax({
			type: "POST",
			url: "<?=$data;?>",
			data: { opcion: 'contactos', ruc: $ruc },
			beforeSend: function(){
				$tablaContactos.trigger('sortReset');
				$tablaContactos.find("tbody").html(" ");
			},
			success: function(d) {
				var response = $.parseJSON(d);
				$.each(response.rows, function(index, item){
					var $fAval = (item.aval=="S") ? "&#10004;" : ""; 
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center>"+$fAval+"</td>"); 
					$fila.append("<td>"+item.nombres+"</td>"); 
					$fila.append("<td>"+item.direccion+"</td>"); 
					$fila.append("<td>"+item.cargo+"</td>"); 
					$fila.append("<td>"+item.docu+"</td>"); 
					$fila.append("<td>"+item.correo+"</td>"); 
					$fila.append("<td>"+item.telefono+"</td>"); 
					$fila.appendTo($tablaContactos);

				});

				$tablaContactos.trigger("update");
				$(".tab").unblock(); 
			}
		});		
	}
	
	// Direccion alternas del clientes segun codigo del cliente (RUC)
	var setDirecciones = function($ruc){
		$.ajax({
			type: "POST",
			url: "<?=$data;?>",
			data: { opcion: 'direcciones', ruc: $ruc },
			beforeSend: function(){
				//$tablaDirecciones.parent().block( $options_block );
				$tablaDirecciones.trigger('sortReset');
				$tablaDirecciones.find("tbody").html(" ");
			},
			success: function(d) {
				var response = $.parseJSON(d);
				$.each(response.rows, function(index, item){
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center>"+item.codigo+"</td><td>"+item.direccion+"</td>"); 
					$fila.appendTo($tablaDirecciones);
				});	
				$tablaDirecciones.parent().unblock(); 
				$tablaDirecciones.trigger("update");
				
			}
		});
		
	}
	
	var setDetalles = function($ruc){

		$.ajax({
			type: "POST", url: "<?=$data;?>", data: { opcion: 'detalle', ruc: $ruc },
			beforeSend: function(){
				//$(".seccion:eq(0)").block( $options_block );
				$( "[id^='c_']" ).html(" "); 
			},
			success: function(d) {
				var response = $.parseJSON(d);
				$.each(response.rows, function(index, item){
					$('#c_linea_credito').html(item.linea_credito);
					$('#c_codigo').html(item.codigo);
					$('#c_nombre').html(item.nombre);
					$('#c_ruc').html(item.ruc);
					$('#c_direccion').html(item.direccion);
					$('#c_departamento').html(item.departamento);
					$('#c_provincia').html(item.provincia);
					$('#c_distrito').html(item.distrito);
					$('#c_telefono').html(item.telefono);
					$('#c_fax').html(item.fax);
					$('#c_tipo_cliente').html(item.tipo_cliente);
					$('#c_vendedor').html(item.vendedor);
					$('#c_forma_venta').html(item.forma_vta);
					$('#c_tipo_precio').html(item.tipo_precio);
					$('#c_afecto').html(item.afecto);
					$('#c_tipo_percep').html(item.tipo_percep);
					$('#c_usuario_creacion').html(item.usuario_creacion);
					$('#c_fec_creacion').html("("+item.fec_creacion+")");
					$('#c_usuario_modifica').html(item.usuario_modifica);
					$('#c_fec_modifica').html("("+item.fec_modifica+")");
					$('#c_comentario').html(item.comentario);
					$('#c_telefono').html(item.telefono);
					$('#c_email').html(item.email);
				});	
				$('.seccion:eq(0)').unblock(); 

			}
		});		
	}
	
	
	var setTransportes = function($ruc){

		$.ajax({
			type: "POST", url: "<?=$data;?>", data: { opcion: 'transportes', ruc: $ruc },
			beforeSend: function(){
				//$tablaTransportes.parent().block( $options_block );
				$tablaTransportes.trigger('sortReset');
				$tablaTransportes.find("tbody").html(" ");
			},
			success: function(d) {
				var response = $.parseJSON(d);
				$.each(response.rows, function(index, item){
					$fila = $("<tr></tr>"); 
					$fila.append("<td align=center>"+item.codigo+"</td>")
					$fila.append("<td>"+item.ruc+"</td>")
					$fila.append("<td>"+item.transporte+"</td>")
					$fila.append("<td>"+item.direccion+"</td>")
					$fila.appendTo($tablaTransportes);
				});	
				$tablaTransportes.trigger("update");
				$tablaTransportes.parent().unblock(); 
			}
		});
		
	}

	
	//$(".content").index(2).css("display", "block")


})
</script>
</head>
<body>

    <style>
	
    .tab {margin: 0 6px; min-width: 320px; max-width: 1200px; }
    .content {background: #fff; color: #373737; border-top: 3px solid #06c; padding: 10px}
    .content p {margin: 0; line-height: 1.5; padding: 0}
    .content > div {display: none;}

    .tab input {display: none;}
    .tab label {display: inline-block; padding: 15px 25px; font-weight: 600; text-align: center; color: #aaa}
    .tab label:hover {color: #666; cursor: pointer; background-color: #f2f2f2; border-radius: 6px 6px 0 0}
    .tab input:checked + label {background: #06c; color: #fff; border-radius: 6px 6px 0 0}

    #tab1:checked ~ .content #content1,
    #tab2:checked ~ .content #content2,
    #tab3:checked ~ .content #content3,
    #tab4:checked ~ .content #content4 {
      display: block;
    }

    @media screen and (max-width: 400px) { label {padding: 15px 10px;} }


	div.seccion { margin-bottom: 6px; padding-bottom:10px}		
	#seccion-buscar {
		background-color: #f6f6f6; 
		margin:0 
		
		width:350px; 
		z-index:10; 
	}

	span[id^='c_'] { color:#06c}
	.wrpBuscar { margin:8px; }
	.wrpDirecciones, .wrpContactos, .wrpTransportes { height:400px; width:100% }		


	/*** GENERALES ***/

	#seccion-buscar, .wrpBuscar {}

	#seccion-buscar h2 {font-size:16px; margin:6px; font-weight:200; color:#06c; display:inline-block}

	table.datos {width: 100%; border-collapse: separate; border-spacing: 5px; margin:0 auto; border:1px solid #CCC}

	td.etiqueta { color: #899; width: 100px; padding:5px 8px; text-align:right;}
	td.dato { color: #06c; padding: 5px 8px; background-color:#f8f8f8;}
	td.dato, td.etiqueta { border:0 none; line-height: 16px; cursor: default; vertical-align: middle;}	
	#tab-datos {position:relative; height:98%;}

	div.div-form { padding:5px; text-align:center}

	.seccion h3 { font-size:14px; margin:6px; border-radius: 5px;  
	font-weight:100; padding:7px 14px; color:#06c; background-color:rgba(0,0,0,0.03); display:inline-block;  }

	.error{border:1px solid #f00 !important; background-color: #FF9}

	/*** GENERALES: ending ***/

</style>     


<table class="contenido">
<tr>
<td width="350px" style="min-width:350px; position:relative">
    
	<div id="seccion-buscar">
	<center>
	<h2>B&uacute;squeda de clientes</h2>
	</center>
	<form id="formBuscar" autocomplete=off>
	<div class="div-form">
	<label>Buscar cliente
	<input type="search" name="buscar" size="25"  placeholder="RUC o Razon social" autocomplete="off" value="">
	<input type="hidden" name="opcion" value="consulta">
	</label>
	<button>Buscar</button>
	</div>
	</form>

	<div class="wrapper wrpBuscar">
	<table id="buscarCliente" class="tablesorter">
	<thead><tr>
	<th>RUC / DNI</th>
	<th>Nombre / Raz&oacute;n Social</th>
	</tr></thead>
	<tbody></tbody>
	</table>
	</div>
	</div>

</td>

<td style="padding:7px 0px">

    <div class="tab">

		<input id="tab1" type="radio" name="tabs" checked><label for="tab1">Datos principales</label>
		<input id="tab2" type="radio" name="tabs"><label for="tab2">Direcciones de entrega</label>
		<input id="tab3" type="radio" name="tabs"><label for="tab3">Agencias de transporte</label>
		<input id="tab4" type="radio" name="tabs"><label for="tab4">Contactos</label>

      <div class="content">
        <div id="content1">
            <div class="seccion">
                <div>
                <table class="datos">
                <tr>
                    <td class="etiqueta">Codigo Cliente</td><td class="dato" id="c_codigo"></td>
                    <td class="etiqueta">Doc. N&deg;</td><td class="dato" id="c_ruc"></td>
                    <td class="etiqueta">Tipo Cliente</td><td class="dato" id="c_tipo_cliente"></td>
                </tr>
                <tr><td class="etiqueta">Razon Social</td><td colspan="5" class="dato" id="c_nombre"></td></tr>
                <tr><td class="etiqueta">Direccion Fiscal</td><td colspan="5" class="dato" id="c_direccion"></td></tr>		    		
                <tr>
                <td class="etiqueta">Forma Venta</td><td class="dato" id="c_forma_venta"></td>
                <td class="etiqueta">Tipo Precio</td><td class="dato" id="c_tipo_precio"></td>                
                </tr>

                <tr>
                	<td class="etiqueta">Linea Cr&eacute;dito US$</td><td class="dato" id="c_linea_credito"></td>
                	<td class="etiqueta">Vendedor</td><td class="dato" id="c_vendedor"></td>
                </tr>
                <tr><td class="etiqueta">Comentario</td><td colspan="5" class="dato" id="c_comentario"></td></tr>

				<tr>
                <td class="etiqueta">Telefono</td><td class="dato" id="c_telefono"></td>
                <td class="etiqueta">e-mail</td><td class="dato" id="c_email" colspan="3"></td>
                </tr>
                                
                <tr>                
                <td colspan="8" align="center" style="padding:10px 0">
                Creacion: <span id="c_usuario_creacion"></span> <span id="c_fec_creacion"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Modificacion: <span id="c_usuario_modifica"></span> <span id="c_fec_modifica"></span>
                </td>
                </tr>
                </table>
                </div>
			</div>

            

        </div>

        <div id="content2">
        <p>
			<div class="seccion">
                <div class="wrapper wrpDirecciones">
                <table id="tabla-direcciones" class="tablesorter">
                    <thead><tr><th>Cod.</th><th>Direccion</th></tr></thead>
                    <tbody></tbody>
                </table>
                </div>    
            </div>
        </p>
        </div>

        <div id="content3">
        <p>

			<div class="seccion">
                <div class="wrapper wrpTransportes">
                <table id="tabla-transportes" class="tablesorter">
                    <thead><tr>
                    	<th>Cod.</th>
                        <th>RUC</th>
                        <th>Agencia</th>
                        <th>Direccion</th>
                    </tr></thead>
                    <tbody></tbody>
                </table>
                </div>    
            </div>

        </p>
        </div>

        <div id="content4">
        <p>
            <div class="seccion">
                <div class="wrapper wrpContactos">
                <table id="tabla-contactos" class="tablesorter">
                    <thead>
                        <tr>
                            <th width="50px">Aval</th>
                            <th class=mini>Nombres</th>
                            <th class=mini>Direcci&oacute;n</th>
                            <th class=mini>Cargo</th>
                            <th class=mini>Documento</th>
                            <th class=mini>Correo</th>
                            <th class=mini>Tel&eacute;fono</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </p>

        </div>

      </div>

    </div>



</td>

</tr>
</table>




