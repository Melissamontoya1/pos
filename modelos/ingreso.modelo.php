<?php

require_once "conexion.php";

class ModeloIngreso{

	/*=============================================
	CREAR INGRESO A CAJA GENERAL
	=============================================*/

	static public function mdlIngresarIngreso($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(
			fecha_ingreso
			,descripcion_ingreso
			,valor_ingreso
			,id_vendedor_fk


			) 
		VALUES (
			:fecha_ingreso
			, :descripcion_ingreso
			, :valor_ingreso
			, :id_vendedor_fk


		)");

		$stmt->bindParam(":fecha_ingreso", $datos["fecha_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion_ingreso", $datos["descripcion_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":valor_ingreso", $datos["valor_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":id_vendedor_fk", $datos["id_vendedor_fk"], PDO::PARAM_STR);



		if($stmt->execute()){

			return "ok";

		}else{

			$arr=$stmt->errorInfo();
			return $arr[2];
			
		}

		$stmt->close();
		$stmt = null;

	}

	static public function mdlMostrarIngresoFechaHoraPDF( $fechaInicial, $fechaFinal){
		$stmt = Conexion::conectar()->prepare("
			SELECT i.id_ingreso, i.fecha_ingreso, i.descripcion_ingreso, i.valor_ingreso, i.id_vendedor_fk,u.id,u.nombre AS nombre_usuario
			FROM ingreso_caja i
			INNER JOIN usuarios u 
			ON i.id_vendedor_fk = u.id
			WHERE i.fecha_ingreso BETWEEN :fechaInicial AND :fechaFinal ");

		$stmt -> bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
		$stmt -> bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
		
		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	MOSTRAR INGRESO
	=============================================*/

	static public function mdlMostrarIngreso($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * 
				
				FROM 
				$tabla 
				WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	MOSTRAR INGRESOS AJAX
	=============================================*/

	static public function mdlMostraringresoAjax(){



		$stmt = Conexion::conectar()->prepare("SELECT id_ingreso 
			,fecha_ingreso as text	
			FROM ingreso_caja");

		$stmt -> execute();

		$arr = $stmt ->errorInfo();


		if ($arr[0]>0){
			$arr[3]="ERROR";
			return $arr;
		}
		else{

			return $stmt -> fetchAll();
		}

		

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarIngresoDTServerSide($valor){

	    //LIMITE DE REGISTROS
		$limit="LIMIT ".$valor['start']."  ,".$valor['length'];

		
	    //BUSQUEDA
		if(isset($valor['search'])){
			$buscar=$valor['search']['value'];
			$busquedaGeneral="and  ( 
			id_ingreso
			like '%".$buscar."%'

			or

			descripcion_ingreso
			like '%".$buscar."%'	

			or

			fecha_ingreso
			like '%".$buscar."%'
			or

			valor_ingreso
			like '%".$buscar."%'
			or

			id_vendedor_fk
			like '%".$buscar."%'
			
			)

			";
		}
		else{
			$busquedaGeneral="";
		}


		//COMO VA SER ORDENADO
		$col =array(
			0   =>  '1',
			1   =>  '5',
			2   =>  '3',
			3   =>  '4',
			4   =>  '2',
			5   =>  '6',
			6   =>  '8',
			7   =>  '10',
			8   =>  '1',
		);

		$orderBy=" ORDER BY ".$col[$valor['order'][0]['column']]."   ".$valor['order'][0]['dir'];

		$stmt = Conexion::conectar()->prepare("SELECT * 
			FROM ingreso_caja
			where 1=1
			$busquedaGeneral
			$orderBy   
			$limit
			");

		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}


	 /*=============================================
	MOSTRAR INGRESOS NUMERO DE REGISTROS
	=============================================*/

	static public function mdlMostrarNumRegistros($valor){

		
		$stmt = Conexion::conectar()->prepare("SELECT count(id_ingreso) as contador FROM ingreso_caja 
			
			");

		$stmt -> execute();

		return $stmt -> fetch();

		

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	EDITAR INGRESO
	=============================================*/

	static public function mdlEditarIngreso($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
			fecha_ingreso = :fecha_ingreso,
			descripcion_ingreso = :descripcion_ingreso,
			valor_ingreso = :valor_ingreso,
			id_vendedor_fk = :id_vendedor_fk
			WHERE id_ingreso = :id_ingreso");

		$stmt->bindParam(":id_ingreso", $datos["id_ingreso"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_ingreso", $datos["fecha_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion_ingreso", $datos["descripcion_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":valor_ingreso", $datos["valor_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":id_vendedor_fk", $datos["id_vendedor_fk"], PDO::PARAM_INT);
		
		

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
			
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR INGRESO
	=============================================*/

	static public function mdlEliminarIngreso($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_ingreso = :id_ingreso");

		$stmt -> bindParam(":id_ingreso", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
			
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

		/*=============================================
	SUMAR EL TOTAL DE INGRESOS
	=============================================*/

	static public function mdlSumaTotalIngreso($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(valor_ingreso) as total_ingreso FROM $tabla  ");

		$stmt -> execute();

		return $stmt -> fetch();


	}


}