/*=============================================
 CARGAR LA TABLA DINÁMICA DE VENTAS
 =============================================*/

// $.ajax({

// 	url: "ajax/datatable-ventas.ajax.php",
// 	success:function(respuesta){

// 		console.log("respuesta", respuesta);

// 	}

// })// 

$('.tablaVentas').DataTable({

    "ajax": "ajax/datatable-ventas.ajax.php",
    "deferRender": true,
    "retrieve": true,
    "processing": true,
    "serverSide" : true,

    
    "language": {

        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }

    },
    "order": [[ 1, "desc" ]]

});


/*=============================================
 AGREGA PRODUCTO CON EL LECTOR DEL CODIGO DE BARRAS
 =============================================*/

function agregarProductoCodigoBarras(valor = "") {

    //console.log(valor);

    //BUSCAR ID
    var datosLector = new FormData();
    datosLector.append("buscarCodigoBarras", "buscarCodigoBarras");
    datosLector.append("codigoBarras", valor);



    var idProducto = "";
    var categoria = "";

    $.ajax({

        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datosLector,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {


            idProducto = respuesta["id"];
            categoria = respuesta["id_categoria"];
            console.log("respuesta", respuesta);
            console.log("idProducto", idProducto);


            if (respuesta == false) {
                swal({
                    title: "El código no existe",
                    type: "error",
                    confirmButtonText: "¡Cerrar!"
                });
                $("#CodigoDeBarras").val("");
                return;
            }

            var minimoCompra = $(this).attr("minimoCOmpra");

            if (minimoCompra == "") {
                minimoCompra = 1;
            }

            lngContador = lngContador + 1;

            if (totalP[idProducto] == null) {
                totalP[idProducto] = 0;
            }





            //$(this).removeClass("btn-primary agregarProducto");

            $(this).addClass("btn-default");

            var datos = new FormData();
            datos.append("idProducto", idProducto);
            datos.append("categoria", categoria);


            $.ajax({

                url: "ajax/productos.ajax.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (respuesta) {
                    console.log("respuesta", respuesta);
                    var codigo = respuesta["codigo"];
                    var descripcion = respuesta["descripcion"];
                    var stock = respuesta["stock"];
                    var precio_compra = respuesta["precio_compra"];
                    var precio = respuesta["precio_venta"];
                    var iva_producto = respuesta["iva_producto"];
            //CALCULAR IVA DE UN PRODUCTO
                    var ivaunacantidad = Number(precio * iva_producto/100);
                    stockP[idProducto] = stock;



                    /*=============================================
                     EVITAR AGREGAR PRODUTO CUANDO EL STOCK ESTÁ EN CERO
                     =============================================

                     if ((stock - totalP[idProducto]) == 0 && document.getElementById("TipoVenta").value != "COT") {

                        swal({
                            title: "No hay stock disponible",
                            type: "error",
                            confirmButtonText: "¡Cerrar!"
                        });

                        $("button[idProducto='" + idProducto + "']").addClass("btn-primary agregarProducto");

                        return;

                    }*/


                    if (lngContador > 1) {

                        var contadorProductos = 0;


                        //NOS TRAEMOS TODA LOS PRODUCTOS QUE HEMOS AGREGADO A LA VENTA
                        var jsonProductos = $("#listaProductos").val();

                        //LO AGREGAMOS A UN ARREGLO
                        var myArr = JSON.parse(jsonProductos);


                        //RECORREMOS TODO EL ARREGLO DE LOS ṔRODUCTOS AGREGADOS A LA VENTA
                        $.each(myArr, function (i, item) {
                            //console.log(myArr[i].renglon);

                            //HACE LA SUMA DE LA CANTIDAD SI EL PRODUCTO ES EL MISMO AL QUE ESTAMOS MODIFICANDO Y SI ESTA EN DIFIRENTE RENGLON
                            if (idProducto == myArr[i].id) {

                                contadorProductos = Number(contadorProductos) + Number(myArr[i].cantidad);

                            }
                        });

                        //VALIDA STOCK CON LO YA AGREGAMOS A LA VENTA MAS
                        if (stockP[idProducto] < 1 + contadorProductos && document.getElementById("TipoVenta").value != "COT") {
                            $(this).val(stockP[idProducto] - contadorProductos);


                            swal({
                                title: "No hay stock disponible",
                                type: "error",
                                confirmButtonText: "¡Cerrar!"
                            });


                            return;
                        }
                    }


                    $(".nuevoProducto").append(
                        '<div class="row" style="padding:5px 15px" id="row' + lngContador + '">' +
                        '<div class="' + lngContador + '" id="renglonProducto"' +
                        ' </div> ' +
                        '<!-- Descripción del producto -->' +
                        '  <div class="col-xs-1" >' +
                        '             <div class="input-group"> ' +
                        '<button type="button" class="btn btn-danger btn-sm quitarProducto" idProducto="' + idProducto + '"><STRONG>X</STRONG></button>' +
                        '             </div>' +
                        '           </div>' +
                        '<div class="col-xs-4" style="padding-right:0px">' +
                        '<div class="input-group">' +
                        '<input type="text" id="nuevaDescripcionProducto"  class="form-control nuevaDescripcionProducto input-sm" renglon="' + lngContador + '" idProducto="' + idProducto + '" name="agregarProducto" value="' + descripcion + '"  required size="30">' +
                        '<input type="hidden" id="codigo"  class="form-control codigo input-sm"  name="codigo" value="' + codigo + '"  required size="30">' +
                        '</div>' +
                        '</div>' +
                        '<!-- Cantidad del producto -->' +
                        '<div class="col-xs-2" >' +
                        '<input type="number" class="form-control nuevaCantidadProducto input-sm" step="any" name="nuevaCantidadProducto" min="1" value="1" stock="' + stock + '" nuevoStock="' + Number(stock - 1) + '" required>' +
                        '</div>' +
                        '<!-- Precio unitario -->' +
                        '<div class="col-md-2">' +
                        '<input type="number" step="any" class="form-control nuevoPrecioUnitarioProducto input-sm" name="nuevoPrecioUnitarioProducto"  value="' + precio + '"  required >' +
                        '<input type="hidden" class="form-control precio_compra"  name="precio_compra" value="'+precio_compra+'"  required>'+


                        '</div>' +
                        '<!-- Precio del producto -->' +
                        '<div class="col-md-2 ingresoPrecio"  >' +
                        '<div class="input-group">' +
                        '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                        '<input type="text" class="form-control nuevoPrecioProducto input-sm"  precioReal="' + precio + '" name="nuevoPrecioProducto" value="' + precio + '"  required>' +
                        '<input type="hidden" class="form-control nuevoIva"  name="nuevoIva" ivaReal="'+ivaunacantidad+'" value="'+ivaunacantidad+'" required>'+
                        '</div>' +
                        '</div>' +
                    //'<button class="btn btn-success btnActivar" ><strong>1</strong></button>'+
                        '</div>' +
                        '</div>')
                    // SUMAR TOTAL DE PRECIOS

                    sumarTotalPrecios()
                    // SUMAR IVA 

                    sumarTotalIva()

                    // AGREGAR IMPUESTO


                    agregarImpuesto()

                    // AGRUPAR PRODUCTOS EN FORMATO JSON

                    listarProductos()

                    // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

                    $(".nuevoPrecioProducto").number(true);

                    $(".nuevoIva").number(true);
                    localStorage.removeItem("quitarProducto");

                    totalP[idProducto] = totalP[idProducto] + 1;
                    stockP[idProducto] = stock;

                    console.log(idProducto, totalP[idProducto])
                    $("#CodigoDeBarras").val("");

                }

            })


}
})





}


