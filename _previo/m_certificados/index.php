
<style type="text/css">
	.certificados { padding:0; text-align: center; width: 86%; margin: auto; cursor: default;}
	.certificados h1 { font-size: 18px; font-weight: 100; margin: 20px}	
	.certificados ul { position: relative; margin:0; padding:0; width: 100%; list-style-type: none;}
	.certificados ul li { vertical-align: bottom; text-align: left;
		border: 1px solid #bbb; margin: 3px; background-color: #eee; 
		padding: 4px
	}

	.certificados ul li div { width: calc(100% - 120px); margin-left: 120px}
	.certificados ul li:hover { background-color: #fff}

	.certificados ul li span { font-size: 12px; display: block; padding: 6px 0; color: #06c;  float: left; 
		width: 120px; text-align: center;
	}
	.certificados ul li a { display: inline-block; color: #333; padding: 4px; border: 1px solid #ccc; border-radius: 6px; padding: 6px 8px; 
		margin: 1px 2px; text-decoration: none;
	}
	.certificados ul li a:hover { background-color: #06c; color: #eee; }
	
	.box { margin: 10px 0 15px 0; background-color: #FFF9DB; width: 250px; padding: 5px; border-radius: 10px}
	.box input { font-size: 16px; border: 0; border-bottom: 1px solid #666; background-color: transparent;}

</style>

<script type="text/javascript">

	$(function(){
		$filtro = $("#filtrar"); 

		$(document).on('keyup', $filtro, function(e) {

			if (e.keyCode == 27) { $filtro.val("")  }

			var $buscar = $filtro.val().toUpperCase(); 
			$('.listado li span').each(function(){
				if($(this).text().toUpperCase().indexOf($buscar) > -1){
					$(this).parent().slideDown(200); 
				}else{
					$(this).parent().slideUp(150);
				} 
			});
		})

	}); 

</script>

<div class="certificados">
	<h1>CERTIFICADOS ISO / OHSAS</h1>

	<div class="box">
		<label>Filtrar:
			<input id="filtrar" placeholder="Filtrar por marca">
		</label>
	</div>

	<ul class="listado">	
		<li>
			<span>ACCELERA</span>
			<div>
				<a href="m_certificados/ACCELERA_ISO 9001.pdf" download="ACCELERA_ISO 9001.pdf">ISO 9001</a>
			</div>
		</li>

		<li>
			<span>ADVANCE</span>
			<div>
				<a href="m_certificados/ADVANCE_ISO9001.pdf" download="ADVANCE_ISO9001.pdf">ISO 9001</a>
				<a href="m_certificados/ADVANCE_ISO14001.pdf" download="ADVANCE_ISO14001.pdf">ISO 14001</a>
			</div>
		</li>

		<li>
			<span>CHANGFENG</span>
			<div>
				<a href="m_certificados/CHANGFENG_ISO9001.pdf" download="CHANGFENG_ISO9001.pdf">ISO 9001</a>
				<a href="m_certificados/CHANGFENG_ISO14001.pdf" download="CHANGFENG_ISO14001.pdf">ISO 14001</a>
				<a href="m_certificados/CHANGFENG_OHSAS18001.pdf" download="CHANGFENG_OHSAS18001.pdf">OHSAS 18001</a>
			</div>
		</li>

		<li>
			<span>COMFORSER</span>
			<div>
				<a href="m_certificados/COMFORSER_ISO9001.pdf" download="COMFORSER_ISO9001.pdf">ISO 9001</a>
				<a href="m_certificados/COMFORSER_ISOTS16949.pdf" download="COMFORSER_ISOTS16949.pdf">ISO/TS 16949</a>
			</div>
		</li>

		<li>
			<span>FEDERAL</span>
			<div>
				<a href="m_certificados/FEDERAL_ISO 9001.pdf" download="FEDERAL_ISO 9001.pdf">ISO 9001</a>
				<a href="m_certificados/FEDERAL_ISO 14001.pdf" download="FEDERAL_ISO 14001.pdf">ISO 14001</a>
				<a href="m_certificados/FEDERAL_OHSAS 18001.pdf" download="FEDERAL_OHSAS 18001.pdf">ISO 18001</a>
			</div>
		</li>

		<li>
			<span>FULLRUN</span>
			<div>
				<a href="m_certificados/FULLRUN_ISO9001.pdf" download="FULLRUN_ISO9001.pdf">ISO 9001</a>
				<a href="m_certificados/FULLRUN_ISO14001.pdf" download="FULLRUN_ISO14001.pdf">ISO 14001</a>
			</div>
		</li>

		<li>
			<span>GOODTYRE</span>
			<div>
				<a href="m_certificados/GOODTYRE_ISO14001.pdf" download="GOODTYRE_ISO14001.pdf">ISO 14001</a>
				<a href="m_certificados/GOODTYRE_OHSAS18001.pdf" download="GOODTYRE_OHSAS18001.pdf">OHSAS 18001</a>
				<a href="m_certificados/GOODTYRE-ISOTS16949.pdf" download="GOODTYRE-ISOTS16949.pdf">ISO/TS 16949</a>
			</div>
		</li>

		<li>
			<span>KAPSEN</span>
			<div>
				<a href="m_certificados/KAPSEN_ISO 16949.pdf" download="KAPSEN_ISO 16949.pdf">ISO 16949</a>
				<a href="m_certificados/KAPSEN_ISO 14001.pdf" download="KAPSEN_ISO 14001.pdf">ISO 14001</a>
				<a href="m_certificados/KAPSEN_OHSAS 18001.pdf" download="KAPSEN_OHSAS 18001.pdf">OHSAS 18001</a>
			</div>
		</li>

		<li>
			<span>LING LONG</span>
			<div>
				<a href="m_certificados/LINGLONG_ISO9001.pdf" download="LINGLONG_ISO9001.pdf">ISO 9001</a>
				<a href="m_certificados/LINGLONG_ISO14001.pdf" download="LINGLONG_ISO14001.pdf">ISO 14001</a>
				<a href="m_certificados/LINGLONG_OHSAS18001.pdf" download="LINGLONG_OHSAS18001.pdf">OHSAS 18001</a>
			</div>
		</li>

		<li>
			<span>MITAS</span>
			<div>
				<a href="m_certificados/MITAS_ISO9001_RC.pdf" download="MITAS_ISO9001_RC.pdf">ISO 9001 RC</a>
				<a href="m_certificados/MITAS_ISO9001_S.pdf" download="MITAS_ISO9001_S.pdf">ISO 9001 S</a>
				<a href="m_certificados/MITAS_ISO14001_RC.pdf" download="MITAS_ISO14001_RC.pdf">ISO 14001 RC</a>
				<a href="m_certificados/MITAS_ISO14001_S.pdf" download="MITAS_ISO14001_S.pdf">ISO 14001 S</a>
				<a href="m_certificados/MITAS_OHSAS18001_RC.pdf" download="MITAS_OHSAS18001_RC.pdf">OHSAS 18001 RC</a>
				<a href="m_certificados/MITAS_OHSAS18001_S.pdf" download="MITAS_OHSAS18001_S.pdf">OHSAS 18001 S</a>
			</div>
		</li>

	</ul>

	<table>
		<tr>
			<td>
			</td>
		</tr>
	</table>
</div>