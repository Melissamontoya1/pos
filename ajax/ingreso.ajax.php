<?php

require_once "../controladores/ingreso.controlador.php";
require_once "../modelos/ingreso.modelo.php";

class AjaxIngreso{

	/*=============================================
	EDITAR INGRESO
	=============================================*/	

	public $id_ingreso;

	public function ajaxEditarIngreso(){

		$item = "id_ingreso";
		$valor = $this->id_ingreso;

		$respuesta = ControladorIngreso::ctrMostrarIngreso($item, $valor);

		echo json_encode($respuesta);


	}

}

/*=============================================
EDITAR INGRESO
=============================================*/	

if(isset($_POST["id_ingreso"])){

	$ingreso = new AjaxIngreso();
	$ingreso -> id_ingreso = $_POST["id_ingreso"];
	$ingreso -> ajaxEditarIngreso();

}