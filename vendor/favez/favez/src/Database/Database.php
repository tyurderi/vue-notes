<?php

namespace Favez\Mvc\Database;

use Favez\Mvc\DI\Injectable;

class Database extends Injectable
{

    /**
     * @var \FluentPDO
     */
    protected $db;

    public function __construct(\Favez\Mvc\App $app)
    {
        parent::__construct();

        $pdo = new \PDO(
            sprintf('mysql:host=%s;dbname=%s',
                $app->config('database.host'),
                $app->config('database.shem')
            ),
            $app->config('database.user'),
            $app->config('database.pass')
        );

        $this->db = new \FluentPDO($pdo);

        $this->events()->publish('core.database.init', [$this]);
    }

    public function PDO()
    {
        return $this->db->getPdo();
    }

    public function query($statement)
    {
        return $this->PDO()->prepare($statement);
    }

    public function delete($table, $primaryKey = null)
    {
        return $this->db->delete($table, $primaryKey);
    }

    public function insert($table, $values = [])
    {
        return $this->db->insertInto($table, $values);
    }

    public function update($table, $set = [], $primaryKey = null)
    {
        return $this->db->update($table, $set, $primaryKey);
    }

    public function from($table, $primaryKey = null)
    {
        return $this->db->from($table, $primaryKey);
    }

}