/*=============================================
 AGREGANDO PRODUCTOS A LA VENTA DESDE LA TABLA
 =============================================*/

$(".tablaVentas tbody").on("click", "button.agregarProducto", function () {

    var idProducto = $(this).attr("idProducto");
    var categoria = $(this).attr("categoria");

    var minimoCompra = $(this).attr("minimoCOmpra");

    if (minimoCompra == "") {
        minimoCompra = 1;
    }

    lngContador = lngContador + 1;

    if (totalP[idProducto] == null) {
        totalP[idProducto] = 0;
    }


    console.log(idProducto, totalP[idProducto]);


    //$(this).removeClass("btn-primary agregarProducto");

    $(this).addClass("btn-default");

    var datos = new FormData();
    datos.append("idProducto", idProducto);
    datos.append("categoria", categoria);


    $.ajax({

        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            console.log("respuesta", respuesta);
            var codigo = respuesta["codigo"];
            var descripcion = respuesta["descripcion"];
            var stock = respuesta["stock"];
            var precio_compra = respuesta["precio_compra"];
            var precio = respuesta["precio_venta"];
            var iva_producto = respuesta["iva_producto"];
            //CALCULAR IVA DE UN PRODUCTO
            var ivaunacantidad = Number(precio * iva_producto/100);

            stockP[idProducto] = stock;



            /*=============================================
             EVITAR AGREGAR PRODUTO CUANDO EL STOCK ESTÁ EN CERO
             =============================================

             if ((stock - totalP[idProducto]) == 0 && document.getElementById("TipoVenta").value != "COT") {

                swal({
                    title: "No hay stock disponible",
                    type: "error",
                    confirmButtonText: "¡Cerrar!"
                });

                $("button[idProducto='" + idProducto + "']").addClass("btn-primary agregarProducto");

                return;

            } */


            if (lngContador > 1) {

                var contadorProductos = 0;


                //NOS TRAEMOS TODA LOS PRODUCTOS QUE HEMOS AGREGADO A LA VENTA
                var jsonProductos = $("#listaProductos").val();

                //LO AGREGAMOS A UN ARREGLO
                var myArr = JSON.parse(jsonProductos);


                //RECORREMOS TODO EL ARREGLO DE LOS ṔRODUCTOS AGREGADOS A LA VENTA
                $.each(myArr, function (i, item) {
                    //console.log(myArr[i].renglon);

                    //HACE LA SUMA DE LA CANTIDAD SI EL PRODUCTO ES EL MISMO AL QUE ESTAMOS MODIFICANDO Y SI ESTA EN DIFIRENTE RENGLON
                    if (idProducto == myArr[i].id) {

                        contadorProductos = Number(contadorProductos) + Number(myArr[i].cantidad);

                    }
                });

                // //VALIDA STOCK CON LO YA AGREGAMOS A LA VENTA MAS
                // if (stockP[idProducto] < 1 + contadorProductos && document.getElementById("TipoVenta").value != "COT") {
                //     $(this).val(stockP[idProducto] - contadorProductos);


                //     swal({
                //         title: "No hay stock disponible",
                //         type: "error",
                //         confirmButtonText: "¡Cerrar!"
                //     });


                //     return;
                // }
            }





            $(".nuevoProducto").append(
                '<div class="row" style="padding:5px 15px" id="row' + lngContador + '">' +
                '<div class="' + lngContador + '" id="renglonProducto"' +
                ' </div> ' +
                '<!-- Descripción del producto -->' +
                '  <div class="col-xs-1" >' +
                '             <div class="input-group"> ' +
                '<button type="button" class="btn btn-danger btn-sm quitarProducto" idProducto="' + idProducto + '"><STRONG>X</STRONG></button>' +
                '             </div>' +
                '           </div>' +
                '<div class="col-xs-4" style="padding-right:0px">' +
                '<div class="input-group">' +
                '<input type="text" id="nuevaDescripcionProducto"  class="form-control nuevaDescripcionProducto input-sm" renglon="' + lngContador + '" idProducto="' + idProducto + '" name="agregarProducto" value="' + descripcion + '"  required size="30" >' +
                '<input type="hidden" id="codigo"  class="form-control codigo input-sm"  name="codigo" value="' + codigo + '"  required size="30">' +
                '</div>' +
                '</div>' +
                '<!-- Cantidad del producto -->' +
                '<div class="col-xs-2" >' +
                '<input type="number" class="form-control nuevaCantidadProducto input-sm" step="any" name="nuevaCantidadProducto" min="1" value="1" stock="' + stock + '" nuevoStock="' + Number(stock - 1) + '" required>' +
                '</div>' +
                '<!-- Precio unitario -->' +
                '<div class="col-md-2">' +
                '<input type="number" step="any" class="form-control nuevoPrecioUnitarioProducto input-sm" name="nuevoPrecioUnitarioProducto"  value="' + precio + '"  required >' +
                '<input type="hidden" class="form-control precio_compra"  name="precio_compra" value="'+precio_compra+'"  required>'+


                '</div>' +
                '<!-- Precio del producto -->' +
                '<div class="col-md-2 ingresoPrecio"  >' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                '<input type="text" class="form-control nuevoPrecioProducto input-sm"  precioReal="' + precio + '" name="nuevoPrecioProducto" value="' + precio + '"  required>' +
                '<input type="hidden" class="form-control nuevoIva"  name="nuevoIva" ivaReal="'+ivaunacantidad+'" value="'+ivaunacantidad+'" required>'+
                '</div>' +
                '</div>' +
                    //'<button class="btn btn-success btnActivar" ><strong>1</strong></button>'+
                '</div>' +
                '</div>')

            // SUMAR TOTAL DE PRECIOS

            sumarTotalPrecios()
            // SUMAR IVA 

            sumarTotalIva()

            // AGREGAR IMPUESTO

            agregarImpuesto()

            // AGRUPAR PRODUCTOS EN FORMATO JSON

            listarProductos()

            // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

            $(".nuevoPrecioProducto").number(true);


            localStorage.removeItem("quitarProducto");

            totalP[idProducto] = totalP[idProducto] + 1;
            stockP[idProducto] = stock;

            console.log(idProducto, totalP[idProducto])

        }

    })

});

