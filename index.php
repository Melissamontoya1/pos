<?php

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/categorias.controlador.php";
require_once "controladores/productos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/ventas.controlador.php";
require_once "controladores/utilerias.controlador.php";
require_once "controladores/empresa.controlador.php";
require_once "controladores/perfiles.controlador.php";
require_once "controladores/CorreoSaliente.controlador.php";
require_once "controladores/pagos.controlador.php";
require_once "controladores/caja.controlador.php";
require_once "controladores/gastos.controlador.php";
require_once "controladores/ingreso.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/categorias.modelo.php";
require_once "modelos/productos.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";
require_once "modelos/empresa.modelo.php";
require_once "modelos/correo.modelo.php";
require_once "modelos/perfiles.modelo.php";
require_once "modelos/pagos.modelo.php";
require_once "modelos/bitacora.modelo.php";
require_once "modelos/caja.modelo.php";
require_once "modelos/gastos.modelo.php";
require_once "modelos/ingreso.modelo.php";
require __DIR__ . '/extensiones/vendor/autoload.php';

// require "vistas/modulos/Classes/PHPExcel/IOFactory.php"; //Agregamos la librería 
// require "vistas/modulos/Classes/PHPExcel/Cell/DefaultValueBinder.php";


$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();