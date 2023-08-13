<?php

if($_SESSION["cajas"] == "off"){

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

			Administrar Gastos

		</h1>

		<ol class="breadcrumb">

			<li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

			<li class="active">Administrar Gastos</li>

		</ol>

	</section>

	<section class="content">

		<div class="box">

			<div class="box-header with-border">

				<button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">

					Ingresar Gasto

				</button>

			</div>

			<div class="box-body">

				<table class="table table-bordered table-striped dt-responsive tablaGastos" width="100%">

					<thead>

						<tr>
							<th>Código</th>
							<th>Fecha</th>
							<th>Descripción</th>
							<th>Valor</th>
							<th>Tipo Caja</th>
							<th>Vendedor</th>
							<th>Caja</th>

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

        	<h4 class="modal-title">Agregar Gasto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

        	<div class="box-body">
        		<!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

        		<div class="form-group ">

        			<!-- ENTRADA PARA FECHA DEL GASTO -->
        			<div class="row">
        				<div class="form-group col-md-6">
        					<label for="">Fecha</label>
        					<div class="input-group">

        						<span class="input-group-addon"><i class="fa fa-calendar "></i></span> 

        						<input type="date" class="form-control input-lg" id="fecha_gasto" name="fecha_gasto"  required>

        					</div>

        				</div>
        				<!-- ENTRADA PARA ELEGIR DE QUE CAJA DARLE SALIDA-->

        				<div class="form-group col-md-6">
        					<label for="">Seleccione Caja</label>
        					<div class="input-group">

        						<span class="input-group-addon"><i class="fa fa-archive"></i></span> 

        						<select name="tipo_caja" id="" class="form-control input-lg">
        							<option value="General">Caja General</option>
        							<option value="Menor">Caja Menor</option>
        						</select>

        					</div>

        				</div>
        			</div>

        			<!-- ENTRADA PARA LA DESCRIPCIÓN -->

        			<div class="form-group">
        				<label for="">Descripción</label>
        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-align-left "></i></span> 

        					<input type="text" class="form-control input-lg" name="descripcion_gasto" placeholder="Ingresar descripción Ej : Compra Escoba" required>
        					<input type="hidden" name="id_vendedor_fk" value="<?php echo $_SESSION["id"]; ?>" id="idVendedor">
        					<input type="hidden" class="form-control" name="id_caja_fk" id="nuevoCaja" value="<?php echo $cajaAbierta["id"]; ?>" readonly>
        				</div>

        			</div>


        			<!-- ENTRADA PARA STOCK -->

        			<div class="form-group">

        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-usd"></i></span> 

        					<input type="number" class="form-control input-lg" name="valor_gasto" min="0" placeholder="Valor del Gasto Ej : 5000" required>

        				</div>

        			</div>

        		</div>



        	</div>

        	<!-- ENTRADA PARA SUBIR FOTO -->



        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

        	<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Salir</button>

        	<button type="submit" class="btn btn-success">Guardar Gasto</button>

        </div>

    </form>

    <?php

    $creargasto = new ControladorGasto();
    $creargasto -> ctrCrearGasto();

    ?>  

</div>

</div>

</div>

<!--=====================================
MODAL EDITAR 
======================================-->

<div id="modalEditarGasto" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

        	<button type="button" class="close" data-dismiss="modal">&times;</button>

        	<h4 class="modal-title">Editar Gasto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body"> <!-- ABRIR CUERPO DEL MODAL -->

        	<div class="box-body">

        		<!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

        		<div class="form-group ">

        			<!-- ENTRADA PARA FECHA DEL GASTO -->
        			<div class="row">
        				<div class="form-group col-md-6">
        					<label for="">Fecha</label>
        					<div class="input-group">

        						<span class="input-group-addon"><i class="fa fa-calendar "></i></span> 
        						<input type="hidden" id="id_gasto" name="id_gasto">
        						<input type="date" class="form-control input-lg" id="editarFechaGasto" name="editarFechaGasto"  required>

        					</div>

        				</div>
        				<!-- ENTRADA PARA ELEGIR DE QUE CAJA DARLE SALIDA-->

        				<div class="form-group col-md-6">
        					<label for="">Seleccione Caja</label>
        					<div class="input-group">

        						<span class="input-group-addon"><i class="fa fa-archive"></i></span> 

        						<select name="editarTipoCaja" id="editarTipoCaja" class="form-control input-lg">
        							<option value="General">Caja General</option>
        							<option value="Menor">Caja Menor</option>
        						</select>

        					</div>

        				</div>
        			</div>

        			<!-- ENTRADA PARA LA DESCRIPCIÓN -->

        			<div class="form-group">
        				<label for="">Descripción</label>
        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-align-left "></i></span> 

        					<input type="text" class="form-control input-lg" name="editarDescripcionGasto" id="editarDescripcionGasto" placeholder="Ingresar descripción Ej : Compra Escoba" required>
        					<input type="hidden" name="id_vendedor_fk" value="<?php echo $_SESSION["id"]; ?>" id="idVendedor">
        					<input type="hidden" class="form-control" name="id_caja_fk" id="nuevoCaja" value="<?php echo $cajaAbierta["id"]; ?>" readonly>
        				</div>

        			</div>


        			<!-- ENTRADA PARA STOCK -->

        			<div class="form-group">

        				<div class="input-group">

        					<span class="input-group-addon"><i class="fa fa-usd"></i></span> 

        					<input type="number" class="form-control input-lg" name="editarValorGasto" id="editarValorGasto" min="0" placeholder="Valor del Gasto Ej : 5000" required>

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

    $editarGasto = new ControladorGasto();
    $editarGasto -> ctrEditarGasto();

    ?>      

</div>

</div>

</div>
<?php

$eliminarGasto = new ControladorGasto();
$eliminarGasto -> ctrEliminarGasto();

?>