/*=============================================
 CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA
 =============================================*/

$(".tablaVentas").on("draw.dt", function () {

    if (localStorage.getItem("quitarProducto") != null) {

        var listaIdProductos = JSON.parse(localStorage.getItem("quitarProducto"));

        for (var i = 0; i < listaIdProductos.length; i++) {

            $("button.recuperarBoton[idProducto='" + listaIdProductos[i]["idProducto"] + "']").removeClass('btn-default');
            $("button.recuperarBoton[idProducto='" + listaIdProductos[i]["idProducto"] + "']").addClass('btn-primary agregarProducto');

        }


    }


})


/*=============================================
 QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
 =============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProducto");

$(".formularioVenta").on("click", "button.quitarProducto", function () {

    $(this).parent().parent().parent().parent().remove();

    var idProducto = $(this).attr("idProducto");

    // LE RESTA -1 AL STOCK VENDIDO DE ESTA VENTA
    totalP[idProducto] = totalP[idProducto] - 1;

    /*=============================================
     ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
     =============================================*/

    if (localStorage.getItem("quitarProducto") == null) {

        idQuitarProducto = [];

    } else {

        idQuitarProducto.concat(localStorage.getItem("quitarProducto"))

    }

    idQuitarProducto.push({"idProducto": idProducto});

    localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto));

    $("button.recuperarBoton[idProducto='" + idProducto + "']").removeClass('btn-default');

    $("button.recuperarBoton[idProducto='" + idProducto + "']").addClass('btn-primary agregarProducto');

    if ($(".nuevoProducto").children().length == 0) {

        $("#nuevoImpuestoVenta").val(0);
        $("#nuevoTotalVenta").val(0);
        $("#totalVenta").val(0);
        $("#nuevoTotalVenta").attr("total", 0);
        //Poner en 0 el iva total
        $("#nuevoTotalIva").val(0);
        $("#totalIva").val(0);
        $("#nuevoTotalIva").attr("totalIva",0);
        //Poner en 0 el Subtotal
        $("#nuevosubtotal").val(0);
        $("#subtotal").val(0);
        $("#nuevosubtotal").attr("subtotal",0);

    } else {

        // SUMAR TOTAL DE PRECIOS

        sumarTotalPrecios()
        // SUMAR IVA 

        sumarTotalIva();
        // AGREGAR IMPUESTO

        agregarImpuesto()

        // AGRUPAR PRODUCTOS EN FORMATO JSON

        listarProductos()

    }

})

