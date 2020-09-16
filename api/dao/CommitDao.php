<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 23:15
 */

namespace App\Dao;

class CommitDao
{
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function selectCommitByRepoId($repo_id) {
        try {
            $stmt = $this->db->prepare("SELECT commit_id, UNIX_TIMESTAMP(created_at) AS created_at FROM commits WHERE repo_id = :repo_id");
            $stmt->bindValue(':repo_id', $repo_id, \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function insertCommit($repo_id, $commit_id, $created_at) {
        try {
            $stmt = $this->db->prepare("INSERT INTO commits VALUES(:repo_id, :commit_id, :created_at)");
            $stmt->bindValue(':commit_id', $commit_id, \PDO::PARAM_STR);
            $stmt->bindValue(':repo_id', $repo_id, \PDO::PARAM_STR);

            $date = new \Datetime();
            $date->setTimestamp($created_at);
            $stmt->bindValue(':created_at', $date->format('Y-m-d H:i:s'), \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }

    public function updateCommit($repo_id, $commit_id, $created_at) {
        try {
            $stmt = $this->db->prepare("UPDATE commits SET commit_id = :commit_id, created_at = :created_at WHERE repo_id = :repo_id");
            $stmt->bindValue(':repo_id', $repo_id, \PDO::PARAM_STR);
            $stmt->bindValue(':commit_id', $commit_id, \PDO::PARAM_STR);

            $date = new \Datetime();
            $date->setTimestamp($created_at);
            $stmt->bindValue(':created_at', $date->format('Y-m-d H:i:s'), \PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch();
        }
        catch (\PDOException $exception) {
            return;
        }
    }
}