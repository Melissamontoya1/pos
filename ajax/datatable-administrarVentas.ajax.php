<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../modelos/clientes.modelo.php";
require_once "../modelos/usuarios.modelo.php";
require_once "../controladores/utilerias.controlador.php";
session_start();

error_reporting(E_ERROR | E_PARSE);
class TablaVentas{

    /*=============================================
     MOSTRAR LA TABLA DE VENTAS
     =============================================*/

     public function mostrarTablaVentas($columnas,$filtros,$busqueda){


        //TOTAL RENGLONGLES EN LA TABLA VENTAS

      $pendientePorCobrar = $this->pendientePorCobrar;
      $soloCobrado = $this->soloCobrado;
      $pendientes =$this->pendientes;
      $cliente = $this->cliente;
      $fechaInicial = $this->fechaInicial;
      $fechaFinal = $this->fechaFinal;
$fechaFinal = $this->fechaFinal;



      $renglones = ControladorVentas::ctrRangoFechasVentas($fechaInicial
        , $fechaFinal
        , "VEN"
        , $pendientePorCobrar
        , $soloCobrado
        , $pendientes
        , $cliente
        , "n"
        ,$busqueda
      );

      $totalRenglones=count($renglones);




      $ventas = ControladorVentas::ctrRangoFechasVentas($fechaInicial
        , $fechaFinal
        , "VEN"
        , $pendientePorCobrar
        , $soloCobrado
        , $cliente
        , $filtros
        , $busqueda
      );


      if(count($ventas) == 0){

       echo '{"data": []}';

       return;
     }


     if(isset($filtros["draw"])){
      $filtros2["draw"]=$filtros["draw"];
    }else{
      $filtros2["draw"]=1;
    }

    $datosJson = '
    {
      "draw": '.intval($filtros2["draw"]).',
      "recordsTotal":'.intval($totalRenglones).',
      "recordsFiltered": '.intval($totalRenglones).',

      "data": [';

      for($i = 0; $i < count($ventas); $i++){
        //BOTONES
        $botones ="<button type='button' class='btn btn-primary  btn-block btnproductos' data-toggle='modal' data-target='#btnproductos' data-dismiss='modal' UUID='".$ventas[$i]["UUID"]."'  restante='".$restante."'  required data-toggle='tooltip' data-placement='top' title='Abonar'>Productos</button>";
        $botones .= "<div class='btn-group'><button class='btn btn-info btnImprimirFactura'  required data-toggle='tooltip' data-placement='top' title='Imprimir Ticket' codigoVenta='".$ventas[$i]["UUID"]."''> <i class='fa fa-print'></i> </button> <button class='btn btn-danger btnImprimirFacturaPdf'  required data-toggle='tooltip' data-placement='top' title='Imprimir PDF' codigoVenta='".$ventas[$i]["UUID"]."''> <i class='fa fa-file-pdf-o'></i> </button>";

        if($_SESSION["modificarVentas"] == "on"){

          $botones .= "<button class='btn btn-warning btnEditarVenta' required data-toggle='tooltip' data-placement='top' title='Editar Venta'idVenta='".$ventas[$i]["id"]."'><i class='fa fa-pencil'></i></button>";
        }


        $restante=$ventas[$i]["total"] - $ventas[$i]["importePagado"];


        if($_SESSION["pagos"] == "on"){

          if($restante<=0){
            $botones .=  "<button type='button'  class='btn btn-success' data-toggle='modal' data-dismiss='modal' idCodigo='".$ventas[$i]["codigo"]."' restante='".$restante."' required data-toggle='tooltip' data-placement='top' title='Documento ya pagado'><i class='fa fa-check-square'></i></button>";


          }
          else{
            $botones .=  "<button type='button' class='btn btn-success btnAbonar' data-toggle='modal' data-target='#modalMetodoDePago' data-dismiss='modal' idCodigo='".$ventas[$i]["codigo"]."'  restante='".$restante."'  required data-toggle='tooltip' data-placement='top' title='Abonar'>Pagar&nbsp;&nbsp;&nbsp;</button>";


          }


        }


        if($_SESSION["historicoPagos"] == "on"){

          $botones .=  "<button type='button' class='btn bg-maroon btnMostrarPagos' data-toggle='modal' data-target='#modalHistoricoDePagos' data-dismiss='modal' idCodigo='".$ventas[$i]["codigo"]."'  restante='".$restante."' required data-toggle='tooltip' data-placement='top' title='Ver Historico de pagos'><i class='fa fa-search'></i> Ver&nbsp;&nbsp;&nbsp;</button>";

        }
        if($_SESSION["eliminarVentas"] == "on"){
         $botones .= "<button class='btn btn-danger btnEliminarVenta' required data-toggle='tooltip' data-placement='top' title='Eliminar' idVenta='".$ventas[$i]["id"]."''><i class='fa fa-trash'></i> Eliminar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>";

       }


       $cliente = ModeloClientes::mdlMostrarClientes("clientes", "id", $ventas[$i]["id_cliente"]);
       $vendedor = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $ventas[$i]["id_vendedor"]);

if(!empty($ventas[$i]["cliente_provicional"]) ){
            $nomCliente=$ventas[$i]["cliente_provicional"];
        }else{
       
           $nomCliente=$cliente["nombre"];
        }

       $datosJson .='[
       "'.$ventas[$i]["id"].'",
       "'.$ventas[$i]["codigo"].'",
       "'.$nomCliente.'",
       "'.$vendedor["nombre"].'",
       "'.$ventas[$i]["metodo_pago"].'",
       "'.number_format($ventas[$i]["neto"]).'",
       "'.number_format($ventas[$i]["total"]).'",
       "'.number_format($ventas[$i]["importePagado"]).'",
       "'.number_format($ventas[$i]["total"]-$ventas[$i]["importePagado"]).'",
       "'.$ventas[$i]["fecha"].'",
       "'.$botones .'"
     ],';

   }



   $datosJson = substr($datosJson, 0, -1);

   $datosJson .=   ']

 }';

 echo $datosJson;


}


}

/*=============================================
ACTIVAR TABLA
=============================================*/

$activarVentas= new TablaVentas();




if(isset($_POST["pendientePorCobrar"])){
  $activarVentas -> pendientePorCobrar=$_POST["pendientePorCobrar"];

}else{
  $activarVentas -> pendientePorCobrar="n";
}

if(isset($_POST["soloCobrado"])){
  $activarVentas -> soloCobrado=$_POST["soloCobrado"];
}else{
  $activarVentas -> soloCobrado= "n";
}



if(isset($_POST["pendientes"])){
  $activarVentas -> pendientes=$_POST["pendientes"];
}else{
  $activarVentas -> pendientes ="n";
}

if(isset($_POST["cliente"])){
  $activarVentas -> cliente=$_POST["cliente"];
}else{
  $activarVentas -> cliente="n";
}

if(isset($_POST["fechaInicial"])){


  $fechaInicial = $_POST["fechaInicial"];
  $fechaFinal = $_POST["fechaFinal"];

  $activarVentas -> fechaInicial=$_POST["fechaInicial"];
  $activarVentas -> fechaFinal=$_POST["fechaFinal"];

}else{

  $fechaInicial = null;
  $fechaFinal = null;
  $activarVentas -> fechaInicial=null;
  $activarVentas -> fechaFinal=null;



}

$request=$_REQUEST;

$busqueda=$_REQUEST;

$columnas="";

$activarVentas -> mostrarTablaVentas($columnas,$request,$busqueda);
