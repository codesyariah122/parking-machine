<?php

namespace App\Models;

use App\Config\Database;

class Users
{
    private $db, $conn;

    public function __construct()
    {
        $this->db = new Database;
        $this->conn = $this->db->connection();
    }

    public function store($params)
    {
        try {
            $model = new Users();
            $pdo = $model->conn;

            $query = "INSERT INTO `users` (id, name, email, role, password, loginStatus) VALUES (:id, :name, :email, :role, :password, :loginStatus)";
            $stmt = $pdo->prepare($query);

            foreach ($params as $data) {
                $stmt->bindParam(':id', $data['id']);
                $stmt->bindParam(':name', $data['name']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':role', $data['role']);
                $stmt->bindParam(':password', $data['password']);
                $stmt->bindParam(':loginStatus', $data['loginStatus']);
                $stmt->execute();
            }

            // Jika ingin mendapatkan ID terakhir yang di-generate oleh MySQL
            // bisa menggunakan ini:
            //$lastInsertedId = $pdo->lastInsertId();
            //return $lastInsertedId;

            return true;

        } catch (\PDOException $e) {
            // Pesan error
            return $e->getMessage();
        }
    }

    public static function userByEmail($email)
    {
        try {
            $model = new Users();
            $pdo = $model->conn;

            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":email", $email, \PDO::PARAM_STR);

            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $user;
        } catch (\PDOException $e) {
            // Pesan error
            return $e->getMessage();
        }
    }

    public static function userByToken($token)
    {
        try {
            $model = new Users();
            $pdo = $model->conn;

            $query = "SELECT * FROM users WHERE token = :token";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":token", $token, \PDO::PARAM_STR);

            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $user;
        } catch (\PDOException $e) {
            // Pesan error
            return $e->getMessage();
        }
    }

    public static function login($data)
    {
        try {
            $model = new Users();
            $pdo = $model->conn;

            // Memulai transaksi
            $pdo->beginTransaction();

            $query = "UPDATE users SET loginStatus =?, token =?, lastLogin =? WHERE email = ?";

            $stmt = $pdo->prepare($query);

            $stmt->execute([$data['loginStatus'], $data['token'], $data['lastLogin'], $data['email']]);

            // Commit transaksi jika berhasil
            $pdo->commit();

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Pesan error
            return $e->getMessage();
        }
    }

    public static function logout($email)
    {
        try {
            $model = new Users();
            $pdo = $model->conn;

            $pdo->beginTransaction();

            $query = "UPDATE users SET loginStatus =?, token =? WHERE email = ?";

            $stmt = $pdo->prepare($query);

            $stmt->execute([0, NULL, $email]);

            $pdo->commit();

            return $stmt->rowCount();

        } catch (\PDOException $e) {
            // Pesan error
            return $e->getMessage();
        }
    }
}