/*=============================================
 AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
 =============================================*/

var numProducto = 0;

$(".btnAgregarProducto").click(function () {

    numProducto++;

    var datos = new FormData();
    datos.append("traerProductos", "ok");

    $.ajax({

        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {

            $(".nuevoProducto").append(
                '<div class="row" style="padding:5px 15px">' +
                '<!-- Descripción del producto -->' +
                '<div class="col-md-6" >' +
                '<div class="input-group ">' +
                '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto><i class="fa fa-times"></i></button></span>' +
                '<select class="form-control nuevaDescripcionProducto" id="producto' + numProducto + '" idProducto name="nuevaDescripcionProducto" required>' +
                '<option>Seleccione el producto</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<!-- Cantidad del producto -->' +
                '<div class="col-xs-2 ingresoCantidad" >' +
                '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="0" stock nuevoStock required>' +
                '</div>' +
                '<!-- Precio del producto -->' +
                '<div class="col-xs-2 ingresoPrecio">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
                '<input type="text" class="form-control nuevoPrecioProducto" precioReal="" name="nuevoPrecioProducto"  required>' +
                '<input type="text" class="form-control nuevoIva" ivaReal=""  name="nuevoIva" required>'+
                '</div>' +
                '</div>' +
                '</div>');


            // AGREGAR LOS PRODUCTOS AL SELECT 

            respuesta.forEach(funcionForEach);

            function funcionForEach(item, index) {

                if (item.stock != 0) {

                    $("#producto" + numProducto).append(
                        '<option idProducto="' + item.id + '" value="' + item.descripcion + '">' + item.descripcion + '</option>'
                        )


                }

            }

            // SUMAR TOTAL DE PRECIOS

            sumarTotalPrecios()
            // SUMAR IVA 

            sumarTotalIva()
            // AGREGAR IMPUESTO

            agregarImpuesto()

             // PONER FORMATO AL PRECIO DE LOS PRODUCTOS Y AL IVA

            $(".nuevoPrecioProducto").number(true);
            $(".nuevoIva").number(true);



        }

    })

})

/*=============================================
 SELECCIONAR PRODUCTO
 =============================================*/

$(".formularioVenta").on("change", "select.nuevaDescripcionProducto", function () {

    var nombreProducto = $(this).val();

    var nuevaDescripcionProducto = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionProducto");

    var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

    var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");
    var nuevoIva = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoIva");
    var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);


    $.ajax({

        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {

            $(nuevaDescripcionProducto).attr("idProducto", respuesta["id"]);
            $(nuevaCantidadProducto).attr("stock", respuesta["stock"]);
            $(nuevaCantidadProducto).attr("nuevoStock", Number(respuesta["stock"]) - nuevaCantidadProducto);
            $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
            $(nuevoPrecioProducto).attr("precioReal", respuesta["precio_venta"]);
            $(nuevoIva).val(respuesta["nuevoIva"]);
            $(nuevoIva).attr("ivaReal", respuesta["nuevoIva"]);
            // AGRUPAR PRODUCTOS EN FORMATO JSON

            listarProductos()

        }

    })

})

/*=============================================
 MODIFICAR LA CANTIDAD
 =============================================*/

