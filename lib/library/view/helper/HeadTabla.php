<?php

class library_view_helper_HeadTabla{

	public static function HeadTabla(array $cabeceras = null){
		$ret = "
			<table summary=\"Lista de Abogados\" id=\"hor-minimalist-a\">
				<thead>
					<tr>
		";
		foreach($cabeceras as $i=>$cabecera){
			$ret .="<th id=\"".str_replace(" ","-",$cabecera)."\" scope=\"col\">$cabecera</th>\n";
		}
		$ret .= "
					</tr>
				</thead>
			<tbody>";
		return $ret;
	}
}