<?php
if($_SESSION["datosEmpresa"] == "off"){

  echo '<script>

  window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">
  <section class="content-header">

    <h1>

      Datos Empresa

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Datos Empresa </li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-body card ">



        <?php

        $item = null;
        $valor = null;

        $empresa= ControladorEmpresa::ctrMostrarEmpresas($item, $valor);

        foreach ($empresa as $key => $value){


          echo '
          <div class="row">
          <div class="col-md-6 card">
          <label> NIT : </label>
          '.$value["RFC"].'
          </div>
          <div class="col-md-6 ">
          <label> Nombre Empresa : </label> 
          '.$value["NombreEmpresa"].'
          </div>
          <div class="col-md-6 ">
          <label>Dirección Empresa : </label>
          '.$value["DireccionEmpresa"].'
          </div>
          <div class="col-md-6 ">
          <label> Telefonos : </label> 
          '.$value["Telefono"].'
          </div>
          <div class="col-md-6 ">
          <label> Correo Electronico : </label>
          '.$value["correoElectronico"].'
          </div>
          <div class="col-md-6 ">
          <label> Pie de Pagina : </label>
          '.$value["pie_pagina"].'
          </div>
          <div class="col-md-6 ">
          <label> Porcentaje Precio Productos : </label>
          '.$value["porcentaje_producto"].'
          </div>
          <div class="col-md-6 ">
          <label> Impresora : </label>
          '.$value["impresora"].'
          </div>
          </div>

          '


          ;




          echo '
          

          <button class="btn btn-warning btnEditarEmpresa" NombreEmpresa="'.$value["NombreEmpresa"].'" data-toggle="modal" data-target="#modalEditarEmpresa"><i class="fa fa-pencil"></i> Editar Datos</button>

          

          ';
        }


        ?>



      </div>

    </div>

  </section>

</div>



<!--=====================================
MODAL EDITAR EMPRESA
======================================-->

<div id="modalEditarEmpresa" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar empresa</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-institution"></i></span>

                <input type="text" class="form-control input-lg" id="editarNombreEmpresa" name="editarNombreEmpresa" value="" required placeholder="Ingresar Nombre">

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCION -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-pin"></i></span>

                <input type="text" class="form-control input-lg" id="editarDireccionEmpresa" name="editarDireccionEmpresa" value="" required placeholder="Ingresar Direccion">

              </div>

            </div>

            <!-- ENTRADA PARA EL RFC -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-qrcode"></i></span>

                <input type="text" class="form-control input-lg" id="editarRFC" name="editarRFC" value="" required placeholder="Ingresar RFC">

              </div>

            </div>

            <!-- ENTRADA PARA EL TELEFONO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-mobile-phone"></i></span>

                <input type="text" class="form-control input-lg" id="editarTelefonoEmpresa" name="editarTelefonoEmpresa" value="" required placeholder="Ingresar Telefono">

              </div>

            </div>

            <!-- ENTRADA PARA EL CORREO ELECTRONICO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>

                <input type="text" class="form-control input-lg" id="editarCorreoElectronicoEmpresa" name="editarCorreoElectronicoEmpresa" value="" required placeholder="Ingresar Correo Electronico">

              </div>

            </div>

            <!-- ENTRADA PARA DIAS ENTREGA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-hourglass-start"></i></span>

                <input type="text" class="form-control input-lg" id="editarDiasEntrega" name="editarDiasEntrega" value=""  placeholder="Dias de entrega">

              </div>

            </div>
            <!-- ENTRADA PARA PIE DE PAGINA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>

                <input type="text" class="form-control input-lg" id="pie_pagina" name="pie_pagina" value=""  placeholder="Pie de Pagina">

              </div>

            </div>
            <!-- ENTRADA PARA PORCENTAAJE PRODUCTO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                <input type="number" class="form-control input-lg" id="porcentaje_producto" name="porcentaje_producto" value=""  placeholder="Porcentaje Productos">

              </div>

            </div>
                                 <!-- ENTRADA PARA IMPRESORA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-print"></i></span>

                <input type="text" class="form-control input-lg" id="impresora" name="impresora" value=""  placeholder="Nombre Impresora">

              </div>

            </div>


            <!--Valida Arqueo de Caja -->

            <div class="form-group">

             <div class="input-group">

               <div class="checkbox">

                 <label>

                   <input type="checkbox" data-toggle="toggle" name="caja" id="caja" data-on="Si" data-off="No">

                   Valida Arqueo de Caja

                 </label>

               </div>

             </div>

           </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar usuario</button>

        </div>
      </form>
      <?php

      $editarEmpresa = new ControladorEmpresa();
      $editarEmpresa -> ctrEditarEmpresa();

      ?>



    </div>

  </div>

</div>

</div>
</div>
<script>
  $('#modalEditarEmpresa').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Button that triggered the modal

          var NombreEmpresa = button.data('NombreEmpresa'); // Extract info from data-* attributes
         
          

          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this);
          modal.find('.modal-body #editarNombreEmpresa').val(NombreEmpresa);
          

        });
</script>