$(".formularioVenta ").on("change", "input.nuevaCantidadProducto", function () {


    var nombreDiv = $(this).parent().parent().parent().children();

    var idProducto1 = $(".formularioVenta ." + nombreDiv.attr("class") + " input.nuevaDescripcionProducto").attr("idProducto");

    var renglon = $(".formularioVenta ." + nombreDiv.attr("class") + " input.nuevaDescripcionProducto").attr("renglon");
    var iva = $(this).parent().parent().children(".ingresoPrecio").children().children(".nuevoIva");

    var ivaFinal = $(this).val() * iva.attr("ivaReal");
    
    iva.val(ivaFinal);
    var contadorProductos = 0;

    console.log($("#listaProductos").val());
    //NOS TRAEMOS TODA LOS PRODUCTOS QUE HEMOS AGREGADO A LA VENTA
    var jsonProductos = $("#listaProductos").val();

    //LO AGREGAMOS A UN ERREGLO
    var myArr = JSON.parse(jsonProductos);


    //RECORREMOS TODO EL ARREGLO DE LOS ṔRODUCTOS AGREGADOS A LA VENTA
    $.each(myArr, function (i, item) {
        //console.log(myArr[i].renglon);

        //HACE LA SUMA DE LA CANTIDAD SI EL PRODUCTO ES EL MISMO AL QUE ESTAMOS MODIFICANDO Y SI ESTA EN DIFIRENTE RENGLON
        if (idProducto1 == myArr[i].id && ((myArr[i].renglon) != renglon)) {

            contadorProductos = Number(contadorProductos) + Number(myArr[i].cantidad);

        }
    });



    //VALIDA STOCK CON LO YA AGREGAMOS A LA VENTA MAS
    // if (stockP[idProducto1] < Number($(this).val()) + Number(contadorProductos)) {
    //     $(this).val(Number(stockP[idProducto1]) - Number(contadorProductos));
    //     return;
    // }

    $(".formularioVenta ." + nombreDiv.attr("class") + " input.nuevoPrecioProducto").val($(this).val() * $(".formularioVenta ." + nombreDiv.attr("class") + " input.nuevoPrecioUnitarioProducto").val());
    // SUMAR TOTAL DE PRECIOS

    sumarTotalPrecios()

    
// SUMAR IVA 
    sumarTotalIva()
        // AGREGAR IMPUESTO
    agregarImpuesto()

    // AGRUPAR PRODUCTOS EN FORMATO JSON

    listarProductos()



})


/*=============================================
 MODIFICAR EL PRECIO
 =============================================*/

$(".formularioVenta").on("change", "input.nuevoPrecioUnitarioProducto", function () {

    var nombreDiv = $(this).parent().parent().parent().children();

    //$("#nuevoImpuestoVenta").val(0);
    $(".formularioVenta ." + nombreDiv.attr("class") + " input.nuevoPrecioProducto").val($(this).val() * $(".formularioVenta ." + nombreDiv.attr("class") + "  input.nuevaCantidadProducto").val());

    // SUMAR TOTAL DE PRECIOS

    sumarTotalPrecios()
// SUMAR IVA 

    sumarTotalIva()
    // AGREGAR IMPUESTO

    agregarImpuesto()

    // AGRUPAR PRODUCTOS EN FORMATO JSON

    listarProductos()


})



/*=============================================
 MODIFICAR LA DESCRIPCION
 =============================================*/

$(".nuevaDescripcionProducto").keyup(function () {


    listarProductos()



})


window.addEventListener("keyup", function (event) {

    $(".nuevaDescripcionProducto").trigger("change")

    listarProductos()



}, false);




/*=============================================
 SUMAR TODOS LOS PRECIOS
 =============================================*/

function sumarTotalPrecios() {

    var precioItem = $(".nuevoPrecioProducto");

    var arraySumaPrecio = [];

    for (var i = 0; i < precioItem.length; i++) {

        arraySumaPrecio.push(Number($(precioItem[i]).val()));


    }

    function sumaArrayPrecios(total, numero) {

        return total + numero;

    }

    var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);

    $("#nuevoTotalVenta").val(sumaTotalPrecio);
    $("#totalVenta").val(sumaTotalPrecio);
    $("#nuevoTotalVenta").attr("total", sumaTotalPrecio);

    $("#nuevosubtotal").val(sumaTotalPrecio);
    $("#subtotal").val(sumaTotalPrecio);
    $("#nuevosubtotal").attr("subtotal",sumaTotalPrecio);


}
    /*=============================================
SUMAR IVA DE LOS PRODUCTOS QUE CONTIENEN IVA
=============================================*/

