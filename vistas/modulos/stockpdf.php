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


//DATOS EMPRESA

require_once "../../controladores/empresa.controlador.php";
require_once "../../modelos/empresa.modelo.php";
require 'fpdf/fpdf.php';

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




}


$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();


//PAGINA PARA PAGOS EN EFECTIVO 
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, );
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,20,'PRODUCTOS CON POCO INVENTARIO ');
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(82, 190, 128 );

$pdf->SetFont('Arial','B',10);
$pdf->Cell(12, 12, utf8_decode('#'),0,0,'C',1);
$pdf->Cell(40, 12, utf8_decode('Codigo'),0,0,'C',1);
$pdf->Cell(100, 12, utf8_decode('Descripcion'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Stock'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Precio Compra'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Precio Venta'),0,0,'C',1);
$pdf->Cell(35, 12, utf8_decode('Ultima Compra'),0,1,'C',1);

$pdf->SetFont('Arial','',10);


$stock = ModeloProductos::mdlMostrarStockProductos(); 
$i=0;

foreach ($stock as $key => $valueStock) {

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
$pdf->Cell(40, 8, utf8_decode($valueStock["codigo"]),'B',0,'C',1);
$pdf->Cell(100, 8, utf8_decode($valueStock["descripcion"]),'B',0,'C',1);
$pdf->Cell(20, 8, utf8_decode($valueStock["stock"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valueStock["precio_compra"]),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($valueStock["precio_venta"]),'B',0,'C',1);
$pdf->Cell(35, 8, utf8_decode($valueStock["fecha"]),'B',1,'C',1);

$pdf->Ln(1);
$i++;
}



$pdf->Output();
?>