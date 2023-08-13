<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";

class TablaProductos {
    /* =============================================
      MOSTRAR LA TABLA DE PRODUCTOS
      ============================================= */

    public function mostrarTablaProductos() {

        $item = null;
        $valor = null;
        $orden = "id";

        $request=$_REQUEST;

        $totalRenglones= ModeloProductos::mdlMostrarNumRegistros($request);


        if ($totalRenglones["totalRenglones"] == 0) {

            echo '{"data": []}';

            return;
        }

        $productos = ModeloProductos::mdlMostrarProductosServerSide("productos",$item, $request, $orden);

        $datosJson = '{
            "draw": '.intval($request["draw"]).',
        "recordsTotal":'.intval($totalRenglones["totalRenglones"]).',
        "recordsFiltered": '.intval($totalRenglones["totalRenglones"]).',

          "data": [';

        for ($i = 0; $i < count($productos); $i++) {
                  /* =============================================
              STOCK
              ============================================= */

            if ($productos[$i]["stock"] <= 3) {

                $stock = "<button class='btn btn-danger'>" . $productos[$i]["stock"] . "</button>";
            } else if ($productos[$i]["stock"] > 3 && $productos[$i]["stock"] <= 5) {

                $stock = "<button class='btn btn-warning'>" . $productos[$i]["stock"] . "</button>";
            } else {

                $stock = "<button class='btn btn-success'>" . $productos[$i]["stock"] . "</button>";
            }


            /* =============================================
              TRAEMOS LA IMAGEN
              ============================================= */

$botones =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$productos[$i]["id"]."'><i class='fa fa-thumbs-o-up' aria-hidden='true'></i></button></div>"; 

            $datosJson .= '[
                
                  "' . $productos[$i]["codigo"] . '",
                  "' . $productos[$i]["descripcion"] . '",
                     "' . $productos[$i]["precio_compra"] . '",
                  "' . $productos[$i]["precio_venta"] . '",
                     "' . $stock . '",
                  "' . $botones . '"
                ],';
        }

        $datosJson = substr($datosJson, 0, -1);

        $datosJson .= ']

         }';

        echo $datosJson;
    }

}

/* =============================================
  ACTIVAR TABLA DE PRODUCTOS
  ============================================= */
$activarProductos = new TablaProductos();
$activarProductos->mostrarTablaProductos();

