<?php
if($_SESSION["cajas"] == "off"){

	echo '<script>

	window.location = "inicio";

	</script>';

	return;

}
$item = null;
$valor = null;
$orden = "id";
$ingreso = ControladorIngreso::ctrSumaTotalIngreso();
$total_ingreso=$ingreso["total_ingreso"];
$gastos = ControladorGasto::ctrSumaTotalGasto();
$total_gastos=$gastos["total_gasto"];
$totalPagado = ControladorVentas::ctrSumaTotalPagos();
$total_ventas=$totalPagado["totalPagado"];
$total_caja=($total_ingreso+$total_ventas)-($total_gastos);

?>
<div class="content-wrapper">

	<section class="content-header">

		<h1>

			Administrar Caja General

		</h1>

		<ol class="breadcrumb">

			<li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

			<li class="active">Administrar Caja General</li>

		</ol>

	</section>

	<section class="content">

		<div class="box">

			<div class="box-header with-border">
				<!-- SUMA DE Ingresos -->
				<div class="col-lg-3 col-xs-6">

					<div class="small-box bg-yellow">

						<div class="inner">

							<h3>$<?php echo number_format($total_ingreso); ?></h3>

							<p>Ingresos a capital</p>

						</div>

						<div class="icon">

							<i class="ion ion-social-usd"></i>

						</div>

						<a href="ventas" class="small-box-footer">

							Más info <i class="fa fa-arrow-circle-right"></i>

						</a>

					</div>

				</div>
				<!-- SUMA DE VENTAS -->
				<div class="col-lg-3 col-xs-6">

					<div class="small-box bg-aqua">

						<div class="inner">

							<h3>$<?php echo number_format($total_ventas); ?></h3>

							<p>Ventas (Pagos / Abonos a creditos)</p>

						</div>

						<div class="icon">

							<i class="ion ion-social-usd"></i>

						</div>

						<a href="ventas" class="small-box-footer">

							Más info <i class="fa fa-arrow-circle-right"></i>

						</a>

					</div>

				</div>
				<!-- SUMA DE VENTAS -->
				<div class="col-lg-3 col-xs-6">

					<div class="small-box bg-red">

						<div class="inner">

							<h3>$<?php echo number_format($total_gastos); ?></h3>

							<p>Gastos</p>

						</div>

						<div class="icon">

							<i class="ion ion-social-usd"></i>

						</div>

						<a href="ventas" class="small-box-footer">

							Más info <i class="fa fa-arrow-circle-right"></i>

						</a>

					</div>

				</div>
				<!-- SUMA DE VENTAS -->
				<div class="col-lg-3 col-xs-6">

					<div class="small-box bg-green">

						<div class="inner">

							<h3>$<?php echo number_format($total_caja); ?></h3>

							<p>Total en Caja</p>

						</div>

						<div class="icon">

							<i class="ion ion-social-usd"></i>

						</div>

						<a href="ventas" class="small-box-footer">

							Más info <i class="fa fa-arrow-circle-right"></i>

						</a>

					</div>

				</div>

			</div>

			<div class="box-body">

				<form action="" method="POST">

					<div class="form-group row">

						<div class="col-xs-4">
							<label for="">Fecha Inicio</label>
							<div class="input-group">

								<span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

								<input type="date" class="form-control " id="fecha_inicio" name="fecha_inicio" step="any" min="0"  required>

							</div>
						</div>
						<div class="col-xs-4">
							<label for="">Fecha Fin</label>
							<div class="input-group">

								<span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

								<input type="date" class="form-control " id="fecha_fin" name="fecha_fin" step="any" min="0"  required>

							</div>
						</div>

						<div class="col-xs-4">
							<label for="">Tipo Reporte</label>
							<div class="input-group">

								<span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

								<select name="tipo_reporte" id="tipo_reporte" class="form-control">
									<option value="pdf">PDF</option>
									<option value="excel">Excel</option>
								</select>

							</div>
						</div>
						</div>
						<div class="col-xs-12 ">
							<button type="submit" class="btn btn-primary btn-block general">Generar Reporte</button>
						</div>
				
				</form>
			</div>

		</div>

	</section>

</div>


<script type="text/javascript">
  $(".general").on("click",  function () {
var fechaInicial = $("#fecha_inicio").val();
    var fechaFinal = $("#fecha_fin").val();
    var tipo_reporte = $("#tipo_reporte").val();
  //alert(fechaFinal+ fechaInicial+tipo_reporte);
    window.open("vistas/modulos/reporte-cajageneral.php?fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal+"&tipo_reporte="+tipo_reporte,"_blank");
  })
</script>