<?php




//FECHA ACTUAL

$fecha_actual = date("Y/m/d");
//sumo 1 día
$dteFechaVencimiento =date("Y/m/d",strtotime($fecha_actual."+ 30 days")); 
//resto 1 día
$item=null;
$valor=null;

$Empresa= new ControladorEmpresa();
$datosEmpresa= $Empresa->ctrMostrarEmpresas($item,$valor);

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Crear Cotización
      
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Crear Cotización</li>
      
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
                OBSERVACIONES
                ======================================-->

                <div class="form-group" >

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
                $valor = $_GET["idCotizacion"];

                $venta = ControladorVentas::ctrTraerCotizacion($valor);



                $itemUsuario = "id";
                $valorUsuario = $venta["id_vendedor"];

                $vendedor = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                $itemCliente = "id";
                $valorCliente = $venta["id_cliente"];

                $cliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
                
                if($venta["neto"]>0){
                  $porcentajeImpuesto = $venta["impuesto"] * 100 / $venta["neto"];
                }   


                ?>
                <div class="row">

                  <!--=====================================
                  ENCABEZADO
                  ======================================-->
                  
                  <div class="col-xs-12 pull-right">
                    
                    <table class="table">

                      <thead>
                        <th style="width: 16.66666667%"></th>  
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
                <center>
                  <div class="form-group row nuevoProducto ">

                   <?php

                   $listaProducto = json_decode($venta["productos"], true);

                   $idCotizacion = $_GET["idCotizacion"];

                   if ($idCotizacion>0){
                    foreach ($listaProducto as $key => $value) {

                      $item = "id";
                      $valor = $value["id"];
                      $orden = "id";

                      $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);

                      $stockAntiguo = $respuesta["stock"] + $value["cantidad"];

                      echo '<div class="row" style="padding:5px 15px">
                      <div class="'.$value["id"].'" id="renglonProducto">


                      <div class="col-xs-1" style="padding-right:0px">

                      <div class="input-group">

                      <button   class="btn btn-danger btn-sm  quitarProducto" idProducto="'.$value["id"].'"><strong>X</strong></button>
                      </div>


                      </div>



                      <div class="col-xs-4" style="padding-right:0px">

                      <div class="input-group">


                      <input type="text" class="form-control nuevaDescripcionProducto" idProducto="'.$value["id"].'" name="agregarProducto" id="agregarProducto" renglon="'.$value["renglon"].'" value="'.$value["descripcion"].'" required size="30" readonly>

                      </div>

                      </div>

                      <div class="col-xs-2">

                      <input type="number" step="any" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" id="nuevaCantidadProducto" min="1" value="'.$value["cantidad"].'" stock="'.$stockAntiguo.'" nuevoStock="'.$value["stock"].'" required>

                      </div>

                      <!-- Precio unitario -->


                      <div class="col-xs-2">



                      <input type="text" class="form-control nuevoPrecioUnitarioProducto" name="nuevoPrecioUnitarioProducto"  value="'.$value["precio"].'" required>



                      </div>

                      <div class="col-xs-2 ingresoPrecio" style="padding-left:0px">

                      <div class="input-group">

                      <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                      <input type="hidden" class="form-control nuevoPrecioProducto" precioReal="'.$respuesta["precio_venta"].'" name="nuevoPrecioProducto" value="'.$value["total"].'" readonly required size="30">

                      </div>

                      </div>




                      </div>


                      </div>

                      '

                      ;
                    }
                  }

                  ?>



                </div>
              </center>
              <input type="hidden" id="listaProductos" name="listaProductos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <!--  <button type="button" class="btn btn-default hidden-lg" data-toggle="modal" data-target="#tabladeproductos2">Agregar producto</button> -->
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
                            <input type="text" class="form-control input-lg" id="nuevoTotalIva" name="nuevoTotalIva" totalIva="" placeholder="00000" readonly required>
                            <input type="hidden" name="totalIva" id="totalIva">
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

                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" placeholder="Valor " required value="0">

                              <input type="hidden" class="form-control" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required>

                              <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required>

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

                <div class="form-group row" hidden>
                  
                  <div class="col-xs-6" style="padding-right:0px">
                    
                   <div class="input-group">
                    
                    <select class="form-control" id="nuevoMetodoPago" name="nuevoMetodoPago" required>
                      <option value="Efectivo">Efectivo</option>
                      <option value="TC">Tarjeta Crédito</option>
                      <option value="TD">Tarjeta Débito</option>                  
                    </select>    

                  </div>

                </div>

                <div class="cajasMetodoPago"></div>

                <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">

              </div>

              <br>
              
            </div>

          </div>

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
                
                <div class="form-group col-lg-6 col-md-6">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">

                  </div>

                </div> 

                <!--=====================================
                FECHA
                ======================================--> 
                <div class="form-group">
                 <div class="input-group date" data-provide="datepicker"  data-date-format="yyyy/mm/dd">
                  
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" class="form-control pull-right" id="fecha" name="fecha" value="<?php echo $fecha_actual; ?>"  placeholder="Fecha">

                </div>
              </div>

                 <!--=====================================
                TIPO VENTA
                ======================================-->
                
                <div class="form-group" hidden>
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 


                    <input type="text" name="TipoVenta" id="TipoVenta" value="COT">

                  </div>

                </div> 


                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================--> 

                <div class="form-group" hidden>
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                    <?php

                    $item = null;
                    $valor = null;

                    $ventas = ControladorVentas::ctrMostrarUltimoFolio("COT");


                    $codigo=$ventas["UltimoFolio"]+1;


                    echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="'.$codigo.'" readonly>';
                    

                    ?>
                    
                    
                  </div>
                  
                </div>

                <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    
                    <select class="form-control select2" id="seleccionarCliente" name="seleccionarCliente" required style="width: 100%;">

                     

                      <?php


                      if( $cliente["id"]<>0){
                       echo '<option value="'.$cliente["id"].'"> '.$cliente["nombre"].'</option>';
                     }

                     $item = null;
                     $valor = null;

                     $categorias = ControladorClientes::ctrMostrarClientes($item, $valor);

                     foreach ($categorias as $key => $value) {

                       echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';

                     }

                     ?>

                   </select>
                   
                   
                   
                 </div>
                 <span class="input-group-addon"><button type="button" class="btn btn-success " data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">Agregar cliente</button></span>
               </div>


                <!--=====================================
                FECHA VENCIMIENTO
                ======================================--> 
                <div class="form-group">
                 <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd" required>
                  
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <input type="text" class="form-control pull-right" name="FechaVencimiento" id="FechaVencimiento" placeholder="Fecha Vencimiento" value="<?php echo $dteFechaVencimiento; ?>" required >


                </div>
              </div>


                 <!--=====================================
                COTIZAR A
                ======================================-->
                
                <div class="form-group" >
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-commenting"></i></span> 

                    <input type="text" class="form-control pull-right" name="cotizarA" id="cotizarA" placeholder="Cotiza a:" value="<?php echo $venta["cotizarA"]; ?>" >

                  </div>

                </div> 


                 <!--=====================================
                OBSERVACIONES
                ======================================-->
                
                <div class="form-group" >
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-commenting"></i></span> 

                    <input type="text" class="form-control pull-right" name="Observaciones" placeholder="observaciones" value="<?php echo $venta["Observaciones"]; ?>" >

                  </div>

                </div> 


               <!--=====================================
               UUID
               ======================================-->

               <?php

               $UUID = ModeloVentas::mdlGeneraUUID();

               $UUID = $UUID["generaUUID"];

               ?>
               
               <div class="form-group" hidden>
                
                <div class="input-group">
                  
                  <span class="input-group-addon"><i class="fa fa-commenting"></i></span> 

                  <input type="text" class="form-control pull-right" name="UUID" id="UUID" placeholder="UUID" value="<?php echo $UUID; ?>" >

                </div>

              </div> 


                <!--=====================================
                TIEMPO ESTIMADO DE ENTREGA
                ======================================-->
                
                <div class="form-group" >
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-hourglass-2"></i></span> 

                    <input type="text" class="form-control pull-right" name="plazoEntrega" id="plazoEntrega" placeholder="Tiempo estimado de entrega" value="<?php echo $datosEmpresa[0]["diasEntrega"]; ?>">

                  </div>

                </div> 




                <div class="box-footer">

                 <!--  <button type="button" class="btn btn-primary pull-left " data-toggle="modal" data-target="#modalAgregarProducto" data-dismiss="modal">Nuevo Producto</button> -->

                 <button type="submit" class="btn btn-success btn-block">Guardar Cotización</button>

               </div>

             </form>

             <?php

             $guardarVenta = new ControladorVentas();
             $guardarVenta -> ctrCrearVenta();
             
             ?>

           </div>
           
         </div>


       </div>
       
     </section>

   </div>




