<?php

require_once "../controladores/ingreso.controlador.php";
require_once "../modelos/ingreso.modelo.php";
require_once "../modelos/usuarios.modelo.php";
session_start();

if(!$_SESSION["iniciarSesion"]){
	return;
}
class TablaIngreso{

 	/*=============================================
 	 MOSTRAR LA TABLA DE CLIENTE
 	 =============================================*/ 

 	 public function mostrarTablaIngreso(){

 	 	$item = null;
 	 	$valor = null;
 	 	$orden = "id_ingreso";


 	 	$request=$_REQUEST;


 	 	$renglones = ModeloIngreso::mdlMostrarNumRegistros($request);
 	 	$totalRenglones=$renglones["contador"];





 	 	$ingreso = ModeloIngreso::mdlMostrarIngresoDTServerSide($request);	

 	 	if(count($ingreso) == 0){

 	 		echo '{"data": []}';

 	 		return;
 	 	}

 	 	$datosJson = '{

 	 		"draw": '.intval($request["draw"]).',
 	 		"recordsTotal":'.intval($totalRenglones).',
 	 		"recordsFiltered": '.intval($totalRenglones).',

 	 		"data": [';

 	 		for($i = 0; $i < count($ingreso); $i++){
 	 			$botones = "<div class='btn-group'><button class='btn btn-warning btnEditarIngreso' id_ingreso='" . $ingreso[$i]["id_ingreso"] . "' data-toggle='modal' data-target='#modalEditarIngreso'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarIngreso' id_ingreso='" . $ingreso[$i]["id_ingreso"] . "'><i class='fa fa-times'></i></button></div>";
 	 			   $vendedor = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $ingreso[$i]["id_vendedor_fk"]);


 	 			$datosJson .='[
 	 			"'.$ingreso[$i]["id_ingreso"].'", 
 	 			"'.$ingreso[$i]["fecha_ingreso"].'",
 	 			"'.$ingreso[$i]["descripcion_ingreso"].'",
 	 			"'.number_format($ingreso[$i]["valor_ingreso"]).'",
 	 			"'.$vendedor["nombre"].'",
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
$activarIngreso= new TablaIngreso();
$activarIngreso -> mostrarTablaIngreso();

