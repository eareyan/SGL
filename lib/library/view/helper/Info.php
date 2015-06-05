<?php

class library_view_helper_Info{

	public static function Info($info = null,$infoifnull,$null = null){
		if($info != "" && $info != $null){
			return $info;
		}else{
			return "[".$infoifnull."]";
		}
	}
}