<?php

class library_casos_historiacaso{

	public static function getComentariosClientes($comentario){
	
		if($comentario == ""){
			return array();
		}
		$resultado	 = array();
		$comentarios = array();
		$clientes 	 = array();
		$j = 0;
		$comentario = explode("\n" , $comentario);
		foreach($comentario as $fila_i=>$datos){
			$datos = explode(":" , $datos);
			$datos[0] = trim($datos[0]);
			if($datos[0] == "***Cliente***"){
				$clientes[] = trim($datos[1]);
			}else if($datos[0] == "***Comentario***"){
				$comentarios[$j] = $datos[1];
			}else if($datos[0] == "***FinComentario***"){
				$j++;
			}else{
				$comentarios[$j] .= "\n". implode(":",$datos);
			}
		}
		foreach($clientes as $i=>$id_cliente){
			$resultado[$id_cliente] = $comentarios[$i];
		}
		//echo "<pre>resultadoxxx:"; print_r($resultado); echo "</pre>";
		return $resultado;
	}
	
	public static function getComentarioCliente($id_cliente,$comentario){
		$comentarios = self::getComentariosClientes($comentario);
		foreach($comentarios as $cliente_id=>$comentario_particular){
			if($cliente_id == $id_cliente){
				return $comentario_particular;
			}
		}
		return null;
	}
	
	public static function prepareComentarioCliente($id_cliente,$comentario_cliente,$comentario_bd){
		$nuevo_comentario = "***Cliente***:$id_cliente\n***Comentario***:$comentario_cliente\n***FinComentario***";
		if($comentario_bd == ""){
			return $nuevo_comentario;
		}else{
			return $comentario_bd .= "\n".$nuevo_comentario;
		}
	}
	
	public static function yaComento($id_cliente,$comentario){
		if(!is_null(self::getComentarioCliente($id_cliente,$comentario))){
			return true;
		}else{
			return false;
		}
	}
}