function sumarTotalIva(){

    var ivaItem = $(".nuevoIva");
    
    var arraySumaIva = [];  

    for(var i = 0; i < ivaItem.length; i++){

        arraySumaIva.push(Number($(ivaItem[i]).val()));
        

    }

    function sumaArrayIva(totaliva, numero){

        return totaliva + numero;

    }

    var sumaTotalIva = arraySumaIva.reduce(sumaArrayIva);
    
    $("#nuevoTotalIva").val(sumaTotalIva);
    $("#totalIva").val(sumaTotalIva);
    $("#nuevoTotalIva").attr("totalIva",sumaTotalIva);
    var modificarsub = $("#nuevosubtotal").val();
    var modificariva = $("#nuevoTotalIva").val();
    var resultadosub = Number(modificarsub-modificariva);
    $("#nuevosubtotal").val(resultadosub);
    $("#subtotal").val(resultadosub);

}
/*=============================================
 FUNCIÓN AGREGAR IMPUESTO
 =============================================*/

function agregarImpuesto() {

    var impuesto = $("#nuevoImpuestoVenta").val();
    var precioTotal = $("#nuevoTotalVenta").attr("total");

    //var subTotal = Number(precioTotal);

    var precioImpuesto = Number(precioTotal-impuesto );

    var totalConImpuesto = Number(precioImpuesto) ;
    var subtotalresta = Number(precioTotal- totalConImpuesto);

    $("#nuevoTotalVenta").val(totalConImpuesto);

    $("#totalVenta").val(totalConImpuesto);

    $("#nuevoPrecioImpuesto").val(precioImpuesto);

    $("#nuevoPrecioNeto").val(precioTotal);
    
}

/*=============================================
 CUANDO CAMBIA EL IMPUESTO
 =============================================*/

$("#nuevoImpuestoVenta").change(function () {

    agregarImpuesto();

});

/*=============================================
 FORMATO AL PRECIO FINAL
 =============================================*/

$("#nuevoTotalVenta").number(true);
$("#nuevosubtotal").number(true);
$("#nuevoTotalIva").number(true);


/*=============================================
 SELECCIONAR MÉTODO DE PAGO
 =============================================*/

$(function () {
    $('#modalMetodoDePago').on('shown.bs.modal', function (e) {
        $('.focus').focus();
    })
});


$("#nuevoMetodoPago").change(function () {

    var metodo = $(this).val();
    
    if (metodo == "Efectivo") {

        $(this).parent().parent().removeClass("col-xs-6");

        $(this).parent().parent().addClass("col-xs-4");

        $(this).parent().parent().parent().children(".cajasMetodoPago").html(
            '<div class="col-xs-4">' +
            '<label>Valor Recibido</label>'+
            '<div class="input-group">' +
            
            '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
            '<input type="text" class="form-control focus" id="nuevoValorEfectivo"   placeholder="000000" required>' +
            '</div>' +
            '</div>' +
            '<div class="col-xs-4 " id="capturarCambioEfectivo" style="padding-left:0px">' +
            '<label>Cambio Efectivo</label>'+
            '<div class="input-group">' +

            '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
            '<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000"  required>' +
            '</div>' +
            '</div>'

            )

        // Agregar formato al precio

        $('#nuevoValorEfectivo').number(true);
        $('#nuevoCambioEfectivo').number(true);


        // Listar método en la entrada
        listarMetodos();
        $("#nuevoValorEfectivo").focus();

    } else {

        $(this).parent().parent().removeClass('col-xs-4');

        $(this).parent().parent().addClass('col-xs-4');

        $(this).parent().parent().parent().children('.cajasMetodoPago').html(
            '<div class="col-xs-4">' +
            '<label>Abono</label>'+
            '<div class="input-group">' +
            
            '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
            '<input type="text" class="form-control focus" id="nuevoValorEfectivo" placeholder="000000" required>' +
            '</div>' +
            '</div>' +
            '<div class="col-xs-4" style="padding-left:0px" hidden>' +

            '<div class="input-group">' +
            
            '<input type="number" min="0" value="1" class="form-control" name="nuevoCodigoTransaccion" id="nuevoCodigoTransaccion" placeholder="Código transacción"  required>' +
            '<span class="input-group-addon"><i class="fa fa-lock"></i></span>' +
            '</div>' +
            '</div>')

        listarMetodos();
        $("#nuevoValorEfectivo").focus();


    }



})

/*=============================================
 CAMBIO EN EFECTIVO
 =============================================*/
$(".metodoPago").on("keyup", "input#nuevoValorEfectivo", function () {



    var efectivo = $(this).val();


    var cambio = Number(efectivo) - Number($('#nuevoTotalVenta').val());

    if (Number(efectivo) < Number($('#nuevoTotalVenta').val())) {
        cambio = 0;
    }

    var nuevoCambioEfectivo = $(this).parent().parent().parent().children('#capturarCambioEfectivo').children().children('#nuevoCambioEfectivo');

    nuevoCambioEfectivo.val(cambio);



})

/*=============================================
 CAMBIO TRANSACCIÓN
 =============================================*/
$(".metodoPago").on("change", "input#nuevoCodigoTransaccion", function () {

    // Listar método en la entrada
    listarMetodos()


})


