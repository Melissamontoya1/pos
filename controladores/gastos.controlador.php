<?php

class ControladorGasto{

	/*=============================================
	CREAR CLIENTES
	=============================================*/

	static public function ctrCrearGasto(){

		if(isset($_POST["descripcion_gasto"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["descripcion_gasto"]) ){

				$tabla = "gastos";
				if ($_POST["tipo_caja"]=="General") {
					$caja="0";
				}else{
					$caja=$_POST["id_caja_fk"];
				}
				$datos = array("fecha_gasto"=>$_POST["fecha_gasto"],
					"descripcion_gasto"=>$_POST["descripcion_gasto"],
					"valor_gasto"=>$_POST["valor_gasto"],
					"tipo_caja"=>$_POST["tipo_caja"],
					"id_vendedor_fk"=>$_POST["id_vendedor_fk"],
					"id_caja_fk"=>$caja);

				$respuesta = ModeloGasto::mdlIngresarGasto($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						type: "success",
						title: "El gasto ha sido guardado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

								window.location = "gastos";

							}
							})

							</script>';

						}

					}else{

						echo'<script>

						swal({
							type: "error",
							title: "¡'.$respuesta[2].'El gasto no puede ir vacío o llevar caracteres especiales!",
							showConfirmButton: true,
							confirmButtonText: "Cerrar"
							}).then(function(result){
								if (result.value) {

									window.location = "gastos";

								}
								})

								</script>';



							}

						}else{
							return "error";
						}

					}
		/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function ctrMostrarGasto($item, $valor){

		$tabla = "gastos";

		$respuesta = ModeloGasto::mdlMostrarGasto($tabla, $item, $valor);

		return $respuesta;

	}


	/*=============================================
	MOSTRAR CLIENTES AJAX
	=============================================*/

	static public function ctrMostrarGastoAjax(){

		$tabla = "gastos";

		$respuesta = ModeloGasto::mdlMostrarGastoAjax();

		return $respuesta;

	}
	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function ctrEditarGasto(){

		if(isset($_POST["editarDescripcionGasto"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcionGasto"]) ){

				$tabla = "gastos";

				$datos = array(
					"id_gasto"=>$_POST["id_gasto"],
					"descripcion_gasto"=>$_POST["editarDescripcionGasto"],
					"valor_gasto"=>$_POST["editarValorGasto"],
					"tipo_caja"=>$_POST["editarTipoCaja"],
					"fecha_gasto"=>$_POST["editarFechaGasto"]
				);

				$respuesta = ModeloGasto::mdlEditarGasto($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						type: "success",
						title: "El Gasto ha sido cambiado correctamente '.$_POST["editarFechaGasto"].'",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

								window.location = "gastos";

							}
							})

							</script>';

						}

					}else{

						echo'<script>

						swal({
							type: "error",
							title: "¡El gasto no puede ir vacío o llevar caracteres especiales!",
							showConfirmButton: true,
							confirmButtonText: "Cerrar"
							}).then(function(result){
								if (result.value) {

									window.location = "gastos";

								}
								})

								</script>';



							}

						}

					}

	/*=============================================
	ELIMINAR GASTO
	=============================================*/

	static public function ctrEliminarGasto(){

		if(isset($_GET["id_gasto"])){

			$tabla ="gastos";
			$datos = $_GET["id_gasto"];

			$respuesta = ModeloGasto::mdlEliminarGasto($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					type: "success",
					title: "El gasto ha sido borrado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
					}).then(function(result){
						if (result.value) {

							window.location = "gastos";

						}
						})

						</script>';

					}		

				}

			}

	 /* =============================================
      SUMA TOTAL INGRESOS
      ============================================= */

      static public function ctrSumaTotalGasto() {

      	$tabla = "gastos";

      	$respuesta = ModeloGasto::mdlSumaTotalGasto($tabla);

      	return $respuesta;
      }

  


  }

    if(isset($_POST["idCaja"])){
			require_once "../modelos/gastos.modelo.php";
			$guardarGastos= new ModeloGasto();
			$totalCaja=$guardarGastos -> mdlMostrarGastosCaja($_POST["idCaja"]);

			echo json_encode($totalCaja);

		}