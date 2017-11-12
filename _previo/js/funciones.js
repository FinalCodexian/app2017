
    jQuery.expr[':'].contains = function(a, i, m) { 
      return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; 
    };


    var $options_block = {
        css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .8, 
            'border-radius': 5, 
            'line-height': '16px', 
            color: '#fff' 
        }, 
        message:'<img src="img/loading-boqueo.gif" style="padding:6px" /><br>Consultando informaci&oacute;n.<br>Espere un momento por favor.'
    };


    $.MonthPicker = {
        i18n: {
            year: "Año",
            prevYear: "Año anterior",
            nextYear: "Año siguiente",
            next5Years: 'Adelantar 5 años',
            backTo: 'Volver a ',
            prev5Years: 'Saltar 5 años',
            nextLabel: "Siguiente",
            prevLabel: "Anterior",
            buttonText: "Open Month Chooser",
            jumpYears: "Mostrar años",
            months: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Set.', 'Oct.', 'Nov.', 'Dic.']
        }
    };

    $.fn.xconfirmation = function(options){
        var opts = $.extend({}, $.fn.xconfirmation.defaults, options);
        var $xobj = $(this[0]); 
        var $tit = opts.titulo; 
        var $fn = opts.funcion; 
        var $id = opts.id; 
        var $url = opts.url; 

        $xobj.addClass("ximgConfirm"); 
        if($fn=='recargar'){$xobj.addClass("utilRefrescar")}
        if($fn=='info'){$xobj.addClass("utilInfo")}
        if($fn=='cerrar'){$xobj.addClass("utilCerrar")}

        $(':not(.ximgConfirm)').click(function() {
            $('.ximgConfirm .xarrow').fadeOut(150);
        });

        var $xcont = '<div class="xflot"><div class="xt1"></div><h1>'+ $tit +'</h1>'; 
        $xcont += '<button class="xaceptar">&#10004; Si</button><button class="xcancelar">&#10006; No</button>'; 
        $xcont += '</div></div>'; 

        $conten = $('<div class="xarrow"></div>').append($xcont); 

        $conten.appendTo($xobj)

        return this.each(function(){
            $(this).click(function(e){
                e.stopPropagation();
                if($fn!=='info'){
                    $('.ximgConfirm .xarrow').fadeOut(150);
                    $(this).find(".xarrow").toggle()
                }else{
                    var $ele = $(this).parent().parent(); 
                    var $cn = $ele.parent(); 
                    if($ele.next().prop('tagName')=="DIV" && $ele.next().hasClass("infoSeccion")) $ele.next().stop().slideToggle(200);  
                }
            })

            $(this).on("click",".xaceptar",function(e){
                e.preventDefault(); e.stopPropagation();
                var $cfc = $("#" + $id); // contenedor
                var $mn = $("a[href='"+$url+"']"); // link menu 

                if($fn=='recargar'){
                    $cfc.fadeOut(100, function(){
                        $mn.prop("disabled", true); 
                        $.post($url,{ruta: $url, id: $id},function(data){
                            $cfc.html(data).fadeIn(200); 
                            $mn.prop("disabled", false); 
                        })
                    })
                }

                if($fn=='cerrar'){
                    $cfc.fadeOut(100, function(){               
                        $cfc.html(" ").attr("cargado", "NO")
                        $mn.attr("cargado", "NO").attr("actual", "NO"); 
                    })
                }

            })

            $(this).on("click",".xcancelar",function(e){
                e.preventDefault(); e.stopPropagation();
                $('.ximgConfirm .xarrow').fadeOut(150);
            })

        })
    };


var getScript = jQuery.getScript;
jQuery.getScript = function( resources, callback ) {
    var // reference declaration & localization
    length = resources.length,
    handler = function() { counter++; },
    deferreds = [],
    counter = 0,
    idx = 0;
    for ( ; idx < length; idx++ ) {
        deferreds.push(
            getScript( resources[ idx ], handler )
        );
    }
    jQuery.when.apply( null, deferreds ).then(function() { callback && callback(); });
};


(function($){
$.fn.extend({
    altoContenido: function(options){
        var opts = $.extend({}, $.fn.altoContenido.defaults, options);
        var $xobj = $(this[0]); 
        var $newH = opts.contenedor.innerHeight()  - opts.ajuste;   
        $xobj.css({ "height": $newH, "min-height": $newH + " !important" }); 
    }
})
})(jQuery)
