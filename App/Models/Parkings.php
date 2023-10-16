<?php

namespace App\Models;

use App\Config\Database;

class Parkings {

	private $db, $conn;

	public function __construct()
	{
		$this->db = new Database;
		$this->conn = $this->db->connection();
	}

	public static function getVehicle()
	{
		try {
			$model = new Parkings();
			$sql = "SELECT * FROM vehicles";
			$stmt = $model->conn->prepare($sql);
			$stmt->execute();

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
			throw new \Exception("Error: " . $e->getMessage());
		}
	}

	public static function checkAvailableBooth($slotFrom, $slotTo)
	{
		try {
			$model = new Parkings();
			$sql = "SELECT slots.*, tickets.*, vehicles.* 
                FROM slots 
                LEFT JOIN tickets ON slots.id = tickets.slot_id
                LEFT JOIN vehicles ON tickets.vehicle_id = vehicles.id
                WHERE slots.id BETWEEN :slotFrom AND :slotTo 
                ORDER BY slots.id ASC";
			$stmt = $model->conn->prepare($sql);
			$stmt->bindParam(':slotFrom', $slotFrom, \PDO::PARAM_INT);
			$stmt->bindParam(':slotTo', $slotTo, \PDO::PARAM_INT);
			$stmt->execute();

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
			throw new \Exception("Error: " . $e->getMessage());
		}
	}

	public static function checkAvailableVehicle($vehicle_id)
	{
		try {
			$model = new Parkings();
			$sql = "SELECT * FROM vehicles WHERE id = :vehicle_id";
			$stmt = $model->conn->prepare($sql);
			$stmt->bindParam(':vehicle_id', $vehicle_id, \PDO::PARAM_INT);
			$stmt->execute();

			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
			throw new \Exception("Error: " . $e->getMessage());
		}
	}

	public static function store($data, $table)
	{
		try {
			// var_dump($data['type']); die;
			$model = new Parkings();
  			$pdo = $model->conn;

  			// Memulai transaksi
			$pdo->beginTransaction();

			$addNewData = $pdo->prepare("INSERT INTO $table (type, harga) VALUES (:type, :harga)");
			$addNewData->bindParam(':type', $data['type']);
			$addNewData->bindParam(':harga', $data['harga']);
			$addNewData->execute();

			 // Mendapatkan nilai id yang baru saja di-generate
			$lastInsertedId = $pdo->lastInsertId();

        	// Commit transaksi jika berhasil
			$pdo->commit();

			return $lastInsertedId;

		} catch (\PDOException $e) {
 			// Rollback transaksi jika terjadi kesalahan
			$pdo->rollback();

  			// Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}
}