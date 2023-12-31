<?php


class ControladorPagos{

	/*=============================================
		TOTAL PAGOS POR CAJA
		=============================================*/

		static public function mdlMostrarPagosCaja($caja){



			$stmt = Conexion::conectar()->prepare("select sum(
				ifnull(importePagado,0)-
				ifnull(importeDevuelto,0)
				) as totalVentaCaja

				from pagos
				where idCaja=".$caja);


			if($stmt->execute()){
				return $stmt -> fetch();
				$stmt -> close();

				$stmt = null;

			}
			else{
				$arr = $stmt ->errorInfo();
				$arr[3]="ERROR";
				return $arr[2];
			}










		}


	/*=============================================
	LEER PAGOS
	=============================================*/

	static public function ctrLeerPagos($idVenta){
		if($idVenta>0){
			$datos = array("idVenta"=>$idVenta
		);

			$respuesta = ModeloPagos::mdlMostrarPagos("pagos", $datos);

			return $respuesta;

		}

	}


	/*=============================================
	LEER PAGO
	=============================================*/

	static public function ctrLeerPago($idPago){
		if($idPago>0){
			$datos = array("idPago"=>$idPago
		);

			$respuesta = ModeloPagos::mdlMostrarPago("pagos", $datos);

			return $respuesta;

		}

	}


	/*=============================================
	PAGOS PENDIENTES
	=============================================*/

	static public function ctrPagosPendientes(){


		$respuesta = ModeloPagos::mdlPagosPendientes("pagos");

		return $respuesta;


	}





	/*=============================================
	CREAR PAGO
	=============================================*/

	static public function ctrCrearPago(){


		if(isset($_POST["codigoVenta"]) ){







			/*=============================================
			GUARDAR EL PAGO
			=============================================*/


			//ASIGNAMOS EL VALOR A LA VARIABLES
			$importePagado=$_POST["nuevoValorEfectivo"];
			$importeDevuelto=$_POST["nuevoCambioEfectivo"];
			$importePendiente=$_POST["nuevoTotalVenta"];
			$fechaPago=$_POST["nuevoFechaPago"];

			$time = time();

			$time = date("h:i:s",$time);
			$valor1b = $fechaPago . ' ' . $time;
			$nuevoTipoPago=$_POST["nuevoTipoPago"];
			$idCaja=$_POST["nuevoCaja"];

			//LE ASIGNAMOS EL IMPORTE DEVUELTO SI ES QUE LO QUE PAGO EL CLIENTE ES MAYOR AL IMPORTE DE LA VENTA
			if($importeDevuelto<0){
				$importeDevuelto =0;
			}


			//VALIDAMOS QUE LO PAGADO NO SEA MAYOR A LA VENTA
			if(($importePagado-$importeDevuelto)>$importePendiente){
				echo'<script>

				swal({
					type: "error",
					title: "No se puede pagar mas del importe total de la venta",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
						if (result.value) {

							window.location = "ventas";

						}
						})

						</script>';

						return;
					}


					$datosPagos = array("idVenta"=>$_POST["codigoVenta"],
						"importePagado"=>$importePagado,
						"importeDevuelto"=>$importeDevuelto,
						"fechaPago"=>$valor1b,
						"tipoPago"=>$nuevoTipoPago,
						"idCaja"=>$idCaja


					);

					$respuestaPago = ModeloPagos::mdlIngresarPago("pagos", $datosPagos);

					if ($respuestaPago=="ok"){

						echo'<script>

						localStorage.removeItem("rango");

						swal({
							type: "success",
							title: "El pago ha sido guardado correctamente",
							showConfirmButton: true,
							confirmButtonText: "Cerrar"
							}).then(function(result){
								if (result.value) {

									window.location = "ventas";

								}
								})

								</script>';

							}





						}


					}


	/*=============================================
	ELIMINAR PAGO
	=============================================*/


	static public function ctrEliminarPago(){

		if(isset($_GET["idPagoEliminar"])){

			$idPago=$_GET["idPagoEliminar"];

			if($idPago>0){
				$datos = array("idPago"=>$idPago
			);

				$respuesta = ModeloPagos::mdlEliminarPago("pagos", $datos);


				if($respuesta=="ok"){

					echo'<script>

					swal({
						type: "success",
						title: "El pago ha sido borrado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

								window.location = "ventas";

							}
							})

							</script>';
						}

					}
				}

			}



		}

		if(isset($_POST["codigoVenta"])){

			require_once "../modelos/pagos.modelo.php";


			$guardarPago= new ControladorPagos();
			$guardarPago -> ctrCrearPago();


		}


		if(isset($_POST["idCajaBuscar"])){
			require_once "../modelos/pagos.modelo.php";
			$guardarPago= new ModeloPagos();
			$totalCaja=$guardarPago -> mdlMostrarPagosCaja($_POST["idCajaBuscar"]);

			echo json_encode($totalCaja);

		}
