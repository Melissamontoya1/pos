<?php
if($_SESSION["modificarVentas"] == "off"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}
$caja=new ModeloCaja();


$cajaAbierta=$caja->mdlVerificaCajaUsuario($_SESSION["id"]);

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Editar venta
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Crear venta</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="row">
        <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->

      <div class="col-lg-5 hidden-md hidden-xs">
        
        <div class="box box-warning">

          <div class="box-header with-border">
                                       <!--=====================================
               CODIGO DE BARRAS
               ======================================-->

               <div class="form-group" >
                <label for=""><B>Buscar por Codigo de Barras</B></label>
                <div class="input-group">

                  <span class="input-group-addon"><i class="fa fa-barcode"></i></span>

                  <input type="text" class="form-control pull-right" value="" name="CodigoDeBarras" id="CodigoDeBarras" placeholder="Codigo de barras" >

                </div>

              </div>
          </div>

          <div class="box-body">
            
            <table class="table table-bordered table-striped  tablaVentas">
              
               <thead>

                 <tr>
                  
                  <th>Código</th>
                  <th>Descripcion</th>
                  <th>Compra</th>
                  <th>Precio</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                </tr>

              </thead>

            </table>

          </div>

        </div>


      </div>
      <!--=====================================
      EL FORMULARIO
      ======================================-->
      
      <div class="col-lg-7 col-xs-12">
        
        <div class="box box-success">
          
          <div class="box-header with-border"></div>

          <form role="form" method="post" class="formularioVenta">

            <div class="box-body">
  
              <div class="box">

                <?php

                    $item = "id";
                    $valor = $_GET["idVenta"];

                    $venta = ControladorVentas::ctrMostrarVentas($item, $valor);

                    $itemUsuario = "id";
                    $valorUsuario = $venta["id_vendedor"];

                    $vendedor = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                    $itemCliente = "id";
                    $valorCliente = $venta["id_cliente"];

                    $cliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

                    $porcentajeImpuesto = $venta["impuesto"] * 100 / $venta["neto"];


                ?>



                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
            
                <div class="form-group col-md-6">
                
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control input-sm" id="nuevoVendedor" value="<?php echo $vendedor["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $vendedor["id"]; ?>">

                  </div>

                </div> 


                <!--=====================================
                 ENTRADA DE LA CAJA
                 ======================================-->

                 <div class="form-group" hidden>

                   <div class="input-group">

                     <span class="input-group-addon"><i class="fa fa-user"></i></span>

                     <input type="text" class="form-control" id="nuevoCaja" value="<?php echo $cajaAbierta["id"]; ?>" readonly>



                   </div>

                 </div>
                 <!--=====================================
                TIPO VENTA
                ======================================-->
            
                <div class="form-group" hidden>
                
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 


                    <input type="text" name="TipoVenta" id="TipoVenta" value="VEN">

                  </div>

                </div> 


                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================--> 

                <div class="form-group" hidden>
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                   <input type="text" class="form-control" id="nuevaVenta" name="editarVenta" value="<?php echo $venta["codigo"]; ?>" readonly>
               
                  </div>
                
                </div>

                 <!--=====================================
                ENTRADA DEL ID
                ======================================--> 

                <div class="form-group" hidden>
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                   <input type="text" class="form-control" id="idVenta" name="idVenta" value="<?php echo $venta["id"]; ?>" readonly>
               
                  </div>
                
                </div>

                <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================--> 

                <div class="form-group col-md-6">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    
                    <select class="form-control input-sm" id="seleccionarCliente" name="seleccionarCliente" required>

                    <option value="<?php echo $cliente["id"]; ?>"><?php echo $cliente["nombre"]; ?></option>

                    <?php

                      $item = null;
                      $valor = null;

                      $categorias = ControladorClientes::ctrMostrarClientes($item, $valor);

                       foreach ($categorias as $key => $value) {

                         echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';

                       }

                    ?>

                    </select>
                    
                    <span class="input-group-addon"><button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">Agregar cliente</button></span>
                  
                  </div>

                
                </div>


                <div class="row">

                  <!--=====================================
                  ENCABEZADO
                  ======================================-->
                  
                  <div class="col-xs-12 pull-right" >
                    
                    <table class="table">

                      <thead>
                        <th style="width: 16.66666667%">Quitar</th>  
                          <th style="width: 25%">Descripcion</th>      
                          <th style="width: 16.66666667%">Cantidad</th>    
                          <th style="width: 16.66666667%">Precio</th>    
                          <th style="width: 16.66666667%">Total</th> 
                        </tr>

                      </thead>

                     
                    </table>

                  </div>

                </div>
                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================--> 

                <div class="form-group row nuevoProducto">



                <?php

                $listaProducto = json_decode($venta["productos"], true);

                foreach ($listaProducto as $key => $value) {

                  $item = "id";
                  $valor = $value["id"];
                  $orden = "id";

                  $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);

                  $stockAntiguo = $respuesta["stock"] + $value["cantidad"];
                 
                  echo '<div class="row" style="padding:5px 15px">
                        <div class="'.$value["id"].'" id="renglonProducto">


                          <div class="col-xs-1" >
              
                            <div class="input-group">
  
                              <button   class="btn btn-danger btn-sm quitarProducto" idProducto="'.$value["id"].'"><strong>X</strong></button>
                            </div>


                          </div>


                        
                          <div class="col-xs-4" >
              
                            <div class="input-group">
                  
          
                              <input type="text" class="form-control nuevaDescripcionProducto input-sm" idProducto="'.$value["id"].'" name="agregarProducto" id="agregarProducto" renglon="'.$value["renglon"].'" value="'.$value["descripcion"].'" required size="30">

                            </div>

                          </div>

                          <div class="col-xs-2">
                
                            <input type="number" step="any" class="form-control nuevaCantidadProducto input-sm" name="nuevaCantidadProducto" id="nuevaCantidadProducto" min="1" value="'.$value["cantidad"].'" stock="'.$stockAntiguo.'" nuevoStock="'.$value["stock"].'" required>

                          </div>

                                       <!-- Precio unitario -->


                          <div class="col-xs-2">

                          

                           <input type="text" class="form-control nuevoPrecioUnitarioProducto input-sm" name="nuevoPrecioUnitarioProducto"  value="'.$value["precio"].'" required>
                                 
              
                             
                          </div>

                          <div class="col-xs-2 ingresoPrecio" >

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                     
                              <input type="text" class="form-control nuevoPrecioProducto input-sm" precioReal="'.$respuesta["precio_venta"].'" name="nuevoPrecioProducto" value="'.$value["total"].'" readonly required>
                             <input type="hidden" class="form-control nuevoIva"  name="nuevoIva" ivaReal="'.$value['iva'].'" value="'.$value['iva'].'" required>
     
                            </div>
                 
                         </div>


                         
                         
                          </div>


                        </div>

                        '

                        ;
                }


                ?>

                </div>

                <input type="hidden" id="listaProductos" name="listaProductos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

            

                     <div class="row">
                  <!--=====================================
                  SUBTOTAL ANTES DEL DESCUENTO
                  ======================================-->
                  
                  <div class="col-xs-12 pull-right">

                    <?php 
                   if($_SESSION["detalles_factura"] == "on"){
                     ?>
                     <table class="table" >
                     <?php }else{ ?>
                       <table class="table" hidden>
                       <?php } ?>

                      <thead>

                        <tr>

                          <th>Subtotal</th> 
                          <th>
                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevosubtotal" name="nuevosubtotal" subtotal="" placeholder="00000" readonly required value="<?php echo $venta["neto"]; ?>">

                              <input type="hidden" name="subtotal" id="subtotal" value="<?php echo $venta["neto"]; ?>">
                              

                            </div>
                          </tr>
                          <th>
                            Iva
                          </th>
                          <th>
                           <div class="input-group">
                            <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                            <input type="text" class="form-control input-lg" id="nuevoTotalIva" name="nuevoTotalIva" totalIva="" placeholder="00000" readonly required value="<?php echo $venta["impuesto"]; ?>">
                            <input type="hidden" name="totalIva" id="totalIva" value="<?php echo $venta["impuesto"]; ?>">
                          </div>
                        </th>
                      </th>     
                    </tr>

                  </thead>

                  <tbody>

                    <tr>

                      <td style="width: 50%"></td>

                      <td style="width: 50%"></td>

                    </tr>

                  </tbody>

                </table>

              </div>

                  <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->
                  
                  <div class="col-xs-12 pull-right">

                    <table class="table">

                      <thead>

                        <tr>
                          <th>Descuento</th>
                          <th>Total</th>      
                        </tr>

                      </thead>

                      <tbody>

                        <tr>

                          <td style="width: 50%">

                            <div class="input-group">

                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" placeholder="Valor " required value="<?php echo $venta["descuento"]; ?>">

                              <input type="hidden" class="form-control" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required value="<?php echo $venta["descuento"]; ?>">

                              <!-- <input type="text" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required> -->

                              <span class="input-group-addon"><i class="fa fa-hand-o-down"></i></span>

                            </div>

                          </td>

                          <td style="width: 50%">

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="<?php echo $venta["total"]; ?>" value="<?php echo $venta["total"]; ?>" placeholder="0" readonly required>

                              <input type="hidden" name="totalVenta" id="totalVenta" value="<?php echo $venta["total"]; ?>">
                              

                            </div>


                          </td>

                        </tr>

                      </tbody>

                    </table>

                  </div>

                </div>

        

                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->
             <div id="modalMetodoDePago" class=" metodoPago" >

                  <div class="box-body">
                    <?php

          //FECHA ACTUAL
                    $fechaActual =date("Y/m/d");

                    ?>

            <!--=====================================
                FECHA
                ======================================-->
                

                <input type="hidden" class="form-control pull-right" id="fechaPago" name="fechaPago" value="<?php echo $fechaActual; ?>"  placeholder="Fecha">

                

                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->

                <div class="form-group row" hidden>

                  <div class="col-xs-4" style="padding-right:0px">

                   <div class="input-group">

                    <select class="form-control" id="nuevoMetodoPago" name="nuevoMetodoPago" >
                      <option value="">Seleccione método de pago</option>
                      <option value="Efectivo">Efectivo</option>
                      <option value="TC">Tarjeta Crédito</option>
                      <option value="TD">Tarjeta Débito</option>
                      <option value="CR">Venta a Credito</option>
                    </select>

                  </div>

                </div>

                <div class="cajasMetodoPago"></div>

                <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">

              </div>

            </div>
          </div>

           
      
              </div>

          </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-success btn-block">Guardar cambios</button>

          </div>

        </form>

        <?php

          $editarVenta = new ControladorVentas();
          $editarVenta -> ctrEditarVenta();
          
        ?>

        </div>
            
      </div>



    </div>
   
  </section>

</div>
<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->

<div id="modalAgregarCliente" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

      </form>

      <?php

        $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente();

      ?>

    </div>

  </div>

</div>

<script type="text/javascript">



  //VARIABLES
var lngContador=0;

window.onload=function() {    
  listarProductos();
$('#nuevaCantidadProducto').trigger("change"); 
}

//SEGUIMIENTO DEL STOCK EN LA VENTA POR LINEA
var totalP= new Array();

//SEGUIMIENTO DEL STOCK EN LA VENTA POR PRODUCTO
var stockP= new Array();

//RENGLON DE LA VENTA
var lngContador=0;

$(document).ready(function(){
  $("#CodigoDeBarras").keydown(function(event){

    var term = $(this).val();


    if(event.which==13){


      agregarProductoCodigoBarras($("#CodigoDeBarras").val());

    }
  });
});


  
</script>