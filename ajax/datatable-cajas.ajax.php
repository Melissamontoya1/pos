<?php

require_once "../controladores/caja.controlador.php";
require_once "../modelos/caja.modelo.php";


class TablaProductosCajas{

  /*=============================================
   MOSTRAR LA TABLA DE CAJAS
    =============================================*/

  public function mostrarTablaCajas(){

    $item = null;
      $valor = null;
      $orden = "id";

      $cajas = ControladorCaja::ctrMostrarCajas($item, $valor, $orden);

      if(count($cajas) == 0){

        echo '{"data": []}';

        return;
      }

      $datosJson = '{
      "data": [';

      for($i = 0; $i < count($cajas); $i++){





        if($cajas[$i]["fecha_cierre"]==""){
                  $botones =  "<button class='btn btn-danger btnCerrarCaja'  idCaja='".$cajas[$i]["id"]."' data-toggle='modal' data-target='#modalCerrarCaja'>Cerrar Caja</button>";
      }else{
          
          $botones = "<div class='btn-group'><button class='btn btn-info btnImprimirCierre'  required data-toggle='tooltip' data-placement='top' title='Imprimir' idCaja='".$cajas[$i]["id"]."''> <i class='fa fa-print'></i> Imprimir Ticket</button>";
      }

        $datosJson .='[
            "'.$cajas[$i]["id"].'",
            "'.$cajas[$i]["nombreUsuario"].'",
            "'.$cajas[$i]["fecha_apertura"].'",
            "'.$cajas[$i]["fecha_cierre"].'",
            "$'.number_format($cajas[$i]["importe_apertura"]).'",
            "$'.number_format($cajas[$i]["total_ventas"]).'",
            "$'.number_format($cajas[$i]["total_gastos"]).'",
            "'.$cajas[$i]["observaciones"].'",
             "$'.number_format($cajas[$i]["recuento_efectivo"]).'",
             "$'.number_format($cajas[$i]["diferencia"]).'",
              "$'.number_format($cajas[$i]["total_final"]).'",
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
ACTIVAR TABLA CAJAS
=============================================*/
$mostrarCajas = new TablaProductosCajas();
$mostrarCajas -> mostrarTablaCajas();
