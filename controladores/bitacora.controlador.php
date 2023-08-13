<?php

class ControladorBitacora{



	/*=============================================
	MOSTRAR BITACORA
	=============================================*/

	static public function ctrMostrarBitacora($item,$valor){

		$tabla = "bitacora";

		$respuesta = ModeloBitacora::mdlMostrarBitacora("bitacora", $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CREAR BITACORA
	=============================================*/

	static public function ctrCrearBitacora($datos){


		$tabla = "bitacora";

		$datos = array("descripcion"=>$datos["descripcion"],
					   "usuario"=>$datos["usuario"]
					   );

		$respuesta = ModeloBitacora::mdlIngresarBitacora($tabla, $datos);

			
	}
		/*=============================================
	BORRAR BITACORA
	=============================================*/

	static public function ctrBorrarBitacora(){

		if(isset($_GET["idBitacora"])){

			$tabla ="bitacora";
		

			$respuesta = ModeloBitacora::mdlBorrarBitacora($tabla);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido borrada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';
			}else{
                            
                            
                            
				echo'<script>

					swal({
						  type: "error",
						  title: "'.$respuesta.'",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';
                            
                        }
		}
		
	}

	

}