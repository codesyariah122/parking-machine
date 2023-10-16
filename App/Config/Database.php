<?php
/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return : __constructor
* @desc : File ini difungsikan untuk konfigurasi awal database untuk aplikasi ini
**/

namespace app\config;

use App\Config\Environment;

class Database {

	public $env, $conn;

	public function __construct()
	{	
		$this->env = Environment::run();
	}

	public function connection()
	{
		$servername = HOST;
		$port = 3306;
		$username = USER;
		$password = PASSWORD;
		$dbname = DB;


		try {
			$this->conn = new \PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return $this->conn;
		} catch (\PDOException $e) {
			echo "Koneksi ke database gagal: " . $e->getMessage();
		}
	}
}
