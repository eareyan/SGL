<?php
/**
 * Sistema para Bufete Legal
 * 
 * LICENCIA
 * 
 * Todos los derechos reservados.
 *
 * @category   BufeteLegal
 * @package    library
 * @subpackage casos
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Clase para el manejo de un caso.
 *	Contiene los clientes, abogados, historia y cuenta del caso.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */

class library_casos_caso{
	/** 
	 *	Arreglo doble con los clientes del caso
	 *	@var array
	 */
	protected $clientes = array();
	
	/** 
	 *	Arreglo doble con los abogados del caso
	 *	@var array
	 */
	protected $abogados = array();

	/** 
	 *	Arreglo doble con la historia del caso
	 *	@var array
	 */
	protected $historia = array();
	
	/** 
	 *	Arreglo con las propiedades de la cuenta del caso
	 *	@var array
	 */	
	protected $cuenta = array();
	
	/** 
	 *	Arreglo con las propiedades del caso. Estan indexadas con los mismos nombres de campos de la base de datos
	 *	@var array
	 */	
	protected $propiedades = array();
	
	public function __construct($propiedades = array() , $clientes = array(), $abogados = array() , $cuenta = array()){
		//Crear el objeto desde datos que vienen como parametros
		self::setPropiedades($propiedades);
		self::setClientes($clientes);
		self::setAbogados($abogados);
		self::setCuenta($cuenta);
	}
	/** 
	 *	Guardar caso. Este es un wrapper que llame a la funcion de guardar del objeto  library_casos_casoBD
	 *
	 *	@return int id_caso del caso que se acaba de guardar
	 */		
	public function guardarCaso(){
		return library_casos_casoBD::guardarCaso($this);
	}
	/** 
	 *	Editar caso. Este es un wrapper que llame a la funcion de editar del objeto  library_casos_casoBD
	 *
	 *	@return bool
	 */		
	public function editarCaso(){
		return library_casos_casoBD::editarCaso($this);
	}
	/** 
	 *	Obtener el numero de cuota. Dados los parametros de un caso (fecha_inicio y fecha_fin), esta funcion calcula
	 *	la cuota que corresponde, por ejemplo la cuota 2 de 3.
	 *
	 *	@return string
	 */		
	public function getNumeroCuota(){
	
		/* Restar la fecha de fin con la inicio para obtener la cantidad total de cuotas*/
	
		$date1 = $this->getPropiedad('fecha_fin_caso');
		$date2 = $this->getPropiedad('fecha_inicio_caso');

		$diff = abs(strtotime($date2) - strtotime($date1));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$cant_total_cuotas = (($years*12) + $months + 1);

		$date1 = date('Y-m-d');
		$date2 = $this->getPropiedad('fecha_inicio_caso');

		$diff = abs(strtotime($date2) - strtotime($date1));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$cuota_actual = (($years*12) + $months);
		
		return $cuota_actual." de ".$cant_total_cuotas ;
	}
	/** 
	 *	Envia una notificacion de pago a los clientes de este caso
	 *
	 *	@return array $reporte, un arreglo que en la primera posicion tiene a quien se le envio sin problema y en 
	 *	a segunda a quien no se le pudo enviar
	 */	
	 public function enviarNotificacionPago(){
	 	$texto = library_gestion_config::getInstance()->emails->pago;
	 	if($texto == null || !is_string($texto)){
	 		throw new library_casos_Exception('El texto de notificacion de pago no puede ser vacio y debe ser un string');
	 	}
	 	//Buscar a los clientes del caso
	 	$clientes = $this->getClientes();
	 	//Iterar sobre cada cliente
	 	$reporte_ok = array();
	 	$reporte_error = array();
	 	if($this->getPropiedadCuenta('numero_cuenta') != ""){
		 	$info_cuenta = 	"\n\nTitular Cuenta: ".	$this->getPropiedadCuenta('titular_cuenta').
						 	"\nBanco: "	 		.	$this->getPropiedadCuenta('banco_cuenta') .
						 	"\nTipo Cuenta: "	.	$this->getPropiedadCuenta('tipo_cuenta') .
						 	"\nNumero Cuenta: "	.	$this->getPropiedadCuenta('numero_cuenta');
			if($this->getPropiedadCuenta('correo_cuenta') != ""){
				$info_cuenta .= "\nCorreo: ".$this->getPropiedadCuenta('correo_cuenta');
			}
		}else{
			$info_cuenta = "\n\nPor favor contactarnos para informaci&oacute;n de la cuenta bancaria";
		}
		$ret = "";
	 	foreach($clientes as $i=>$cliente){
	 		try{
		 		//Enviar email de pago reemplazando los campos dinamicos por los valores correspondientes.
		 		$texto_cliente = str_replace(array(	'[cliente_caso]',
		 											'[nombre_caso]',
		 											'[cuota_caso]',
		 											'[cuota_numero]',
		 											'[cuenta_caso]'),
		 									 array(	$cliente['nombres_cliente'],
		 									 		$this->getPropiedad('nombre_caso'),
		 									 		$this->getPropiedad('cuota_caso'),
		 									 		$this->getNumeroCuota(),
		 									 		$info_cuenta),
		 									 $texto);
				$mail = new Zend_Mail();
				$mail->setBodyText($texto_cliente);
				$mail->setFrom(library_gestion_config::getInstance()->emails->sender, library_gestion_config::getInstance()->emails->sender_name);
				$mail->addTo($cliente['email_principal_cliente'], $cliente['nombres_cliente']);
				$mail->setSubject('Notificacion de Cobro');
				$mail->send();		 		
		 		$reporte_ok[]  	 = array('nombre'=>$cliente['nombres_cliente'],'email'=>$cliente['email_principal_cliente'],'nombre_caso'=>$this->getPropiedad('nombre_caso'));
	 		}catch(Exception $e){
	 			//Ocurrio alguna excepcion, reportarla
		 		$reporte_error[] = array('nombre'=>$cliente['nombres_cliente'],'email'=>$cliente['email_principal_cliente'],'nombre_caso'=>$this->getPropiedad('nombre_caso'));
	 		}
	 	}
	 	//Reportar a quien se le envio el mail
	 	return array('ok'=>$reporte_ok,'error'=>$reporte_error);
	 }
	/** 
	 *	Envia una notificacion de cambio de estatus a los clientes de este caso
	 *
	 *	@return array $reporte, un arreglo que en la primera posicion tiene a quien se le envio sin problema y en 
	 *	a segunda a quien no se le pudo enviar
	 */	
	 public function enviarNotificacionCambioEstatus(){
	 	//Buscar a los clientes del caso
	 	$clientes = $this->getClientes();
	 	$reporte_ok = array();
	 	$reporte_error = array();	 	
	 	foreach($clientes as $i=>$cliente){
			try{
				//Enviar email de cambio de estatus
				$texto_cliente = library_gestion_config::getInstance()->emails->cambioestatus;
				//Cambiar campos dinamicos			
		 		$texto_cliente = str_replace(array(	'[nombres_cliente]',
		 											'[nombre_caso]'),
		 									 array(	$cliente['nombres_cliente'],
		 									 		$this->getPropiedad('nombre_caso')),
		 									 $texto_cliente);
				$mail = new Zend_Mail();
				$mail->setBodyText($texto_cliente);
				$mail->setFrom(library_gestion_config::getInstance()->emails->sender, library_gestion_config::getInstance()->emails->sender_name);
				$mail->addTo($cliente['email_principal_cliente'], $cliente['nombres_cliente']);
				$mail->setSubject('Notificacion de Cambio de Estatus');
				$mail->send();		 		
		 		$reporte_ok[]  	 = array('nombre'=>$cliente['nombres_cliente'],'email'=>$cliente['email_principal_cliente'],'nombre_caso'=>$this->getPropiedad('nombre_caso'));
	 		}catch(Exception $e){
	 			//Ocurrio alguna excepcion, reportarla
		 		$reporte_error[] = array('nombre'=>$cliente['nombres_cliente'],'email'=>$cliente['email_principal_cliente'],'nombre_caso'=>$this->getPropiedad('nombre_caso'));
	 		}	 		
		}
	 	//Reportar a quien se le envio el mail
	 	return array('ok'=>$reporte_ok,'error'=>$reporte_error);
	 }
	 
