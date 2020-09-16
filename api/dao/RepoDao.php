<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 23:15
 */

namespace App\Dao;


class RepoDao
{
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function selectRepoById($id) {
        try {
            $stmt = $this->db->prepare("SELECT directory, locker FROM repos WHERE id = :id");
            $stmt->bindValue(':id', $id, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function updateUserLock($user_id, $repo_id) {
        try {
            $stmt = $this->db->prepare("UPDATE repos SET locker = :user_id WHERE id = :id");
            $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_STR);
            $stmt->bindValue(':id', $repo_id, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }
}