<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->

<div id="modalAgregarProducto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            <div class="form-group" >
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control input-lg select2" id="nuevaCategoria" name="nuevaCategoria" required style="width: 100%;">
                  
                  <option value="">Selecionar categoría</option>

                  <?php

                  $item = null;
                  $valor = null;

                  $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                  }

                  ?>
                  
                </select>

              </div>

            </div>





            <!-- ENTRADA PARA EL CÓDIGO -->
            
            <div class="form-group">
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="nuevoCodigo" name="nuevoCodigo" placeholder="Ingresar código" readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

            <div class="form-group">
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDescripcion" id="nuevaDescripcion" placeholder="Ingresar descripción" required>

              </div>

            </div>

            <!-- ENTRADA PARA STOCK -->

            <div class="form-group" >
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                <input type="number" class="form-control input-lg" name="nuevoStock" id="nuevoStock"  value="1" placeholder="Stock">

              </div>

            </div>

            <!-- ENTRADA PARA PRECIO COMPRA -->

            <div class="form-group row">

              <div class="col-xs-6">
                
                <div class="input-group">
                  
                  <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                  <input type="number" class="form-control input-lg" id="nuevoPrecioCompra" name="nuevoPrecioCompra" step="any" min="0" placeholder="Precio de compra" required>

                </div>

              </div>

              <!-- ENTRADA PARA PRECIO VENTA -->

              <div class="col-xs-6">
                
                <div class="input-group">
                  
                  <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                  <input type="number" class="form-control input-lg" id="nuevoPrecioVenta" name="nuevoPrecioVenta" step="any" min="0" placeholder="Precio de venta" required>

                </div>
                
                <br>

                <!-- CHECKBOX PARA PORCENTAJE -->

                <div class="col-xs-6">
                  
                  <div class="form-group">
                    
                    <label>
                      
                      <input type="checkbox" class="minimal porcentaje" checked>
                      Utilizar procentaje
                    </label>

                  </div>

                </div>

                <!-- ENTRADA PARA PORCENTAJE -->

                <div class="col-xs-6" style="padding:0">
                  
                  <div class="input-group">
                    
                    <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="40" required>

                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                  </div>

                </div>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO 

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen" id="nuevaImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div> -->

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="button" class="btn btn-primary btnGuardarProductoAjax" data-dismiss="modal">Guardar producto</button>

        </div>

      </form>

      <?php

          //$crearProducto = new ControladorProductos();
          //$crearProducto -> ctrCrearProducto("cotizaciones");

      ?>  

    </div>

  </div>

