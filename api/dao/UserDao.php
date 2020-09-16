<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 23:03
 */

namespace App\Dao;

class UserDao {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function selectUserById($id) {
        try {
            $stmt = $this->db->prepare("SELECT first_name, email FROM users WHERE id = :id");
            $stmt->bindValue(':id', $id, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return function ($response) use ($exception) {
                return $this->responseProvider->withError($response, $exception->getMessage(), 401);
            };
        }
    }

    public function selectUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT id, first_name, email, password FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
        }
    }

    public function selectUsers() {
        try {
            $stmt = $this->db->query("SELECT id, first_name, last_name, email FROM users");

            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (\PDOException $exception) {
            return;
        }
    }
}