<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 22.08.2019
 * Time: 22:55
 */

namespace App\Dao;


class SessionDao
{
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function selectSession($id) {
        try {
            $stmt = $this->db->prepare("SELECT last_updated FROM sessions WHERE user_id = :id");
            $stmt->bindValue(':id', $id, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            die($exception->getMessage());
            return;
        }
    }

    public function insertSession($id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO sessions VALUES (:id, CURRENT_TIMESTAMP)");
            $stmt->bindValue(':id', $id, \PDO::PARAM_STR);

            $stmt->execute();

            return;
        }
        catch (\PDOException $exception) {
            die($exception->getMessage());
            return;
        }
    }

    public function updateSession($id) {
        try {
            $stmt = $this->db->prepare("UPDATE sessions SET last_updated = CURRENT_TIMESTAMP WHERE user_id = :id");
            $stmt->bindValue(':id', $id, \PDO::PARAM_STR);

            $stmt->execute();

            return;
        }
        catch (\PDOException $exception) {
            die($exception->getMessage());
            return;
        }
    }
}