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
        $this->Image('icono-negro.png',150,15,25);
        $this->SetY(40);
        $this->SetX(143);

        $this->SetFont('Arial','B',12);
        $this->Cell(89, 8, 'REPORTE DETALLADO',0,1);
        $this->SetY(45);
        $this->SetX(144);
        $this->SetFont('Arial','',8);
        $this->Cell(40, 8, utf8_decode('PAGOS - CREDITOS - VENTAS'));

        $this->Ln(20);

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



$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'PRODUCTOS VENDIDOS DE CONTADO| '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(25,132,151);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(72, 12, utf8_decode('Descripcion'),0,0,'C',1);
$pdf->Cell(10, 12, utf8_decode('Cant'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('P.Compra'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('P.Venta'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Utilidad'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Total Venta'),0,1,'C',1);

$pdf->SetFont('Arial','',10);

$venta = ModeloVentas::mdlMostrarVentasPdf("ventas",$fechaInicial,$fechaFinal,"Efectivo"); 
$p=0;
$acumte=0;
$acumP=0;
$acumS=0;
foreach ($venta as $key => $value) {

    $productos = json_decode($value["productos"], true);
    foreach ($productos as $key => $valueProductos) {
         $pdf->SetX(15);//posicionamos en x
           //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

         if($p%2==0){
            $pdf->SetFillColor(232, 232, 232 );
            $pdf->SetDrawColor(65, 61, 61);
        }else{
            $pdf->SetFillColor(255, 255, 255 );
            $pdf->SetDrawColor(65, 61, 61);
        }

        $subUtilidade=$valueProductos["precio_compra"]*$valueProductos["cantidad"];
        $utilidade=$valueProductos["total"]-$subUtilidad;
        $pdf->Cell(72, 8, utf8_decode($valueProductos["descripcion"]),'B',0,'C',1);
        $pdf->Cell(10, 8, utf8_decode($valueProductos["cantidad"]),'B',0,'C',1);
        $pdf->Cell(30, 8, utf8_decode($valueProductos["precio_compra"]),'B',0,'C',1);
        $pdf->Cell(30, 8, utf8_decode($valueProductos["precio"]),'B',0,'C',1);
        $pdf->Cell(20, 8, utf8_decode($utilidade),'B',0,'C',1);
        $pdf->Cell(20, 8, utf8_decode("$".number_format($valueProductos["total"])),'B',1,'C',1);
        $acumte+=$valueProductos["total"];
        $acumP+=$utilidade;
        $acumS+=$subUtilidade;
    } 
    $pdf->Ln(0.5);
    $p++;     

}
$pdf->Cell(80,20,'Total Ventas  (Precio de Venta) | $ '.number_format($acumte));
$pdf->Ln();

$pdf->Cell(80,20,'Total Compra (Precio de Compra)  | $ '.number_format($acumS));
$pdf->Ln();

$pdf->Cell(80,20,'Total Utilidad  | $ '.number_format($acumP));
$pdf->Ln();

//PAGINA PARA PAGOS DE CREDITO
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'PAGOS EN EFECTIVO| '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(25,132,151);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(12, 12, utf8_decode('N°'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Factura #'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Tipo Factura'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Cliente'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Vendedor'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Fecha'),0,0,'C',1);
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
$pdf->Cell(30, 8, utf8_decode($valuePago["nombre"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["nombre_usuario"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["fecha"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode("$".number_format($totale)),'B',1,'C',1);

$pdf->Ln(0.5);
$i++;
}
$pdf->Cell(80,20,'Pagos Ventas en Efectivo | $ '.number_format($acume));
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'PRODUCTOS VENDIDOS A CREDITO| '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(25,132,151);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(72, 12, utf8_decode('Descripcion'),0,0,'C',1);
$pdf->Cell(10, 12, utf8_decode('Cant'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('P.Compra'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('P.Venta'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Utilidad'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Total Venta'),0,1,'C',1);

$pdf->SetFont('Arial','',10);

$venta = ModeloVentas::mdlMostrarVentasPdf("ventas",$fechaInicial,$fechaFinal,"CR"); 
$p=0;
$acumtec=0;
$acumPc=0;
$acumSc=0;
foreach ($venta as $key => $value) {

    $productos = json_decode($value["productos"], true);
    foreach ($productos as $key => $valueProductos) {
         $pdf->SetX(15);//posicionamos en x
           //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

         if($p%2==0){
            $pdf->SetFillColor(232, 232, 232 );
            $pdf->SetDrawColor(65, 61, 61);
        }else{
            $pdf->SetFillColor(255, 255, 255 );
            $pdf->SetDrawColor(65, 61, 61);
        }

        $subUtilidadc=$valueProductos["precio_compra"]*$valueProductos["cantidad"];
        $utilidadc=$valueProductos["total"]-$subUtilidadc;
        $pdf->Cell(72, 8, utf8_decode($valueProductos["descripcion"]),'B',0,'C',1);
        $pdf->Cell(10, 8, utf8_decode($valueProductos["cantidad"]),'B',0,'C',1);
        $pdf->Cell(30, 8, utf8_decode($valueProductos["precio_compra"]),'B',0,'C',1);
        $pdf->Cell(30, 8, utf8_decode($valueProductos["precio"]),'B',0,'C',1);
        $pdf->Cell(20, 8, utf8_decode($utilidadc),'B',0,'C',1);
        $pdf->Cell(20, 8, utf8_decode("$".number_format($valueProductos["total"])),'B',1,'C',1);

     $acumtec+=$valueProductos["total"];
        $acumPc+=$utilidadc;
        $acumSc+=$subUtilidadc;
    } 
    $pdf->Ln(0.5);
    $p++;      
}
$pdf->Cell(80,20,'Total Ventas Credito (Precio de Venta) | $ '.number_format($acumtec));
$pdf->Ln();

$pdf->Cell(80,20,'Total Compra (Precio de Compra)  | $ '.number_format($acumSc));
$pdf->Ln();

$pdf->Cell(80,20,'Total Utilidad  | $ '.number_format($acumPc));
$pdf->Ln();
//PAGINA PARA PAGOS DE CREDITOS
$pdf->AddPage();
//TABLA DE PAGOS DE CREDITOS
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'PAGOS DE CREDITOS| '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(25,132,151);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(12, 12, utf8_decode('N°'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Factura #'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Tipo Factura'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Cliente'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Vendedor'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Fecha'),0,0,'C',1);
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
$pdf->Cell(30, 8, utf8_decode($valuePago["nombre"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["nombre_usuario"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valuePago["fecha"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode("$".number_format($total)),'B',1,'C',1);

$pdf->Ln(0.5);
$e++;
}
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'Total Creditos | $ '.number_format($acumtec));
$pdf-> Ln();
$pdf->Cell(80,20,'Pagos Ventas a Credito | $ '.number_format($acumc));
$pdf-> Ln();
$pdf->Cell(80,20,'Saldo por Cobrar | $ '.number_format($acumtec-$acumc));
$sumaTotal=$acumc+$acume;
$pdf->AddPage();
$pdf->SetX(15);
$pdf->SetFont('Arial','B',20);
$pdf-> MultiCell(0,$height,'Resumen de Ventas',0,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'Total Ventas  | $ '.number_format($sumaTotal));
$pdf->Ln();

$pdf->Output();
?>