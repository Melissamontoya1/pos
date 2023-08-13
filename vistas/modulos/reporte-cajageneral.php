<?php 

require_once "../../controladores/ventas.controlador.php";
require_once "../../modelos/ventas.modelo.php";

require_once "../../controladores/caja.controlador.php";
require_once "../../modelos/caja.modelo.php";

require_once "../../controladores/pagos.controlador.php";
require_once "../../modelos/pagos.modelo.php";

require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";

require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";

require_once "../../controladores/productos.controlador.php";
require_once "../../modelos/productos.modelo.php";

require_once "../../controladores/ingreso.controlador.php";
require_once "../../modelos/ingreso.modelo.php";

require_once "../../controladores/gastos.controlador.php";
require_once "../../modelos/gastos.modelo.php";

//DATOS EMPRESA

require_once "../../controladores/empresa.controlador.php";
require_once "../../modelos/empresa.modelo.php";


//CONSULTA PARA TRAER EL CAJERO
$tablaVendedor = "usuarios";
$item = "id";
$valor = $cajas["idUsuario"];

$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);
//DATOS EMPRESA

require_once "../../controladores/empresa.controlador.php";
require_once "../../modelos/empresa.modelo.php";
require 'fpdf/fpdf.php';
$fechaInicial=$_GET['fechaInicial'];
$fechaFinal=$_GET['fechaFinal'];
$tipo_reporte=$_GET['tipo_reporte'];
date_default_timezone_set('America/Bogota');
$conn = mysqli_connect("localhost","root","","pos" ) or die ("error" . mysqli_error($conn));
class PDF extends FPDF
{

    function Header()
    {
        //$this->Image('fondo.png',-10,-1,110);
        $this->Image('icono-negro.png',240,4,10);
        $this->SetY(15);
        $this->SetX(220);

        $this->SetFont('Arial','B',12);
        $this->Cell(89, 8, 'REPORTE DETALLADO',0,1);
        $this->SetY(20);
        $this->SetX(210);
        $this->SetFont('Arial','',8);
        $this->Cell(40, 8, utf8_decode('PAGOS - CREDITOS - VENTAS- INGRESOS - GASTOS'));

        $this->Ln(5);

    }

    function Footer()
    {
       $this->SetFont('helvetica', 'B', 8);
       $this->SetY(-15);
       $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
       $this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
       $this->Line(10,287,200,287);
       $this->Cell(0,5,utf8_decode("Hexadot © Todos los derechos reservados."),0,0,"C");

   }


}



$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();


//PAGINA PARA PAGOS EN EFECTIVO 
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, );
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'INGRESOS VENTAS EFECTIVO | '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(82, 190, 128 );

