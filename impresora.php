<?php
/*
$handle = printer_open('EPSON FX-890 Ver 2.0');
printer_write($handle, "Text to print");
printer_close($handle);
exit;
*/

$espacio =1340;
$espacio_letra = 15;
$handle = printer_open('EPSON FX-890 ESC/P');

//printer_set_option($handle, PRINTER_MODE, "TEXT");
//printer_set_option($handle, PRINTER_MODE, "RAW");


printer_set_option($handle,PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
printer_set_option($handle,PRINTER_PAPER_WIDTH,320);
printer_set_option($handle,PRINTER_PAPER_LENGTH,250);

printer_start_doc($handle, "Mi Documento");
printer_start_page($handle);
$pen = printer_create_pen(PRINTER_PEN_DOT, 1, "000000");
$font = printer_create_font("Draft 17cpi",20,6, PRINTER_FW_ULTRALIGHT, false, false, false, 0);

printer_select_pen($handle, $pen);
printer_select_font($handle, $font);


$mostrar="JCH Llantas";
$mostrar2= "20318171701";
$mostrar3= "Av. Nicolas Arriola 2291 La Victoria Lima";
$fecha= "20/11/2017";
$descripcion= "descripcion";
$subtotal = "200";
$igv = "18";
$total = "236";
$pagado = "doscientos treinta y seis";
$costo_igv = "18";
$soles = "S/. ";
$fecha_cancelado = "20/11/2017";
$numero_st = strlen($subtotal) -3;
$numero_igv = strlen($igv) -3;
$numero_total = strlen($total) -3;

$espacio_subtotal = $espacio - ($espacio_letra * $numero_st);
$espacio_igv = $espacio - ($espacio_letra * $numero_igv);
$espacio_total = $espacio - ($espacio_letra * $numero_total);

printer_draw_text($handle,$mostrar,130,250);
//x –>margen a la izquierda
//y — margen top
printer_draw_text($handle,$mostrar2,130,300);
printer_draw_text($handle,$mostrar3,130,350);

printer_draw_text($handle,$fecha,1050,315);

printer_draw_text($handle,$descripcion,240,500);
printer_draw_text($handle,$pagado,240,610);
printer_draw_text($handle,$fecha_cancelado,560,888);
printer_draw_text($handle,$subtotal,$espacio_subtotal,490);
printer_draw_text($handle,$subtotal,$espacio_subtotal,850);
printer_draw_text($handle,$soles,1195,850);
printer_draw_text($handle,$igv,$espacio_igv,900);
printer_draw_text($handle,$costo_igv,1135,900);
printer_draw_text($handle,$soles,1195,900);
printer_draw_text($handle,$total,$espacio_total,950);
printer_draw_text($handle,$soles,1195,950);

printer_delete_font($font);
printer_delete_pen($pen);
printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);

?>
