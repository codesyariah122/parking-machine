<?php
/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return : environment.file
* @desc : File ini difungsikan untuk konfigurasi environment constanta dalam hal ini adalah guna di peruntukan bagi setup database awal.
**/


namespace App\Config;

class Environment {

	private static function config()
	{
		$dotenv = parse_ini_file('.env');

		if(!defined('APP_NAME')) {
			define('APP_NAME', $dotenv['APP_NAME']);
		}
		if(!defined('BASE_URL')) {
			define('BASE_URL', $dotenv['BASE_URL']);
		}

		if (!defined('HOST')) {
            define('HOST', $dotenv['HOST_DB']);
        }

        if (!defined('USER')) {
            define('USER', $dotenv['USER_DB']);
        }

        if (!defined('PASSWORD')) {
            define('PASSWORD', $dotenv['USER_PW']);
        }

        if (!defined('DB')) {
            define('DB', $dotenv['DB']);
        }
	}

	public static function run()
	{
		self::config();
	}
}