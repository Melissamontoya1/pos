<?php

class ControladorIngreso{

	/*=============================================
	CREAR INGRESO A LA CAJA GENERAL
	=============================================*/

	static public function ctrCrearIngreso(){

		if(isset($_POST["descripcion_ingreso"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["descripcion_ingreso"]) ){

				$tabla = "ingreso_caja";

				$datos = array("fecha_ingreso"=>$_POST["fecha_ingreso"],
					"descripcion_ingreso"=>$_POST["descripcion_ingreso"],
					"valor_ingreso"=>$_POST["valor_ingreso"],
					"id_vendedor_fk"=>$_POST["id_vendedor_fk"]
				);

					//ENVIAR LA INFORMACION POR MEDIO DE UN ARREGLO AL INSERT UBICADO EN EL MODELO 
				$respuesta = ModeloIngreso::mdlIngresarIngreso($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						type: "success",
						title: "El cliente ha sido guardado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

								window.location = "ingreso";

							}
							})

							</script>';

						}

					}else{

						echo'<script>

						swal({
							type: "error",
							title: "¡'.$respuesta[2].'El cliente no puede ir vacío o llevar caracteres especiales!",
							showConfirmButton: true,
							confirmButtonText: "Cerrar"
							}).then(function(result){
								if (result.value) {

									window.location = "ingreso";

								}
								})

								</script>';



							}

						}else{
							return "error";
						}

					}
		/*=============================================
	MOSTRAR INGRESO
	=============================================*/

	static public function ctrMostrarIngreso($item, $valor){

		$tabla = "ingreso_caja";

		$respuesta = ModeloIngreso::mdlMostrarIngreso($tabla, $item, $valor);

		return $respuesta;

	}


	/*=============================================
	MOSTRAR INGRESO AJAX
	=============================================*/

	static public function ctrMostrarIngresoAjax(){

		$tabla = "ingreso_caja";

		$respuesta = ModeloIngreso::mdlMostrarIngresoAjax();

		return $respuesta;

	}
	/*=============================================
	EDITAR Ingreso
	=============================================*/

	static public function ctrEditarIngreso(){

		if(isset($_POST["editarDescripcionIngreso"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcionIngreso"]) ){

				$tabla = "ingreso_caja";

				$datos = array(
					"id_ingreso"=>$_POST["id_ingreso"],
					"fecha_ingreso"=>$_POST["editarFechaIngreso"],
					"descripcion_ingreso"=>$_POST["editarDescripcionIngreso"],
					"valor_ingreso"=>$_POST["editarValorIngreso"],
					"id_vendedor_fk"=>$_POST["id_vendedor_fk"]
					
					
				);

				$respuesta = ModeloIngreso::mdlEditarIngreso($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						type: "success",
						title: "El Ingreso ha sido cambiado correctamente '.$_POST["editarFechaIngreso"].'",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

								window.location = "ingreso";

							}
							})

							</script>';

						}

					}else{

						echo'<script>

						swal({
							type: "error",
							title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
							showConfirmButton: true,
							confirmButtonText: "Cerrar"
							}).then(function(result){
								if (result.value) {

									window.location = "ingreso";

								}
								})

								</script>';



							}

						}

					}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function ctrEliminarIngreso(){

		if(isset($_GET["id_ingreso"])){

			$tabla ="ingreso_caja";
			$datos = $_GET["id_ingreso"];

			$respuesta = ModeloIngreso::mdlEliminarIngreso($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					type: "success",
					title: "El Ingreso ha sido borrado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
					}).then(function(result){
						if (result.value) {

							window.location = "ingreso";

						}
						})

						</script>';

					}		

				}

			}


    /* =============================================
      SUMA TOTAL INGRESOS
      ============================================= */

      static public function ctrSumaTotalIngreso() {

      	$tabla = "ingreso_caja";

      	$respuesta = ModeloIngreso::mdlSumaTotalIngreso($tabla);

      	return $respuesta;
      }


  }

