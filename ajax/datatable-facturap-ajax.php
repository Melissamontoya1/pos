<?php
require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
session_start();

class TablaFactP{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PAGOS
 	 =============================================*/ 

 	 public function mostrarTablaFactura(){

 	 	$valor =$this->UUID ;


 	 	$venta = ModeloVentas::mdlMostrarVentas("ventas","UUID",$valor); 

 	 	$produc = json_decode($venta["productos"], true);


 	 	if(count($produc) == 0){

 	 		echo '{"data": []}';

 	 		return;
 	 	}	

 	 	$datosJson = '{
 	 		"data": [';

 	 		foreach ($produc as $key => $value) {

 	 		$datosJson .='[
 	 		"'.$value["descripcion"].'",
 	 		"'.$value["cantidad"].'",
 	 		"'.$value["precio"].'"
 	 		
 	 	],';

 	 }

 	 $datosJson = substr($datosJson, 0, -1);

 	 $datosJson .=   '] 

 	}';

 	echo $datosJson;


 }


}

if(isset($_POST["codigoUUID"])){
	/*=============================================
	ACTIVAR TABLA DE HISTORICO DE PAGOS
	=============================================*/ 
	$activarPro = new TablaFactP();
	$activarPro -> UUID=$_POST["codigoUUID"]; //$_POST["idVenta1"];
	$activarPro -> mostrarTablaFactura();
}
