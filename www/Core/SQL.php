<?php
namespace App\Core;

class SQL{

    private $pdo;
    private $table;
    private static $instance;
    private $connection;

    protected function __construct() {
        try {
            $this->connection = new \PDO("pgsql:host=database;dbname=esgi;port=5432", "esgi", "Test1234");
        }catch(\Exception $e){
            die("Erreur SQL : ".$e->getMessage());
        }
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new SQL();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public static function populate(Int $id): object
    {
        $class = get_called_class();
        $objet = new $class();
        return $objet->getOneWhere(["id"=>$id]);
    }

    public function getOneWhere(array $where): object
    {
        $sqlWhere = [];
        foreach ($where as $column=>$value) {
            $sqlWhere[] = $column."=:".$column;
        }
        $queryPrepared = $this->pdo->prepare("SELECT * FROM ".$this->table." WHERE ".implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode( \PDO::FETCH_CLASS, get_called_class());
        $queryPrepared->execute($where);
        return $queryPrepared->fetch();
    }


    public function save(): void
    {
        $columns = get_object_vars($this);
        $columnsToExclude = get_class_vars(get_class());
        $columns = array_diff_key($columns, $columnsToExclude);

        if(is_numeric($this->getId()) && $this->getId()>0) {
            $sqlUpdate = [];
            foreach ($columns as $column=>$value) {
                $sqlUpdate[] = $column."=:".$column;
            }
            $queryPrepared = $this->pdo->prepare("UPDATE ".$this->table.
                " SET ".implode(",", $sqlUpdate). " WHERE id=".$this->getId());
        }else{
            $queryPrepared = $this->pdo->prepare("INSERT INTO ".$this->table.
                " (".implode("," , array_keys($columns) ).") 
            VALUES
             (:".implode(",:" , array_keys($columns) ).") ");
        }

        $queryPrepared->execute($columns);

    }

}