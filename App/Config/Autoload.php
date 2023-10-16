<?php
/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return className
* @desc : File ini menjalankan built in function yang ada dalam package bahasa pemrogramman PHP khusus di versi 5 keatas
**/

spl_autoload_register(function($className) {
	$classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);

	$classFile = $classPath . '.php';

	if(file_exists($classFile))
		require_once $classFile;
});