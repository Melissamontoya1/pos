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
$codigo=$_GET['codigo'];
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
$pdf->Cell(80,20,'PRODUCTOS VENDIDOS| '.'Fecha Inicial : '.$fechaInicial.' -  Fecha Final : '.$fechaFinal);
$pdf->Ln();

$pdf->SetX(15);
$pdf->SetFillColor(25,132,151);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(72, 12, utf8_decode('Descripcion'),0,0,'C',1);
$pdf->Cell(10, 12, utf8_decode('Cant'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('P.Compra'),0,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('P.Venta'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Total Venta'),0,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Utilidad'),0,1,'C',1);

$pdf->SetFont('Arial','',10);

$respuesta = ControladorVentas::ctrRangoFechasVentasProductoI($fechaInicial
 , $fechaFinal
 ,$codigo
 ,"VEN"
 ,"CR"
 ,"TB"
 ,$pendientePorCobrar
 ,$soloCobrado
 ,$cliente );
$p=0;
$acumP=0;
$acumV=0;
$acumS=0;
foreach ($respuesta as $key => $value) {
         $pdf->SetX(15);//posicionamos en x
           //-------------INTERCALAMOS COLOR LOS PARES DE UN COLOR Y LOS QUE NO DE OTRO

         if($p%2==0){
            $pdf->SetFillColor(232, 232, 232 );
            $pdf->SetDrawColor(65, 61, 61);
        }else{
            $pdf->SetFillColor(255, 255, 255 );
            $pdf->SetDrawColor(65, 61, 61);
        }

        $subUtilidad=$value["precioCompra"]*$value["cantidadProducto"];
        $utilidad=$value["totalProducto"]-$subUtilidad;

        $pdf->Cell(72, 8, utf8_decode($value["descripcionProducto"]),'B',0,'C',1);
        $pdf->Cell(10, 8, utf8_decode($value["cantidadProducto"]),'B',0,'C',1);
        $pdf->Cell(30, 8, utf8_decode($value["precioCompra"]),'B',0,'C',1);
        $pdf->Cell(30, 8, utf8_decode($value["precioProducto"]),'B',0,'C',1);
        $pdf->Cell(20, 8, utf8_decode("$".number_format($value["totalProducto"])),'B',0,'C',1);
        $pdf->Cell(20, 8, utf8_decode("$".number_format($utilidad)),'B',1,'C',1);


        $pdf->Ln(0.5);
        $acumV+=$value["totalProducto"];
        $acumP+=$utilidad;
        $acumS+=$subUtilidad;
        $p++;      
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(80,20,'Total Ventas  (Precio de Venta) | $ '.number_format($acumV));
$pdf->Ln();
    
    $pdf->Cell(80,20,'Total Compra (Precio de Compra)  | $ '.number_format($acumS));
$pdf->Ln();

    $pdf->Cell(80,20,'Total Utilidad  | $ '.number_format($acumP));
    $pdf->Ln();
    $pdf->Output();
?>