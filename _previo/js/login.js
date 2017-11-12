
var Login = function () {
	var handleLogin = function(){

		$("button[type=submit]").prop("disabled",false);
		$("select[name='_base']").prop("disabled",false);

		$.extend({
			ucwords : function(str) {
			    strVal = '';
			    str = str.split(' ');
			    for (var chr = 0; chr < str.length; chr++) {
			        strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
			    }
			    return strVal
			}
		});


		var $mensaje = $("#mensaje"); 

		var $valido = $('#login-form').validate({
		    errorClass: 'error', 
		    rules: { _usuario: {required: true}, _clave: {required: true}},
		    
			submitHandler: function(form) {
				$mensaje.html("Espere un momento...").show(); 
				
				$("input[name='empresa']").val( $("select[name='_base'] option:selected").text() ); 
				$("input[name='sub']").val( $("select[name='_base'] option:selected").attr("sub") ); 
				$("input[name='usuario']").val( $("input[name='_usuario']").val() ); 
				$("input[name='pass']").val( $("input[name='_clave']").val() ); 
				$("input[name='base']").val( $("select[name='_base'] option:selected").val() ); 

				$("input[name='_usuario']").prop("disabled",true);
				$("input[name='_clave']").prop("disabled",true);
				$("button[type=submit]").prop("disabled",true);
				$("select[name='_base']").prop("disabled",true);


				$.ajax({
					type: 'POST',
					url: 'login.php',
					data: $(form).serialize(),
					success: function(d) { 

						var obj = $.parseJSON(d);

						if(obj.estado=="true"){

							var $today = new Date(); 
							var $saludo = ""; 
							if(($today.getHours() >=0) && ($today.getHours() <=11)) $saludo = "Buen día"; 
							if(($today.getHours() >=12) && ($today.getHours() <=18)) $saludo = "Buenas tardes"; 
							if(($today.getHours() >=19) && ($today.getHours() <=23)) $saludo= "Buenas noches";

							$mensaje
							.removeClass('error')
							.addClass('correcto')
							.html($saludo + " <i>"+ $.ucwords((obj.rows[0].usuario_nombre).toLowerCase())+"</i>")
							.slideDown('400', function(){

								var $barra = $("#progreso .barra"); 
								var $id = setInterval(frame, 30);
								var $width = 0;
								$("#progreso").show(); 

								function frame() {
									if ($width >= 100) {
										clearInterval($id);
										location.href = "inicio.php?sub="+obj.rows[0].sub;
									} else {
										$width++;
										$barra.css("width", $width+"%"); 
									}
								}

							});

						}else{

							$mensaje
							.addClass('correcto')
							.addClass('error')
							.html("Datos incorrectos. Compruebe sus datos para ingresar")
							.slideDown('400'); 

							$("select[name='base']").prop("disabled",false);
							$("input[name='_usuario']").prop("disabled",false).val("").focus();
							$("input[name='_clave']").prop("disabled",false).val("");
							$("button[type=submit]").prop("disabled",false);
							$("select[name='_base']").prop("disabled",false);

							$("input[name='usuario']").val("");
							$("input[name='clave']").val("");
							$("input[name='base']").val("");

							//$boton.prop("disabled", false); 
						}

					} 
				}); 

				//$valido.resetForm();
			},
		    errorPlacement: function(error,element) {return true;}, 
		})


		var fnAcceder = function(){
			$("input[name='usuario']").prop("disabled",true);
			$("input[name='clave']").prop("disabled",true);
			$("input[type='checkbox']").prop("checked",true);
			return false; 			
		}
		
		$(document).bind('keydown', 'Alt+f1', fnAcceder);
		$("input,select").bind('keydown', 'Alt+f1', fnAcceder);
		$("input[type='checkbox']").removeAttr("checked");


		$.backstretch([
			"img/bg-login/1.jpg", 
			"img/bg-login/2.jpg",
			"img/bg-login/3.jpg"
			
		], {
		      fade: 1000,
		      duration: 5000
		});

	}

    return {
        
        init: function () {
        	
            handleLogin();
	       
        }

    };

}();