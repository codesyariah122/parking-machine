<?php

namespace App\Models;

use App\Config\Database;

class Tickets {

	private $db, $conn;

	public function __construct()
	{
		$this->db = new Database;
		$this->conn = $this->db->connection();
	}

	public static function maxIdTicket()
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;
			$query = "SELECT id FROM tickets ORDER BY id DESC LIMIT 1";
			$stmt = $pdo->query($query);

			$result = $stmt->fetch(\PDO::FETCH_ASSOC);

			// var_dump($result); die;
			if($result) {
				return $result['id'];
			} else {
				return ;
			}
		} catch(\PDOException $e){
			echo $e->getMessage();
		}
	}

	public static function countAllData()
    {
        try {
        	$model = new Tickets;
			$pdo = $model->conn;
            $query = "SELECT COUNT(*) AS total FROM tickets";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (\PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public static function countSearchData($keyword)
    {
        try {
        	$model = new Tickets;
			$pdo = $model->conn;
            $query = "SELECT COUNT(*) AS total FROM vehicles v
                  INNER JOIN tickets t ON v.id = t.vehicle_id
                  WHERE t.barcode LIKE :keyword OR v.type LIKE :keyword";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':keyword', "%$keyword%", \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (\PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

	public static function all($query)
	{
		try{
			$model = new Tickets;
			$pdo = $model->conn;
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$sql = $pdo->query($query);
			$rows=[];

			while($row = $sql->fetch(\PDO::FETCH_ASSOC)):
				$rows[] = $row;
			endwhile;

			return $rows;
		}catch(\PDOException $e){
			echo $e->getMessage();
		}
	}


	public static function searchData($keyword, $limitStart, $limit)
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;
			$query = "SELECT v.id AS vehicle_id, v.type, v.harga, t.id AS ticket_id, t.barcode, t.startedAt
			FROM vehicles v
			INNER JOIN tickets t ON v.id = t.vehicle_id
			WHERE t.barcode LIKE :keyword OR v.type LIKE :keyword
			ORDER BY v.id DESC";

			if ($limitStart !== null && $limit !== null) {
				$query .= " LIMIT $limitStart, $limit";
			}

			$stmt = $pdo->prepare($query);
			$keyword = "%$keyword%";
			$stmt->bindParam(':keyword', $keyword);
			$stmt->execute();
			$results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $results;

		} catch (\PDOException $e) {
			echo "Ooops error: " . $e->getMessage();
		}
	}


	public static function getData($id)
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;

        	// Query SQL dengan pernyataan JOIN
			$sql = "SELECT v.id AS vehicle_id, v.type,v.harga, t.id AS ticket_id, t.barcode, t.startedAt
			FROM vehicles v
			INNER JOIN tickets t ON v.id = t.vehicle_id WHERE t.id = :id";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
			$stmt->execute();

        	// Mengembalikan hasil query
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
        	// Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}

	public static function checkTicket($barcode)
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;
			// Query SQL dengan parameter barcode
			$sql = "SELECT * FROM tickets WHERE barcode = :barcode";
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':barcode', $barcode);
			$stmt->execute();

        	// Mengembalikan hasil query
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);

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

	public static function checkVehicleByTicket($vehicle_id)
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;
			// Query SQL dengan parameter barcode
			$sql = "SELECT * FROM tickets WHERE vehicle_id = :vehicle_id";
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':vehicle_id', $vehicle_id);
			$stmt->execute();

        	// Mengembalikan hasil query
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);

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
			$model = new Tickets;
			$pdo = $model->conn;

  			// Memulai transaksi
			$pdo->beginTransaction();

			$addNewData = $pdo->prepare("INSERT INTO $table (barcode, vehicle_id, startedAt) VALUES (:barcode, :vehicle_id, :startedAt)");
			$addNewData->bindParam(':barcode', $data['barcode']);
			$addNewData->bindParam(':vehicle_id', $data['vehicle_id']);
			$addNewData->bindParam(':startedAt', $data['startedAt']);
			$addNewData->execute();
			$lastInsertedId = $pdo->lastInsertId();
			// Commit transaksi jika berhasil
			$pdo->commit();

			return $lastInsertedId;

		} catch (\PDOException $e) {
 			// Rollback transaksi jika terjadi kesalahan
			if ($pdo->inTransaction()) {
				$pdo->rollback();
			}

  			// Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}

	public static function update($data, $table)
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;

  			// Memulai transaksi
			$pdo->beginTransaction();

			$sql = "UPDATE $table SET slot_id=? WHERE `id` = ?";
			
			$update = $pdo->prepare($sql);
			
			$update->execute([$data['slot_id'], $data['id']]);

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

	public static function endedAt($barcode) {
		try {
			$model = new Tickets;
			$pdo = $model->conn;

			// Ubah tipe data kolom menjadi TIMESTAMP (jika belum)
			$alterTableQuery = "ALTER TABLE tickets MODIFY COLUMN endedAt TIMESTAMP";
			$pdo->exec($alterTableQuery);


        	// Buat pernyataan SQL UPDATE
			$statement = $pdo->prepare("UPDATE tickets SET endedAt = NOW() WHERE barcode = :barcode");

        	// Bind parameter
			$statement->bindParam(':barcode', $barcode);

        	// Jalankan pernyataan SQL
			$statement->execute();

        	// Dapatkan jumlah baris yang terpengaruh
			$rowCount = $statement->rowCount();

			return $rowCount;

		} catch (\PDOException $e) {
        // Rollback transaksi jika terjadi kesalahan
			if ($pdo->inTransaction()) {
				$pdo->rollback();
			}

        // Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}

	public static function deleted($id)
	{
		try {
			$model = new Tickets;
			$pdo = $model->conn;

        	// Memulai transaksi
			$pdo->beginTransaction();

			$sql = "DELETE FROM tickets WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
        	// Commit transaksi jika berhasil
			$pdo->commit();
			
			$resetAutoIncrement = $pdo->prepare("ALTER TABLE tickets AUTO_INCREMENT = 1");
			$resetAutoIncrement->execute();

			return $stmt->rowCount();
		} catch (\PDOException $e) {
        	// Rollback transaksi jika terjadi kesalahan
			if ($pdo->inTransaction()) {
				$pdo->rollback();
			}

        	// Pesan error
			echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}

}