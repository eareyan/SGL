<?php 
      class library_validate_FechaFin extends Zend_Validate_Abstract
      {
          const NOT_MATCH = 'notMatch';
          
          protected $_messageTemplates = array(
              self::NOT_MATCH => 'Si existe Fecha Fin debe existir Fecha Inicio'
          );
          
          public function isValid($value, $context = null)
          {
			$value = (string) $value;
			$this->_setValue($value);
			if (is_array($context)) {
				if (isset($context['fecha_inicio_caso'])){
					if($value != "" && $context['fecha_inicio_caso'] == ""){
						$this->_error(self::NOT_MATCH);
						return false;
					}
				}
			}
			return true;
          }
      }