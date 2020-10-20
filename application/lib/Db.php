<?php


namespace Application\Lib;


use PDO;

class Db
{
    protected $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . '', DB_USER, DB_PASS);
    }

    /**
     * Query db method
     *
     * @param $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if ($key == 'offset') {
                    $stmt->bindValue(':' . $key, $val, PDO::PARAM_INT);
                } elseif ($key == 'limit') {
                    $stmt->bindValue(':' . $key, $val, PDO::PARAM_INT);
                } elseif ($key == 'is_modified_by_admin') {
                    $stmt->bindValue(':' . $key, $val, PDO::PARAM_BOOL);
                } else {
                    $stmt->bindValue(':' . $key, $val);
                }
            }
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * Return rows of query result
     *
     * @param $sql
     * @param array $params
     * @return array
     */
    public function row($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return first column
     *
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }
}