	/** 
	 *	clientExists. Chequea si el id de cliente recibido por parametro es parte de este caso. Devuelve true si es parte del caso
	 *	o false si no lo es.
	 *
	 *	@param int $id_cliente el id del cliente a chequear
	 *	@return bool
	 */		
	public function clientExists($id_cliente = null){
		$clientes = $this->getClientes();
		foreach($clientes as $i=>$cliente){
			if($cliente['id_cliente'] == $id_cliente){
				return true;
			}
		}
		return false;
	}
	 
	/** 
	 *	Setter propiedades
	 *
	 *	@param array $propiedades los indices son identicos a los de la BD
	 *	@return null
	 */		
	public function setPropiedades($propiedades = array()){
		$this->propiedades = $propiedades;
	}
	/** 
	 *	Setter de una propiedad
	 *
	 *	@param string $propiedad nombre de la propiedad
	 *	@param string $valor valor de la propiedad
	 *	@return null
	 */		
	public function setPropiedad($propiedad,$valor){
		$this->propiedades[$propiedad] = $valor;
	}
	/** 
	 *	Getter propiedades
	 *
	 *	@return array propiedades
	 */
	public function getPropiedades(){
		return $this->propiedades;
	}
	/** 
	 *	Setter clientes
	 *
	 *	@param array $clientes arreglo doble con los clientes
	 *	@return null
	 */
	public function setClientes($clientes = array()){	
		$this->clientes = $clientes;
	}
	/** 
	 *	Getter cliente. Obtiene un solo cliente
	 *
	 *	@param int $offset la posicion del cliente en el arreglo
	 *	@return array cliente
	 */
	public function getCliente($offset){
		return $this->clientes[$offset];
	}
	/** 
	 *	Getter clientes
	 *
	 *	@return array clientes
	 */
	public function getClientes(){
		return $this->clientes;
	}
	/** 
	 *	Setter abogados
	 *
	 *	@param array $abogados arreglo doble con los abogados
	 *	@return null
	 */
	public function setAbogados($abogados = array()){
		$this->abogados = $abogados;
	}
	/** 
	 *	Getter abogado. Obtiene un solo abogado
	 *
	 *	@param int $offset la posicion del abogado en el arreglo
	 *	@return array abogado
	 */	
	public function getAbogado($offset){
		return $this->abogados[$offset];
	}
	/** 
	 *	Getter abogados
	 *
	 *	@return array abogados
	 */
	public function getAbogados(){
		return $this->abogados;
	}
	/** 
	 *	Setter cuenta
	 *
	 *	@param array $cuenta arreglo con las propiedades de la cuenta
	 *	@return null
	 */	
	public function setCuenta($cuenta = array()){
		$this->cuenta = $cuenta;
	}
	/** 
	 *	Getter cuenta
	 *
	 *	@return array cuenta
	 */	
	public function getCuenta(){
		return $this->cuenta;
	}
	/** 
	 *	Getter propiedad de la cuenta. Revisa si la propiedad de la cuenta existe y la devuelve.
	 *
	 *	@return string propiedad
	 */	
	public function getPropiedadCuenta($propiedad = null){
		if(!is_null($propiedad) && isset($this->cuenta[$propiedad])){
			return $this->cuenta[$propiedad];
		}else{
			return null;
		}
	}
	/** 
	 *	Getter propiedad del caso. Revisa si la propiedad del caso existe y la devuelve.
	 *
	 *	@return string propiedad
	 */		
	public function getPropiedad($propiedad = null){
		if(!is_null($propiedad) && isset($this->propiedades[$propiedad])){
			return $this->propiedades[$propiedad];
		}else{
			return null;
		}
	}
}