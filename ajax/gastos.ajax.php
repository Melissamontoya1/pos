<?php

require_once "../controladores/gastos.controlador.php";
require_once "../modelos/gastos.modelo.php";

class AjaxGasto{

	/*=============================================
	EDITAR CLIENTE
	=============================================*/	

	public $id_gasto;

	public function ajaxEditarGasto(){

		$item = "id_gasto";
		$valor = $this->id_gasto;

		$respuesta = ControladorGasto::ctrMostrarGasto($item, $valor);

		echo json_encode($respuesta);


	}

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["id_gasto"])){

	$gasto = new AjaxGasto();
	$gasto -> id_gasto = $_POST["id_gasto"];
	$gasto -> ajaxEditarGasto();

}