$pdf->SetFont('Arial','B',10);
$pdf->Cell(12, 12, utf8_decode('N°'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Factura #'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Tipo Factura'),0,0,'C',1);
$pdf->Cell(80, 12, utf8_decode('Cliente'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Vendedor'),0,0,'C',1);
$pdf->Cell(60, 12, utf8_decode('Fecha'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Total'),0,1,'C',1);

$pdf->SetFont('Arial','',10);

$pagos = ModeloPagos::mdlMostrarPagosFechaHoraPDF($fechaInicial,$fechaFinal,"Efectivo"); 
$i=0;
$acume=0;
foreach ($pagos as $key => $valuePago) {
    $totale=$valuePago["importePagado"]-$valuePago["importeDevuelto"];
    $acume+=$totale;
  $pdf->SetX(15);//posicionamos en x

  //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

  if($i%2==0){
    $pdf->SetFillColor(232, 232, 232 );
    $pdf->SetDrawColor(65, 61, 61);
}else{
    $pdf->SetFillColor(255, 255, 255 );
    $pdf->SetDrawColor(65, 61, 61);
}
//--------------------------------TERMINAMOS DE PINTAR----------------------------

//                          DATOS
$pdf->Cell(12, 8, $i+1,'B',0,'C',1);
$pdf->Cell(20, 8, utf8_decode($valuePago["idVenta"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["metodo_pago"]),'B',0,'C',1);
$pdf->Cell(80, 8, utf8_decode($valuePago["nombre"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["nombre_usuario"]),'B',0,'C',1);
$pdf->Cell(60, 8, utf8_decode($valuePago["fecha"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode("$".number_format($totale)),'B',1,'C',1);

$pdf->Ln(0.5);
$i++;
}
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'Ingresos Ventas en Efectivo | $ '.number_format($acume));
//PAGINA PARA PAGOS DE CREDITOS
//$pdf->AddPage();
$pdf->Ln(20);
//TABLA DE PAGOS DE CREDITOS
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'ABONOS A CREDITOS | '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(82, 190, 128 );

$pdf->SetFont('Arial','B',10);
$pdf->Cell(12, 12, utf8_decode('N°'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Factura #'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Tipo Factura'),0,0,'C',1);
$pdf->Cell(80, 12, utf8_decode('Cliente'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Vendedor'),0,0,'C',1);
$pdf->Cell(60, 12, utf8_decode('Fecha'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Total'),0,1,'C',1);

$pdf->SetFont('Arial','',10);

$pagos = ModeloPagos::mdlMostrarPagosFechaHoraPDF($fechaInicial,$fechaFinal,"CR"); 
$e=0;
$acumc=0;
foreach ($pagos as $key => $valuePago) {
    $total=$valuePago["importePagado"]-$valuePago["importeDevuelto"];
    $acumc+=$total;
  $pdf->SetX(15);//posicionamos en x

  //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

  if($e%2==0){
    $pdf->SetFillColor(232, 232, 232 );
    $pdf->SetDrawColor(65, 61, 61);
}else{
    $pdf->SetFillColor(255, 255, 255 );
    $pdf->SetDrawColor(65, 61, 61);
}
//--------------------------------TERMINAMOS DE PINTAR----------------------------

//                          DATOS
$pdf->Cell(12, 8, $e+1,'B',0,'C',1);
$pdf->Cell(20, 8, utf8_decode($valuePago["idVenta"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["metodo_pago"]),'B',0,'C',1);
$pdf->Cell(80, 8, utf8_decode($valuePago["nombre"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["nombre_usuario"]),'B',0,'C',1);
$pdf->Cell(60, 8, utf8_decode($valuePago["fecha"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode("$".number_format($total)),'B',1,'C',1);

$pdf->Ln(0.5);
$e++;
}
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(80,20,'Ingresos Abonos a Credito | $ '.number_format($acumc));
//PAGINA PARA PAGOS DE CREDITOS
//$pdf->AddPage();
//TABLA DE INGRESOS A LA CAJA GENERAL
$pdf->Ln(20);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'INGRESOS A CAJA GENERAL |  '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(82, 190, 128 );

$pdf->SetFont('Arial','B',10);
$pdf->Cell(6, 12, utf8_decode('N°'),0,0,'C',1);
$pdf->Cell(100, 12, utf8_decode('Descripcion'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Fecha'),0,0,'C',1);
$pdf->Cell(60, 12, utf8_decode('Vendedor'),0,0,'C',1);
$pdf->Cell(40, 12, utf8_decode('Total'),0,1,'C',1);



$pdf->SetFont('Arial','',10);

$ingreso = ModeloIngreso::mdlMostrarIngresoFechaHoraPDF($fechaInicial,$fechaFinal); 
$c=0;
$acumG=0;
foreach ($ingreso as $key => $valueingreso) {
    $totalg=$valueingreso["valor_ingreso"];
    $acumG+=$totalg;
  $pdf->SetX(15);//posicionamos en x

  //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

  if($e%2==0){
    $pdf->SetFillColor(232, 232, 232 );
    $pdf->SetDrawColor(65, 61, 61);
}else{
    $pdf->SetFillColor(255, 255, 255 );
    $pdf->SetDrawColor(65, 61, 61);
}
//--------------------------------TERMINAMOS DE PINTAR----------------------------

//                          DATOS
$pdf->Cell(6, 8, $c+1,'B',0,'C',1);
$pdf->Cell(100, 8, utf8_decode($valueingreso["descripcion_ingreso"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valueingreso["fecha_ingreso"]),'B',0,'C',1);
$pdf->Cell(60, 8, utf8_decode($valueingreso["nombre_usuario"]),'B',0,'C',1);
$pdf->Cell(40, 8, utf8_decode("$".number_format($totalg)),'B',1,'C',1);

$pdf->Ln(0.5);
$c++;
}
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(80,20,'Ingresos a Caja General | $ '.number_format($acumG));
//PAGINA PARA LOS GASTOS
$pdf->AddPage();
//TABLA DE GASTOS 
$pdf->Ln(20);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'GASTOS CAJA GENERAL |  '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(255, 57, 51 );

$pdf->SetFont('Arial','B',10);
$pdf->Cell(6, 12, utf8_decode('N°'),0,0,'C',1);
$pdf->Cell(100, 12, utf8_decode('Descripcion'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Fecha'),0,0,'C',1);
$pdf->Cell(40, 12, utf8_decode('Caja'),0,0,'C',1);
$pdf->Cell(40, 12, utf8_decode('Vendedor'),0,0,'C',1);
$pdf->Cell(40, 12, utf8_decode('Total'),0,1,'C',1);



$pdf->SetFont('Arial','',10);

$Gastos = ModeloGasto::mdlMostrarGastosFechaHoraPDF($fechaInicial,$fechaFinal); 
$g=0;
$acumGastos=0;
foreach ($Gastos as $key => $valueGastos) {
    $totalGastos=$valueGastos["valor_gasto"];
    $acumGastos+=$totalGastos;
      
  $pdf->SetX(15);//posicionamos en x

  //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

  if($e%2==0){
    $pdf->SetFillColor(232, 232, 232 );
    $pdf->SetDrawColor(65, 61, 61);
}else{
    $pdf->SetFillColor(255, 255, 255 );
    $pdf->SetDrawColor(65, 61, 61);
}
//--------------------------------TERMINAMOS DE PINTAR----------------------------

//                          DATOS
$pdf->Cell(6, 8, $g+1,'B',0,'C',1);
$pdf->Cell(100, 8, utf8_decode($valueGastos["descripcion_gasto"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valueGastos["fecha_gasto"]),'B',0,'C',1);
$pdf->Cell(40, 8, utf8_decode($valueGastos["tipo_caja"]),'B',0,'C',1);
$pdf->Cell(40, 8, utf8_decode($valueGastos["nombre_usuario"]),'B',0,'C',1);
$pdf->Cell(40, 8, utf8_decode("$".number_format($totalGastos)),'B',1,'C',1);

$pdf->Ln(0.5);
$g++;
}
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(80,20,'Total Gastos | $ '.number_format($acumGastos));

$pdf->Output();
?>