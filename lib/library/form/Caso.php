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
 * @subpackage form
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Formulario de Caso
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Caso extends library_form_Form
      {
      	public function __construct(array $clientes = null,array $abogados = null){
      		if(!is_null($clientes) || !is_null($abogados)){
      			$this->agregarCheckboxCliente($clientes,$abogados);
      		}
      		parent::__construct();
      	}
      
          public function init()
          {
          	parent::init();
          	for($i=0;$i<3;$i++){
				$cliente = new Zend_Dojo_Form_Element_FilteringSelect('cliente_id_'.$i);
				$cliente->setLabel('Cliente '.($i+1).':')
						->setAutoComplete(true)
						->setStoreId('id_cliente')
						//->setStoreType('dojo.data.ItemFileReadStore')
						->setStoreType('dojox.data.QueryReadStore')						
						->setStoreParams(array('url' => '/casos/index/autocomplete/tipo/cliente/','value'=>1))
						->setAttrib("searchAttr", "nombres_cliente");
				if($i == 0){
					$cliente = $cliente->setRequired(true);
				}else{
					$cliente = $cliente->setRequired(false);				
				}
				$this->addElement($cliente);
			}
          	for($i=0;$i<3;$i++){
				$abogado = new Zend_Dojo_Form_Element_FilteringSelect('abogado_id_'.$i);
				$abogado->setLabel('Abogado:'.($i+1).':')
						->setAutoComplete(true)
						->setStoreId('id_abogado')
						//->setStoreType('dojo.data.ItemFileReadStore')
						->setStoreType('dojox.data.QueryReadStore')						
						->setStoreParams(array('url' => '/casos/index/autocomplete/tipo/abogado/','value'=>1))
						->setAttrib("searchAttr", "nombres_abogado")
						->setRequired(true);						
				if($i == 0){
					$abogado = $abogado->setRequired(true);
				}else{
					$abogado = $abogado->setRequired(false);				
				}				
				$this->addElement($abogado);
			}				 
          	  
              $nombre_caso = new Zend_Form_Element_Text('nombre_caso');
              $nombre_caso->setLabel('Nombre:')
              		   ->setRequired(true);

              $descripcion_caso = new Zend_Form_Element_Textarea('descripcion_caso');
              $descripcion_caso->setLabel('Descripcion:')
								->setAttrib('COLS', '40')
							    ->setAttrib('ROWS', '7');


			  $fecha_inicio_caso = new ZendX_JQuery_Form_Element_DatePicker("fecha_inicio_caso", 
	  																		array("label" => "Fecha Inicio:"));
			  $fecha_inicio_caso->setJQueryParam('dateFormat', 'yy-mm-dd')
								->addValidator('FechaInicio'); 

			  $fecha_fin_caso = new ZendX_JQuery_Form_Element_DatePicker("fecha_fin_caso", 
	  																		array("label" => "Fecha Fin:"));
			  $fecha_fin_caso->setJQueryParam('dateFormat', 'yy-mm-dd') 
								->addValidator('FechaFin'); 
							    
              $cuota_caso = new Zend_Form_Element_Text('cuota_caso');
              $cuota_caso->setLabel('Cuota:');

              $publico_caso = new Zend_Form_Element_Checkbox('publico_caso');
              $publico_caso->setLabel('Mostar en la web?:')
              				->setValue(1);

              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $nombre_caso,
                  $descripcion_caso,
                  $fecha_inicio_caso,
                  $fecha_fin_caso,
                  $cuota_caso,
                  $publico_caso,
                  $this->buscarCuentas(),
                  $submit
              ));
          }
          protected function buscarCuentas(){
				$model_cuentas = new library_models_cuentas;	
				$cuentas = $model_cuentas->select()->order('id_cuenta DESC')->query()->fetchAll();
              	$cuenta_id = new Zend_Form_Element_Select('cuenta_id');
              	$cuenta_id->setLabel('Cuenta Bancaria:');
				if(count($cuentas)>0){
					$cuenta_id->addMultiOption('','Seleccione');
					foreach($cuentas as $i=>$cuenta){
						$cuenta_id->addMultiOption($cuenta['id_cuenta'],$cuenta['nombre_cuenta']);
					}
				}else{
					$cuenta_id->addMultiOption('','No hay cuentas bancarias definidas');
				}
              	return $cuenta_id;
          }
          public function agregarCheckboxCliente(array $clientes = null,array $abogados = null){
          		foreach($clientes as $i=>$datos_cliente){
	          		$cliente = new Zend_Form_Element_Checkbox('cliente_'.$i);
    	      		$cliente->setLabel($datos_cliente['nombres_cliente'] .' '. $datos_cliente['apellidos_cliente'])
    	      				->setCheckedValue($datos_cliente['id_cliente'])
    	      				->setChecked(true);
        	  		$this->addElement($cliente);
				}
          		foreach($abogados as $i=>$datos_abogado){
	          		$abogado = new Zend_Form_Element_Checkbox('abogado_'.$i);
    	      		$abogado->setLabel($datos_abogado['nombres_abogado'].' '.$datos_abogado['apellidos_abogado'])
    	      				->setCheckedValue($datos_abogado['id_abogado'])
    	      				->setChecked(true)
    	      				->setOrder(count($clientes)+3+$i);
        	  		$this->addElement($abogado);
				}          
		}
      }