/*=============================================
 LISTAR TODOS LOS PRODUCTOS
 =============================================*/

function listarProductos() {

    var listaProductos = [];
    var codigo = $(".codigo");
    var descripcion = $(".nuevaDescripcionProducto");
    var cantidad = $(".nuevaCantidadProducto");
    var precio_compra = $(".precio_compra");
    var precio = $(".nuevoPrecioProducto");
    var precioUnitario = $(".nuevoPrecioUnitarioProducto");
    var iva_producto = $(".nuevoIva");
    var renglon = $(this).parent().parent().parent().children();

    for (var i = 0; i < descripcion.length; i++) {



        listaProductos.push({"id": $(descripcion[i]).attr("idProducto"),
            "renglon": $(descripcion[i]).attr("renglon"),
            "codigo": $(codigo[i]).val(),
            "descripcion": $(descripcion[i]).val(),
            "cantidad": $(cantidad[i]).val(),
            "stock": $(cantidad[i]).attr("nuevoStock"),
            "precio_compra": $(precio_compra[i]).val(),
            "precio": $(precioUnitario[i]).val(),
            "total": $(precio[i]).val(),
            "iva" : $(iva_producto[i]).attr("ivaReal"),
            "iva_final" : $(iva_producto[i]).val()
        })

    }

    $("#listaProductos").val(JSON.stringify(listaProductos));

}

/*=============================================
 LISTAR MÉTODO DE PAGO
 =============================================*/

function listarMetodos() {

    var listaMetodos = "";

    if ($("#nuevoMetodoPago").val() == "Efectivo") {

        $("#listaMetodoPago").val("Efectivo");

    } else {



        $("#listaMetodoPago").val($("#nuevoMetodoPago").val() + "-" + $("#nuevoCodigoTransaccion").val());

    }

}

/*=============================================
 BOTON EDITAR VENTA
 =============================================*/
$(".AdministrarVentas").on("click", ".btnEditarVenta", function () {

    var idVenta = $(this).attr("idVenta");

    window.location = "index.php?ruta=editar-venta&idVenta=" + idVenta;


})

/*=============================================
 BOTON EDITAR COTIZACION
 =============================================*/
$(".administrarCotizaciones").on("click", ".btnEditarCotizacion", function () {

    var idVenta = $(this).attr("idVenta");



    window.location = "index.php?ruta=editar-cotizacion&idVenta=" + idVenta;


})

/*=============================================
 FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
 =============================================*/

function quitarAgregarProducto() {

    //Capturamos todos los id de productos que fueron elegidos en la venta
    var idProductos = $(".quitarProducto");

    //Capturamos todos los botones de agregar que aparecen en la tabla
    var botonesTabla = $(".tablaVentas tbody button.agregarProducto");




    //Recorremos en un ciclo para obtener los diferentes idProductos que fueron agregados a la venta
    for (var i = 0; i < idProductos.length; i++) {

        //Capturamos los Id de los productos agregados a la venta
        var boton = $(idProductos[i]).attr("idProducto");

        //Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
        for (var j = 0; j < botonesTabla.length; j++) {

            if ($(botonesTabla[j]).attr("idProducto") == boton) {

                $(botonesTabla[j]).removeClass("btn-primary agregarProducto");
                $(botonesTabla[j]).addClass("btn-default");

            }
        }

    }

}

/*=============================================
 CADA VEZ QUE CARGUE LA TABLA CUANDO NAVEGAMOS EN ELLA EJECUTAR LA FUNCIÓN:
 =============================================*/

$('.tablaVentas').on('draw.dt', function () {

    quitarAgregarProducto();

})


/*=============================================
 BORRAR VENTA
 =============================================*/
$(".AdministrarVentas").on("click", ".btnEliminarVenta", function () {

    var idVenta = $(this).attr("idVenta");

    swal({
        title: '¿Está seguro de borrar la venta?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar venta!'
    }).then(function (result) {
        if (result.value) {

            window.location = "index.php?ruta=ventas&idVenta=" + idVenta;
        }

    })

})

/*=============================================
 BORRAR COTIZACION
 =============================================*/
$(".administrarCotizaciones").on("click", ".btnEliminarCotizacion", function () {

    var idVenta = $(this).attr("idVenta");

    swal({
        title: '¿Está seguro de borrar la cotizacion?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar cotizacion!'
    }).then(function (result) {
        if (result.value) {

            window.location = "index.php?ruta=administrarcotizaciones&idVenta=" + idVenta;
        }

    })

})


/*=============================================
 IMPRIMIR FACTURA
 =============================================*/

$(".AdministrarVentas").on("click", ".btnImprimirFactura", function () {

    var codigoVenta = $(this).attr("codigoVenta");
    window.location = "vistas/modulos/ticketVenta.php?codigoVenta=" + codigoVenta;


})

