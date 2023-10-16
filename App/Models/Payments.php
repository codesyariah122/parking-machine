<?php

namespace App\Models;

use App\Config\Database;

class Payments {

	private $db, $conn;

	public function __construct()
	{
		$this->db = new Database;
		$this->conn = $this->db->connection();
	}

	public static function maxIdTicket()
	{
		try {
			$model = new Payments;
			$pdo = $model->conn;
			$query = "SELECT id FROM payments ORDER BY id DESC LIMIT 1";
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
        	$model = new Payments;
			$pdo = $model->conn;
            $query = "SELECT COUNT(*) AS total FROM payments";
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
        	$model = new Payments;
			$pdo = $model->conn;
            $query = "SELECT COUNT(*) AS total FROM vehicles v
                  INNER JOIN payments p ON v.id = p.vehicle_id
                  WHERE p.barcode LIKE :keyword OR v.type LIKE :keyword";
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
			$model = new Payments;
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
			$model = new Payments;
			$pdo = $model->conn;
			$query = "SELECT v.id AS vehicle_id, v.type, v.harga, p.id AS payment_id, p.barcode, p.duration, p.paymentAmount, p.paymentDate
			FROM vehicles v
			INNER JOIN payments p ON v.id = p.vehicle_id
			WHERE p.barcode LIKE :keyword OR v.type LIKE :keyword
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

	public static function getData($payment_id)
	{
		try {
			$model = new Payments;
			$pdo = $model->conn;

        	// Query SQL dengan pernyataan JOIN
			$sql = "SELECT p.*, v.* 
                FROM payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                WHERE p.id = :payment_id";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':payment_id', $payment_id);
			$stmt->execute();

        	// Mengembalikan hasil query
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		} catch (\PDOException $e) {
        	// Pesan error
			throw new \Exception("Error: " . $e->getMessage());
		}
	}

	public static function store($data, $table)
	{
		try {
			$model = new Payments;
			$pdo = $model->conn;

  			// Memulai transaksi
			$pdo->beginTransaction();

			$addNewData = $pdo->prepare("INSERT INTO $table (vehicle_id, slot_id, barcode, duration, paymentAmount, paymentDate) VALUES (:vehicle_id, :slot_id, :barcode, :duration, :paymentAmount, :paymentDate)");
			$addNewData->bindParam(':vehicle_id', $data['vehicle_id']);
			$addNewData->bindParam(':slot_id', $data['slot_id']);
			$addNewData->bindParam(':barcode', $data['barcode']);
			$addNewData->bindParam(':duration', $data['duration']);
			$addNewData->bindParam(':paymentAmount', $data['paymentAmount']);
			$addNewData->bindParam(':paymentDate', $data['paymentDate']);
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