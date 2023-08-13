
$('.tablaGastos').DataTable( {
	"ajax": "ajax/datatable-gastos.ajax.php",
	"deferRender": true,
	"serverSide" : true,
	"retrieve": true,
	"processing": true,
	"language": {

		"sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún dato disponible en esta tabla",
		"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
		},
		"oAria": {
			"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		}

	},
	"order": [[ 0, "desc" ]]

} )



/*=============================================
EDITAR CLIENTE
=============================================*/
$(".tablaGastos").on("click", ".btnEditarGasto", function(){

	var id_gasto = $(this).attr("id_gasto");

	var datos = new FormData();
	datos.append("id_gasto", id_gasto);

	$.ajax({

		url:"ajax/gastos.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType:"json",
		success:function(respuesta){

			$("#id_gasto").val(respuesta["id_gasto"]);
			$("#editarFechaGasto").val(respuesta["fecha_gasto"]);
			$("#editarDescripcionGasto").val(respuesta["descripcion_gasto"]);
			$("#editarValorGasto").val(respuesta["valor_gasto"]);
			$("#editarTipoCaja").val(respuesta["tipo_caja"]);
		
		}

	})

})

/*=============================================
ELIMINAR GASTO
=============================================*/
$(".tablaGastos").on("click", ".btnEliminarGasto", function(){

	var id_gasto = $(this).attr("id_gasto");
	
	swal({
		title: '¿Está seguro de borrar el gasto?',
		text: "¡Si no lo está puede cancelar la acción!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, borrar gasto!'
	}).then(function(result){
		if (result.value) {

			window.location = "index.php?ruta=gastos&id_gasto="+id_gasto;
		}

	})

})