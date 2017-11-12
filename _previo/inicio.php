<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" /> 
<meta http-equiv="Pragma" content="no-cache"> 
<meta http-equiv="Expires" content="-1">
<link rel='icon' type='image/png' href='favicon.png'>

<title>JCH Llantas - INTRANET 2017</title>
<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<!--link rel="stylesheet" href="css/morris.css"-->
<link rel="stylesheet" href="css/hamburgers.css">
<link rel="stylesheet" href="css/theme.metro-dark.css">
<link rel="stylesheet" href="css/site.css">
<link rel="stylesheet" href="css/inicio.css">
<link rel="stylesheet" href="css/MonthPicker.min.css">
<link rel="stylesheet" href="css/ion.checkRadio.css">
<link rel="stylesheet" href="css/ion.checkRadio.html5.css">


<script language="javascript" type="text/javascript" src="js/jquery-1.12.1.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.validate.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
<script language="javascript" type="text/javascript" src="js/highlight.js"></script>

<script language="javascript" type="text/javascript" src="js/jquery-ui.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui.datepicker-es.js"></script>



<script language="javascript" type="text/javascript" src="js/jquery.binarytransport.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.form.js"></script>
<script language="javascript" type="text/javascript" src="js/MonthPicker.min.js"></script>
<script language="javascript" type="text/javascript" src="js/ion.checkRadio.js"></script>
<script language="javascript" type="text/javascript" src="js/funciones.js"></script>

<script type="text/javascript">


	$(document).ready(function(){

		$(".hamburger").addClass("is-active");
    $(".botonSidebar").css('position','fixed');

		$(".hamburger").click(function(e){
			e.preventDefault(); 

			if($(this).hasClass('is-active')){
        
				$(this).removeClass("is-active");
				$("#dinamico, #header").stop().animate({marginLeft: 0}, 150, function(){
					$("#sidebar").stop().animate({"width": "0"},100,function(){
            $(".botonSidebar").css('position','absolute');
          })
					$("span.libre").css("display","inline-block").show();

				});					

			}else{
        
        $(".botonSidebar").css('position','fixed');
				$(this).addClass("is-active");

				$("#sidebar").stop().animate({width: 250}, 150, function(){
					$("#dinamico, #header").stop().animate({"marginLeft": "250px"}, 100)
				});					
				$("span.libre").hide();

			}

		})
		

		$("#accordian h3").click(function(){
			$("#accordian ul ul").slideUp();
			if(!$(this).next().is(":visible")) $(this).next().slideDown();
		})

    $(".inner, .ventana").click(function(e){
      var $refe = $(this).attr("href"); 
      if( $refe.substring($refe.length - 1)=="#") return false; 
    })

		if("<?=$_GET['secc']?>"!=""){
      $.post("<?=$_GET['secc'];?>",{sub: "<?=$_GET[sub];?>"}, function(data){
				 $("#dinamico").html(data)
			})			
		}
		$("#accordian ul li").find("[class='activo']").parent().slideDown(450); 


	})


</script>
</head>
<body>

<div class="botonSidebar">
  <div class="hamburger hamburger--vortex">
    <div class="hamburger-box">
      <div class="hamburger-inner"></div>
    </div>
  </div>
</div>

<div id="sidebar">
	<?
	$sub = $_GET["sub"]; 
	?>

	<div class="usuario">Men&uacute; Principal</div>

	<div id="accordian">				
	<ul>
	<?php
		require_once("lib/menu.php"); // archivo de configuracion del menu
		$i = 0; 
		foreach($menu as $key=>$item):
			echo "<li><h3>" . $key . "</h3><ul>"; 
			foreach($item as $k=>$h):
				echo (strpos($h,$_GET["secc"])!==false) ?  "<li class='activo'>" : "<li>"; 
				echo "<a class='inner' href='?secc=".$h."&sub=".$sub."' target='_self'>".$k."</a>"; 
				echo "<a class='ventana' href='?secc=".$h."&sub=".$sub."' target='_blank' title='Abrir en nueva ventana'><span></span></a>"; 
				echo "</li>"; 
				$i++; 
			endforeach; 
			echo "</ul></li>"; 
		endforeach;
	?>
	</ul>
	</div>


</div>

