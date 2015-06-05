<?php
class library_Directory_Util{
		
	public static function getSafeFileName($fileName,$absPath){
		
		/*** Revisar que la extension no sea en mayusculas ***/
		$datosExt = explode("." , $fileName);
		$cantExt = count($datosExt);
		//Cambiar la extension a minusculas
		$datosExt[$cantExt - 1] = strtolower($datosExt[$cantExt - 1]);
		$fileName = implode("." , $datosExt);
		
		/* Revisar que no este repetido */
		$newFileName = $fileName;
		$j = 1;
		while (file_exists($absPath . "/" . $newFileName)){
			$newFileName = $j . "_" . $fileName;
			$j = $j + 1;
		}
		return $newFileName;
	}
	public static function createDir($donde = null){
		/*Chequea que exista un directorio y si no existe intenta crearlo */
		if(is_null($donde)){
			throw new Exception('Se esta intentando crear un directorio nulo');
		}
		/* Chequear que el directorio tengan un / al final */
		if(substr($donde,-1,1)!='/'){
				$donde .= '/';
		}		
		if(!file_exists($donde)){
			//No existe, intenta crearlo
			if(!mkdir($donde)){
				//No logro crearlo, arroja una excepcion
				throw new Exception('Error creando el directorio '.$donde);	
			}
		}
	}
	public static function deleteFile($file = null){
		if(is_null($file)){
			throw new Exception('Se esta intentando borrar un archivo nulo');
		}
		/*Chequea que exista el archivo y lo borra */
		if(file_exists($file)){
			if(!unlink($file)){
				throw new Exception("Error eliminando archivo $file");
			}
		}
		
	}
	public static function deleteFileSafe($file = null){
		if(!is_dir($file) && substr($file,-4,1)=="."){//Asegurar que es un archivo y evitar borrar directorios
			self::deleteFile($file);
		}
	}
	public static function recursive_remove_directory($directory, $empty=false){
		// if the path has a slash at the end we remove it here
		if(substr($directory,-1) == '/'){
			$directory = substr($directory,0,-1);
		}
		// if the path is not valid or is not a directory ...
		if(!file_exists($directory) || !is_dir($directory)){
		// ... we return false and exit the function
			return false;
		// ... if the path is not readable
		}elseif(!is_readable($directory)){
		// ... we return false and exit the function
			return false;
		// ... else if the path is readable
		}else{
			// we open the directory
			$handle = opendir($directory);
			// and scan through the items inside
			while (false !== ($item = readdir($handle))){
			// if the filepointer is not the current directory
			// or the parent directory
				if($item != '.' && $item != '..'){
					// we build the new path to delete
					$path = $directory.'/'.$item;
					// if the new path is a directory
					if(is_dir($path)) {
						// we call this function with the new path
						lib_Directory_Util::recursive_remove_directory($path);				
					// if the new path is a file
					}else{
						// we remove the file
						unlink($path);
					}
				}
			}
			// close the directory
			closedir($handle);
			
			// if the option to empty is not set to true
			if(!$empty){
				// try to delete the now empty directory
				if(!rmdir($directory)){
					// return false if not possible
					return false;
				}
			}
			// return success
			return true;
		}
	}
	// removes files and non-empty directories
	function rrmdir($dir) {
	  if (is_dir($dir)) {
	    $files = scandir($dir);
	    foreach ($files as $file)
	    if ($file != "." && $file != "..") self::rrmdir("$dir/$file");
	    rmdir($dir);
	  }
	  else if (file_exists($dir)) unlink($dir);
	} 	
	// copies files and non-empty directories	
	public static function rcopy($src, $dst) {
	  if (file_exists($dst)) self::rrmdir($dst);
	  if (is_dir($src)) {
	    mkdir($dst);
	    $files = scandir($src);
	    foreach ($files as $file)
	    if ($file != "." && $file != "..") self::rcopy("$src/$file", "$dst/$file");
	  }
	  else if (file_exists($src)) copy($src, $dst);
	}	
}