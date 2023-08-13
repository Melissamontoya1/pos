	<?php 

	require __DIR__ . '../../../extensiones/vendor/autoload.php';


	use Mike42\Escpos\Printer;
	use Mike42\Escpos\EscposImage;
	use Mike42\Escpos\PrintConnectors\FilePrintConnector;
	use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

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


// https://parzibyte.github.io/ticket-js/3/
//DATOS DE LA EMPRESA
	$DatosEmpresa= ControladorEmpresa::ctrMostrarEmpresas(null, null);

	$nombreEmpresa=$DatosEmpresa[0]["NombreEmpresa"];
	$direccionEmpresa=$DatosEmpresa[0]["DireccionEmpresa"];
	$RFCEmpresa=$DatosEmpresa[0]["RFC"];
	$TelefonoEmpresa=$DatosEmpresa[0]["Telefono"];
	$pie_pagina=$DatosEmpresa[0]["pie_pagina"];
	$impresora=$DatosEmpresa[0]["impresora"];
	

	$item="id";
	$valor=$_GET["idCaja"];
	$idCaja=$valor;
	$cajas = ModeloCaja::mdlMostrarCajas("caja", $item, $valor);
	$fechaInicial= $cajas["fecha_apertura"];
	$fechaFinal= $cajas["fecha_cierre"];
	$diferencia=$cajas["diferencia"];
	$total_ventas=$cajas["total_ventas"];
	$importe_apertura=$cajas["importe_apertura"];
	$observaciones=$cajas["observaciones"];
	$dinero_caja=(($total_ventas+$importe_apertura)-$diferencia);
	//CONSULTA PARA TRAER EL CAJERO
	$tablaVendedor = "usuarios";
	$item = "id";
	$valor = $cajas["idUsuario"];

	$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);
	//IMPRIMIR LAS VENTAS DEL CAJERO DENTRO DE ESE RANGO DE FECHA Y HORA
	$impresora = $impresora;

	$conector = new WindowsPrintConnector($impresora);

	$printer = new Printer($conector);

	$printer -> setJustification(Printer::JUSTIFY_CENTER);


				//DATOS DE LA EMPRESA
//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
	$printer -> setEmphasis(true);
	$printer->setJustification(Printer::JUSTIFY_CENTER);
	$printer -> text($nombreEmpresa."\n");
	$printer -> text("NIT :".$RFCEmpresa."\n");
	$printer -> text("--------------------------------");
	$printer -> setEmphasis(false);
	$printer -> selectPrintMode();
	$printer -> setJustification(Printer::JUSTIFY_LEFT);
	$printer -> text("DIRECCION :\n");
	$printer -> text($direccionEmpresa."\n");
	$printer -> text("TELEFONOS :".$TelefonoEmpresa."\n");
	//$printer -> text("FACT- # :" .$venta["codigo"]."\n");
	$printer -> feed();

//FORMA DE PAGO (EFECTIVO- CHEQUE- TRANDFERENCIA / VENDEDOR)
	$printer -> text("--------------------------------");
	$printer -> text("Fecha : ".date("Y-m-d H:i:s")."\n");
	$printer -> text("Cajero : ".$traerVendedor["nombre"]."\n");
	$printer -> text("--------------------------------");
	//ESTRUCTURA CIERRE
	$printer -> feed(1); //Alimentamos el papel 1 vez
	// $printer -> setEmphasis(true);
	// $printer->setJustification(Printer::JUSTIFY_CENTER);
	// $printer -> text("DETALLES DEL CIERRE VENTAS \n");
	// $printer -> setEmphasis(false);
	// $venta = ModeloVentas::mdlMostrarVentasFechaHora("ventas",$fechaInicial,$fechaFinal,$idCaja); 
	// $acum_cierre=0;
	// foreach ($venta as $key => $value) {
	// 	$printer->setJustification(Printer::JUSTIFY_LEFT);
	// 	$productos = json_decode($value["productos"], true);
	// 	foreach ($productos as $key => $valueProductos) {
	// 		$printer->text($valueProductos["descripcion"]."\n");//Nombre del producto
			
	// 		$printer->setJustification(Printer::JUSTIFY_RIGHT);

	// 		$printer->text("$ ".number_format($valueProductos["precio"])." Und x ".$valueProductos["cantidad"]." = $ ".number_format($valueProductos["total"])."\n");	
	// 	}		
	// }
	//ESTRUCTURA CIERRE
	$printer -> feed(1); //Alimentamos el papel 1 vez
	$printer -> setEmphasis(true);
	$printer->setJustification(Printer::JUSTIFY_CENTER);
	$printer -> text("DETALLES DE PAGOS  \n");
	$printer -> setEmphasis(false);
	
	$pagos = ModeloPagos::mdlMostrarPagosFechaHora("pagos",$fechaInicial,$fechaFinal,$idCaja); 
	
	foreach ($pagos as $key => $valuePago) {
		$printer->setJustification(Printer::JUSTIFY_LEFT);
		$printer->text("FACT # : ".$valuePago["idVenta"]."\n");//Nombre del producto
		$printer->setJustification(Printer::JUSTIFY_RIGHT);

		
			$printer->text("$".$valuePago["importePagado"]."-".$valuePago["fechaPago"]."\n");//Nombre del producto
		}
		$printer -> feed(1); //Alimentamos el papel 1 vez

		$printer->text("--------\n");

		$printer->text("TOTAL Ventas: $ ".number_format($total_ventas)."\n"); //ahora va el total
		$printer->text("Base: $ ".number_format($importe_apertura)."\n"); //ahora va el total
		$printer->text("Dinero en Caja: $ ".number_format($dinero_caja)."\n"); //ahora va el total
		$printer->text("Dinero Faltante: $ ".number_format($diferencia)."\n"); //ahora va el total
		
		$printer -> feed(1); //Alimentamos el papel 3 veces
		$printer -> text("--------------------------------");
				$printer->text("Cierre de Caja Exitoso"); //Podemos poner también un pie de página

				$printer -> feed(3); //Alimentamos el papel 3 veces

				$printer -> cut(); //Cortamos el papel, si la impresora tiene la opción

				$printer -> pulse(); //Por medio de la impresora mandamos un pulso, es útil cuando hay cajón moneder

				$printer -> close();

				echo '<script>

				window.location = "../../cajadiaria";

				</script>';
				?>