</div>

      <!--=====================================
      LA TABLA DE PRODUCTOS MODAL
      ======================================-->


      <div class="col-lg-2 modal fade" id="tabladeproductos2"  role="dialog" width="50%">

        
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
              
              <table class="table table-bordered table-striped  tablaVentas" width="100%">

                
                
               <thead>

                 <tr>

                  <th>Código</th>
                  <th>Descripcion</th>
                  <th>Precio</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                </tr>

              </thead>

            </table>

          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          </div>




        </div>

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

                <input type="text" class="form-control input-lg" name="nuevoCliente" id="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA PERSONA O CONTACTO -->
            
            <div class="form-group">
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevoDocumentoId" id="nuevoDocumentoId" placeholder="Persona o contacto" >

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email" >

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask >

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" >

              </div>

            </div>

            <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->
            
            <div class="form-group" >
              
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" id="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask value="1900/01/01">

              </div>

            </div>
            
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          
          <button type="button" class="btn btn-default pull-left " data-dismiss="modal">Salir</button>

          <button type="button" class="btn btn-primary btnGuardarClienteAjax" data-dismiss="modal">Guardar cliente</button>

        </div>

      </form>

      <?php /*

        $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente();

        */

        ?>

      </div>

    </div>

  </div>

  <script type="text/javascript">
    

//VARIABLES
var lngContador=0;





