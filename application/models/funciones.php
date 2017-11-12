<?php 
function conexion() {
  $out['host'] = "SDLima";
  $out['base'] = "JCHS2016";
  $out['user'] = "sa";
  $out['pwd'] = DesencriptaSoftcom("hVbZel&,%'"); 
  return $out;
}

function EncriptaSoftcom($CCLAVE){
  $cDecrip = ""; 
  for($i=0;$i<=(strlen($CCLAVE)-1);++$i):
    $cKey = ord(substr($CCLAVE, $i , 1));
  $cDecrip = $cDecrip . chr($cKey + strlen($CCLAVE));
  endfor; 
  return $cDecrip; 
}

function DesencriptaSoftcom($CCLAVE){
	$cDecrip = ""; 
  for($i=0;$i<=(strlen($CCLAVE)-1);++$i):
    $cKey = ord(substr($CCLAVE, $i , 1));
  $cDecrip = $cDecrip . chr($cKey - strlen($CCLAVE));
  endfor; 
  return $cDecrip; 
}

//echo DesencriptaSoftcom('5==4'); 

function ellipsis($text, $max=100, $append='&hellip;') {
 if (strlen($text) <= $max) return $text;
 $out = substr($text,0,$max);
 if (strpos($text,' ') === FALSE) return $out.$append;
 return preg_replace('/\w+$/','',$out).$append;
}
?>