<div id="header">
	<ul class="menu">
		<li><span class="libre"></span></li>
		<li><a href="inicio.php?sub=jch_">Inicio</a></li>
		<li><a href="javascript:void(0);" class="seccion"></a></li>
		<li style="float:right">

      <a style="padding:10px" class="active" href="#"><img src="img/user.png" width="22px" align="absmiddle" title="<?=$_COOKIE[$_GET["sub"]."usuario_nombre"];?>"></a>
    </li>
		<li style="float:right"><span class=agencia><?=$_COOKIE[$sub."empresa_nombre"];?> &bull; <?=$_COOKIE[$_GET["sub"]."agencia_nombre"];?></span></li>
	</ul>
</div>
<div id="dinamico">
	
	<!--div id="myfirstchart" style="height: 280px; width: 380px; float:left"></div>
	<div id="hero-area" style="height: 280px; width: 380px; float:left"></div>
	<div id="hero-donut" style="height: 280px; width: 380px; float:left"></div-->

<script>

/*
  var tax_data = [
       {"period": "2011 Q3", "licensed": 3407, "sorned": 660},
       {"period": "2011 Q2", "licensed": 3351, "sorned": 629},
       {"period": "2011 Q1", "licensed": 3269, "sorned": 618},
       {"period": "2010 Q4", "licensed": 3246, "sorned": 661},
       {"period": "2009 Q4", "licensed": 3171, "sorned": 676},
       {"period": "2008 Q4", "licensed": 3155, "sorned": 681},
       {"period": "2007 Q4", "licensed": 3226, "sorned": 620},
       {"period": "2006 Q4", "licensed": 3245, "sorned": null},
       {"period": "2005 Q4", "licensed": 3289, "sorned": null}
  ];
  
  Morris.Line({
    element: 'myfirstchart',
    data: tax_data,
    xkey: 'period',
    ykeys: ['licensed', 'sorned'],
    labels: ['Licensed', 'Off the road'],
    hideHover: 'auto'
  });

  Morris.Area({
    element: 'hero-area',
    data: [
      {period: '2010 Q1', iphone: 2666, ipad: null, itouch: 2647},
      {period: '2010 Q2', iphone: 2778, ipad: 2294, itouch: 2441},
      {period: '2010 Q3', iphone: 4912, ipad: 1969, itouch: 2501},
      {period: '2010 Q4', iphone: 3767, ipad: 3597, itouch: 5689},
      {period: '2011 Q1', iphone: 6810, ipad: 1914, itouch: 2293},
      {period: '2011 Q2', iphone: 5670, ipad: 4293, itouch: 1881},
      {period: '2011 Q3', iphone: 4820, ipad: 3795, itouch: 1588},
      {period: '2011 Q4', iphone: 15073, ipad: 5967, itouch: 5175},
      {period: '2012 Q1', iphone: 10687, ipad: 4460, itouch: 2028},
      {period: '2012 Q2', iphone: 8432, ipad: 5713, itouch: 1791}
    ],
    xkey: 'period',
    ykeys: ['iphone', 'ipad', 'itouch'],
    labels: ['iPhone', 'iPad', 'iPod Touch'],
    pointSize: 3,
    hideHover: 'auto'
  });


  Morris.Donut({
    element: 'hero-donut',
    data: [
      {label: 'Jam', value: 25 },
      {label: 'Frosted', value: 40 },
      {label: 'Custard', value: 25 },
      {label: 'Sugar', value: 10 }
    ],
    formatter: function (y) { return y + "%" }
  });

*/

</script>



	<script src="js/highcharts.js"></script>
	<script src="js/exporting.js"></script>

	<div id="container" style="width: 50%; height: 300px; margin: 0 auto"></div>

<script>


$(function () {
  if("<?=$_GET['secc']?>"==""){
/*
    $('#container').highcharts({  	
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'Average fruit consumption during one week'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 80,
            y: 50,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday'
            ],
            plotBands: [{ // visualize the weekend
                from: 4.5,
                to: 6.5,
                color: 'rgba(68, 170, 213, .2)'
            }]
        },
        yAxis: {
            title: {
                text: 'Fruit units'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' units'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{
            name: 'John',
            data: [3, 4, 3, 5, 4, 1, 12]
        }, {
            name: 'Jane',
            data: [1, 3, 4, 3, 3, 25, 4]
        }]
    });

*/
  }
});

</script>
</div>
</body>
</html>