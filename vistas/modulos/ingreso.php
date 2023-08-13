<?php

if($_SESSION["cajas"] == "off"){

	echo '<script>

	window.location = "inicio";

	</script>';

	return;

}


?>
<div class="content-wrapper">

	<section class="content-header">

		<h1>

			Administrar Ingresos Caja General

		</h1>

		<ol class="breadcrumb">

			<li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

			<li class="active">Administrar Ingresos Caja General</li>

		</ol>

	</section>

	<section class="content">

		<div class="box">

			<div class="box-header with-border">

				<button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">

					Ingresar Dinero

				</button>

			</div>

			<div class="box-body">

				<table class="table table-bordered table-striped dt-responsive tablaIngreso" width="100%">

					<thead>

						<tr>
							<th>Código</th>
							<th>Fecha</th>
							<th>Descripción</th>
							<th>Valor</th>
							<th>Usuario</th>
							<th>Acciones</th>

						</tr> 

					</thead>      

				</table>


				<input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

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

        	<h4 class="modal-title">Agregar Dinero</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

        	<div class="box-body">
        		<!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

        		<div class="form-group ">

        			<!-- ENTRADA PARA FECHA DEL INRESO A LA CAJA GENERAL -->
        			
        			<div class="form-group ">
        				<label for="">Fecha</label>
        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-calendar "></i></span> 

        					<input type="date" class="form-control input-lg" id="fecha_ingreso" name="fecha_ingreso"  required>

        				</div>

        			</div>

        			

        			<!-- ENTRADA PARA LA DESCRIPCIÓN DEL INGRESO DEL DINERO-->

        			<div class="form-group">
        				<label for="">Descripción</label>
        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-align-left "></i></span> 

        					<input type="text" class="form-control input-lg" name="descripcion_ingreso" placeholder="Describa el Ingreso" required>
        					<input type="hidden" name="id_vendedor_fk" value="<?php echo $_SESSION["id"]; ?>" id="idVendedor">
        					
        				</div>

        			</div>


        			<!-- ENTRADA PARA EL VALOR DEL INGRESO A LA CAJA GENERAL -->

        			<div class="form-group">

        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-usd"></i></span> 

        					<input type="number" class="form-control input-lg" name="valor_ingreso" min="0" placeholder="Valor del Ingreso Ej : 5000" required>

        				</div>

        			</div>

        		</div>



        	</div>




        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

        	<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>

        	<button type="submit" class="btn btn-success">Guardar Ingreso</button>

        </div>

    </form>

    <?php

    $crearingreso = new ControladorIngreso();
    $crearingreso -> ctrCrearIngreso();

    ?>  

</div>

</div>

</div>

<!--=====================================
MODAL EDITAR 
======================================-->

<div id="modalEditarIngreso" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

        	<button type="button" class="close" data-dismiss="modal">&times;</button>

        	<h4 class="modal-title">Editar Ingreso</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body"> <!-- ABRIR CUERPO DEL MODAL -->

        	<div class="box-body">

        		<!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

        		<div class="form-group ">

        			<!-- ENTRADA PARA FECHA DEL INGRESO -->

        			<div class="form-group ">
        				<label for="">Fecha</label>
        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-calendar "></i></span> 
        					<input type="hidden" id="id_ingreso" name="id_ingreso">
        					<input type="date" class="form-control input-lg" id="editarFechaIngreso" name="editarFechaIngreso"  required>

        				</div>

        			</div>



        			<!-- ENTRADA PARA LA DESCRIPCIÓN -->

        			<div class="form-group">
        				<label for="">Descripción</label>
        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-align-left "></i></span> 

        					<input type="text" class="form-control input-lg" name="editarDescripcionIngreso" id="editarDescripcionIngreso" placeholder="Ingresar descripción Ej : Compra Escoba" required>

        					<input type="hidden" name="id_vendedor_fk" value="<?php echo $_SESSION["id"]; ?>" id="id_vendedor_fk">
        					
        				</div>

        			</div>


        			<!-- ENTRADA PARA STOCK -->

        			<div class="form-group">

        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-usd"></i></span> 

        					<input type="number" class="form-control input-lg" name="editarValorIngreso" id="editarValorIngreso" min="0" placeholder="Valor del Ingreso Ej : 5000" required>

        				</div>

        			</div>

        		</div>

        	</div>

        </div> <!-- CERRAR CUERPO DEL MODAL -->
        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

        	<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>

        	<button type="submit" class="btn btn-success">Guardar Cambios</button>

        </div>

    </form>

    <?php

    $editarIngreso = new ControladorIngreso();
    $editarIngreso -> ctrEditarIngreso();

    ?>      

</div>

</div>

</div>
<?php

$eliminarIngreso = new ControladorIngreso();
$eliminarIngreso -> ctrEliminarIngreso();

?>




