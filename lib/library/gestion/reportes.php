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
 * @subpackage gestion
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Clase para el manejo de los envios de emails automaticos.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage gestion 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class library_gestion_reportes{

	/** 
	 *	Indica si se envian los correos o no
	 *	@var bool
	 */
	public static $enviar_emails = false;

	/** 
	 *	Buscar todos los casos activos, que aun no hayan vencido y que cumplan con la regla establecida para
	 *	recibir el correo de pago, i.e., que dentro de 5 dias tenga que pagar o que hoy sea la fecha de pago
	 *	Se buscan los casos sin dependencias, ya que solo queremos los clientes los cuales agregaremos luego
	 *
	 *	Por cada caso que cumpla la condicion, se les envia a los clientes la notificacion de pago.
	 *
	 *	@return null
	 */
	public static function enviarEmailPagos($modo = 'browser',$imprimirResultados = true){
	
		//Buscar a todos los escritorios juridicos, excepto al 1 que es el demo
		$model_escritoriojuridico = new library_models_escritoriosjuridicos;
		$escritorios_juridicos = $model_escritoriojuridico->select()->where("id_escritorio_juridico != 1")->query()->fetchAll();

		foreach($escritorios_juridicos as $i=>$escritorio){
			//Configurar el id del escritorio para poder obtener el archivo config.ini
			library_gestion_config::setIdEscritorioJuridico($escritorio['id_escritorio_juridico']);
			//Reseteo la cache
			library_casos_Cache::resetCache();
			//Por cada escritorio, conectarse a la BD y buscar sus casos
			$db = Zend_Db::factory('pdo_mysql', array(	'host'		=>	'127.0.0.1',
														'port'	 	=>	'8889',
														'password' 	=>	$escritorio['password_db_escritorio_juridico'],
														'username'	=>	$escritorio['username_db_escritorio_juridico'],
														'dbname'	=> 	$escritorio['dbname_db_escritorio_juridico']));
			Zend_Db_Table::setDefaultAdapter($db);
			//Buscar los casos del escritorio
			$casos = library_casos_Factory::buscarVariosBD('finalizado_caso = 0 AND 
															activo_caso = 1 AND
															CURDATE() <= fecha_fin_caso AND
															(DAYOFMONTH(fecha_inicio_caso) = DAYOFMONTH(INTERVAL 5 DAY + CURDATE()) OR
															DAYOFMONTH(fecha_inicio_caso) = DAYOFMONTH(CURDATE()))',false);
			if(count($casos) == 0){
				if($imprimirResultados){
					echo "\nNo hay casos para el escritorio ".$escritorio['nombre_escritorio_juridico'] ."\n";
				}
				continue;
			}												
			//Ir por cada caso y mandar la notificacion
			$reporte_ok = array();
			$reporte_error = array();
			//Busco el texto de pago
			foreach($casos as $i=>$caso){
				if($modo == 'browser' && $imprimirResultados){
					echo "<h2>El caso <i>".$caso->getPropiedad('nombre_caso')."</i></h2>";
					echo "<h3 style='margin-left:50px'>Fecha Inicio ".$caso->getPropiedad('fecha_inicio_caso')."</h3>";
					echo "<h3 style='margin-left:50px'>Fecha Fin ".$caso->getPropiedad('fecha_fin_caso')."</h3>";
				}
				//Busco los clientes del caso y omito a los abogados ya que con ellos no voy a hacer nada
				library_casos_Factory::agregarDependencias($caso,$clientes = true,$abogados = false);
				//BUscar la informacion de la cuenta
				library_casos_cuentas_Factory::agregarCuenta($caso);
				//Le notifico del pago
				$reporte = $caso->enviarNotificacionPago();
				//Guardo el resultado de la notificacion para reportar el resultado
				$reporte_ok = array_merge($reporte_ok,$reporte['ok']);
				$reporte_error = array_merge($reporte_error,$reporte['error']);
			}
			if($modo == 'browser' && $imprimirResultados){
				echo "<h1 style='color:green'>Resultados</h1>";
				echo "Reportar resultado a ".library_gestion_config::getInstance()->emails->receiver_name .'-'.
											library_gestion_config::getInstance()->emails->receiver;
			}
			$texto_reporte = "";
			if(count($reporte_ok)){
				$texto_reporte .= "Se envio un email a los siguientes clientes notificandoles sobre su cuota pendiente:\n\n";
				foreach($reporte_ok as $i=>$datos){
					$texto_reporte .= "\t".$datos['nombre']." ".$datos['email']. ", del caso ". $datos['nombre_caso'] ."\n";
				}
			}
			if(count($reporte_error)){
				$texto_reporte .= "Ocurrio un error al enviarle el email de cobro a los siguientes clientes:\n\n";
				foreach($reporte_error as $i=>$datos){
					$texto_reporte .= $datos['nombre']." ".$datos['email']. ", del caso ". $datos['nombre_caso'] ."\n";;
				}
			}
			if($texto_reporte != ""){
				//Hay algo que reportar, por lo tanto, eeportar al administrador los resultados de los mail que se enviaron
				$mail = new Zend_Mail();
				$mail->setBodyText($texto_reporte);
				$mail->setFrom(library_gestion_config::getInstance()->emails->sender, library_gestion_config::getInstance()->emails->sender_name);
				$mail->addTo(library_gestion_config::getInstance()->emails->receiver, library_gestion_config::getInstance()->emails->receiver_name);
				$mail->setSubject('Mensaje automatico del Sistema de Gestion Legal');
				$mail->send();
				if($modo == 'browser' && $imprimirResultados){
					echo "<pre>Enviando mail de reporte:\n\n $texto_reporte</pre>";
				}elseif($modo == 'terminal' && $imprimirResultados){
					echo "\n------------------------------------------------------\nEscritorio : ".$escritorio['nombre_escritorio_juridico']." \nLa siguiente notificacion se envio a: ".library_gestion_config::getInstance()->emails->receiver."\n".$texto_reporte;
				}
			}
		}		
	}
}