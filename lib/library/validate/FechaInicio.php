<?php 
      class library_validate_FechaInicio extends Zend_Validate_Abstract
      {
          const NOT_MATCH = 'notMatch';
          
          protected $_messageTemplates = array(
              self::NOT_MATCH => 'La Fecha Inicio no puede ser mayor o igual que la Fecha Fin'
          );
          
          public function isValid($value, $context = null)
          {
			$value = (string) $value;
			$this->_setValue($value);
			$date1 = $value;
			if (is_array($context)) {
				if (isset($context['fecha_fin_caso'])){
					$date2 = $context['fecha_fin_caso'];
					if($date1 != "" && $date2 == ""){
						$this->_error(self::NOT_MATCH);
						return false;
					}
					$diff = strtotime($date2) - strtotime($date1);
					if($diff > 0){
						return true;
					}
					if($date2 == "0000-00-00" && $date1 == "0000-00-00"){
						return true;
					}
				}
			} elseif (is_string($context) && (strtotime($context) - strtotime($date1)) > 0 && $context != "0000-00-00" && $date1 != "0000-00-00") {
				return true;
			}
			$this->_error(self::NOT_MATCH);
			return false;
          }
      }