/*=============================================
GUARDAR EL PRODUCTO AJAX
=============================================*/
$(".modal-footer").on("click", ".btnGuardarProductoAjax", function(){

  var nuevaCategoria= $("#nuevaCategoria").val();
  var nuevoCodigo= $("#nuevoCodigo").val();
  var nuevaDescripcion= $("#nuevaDescripcion").val();
  var nuevoStock= $("#nuevoStock").val();
  var nuevoPrecioCompra= $("#nuevoPrecioCompra").val();
  var nuevoPrecioVenta= $("#nuevoPrecioVenta").val();
  
  var idClienteModal= $("#idClienteModal").val();
  
  var crearProducto= "crearProducto";

  var datos = new FormData();
  datos.append("nuevoCodigo", nuevoCodigo);
  datos.append("nuevaCategoria", nuevaCategoria);
  datos.append("nuevaDescripcion", nuevaDescripcion);
  datos.append("nuevoStock", "0");
  datos.append("nuevoPrecioCompra", nuevoPrecioCompra);
  datos.append("nuevoPrecioVenta", nuevoPrecioVenta);
  
  datos.append("crearProducto", crearProducto);




  $.ajax({

    url:"controladores/productos.controlador.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
        //dataType:"json",
        success:function(respuesta){
          console.log("respuesta",respuesta);
          if (respuesta.match(/correctamente.*/)){
            


            swal({
              type: "success",
              title: "El producto ha sido guardado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar"
            }).then(function(result){
              
              

              
            }
            )
          }else{
            swal({
              type: "error",
              title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar"
            }).then(function(result){
              if (result.value) {



              }
            })
            
            
          }

        }

      }

      )

})




/*=============================================
GUARDAR EL CLIENTE AJAX
=============================================*/
$(".modal-footer").on("click", ".btnGuardarClienteAjax", function(){
  var nuevoCliente= $("#nuevoCliente").val();
  var nuevoDocumentoId= $("#nuevoDocumentoId").val();
  var nuevoEmail= $("#nuevoEmail").val();
  var nuevoTelefono= $("#nuevoTelefono").val();
  var nuevaDireccion= $("#nuevaDireccion").val();
  var nuevaFechaNacimiento= $("#nuevaFechaNacimiento").val();
  var guardarAjax= "guardarAjax";

  var ajaxCliente= "ajaxCliente";



  var datos = new FormData();
  datos.append("nuevoCliente", nuevoCliente);
  datos.append("nuevoDocumentoId", nuevoDocumentoId);
  datos.append("nuevoEmail", nuevoEmail);
  datos.append("nuevoTelefono", nuevoTelefono);
  datos.append("nuevaDireccion", nuevaDireccion);
  datos.append("nuevaFechaNacimiento", nuevaFechaNacimiento);
  datos.append("guardarAjax", guardarAjax);

  var datosLeer = new FormData();



  datosLeer.append("ajaxCliente", ajaxCliente);

  $.ajax({

    url:"controladores/clientes.controlador.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
        //dataType:"json",
        success:function(respuesta){
         

          if (respuesta.match(/correctamente.*/)){
            



            swal({
              type: "success",
              title: "El cliente ha sido guardado correctamente",
              showConfirmButton: true,
              confirmButtonText: "Cerrar"
            }).then(function(result){
              
              $.ajax({

                url:"controladores/clientes.controlador.php",
                method: "POST",
                data: datosLeer,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                  
                  var resultado=respuesta;
                  $("#seleccionarCliente").select2({ data: resultado });
                }
              })
              

              
            }
            )
          }else{
            swal({
              type: "error",
              title: "¡El cliente no puede ir con los campos vacíos o llevar caracteres especiales!",
              showConfirmButton: true,
              confirmButtonText: "Cerrar"
            }).then(function(result){
              if (result.value) {



              }
            })
            
            
          }

        }

      }

      )





})

window.onload=function() {    
  
  listarProductos();
  $('#nuevaCantidadProducto').trigger("change"); 
}


//SEGUIMIENTO DEL STOCK EN LA VENTA
var totalP= new Array();

//SEGUIMIENTO DEL STOCK EN LA VENTA POR PRODUCTO
var stockP= new Array();

//RENGLON DE LA VENTA
var lngContador=0;
//CODIGO BARRAS

$(document).ready(function(){
  $("#CodigoDeBarras").keydown(function(event){

    var term = $(this).val();


    if(event.which==13){


      agregarProductoCodigoBarras($("#CodigoDeBarras").val());

    }
  });
});

</script>

