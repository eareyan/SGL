<?php

class library_decorator_DescripcionExtendida extends Zend_Form_Decorator_Description
{	
    public function render($content){

    	$content = substr($content,0,strlen($content)-5)."<div class='MensajeExtra'>".$this->getOption('MensajeExtra')."</div></dd>";
    	return $content;
    }
}