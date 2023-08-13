<?php

require_once "../controladores/gastos.controlador.php";
require_once "../modelos/gastos.modelo.php";
require_once "../modelos/usuarios.modelo.php";
session_start();

if(!$_SESSION["iniciarSesion"]){
	return;
}
class TablaGastos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE CLIENTE
 	 =============================================*/ 

 	 public function mostrarTablaGastos(){

 	 	$item = null;
 	 	$valor = null;
 	 	$orden = "id_gasto";


 	 	$request=$_REQUEST;


 	 	$renglones = ModeloGasto::mdlMostrarNumRegistros($request);
 	 	$totalRenglones=$renglones["contador"];





 	 	$gastos = ModeloGasto::mdlMostrarGastosDTServerSide($request);	

 	 	if(count($gastos) == 0){

 	 		echo '{"data": []}';

 	 		return;
 	 	}

 	 	$datosJson = '{

 	 		"draw": '.intval($request["draw"]).',
 	 		"recordsTotal":'.intval($totalRenglones).',
 	 		"recordsFiltered": '.intval($totalRenglones).',

 	 		"data": [';

 	 		for($i = 0; $i < count($gastos); $i++){
 	 			$botones = "<div class='btn-group'><button class='btn btn-warning btnEditarGasto' id_gasto='" . $gastos[$i]["id_gasto"] . "' data-toggle='modal' data-target='#modalEditarGasto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarGasto' id_gasto='" . $gastos[$i]["id_gasto"] . "'><i class='fa fa-times'></i></button></div>";
 	 			   $vendedor = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $gastos[$i]["id_vendedor_fk"]);


 	 			$datosJson .='[
 	 			"'.$gastos[$i]["id_gasto"].'", 
 	 			"'.$gastos[$i]["fecha_gasto"].'",
 	 			"'.$gastos[$i]["descripcion_gasto"].'",
 	 			"$'.number_format($gastos[$i]["valor_gasto"]).'",
 	 			"'.$gastos[$i]["tipo_caja"].'",
 	 			"'.$vendedor["nombre"].'",
 	 			"'.$gastos[$i]["id_caja_fk"].'",
 	 			"'.$botones.'"

 	 		],';

 	 	}

 	 	$datosJson = substr($datosJson, 0, -1);

 	 	$datosJson .=   '] 

 	 }';

 	 echo $datosJson;


 	}


 }

/*=============================================
ACTIVAR TABLA DE CLIENTES
=============================================*/ 
$activarGastos= new TablaGastos();
$activarGastos -> mostrarTablaGastos();

