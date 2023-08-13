<?php //INICIO DE FACTURA POS


	require __DIR__ . '../../../extensiones/vendor/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
	use Mike42\Escpos\EscposImage;
	use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
	use Mike42\Escpos\Printer;

	require_once "../../controladores/ventas.controlador.php";
	require_once "../../modelos/ventas.modelo.php";

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
	function normaliza ($cadena){
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$cadena = utf8_decode($cadena);
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);

		return utf8_encode($cadena);
	}
	/* Un contenedor para organizar los nombres y precios de artículos en columnas */
	class item
	{
		private $name;
		private $price;
		private $dollarSign;

		public function __construct($name = '', $price = '', $dollarSign = false)
		{
			$this -> name = $name;
			$this -> price = $price;
			$this -> dollarSign = $dollarSign;
		}

		public function __toString()
		{
			$rightCols = 10;
			$leftCols = 38;
			if ($this -> dollarSign) {
				$leftCols = $leftCols / 2 - $rightCols / 2;
			}
			$left = str_pad($this -> name, $leftCols) ;

			$sign = ($this -> dollarSign ? '$ ' : '');
			$right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
			return "$left$right\n";
		}
	}
//DATOS DE LA EMPRESA
	$DatosEmpresa= ControladorEmpresa::ctrMostrarEmpresas(null, null);

	$nombreEmpresa=$DatosEmpresa[0]["NombreEmpresa"];
	$direccionEmpresa=$DatosEmpresa[0]["DireccionEmpresa"];
	$RFCEmpresa=$DatosEmpresa[0]["RFC"];
	$TelefonoEmpresa=$DatosEmpresa[0]["Telefono"];
	$pie_pagina=$DatosEmpresa[0]["pie_pagina"];
	$impresora=$DatosEmpresa[0]["impresora"];

	$UUID = $_GET["codigoVenta"];

	$venta = ModeloVentas::mdlMostrarVentas("ventas","UUID",$UUID); 

	$productos = json_decode($venta["productos"], true);

	$tablaVendedor = "usuarios";
	$item = "id";
	$valor = $venta["id_vendedor"];

	$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);

/*
	Este ejemplo imprime un
	ticket de venta desde una impresora térmica
*/

/*
Este ejemplo imprime un Tiket de venta en una impresora de tickets
en Windows.
La impresora debe estar instalada como genérica y debe estar
compartida
 */

/*
Conectamos con la impresora
 */

/*
Aquí, en lugar de "POS-58" (que es el nombre de mi impresora)
escribe el nombre de la tuya. Recuerda que debes compartirla
desde el panel de control
 */
/* DATOS TRAIDOS DE LA CONSULTA SQL*/

$impresora = $impresora;

$conector = new WindowsPrintConnector($impresora);

$printer = new Printer($conector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

//detalles de la factura como iva/ subtotal y total
$subtotal = new item('Subtotal',"$". number_format($venta["neto"]));
$descuento = new item('Descuento', "$".number_format($venta["descuento"]));
$tax = new item('Iva',"$". number_format($venta["impuesto"]));
$totales = new item('Total');
/* Date is kept the same for testing */
$date = date('d/m/Y h:i:s A');


//DATOS DE LA EMPRESA
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer-> text("COTIZACION \n");
$printer -> text($nombreEmpresa."\n");
$printer -> text("NIT :".$RFCEmpresa."\n");
$printer -> feed(2);
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text("DIRECCION :\n");
$printer -> text($direccionEmpresa."\n");
$printer -> text("TELEFONO :".$TelefonoEmpresa."\n");
$printer -> text("COT- # :".$venta["codigo"]."\n");
$printer -> feed();
//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
$printer -> text("--------------------------------");
//$printer -> text("Forma de Pago :".$venta["metodo_pago"]."\n");
$printer -> text("Fecha : ".date("Y-m-d H:i:s")."\n");
$printer -> text("Cajero : ".$traerVendedor["nombre"]."\n");
$printer -> text("Vence : ".$venta["FechaVencimiento"]."\n");
$printer -> text("Plazo Entrega : ".$venta["plazoEntrega"]."\n");
$printer -> text("--------------------------------");
/* Titulo del Recibo */
$printer -> setEmphasis(true);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("DETALLES DE LA COTIZACION\n");
$printer -> setEmphasis(false);
$printer -> feed();

foreach ($productos as $key => $value) {

	/*Alinear a la izquierda para la cantidad y el nombre*/
	$printer->setJustification(Printer::JUSTIFY_LEFT);
	$printer->text($value["descripcion"]."\n"."$ ".number_format($value["precio"])." X ". $value["cantidad"] );

	/*Y a la derecha precio unidad y precio total*/
	$printer->setJustification(Printer::JUSTIFY_RIGHT);
	$printer->text("  $ " .number_format($value["total"]). "\n");
}

/* Impresion de item Subtotal*/
$printer -> setEmphasis(true);
$printer -> text($subtotal);
$printer -> setEmphasis(false);
$printer -> feed();
$printer -> feed(1);
/* Impresion de item Subtotal*/
$printer -> setEmphasis(true);
$printer -> text($tax);
$printer -> setEmphasis(false);
$printer -> feed();
$printer -> feed(1);
/* Impresion de item Iva y Total*/
$printer -> text($descuento);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text($totales);
$printer -> text("$".number_format($venta["total"]));
$printer -> selectPrintMode();
$printer -> feed(2);
$printer -> text("--------------------------------");
				$printer -> feed(1); //Alimentamos el papel 1 vez
				$printer -> setEmphasis(true);
				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer -> text("DATOS DEL CLIENTE\n");
				$printer -> setEmphasis(false);	
				$printer -> text("--------------------------------");
				$printer -> setEmphasis(false);
				$printer -> selectPrintMode();
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				/* Titulo del Recibo */
				$tablaClientes = "clientes";
				$item = "id";
				$valor = $venta["id_cliente"];

				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);
				$printer -> text("ID: ".$traerCliente["documento"]."\n");//Cedula del cliente
				$printer -> text("Cliente: ".$traerCliente["nombre"]."\n");//Nombre del cliente
				$printer -> text("Telefono: ".$traerCliente["telefono"]."\n");//Nombre del cliente
				$printer -> text("Correo: ".$traerCliente["email"]."\n");//Nombre del cliente
				$printer -> text("Observaciones: ".$venta["Observaciones"]."\n");//Nombre del cliente
				$printer -> text("--------------------------------");
		

              

              /* Footer - Pie de pagina */
              $printer -> feed(2);
              $printer -> setJustification(Printer::JUSTIFY_CENTER);
              $printer -> text("Gracias por preferirnos\n");
              $printer -> text($pie_pagina);
              $printer -> feed(3);


              /*Corte el recibo y abra el cajón o monedero */
              $printer -> cut();
              $printer -> Pulse();
              /*Cerramos la sesion de la impresora*/
              $printer -> close();

/*REDIRECIONAMOS AL INDEX
header("Location: ../index.php");*/



echo '<script>

window.location = "../../administrarcotizaciones";

</script>';

?>