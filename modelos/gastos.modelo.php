<?php

require_once "conexion.php";

class ModeloGasto{

	/*=============================================
	CREAR CLIENTE
	=============================================*/

	static public function mdlIngresarGasto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(
			fecha_gasto
			,descripcion_gasto
			,valor_gasto
			,tipo_caja
			,id_vendedor_fk
			, id_caja_fk

			) 
		VALUES (:fecha_gasto
			, :descripcion_gasto
			, :valor_gasto
			, :tipo_caja
			, :id_vendedor_fk
			, :id_caja_fk

		)");

		$stmt->bindParam(":fecha_gasto", $datos["fecha_gasto"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion_gasto", $datos["descripcion_gasto"], PDO::PARAM_STR);
		$stmt->bindParam(":valor_gasto", $datos["valor_gasto"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_caja", $datos["tipo_caja"], PDO::PARAM_STR);
		$stmt->bindParam(":id_vendedor_fk", $datos["id_vendedor_fk"], PDO::PARAM_STR);
		$stmt->bindParam(":id_caja_fk", $datos["id_caja_fk"], PDO::PARAM_STR);


		if($stmt->execute()){

			return "ok";

		}else{

			$arr=$stmt->errorInfo();
			return $arr[2];
			
		}

		$stmt->close();
		$stmt = null;

	}
/*=============================================
	CONSULTA PDF GASTOS FECHA
	=============================================*/
static public function mdlMostrarGastosFechaHoraPDF( $fechaInicial, $fechaFinal){
		$stmt = Conexion::conectar()->prepare("
			SELECT g.id_gasto, g.fecha_gasto, g.descripcion_gasto, g.valor_gasto, g.tipo_caja, g.id_vendedor_fk, g.id_caja_fk,u.id,u.nombre AS nombre_usuario
			FROM gastos g
			INNER JOIN usuarios u 
			ON g.id_vendedor_fk = u.id
			WHERE g.fecha_gasto BETWEEN :fechaInicial AND :fechaFinal ");

		$stmt -> bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
		$stmt -> bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
		
		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarGasto($tabla, $item, $valor){

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
	MOSTRAR CLIENTES AJAX
	=============================================*/

	static public function mdlMostrarGastosAjax(){



		$stmt = Conexion::conectar()->prepare("SELECT id_gasto 
			,fecha_gasto as text	
			FROM gastos");

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

	static public function mdlMostrarGastosDTServerSide($valor){

	    //LIMITE DE REGISTROS
		$limit="LIMIT ".$valor['start']."  ,".$valor['length'];

		
	    //BUSQUEDA
		if(isset($valor['search'])){
			$buscar=$valor['search']['value'];
			$busquedaGeneral="and  ( 
			id_gasto
			like '%".$buscar."%'

			or

			descripcion_gasto
			like '%".$buscar."%'	

			or

			fecha_gasto
			like '%".$buscar."%'
			or

			valor_gasto
			like '%".$buscar."%'
			or

			tipo_caja
			like '%".$buscar."%'

			or

			id_caja_fk
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
			FROM gastos
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
	MOSTRAR PRODUCTOS NUMERO DE REGISTROS
	=============================================*/

	static public function mdlMostrarNumRegistros($valor){

		
		$stmt = Conexion::conectar()->prepare("SELECT count(id_gasto) as contador FROM gastos 
			
			");

		$stmt -> execute();

		return $stmt -> fetch();

		

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function mdlEditarGasto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
			
			descripcion_gasto = :descripcion_gasto,
			valor_gasto = :valor_gasto,
			tipo_caja = :tipo_caja,
			fecha_gasto = :fecha_gasto
			
			WHERE id_gasto = :id_gasto");

		$stmt->bindParam(":id_gasto", $datos["id_gasto"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_gasto", $datos["fecha_gasto"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion_gasto", $datos["descripcion_gasto"], PDO::PARAM_STR);
		$stmt->bindParam(":valor_gasto", $datos["valor_gasto"], PDO::PARAM_INT);
		$stmt->bindParam(":tipo_caja", $datos["tipo_caja"], PDO::PARAM_STR);
		

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
			
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR GASTO
	=============================================*/

	static public function mdlEliminarGasto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_gasto = :id_gasto");

		$stmt -> bindParam(":id_gasto", $datos, PDO::PARAM_INT);

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

	static public function mdlSumaTotalGasto($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(valor_gasto) as total_gasto FROM $tabla  ");

		$stmt -> execute();

		return $stmt -> fetch();


	}
		/*=============================================
		TOTAL PAGOS POR CAJA
		=============================================*/

		static public function mdlMostrarGastosCaja($caja){



			$stmt = Conexion::conectar()->prepare("select sum(
				valor_gasto
				) as totalGastos

				from gastos
				where id_caja_fk=".$caja);


			if($stmt->execute()){
				return $stmt -> fetch();
				$stmt -> close();

				$stmt = null;

			}
			else{
				$arr = $stmt ->errorInfo();
				$arr[3]="ERROR";
				return $arr[2];
			}

		}


}
 