
$('.tablaIngreso').DataTable( {
	"ajax": "ajax/datatable-ingreso.ajax.php",
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
EDITAR INGRESO
=============================================*/
$(".tablaIngreso").on("click", ".btnEditarIngreso", function(){

	var id_ingreso = $(this).attr("id_ingreso");

	var datos = new FormData();
	datos.append("id_ingreso", id_ingreso);

	$.ajax({

		url:"ajax/ingreso.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType:"json",
		success:function(respuesta){

			$("#id_ingreso").val(respuesta["id_ingreso"]);
			$("#editarFechaIngreso").val(respuesta["fecha_ingreso"]);
			$("#editarDescripcionIngreso").val(respuesta["descripcion_ingreso"]);
			$("#editarValorIngreso").val(respuesta["valor_ingreso"]);
			$("#id_vendedor_fk").val(respuesta["id_vendedor_fk"]);
		
		}

	})

})

/*=============================================
ELIMINAR INGRESO DE LA CAJA GENERAL
=============================================*/
$(".tablaIngreso").on("click", ".btnEliminarIngreso", function(){

	var id_ingreso = $(this).attr("id_ingreso");
	
	swal({
		title: '¿Está seguro de borrar el Ingreso?',
		text: "¡Si no lo está puede cancelar la acción!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, borrar Ingreso!'
	}).then(function(result){
		if (result.value) {

			window.location = "index.php?ruta=ingreso&id_ingreso="+id_ingreso;
		}

	})

})