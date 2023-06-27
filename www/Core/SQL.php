<?php
namespace App\Core;

class ResponseSave{
    public bool $success;
    public int $idNewElement;
}

class SQL{
    private static $instance;
    private static $connection;

    protected function __construct() {
        try {
            self::$connection = new \PDO("pgsql:host=database;dbname=esgi;port=5432", "esgi", "Test1234");
        }catch(\Exception $e){
            die("Erreur SQL : ".$e->getMessage());
        }
    }

    public static function getTable() {
        throw new \Exception("getTable() method not implemented");
    }

    public static function getInstance() {
        
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        
        if (isset($trace[1]['class'])) {
            if (is_null(self::$instance)) {
                self::$instance = new SQL($trace[1]['class']);
            }

            return self::$instance;
        }
        die("Pas de class d'appel");
    }

    public function getConnection() {
        return self::$connection;
    }

    public static function populate(Int $id): object
    {
        $class = get_called_class();
        $objet = new $class();
        return $objet->getOneWhere(["id"=>$id]);
    }

    //le resultat sera sous d'un tableau associatif, l'unique colonne qu'on aura se nomme "column_exists" avec comme contenu soit 
    //"the value for column the nom_colonne_concerné already exists" ou "none_exists".
    // si le resultat de cette methode revoi un bool (false) ca veut dire que dans la table il n'y a aucune donné 
    public function existOrNot(array $where):array|bool
    {
        $sqlWhere = [];
        foreach ($where as $column=>$value) {
            $sqlWhere[] = "WHEN EXISTS (SELECT 1 FROM ".static::getTable()." WHERE ".$column."=:".$column.") THEN 'the value for column the ".$column." already exists'";
        }
        $queryPrepared = self::$connection->prepare("SELECT CASE ".implode("  ", $sqlWhere)." ELSE 'none_exists' END AS column_exists FROM ".static::getTable()." LIMIT 1;");
        $queryPrepared->setFetchMode( \PDO::FETCH_ASSOC);
        $queryPrepared->execute($where);

        return $queryPrepared->fetch(); 
        
    }

    public function getOneWhere(array $where)
    {
        $sqlWhere = [];
        foreach ($where as $column=>$value) {
            $sqlWhere[] = $column."=:".$column;
        }
        $queryPrepared = self::$connection->prepare("SELECT * FROM ".static::getTable()." WHERE ".implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode( \PDO::FETCH_CLASS, get_called_class());
        $queryPrepared->execute($where);
        return $queryPrepared->fetch();
    }

    public function selectAll(): array
    {
        $queryPrepared = self::$connection->prepare("SELECT * FROM ".static::getTable());
        $queryPrepared->setFetchMode( \PDO::FETCH_CLASS, get_called_class());
        $queryPrepared->execute();
        return $queryPrepared->fetchAll();
    }

    //Exemple de $fkInfos:
    // $FkInfos = [
    //     [0]=>[
    //         "table"=>"category_article",
    //         "fkColumns"=> ["fkOriginId"=>category_id,
    //                       "idTargetTable"=>"id",
    //                     ]
    //     ]
    // ]
    public function selectWithFk(array $fkInfos): array
    {        
        $sqlJoin = [];
        foreach($fkInfos as $fkInfo){
            $sqlJoin[] = "JOIN ".$fkInfo["table"]." ON ".static::getTable().".".$fkInfo["foreignKeys"]["originColumn"]."=".$fkInfo["table"].".".$fkInfo["foreignKeys"]["targetColumn"];
        }
        $queryPrepared = self::$connection->prepare("SELECT * FROM ".static::getTable()." ".implode(" ", $sqlJoin));
        $queryPrepared->setFetchMode( \PDO::FETCH_ASSOC);
        $queryPrepared->execute();

        return $queryPrepared->fetchAll();
    }

    public function insertIntoJoinTable():bool
    {

        $columns = get_object_vars($this);
        $columnsToExclude = get_class_vars(get_class());
        $columns = array_diff_key($columns, $columnsToExclude);

        $queryPrepared = self::$connection->prepare("INSERT INTO ".static::getTable().
                " (".implode("," , array_keys($columns) ).") 
            VALUES
            (:".implode(" , :" , array_keys($columns) ).") ");
        return $queryPrepared->execute($columns);
        
    }

    public function save(): ResponseSave
    {
        $columns = get_object_vars($this);
        $columnsToExclude = get_class_vars(get_class());
        $columns = array_diff_key($columns, $columnsToExclude);
        
        $methode = "";
        
        if(is_numeric($this->getId()) && $this->getId()>0) {
            $methode = "update";
            $sqlUpdate = [];
            foreach ($columns as $column=>$value) {
                $sqlUpdate[] = $column."=:".$column;
            }
            $queryPrepared = self::$connection->prepare("UPDATE ".static::getTable().
                " SET ".implode(",", $sqlUpdate). " WHERE id=".$this->getId());
        }else{
            $methode = "insert";
            $queryPrepared = self::$connection->prepare("INSERT INTO ".static::getTable().
                " (".implode("," , array_keys($columns) ).") 
            VALUES
            (:".implode(" , :" , array_keys($columns) ).") ");
        }
        foreach ($columns as $key => $value) {
            if (is_bool($value)) {
                $columns[$key] = $value ? 'true' : 'false'; // Convertir la valeur booléenne en chaîne de caractères
            }
        }

        $response = new ResponseSave();
        $response->success = $queryPrepared->execute($columns);
        if($methode == "insert"){
            $response->idNewElement = self::$connection->lastInsertId();
        }else{
            $response->idNewElement = 0;
        }
        return $response;
        
        

    }

}