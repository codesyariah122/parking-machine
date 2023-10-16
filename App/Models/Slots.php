<?php

namespace App\Models;

use App\Config\Database;

class Slots {

	private $db, $conn;

	public function __construct()
	{
		$this->db = new Database;
		$this->conn = $this->db->connection();
	}

	public static function getData($slot_id)
	{
		try {
			$model = new Slots;
			$pdo = $model->conn;

			$sql = "SELECT s.id AS slot_id, s.name AS slot_name, s.status,
			t.id AS ticket_id, t.barcode, t.startedAt,
			v.id AS vehicle_id, v.type, v.harga
			FROM slots s
			INNER JOIN tickets t ON s.id = t.slot_id
			INNER JOIN vehicles v ON t.vehicle_id = v.id WHERE s.id = :slot_id";

			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':slot_id', $slot_id);
			$stmt->execute();

        	// Mengembalikan hasil query
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
 			// Rollback transaksi jika terjadi kesalahan
			if ($pdo->inTransaction()) {
				$pdo->rollback();
			}

  			// Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}

	public static function slotById($slot_id)
	{
		try {
			$model = new Slots;
			$pdo = $model->conn;

			$sql = "SELECT * FROM slots WHERE id = :slot_id";

			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':slot_id', $slot_id);
			$stmt->execute();

        	// Mengembalikan hasil query
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
 			// Rollback transaksi jika terjadi kesalahan
			if ($pdo->inTransaction()) {
				$pdo->rollback();
			}

  			// Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}

	public static function store($data, $table)
	{
		try {
			$model = new Slots;
			$pdo = $model->conn;

  			// Memulai transaksi
			$pdo->beginTransaction();

			$sql = "UPDATE $table SET status=? WHERE `id` = ?";
			
			$update = $pdo->prepare($sql);
			
			$update->execute([$data['status'], $data['slot_id']]);

        	// Commit transaksi jika berhasil
			$pdo->commit();

			return $update->rowCount();
			
		} catch (\PDOException $e) {
			if ($pdo->inTransaction()) {
				$pdo->rollback();
			}
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}


	public static function update($data, $table)
	{
		try {
			$model = new Slots;
			$pdo = $model->conn;

			$sql = "UPDATE $table SET status=? WHERE `id` = ?";
			
			$update = $pdo->prepare($sql);
			
			$update->execute([$data['status'], $data['slot_id']]);

			$updatedId = $data['slot_id'];

			return $updatedId;
			
		} catch (\PDOException $e) {
			$pdo->rollback();
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}
}