$(".AdministrarVentas").on("click", ".btnImprimirFacturaPdf", function () {

    var codigoVenta = $(this).attr("codigoVenta");

    window.open("extensiones/tcpdf/pdf/factura.php?codigo=" + codigoVenta, "_blank");

})

/*=============================================
 IMPRIMIR COTIZACIONES PDF Y TICKET
 =============================================*/

$(".administrarCotizaciones").on("click", ".btnImprimirTicketC", function () {

    var codigoVenta = $(this).attr("codigoVenta");

    window.location = "vistas/modulos/ticketCotizacion.php?codigoVenta=" + codigoVenta;

})

$(".administrarCotizaciones").on("click", ".btnImprimirCotizacion", function () {

    var codigoVenta = $(this).attr("codigoVenta");

    window.open("extensiones/tcpdf/pdf/cotizacion.php?codigo=" + codigoVenta, "_blank");

})




/*=============================================
 ABRIR ARCHIVO XML EN NUEVA PESTAÑA
 =============================================*/

$(".abrirXML").click(function () {

    var archivo = $(this).attr("archivo");
    window.open(archivo, "_blank");


})



function tablaAdministrarVentas(fechaInicial1
    , fechaFinal1
    , tipoVenta1
    , pendientePorCobrar1
    , soloCobrado1
    , cliente1
    )
{

    $('.AdministrarVentas').DataTable().destroy();

    var dataTable = $('.AdministrarVentas').DataTable({
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "orderCellsTop": true,
        //"retrieve": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100, 150, 200],

        "language": {

            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",

            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }

        },
        "ajax": {
            url: "ajax/datatable-administrarVentas.ajax.php",
            type: "POST",

            data: {
                fechaInicial: fechaInicial1
                , fechaFinal: fechaFinal1
                , tipoVenta: tipoVenta1
                , pendientePorCobrar: pendientePorCobrar1
                , soloCobrado: soloCobrado1
                , cliente: cliente1
            },
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
            };

            // Total Ventas
            total = api
            .column(5)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            // NETO
            pageNeto = api
            .column(4, {page: 'current'})
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);



            // Total over this page
            pageTotal = api
            .column(5, {page: 'current'})
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);




            // Total Pagado
            totalPagado = api
            .column(6, {page: 'current'})
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            // SALDO
            saldo = api
            .column(7, {page: 'current'})
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);


            // Update footer

            $(api.column(3).footer()).html(
                '<strong> TOTALES:</strong>'
                );

            $(api.column(4).footer()).html(
                '<strong> ' + pageNeto.toFixed(2) + '</strong>'
                );


            $(api.column(5).footer()).html(
                '<strong> ' + pageTotal.toFixed(2) + '</strong>'
                );


            $(api.column(6).footer()).html(
                '<strong> ' + totalPagado.toFixed(2) + '</strong>'
                );

            $(api.column(7).footer()).html(
                '<strong> ' + Number(saldo).toFixed(2) + '</strong>'
                );
        }
    });


    $("#AdministrarVentas thead tr:eq(1)").remove();


    $('#AdministrarVentas thead tr').clone(true).appendTo('#AdministrarVentas thead');

    $('#AdministrarVentas thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).removeClass('sorting')
        $(this).removeClass('sorting_asc')


        if (title == "#"
            || title == "Agregar o quitar tarjeta"
            || title == "CodigoCliente"
            || title == "Vendedor"
            || title == "Cliente"
            || title == "Neto"
            || title == "Total"
            || title == "Total Pagado"
            || title == "Saldo"
            || title == "Estado"
            || title == "Entregado"
            || title == "Acciones"
            ) {
            $(this).html('<input type="hidden" placeholder="Buscar por ' + title + '" />');
    } else {
        $(this).html('<input type="text" placeholder="Buscar por ' + title + '" id="' + title + '" name="' + title + '" />');
    }



    $('input', this).on('keyup change', function () {
        if (dataTable.column(i).search() !== this.value) {
            dataTable


            .column(i)
            .search(this.value)
            .draw();

        }
    });
});

    var dataTable = $('#AdministrarVentas').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        retrieve: true,
        paging: false
    });





}





function tablaAdministrarCotizaciones(fechaInicial1
    , fechaFinal1
    )
{

    $('.administrarCotizaciones').DataTable().destroy();

    var dataTable = $('.administrarCotizaciones').DataTable({

        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "orderCellsTop": true,
        //"retrieve": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100, 150, 200],

        "language": {

            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",

            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }

        },
        "ajax": {
            url: "ajax/datatable-administrarCotizaciones.ajax.php",
            type: "POST",

            data: {
                fechaInicial: fechaInicial1
                , fechaFinal: fechaFinal1
            },
        },

    });
}


function isset(accessor) {
    try {
        // Note we're seeing if the returned value of our function is not
        // undefined
        return typeof accessor !== 'undefined';
    } catch (e) {
        // And we're able to catch the Error it would normally throw for
        // referencing a property of undefined
        return false;
    }
}