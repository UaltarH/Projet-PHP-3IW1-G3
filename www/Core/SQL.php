<?php

namespace App\Core;

class ResponseSave
{
    public bool $success;
    public string|int|null $idNewElement;
}

class SQL
{
    private static $instance;
    private static $connection;

    protected function __construct()
    {
        try {
            self::$connection = new \PDO("pgsql:host=database;dbname=esgi;port=5432", "esgi", "Test1234");
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }

    public static function getTable()
    {
        throw new \Exception("getTable() method not implemented");
    }

    public static function getInstance()
    {

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (isset($trace[1]['class'])) {
            if (is_null(self::$instance)) {
                self::$instance = new SQL($trace[1]['class']);
            }

            return self::$instance;
        }
        die("Pas de class d'appel");
    }

    public function getConnection()
    {
        return self::$connection;
    }

    public static function populate(int $id): object
    {
        $class = get_called_class();
        $objet = new $class();
        return $objet->getOneWhere(["id" => $id]);
    }

    //le resultat sera sous d'un tableau associatif, l'unique colonne qu'on aura se nomme "column_exists" avec comme contenu soit 
    //"the value for column the nom_colonne_concerné already exists" ou "none_exists".
    // si le resultat de cette methode revoi un bool (false) ca veut dire que dans la table il n'y a aucune donné 
    public function existOrNot(array $where): array|bool
    {
        if(empty($where))
            return true;
        $sqlWhere = [];
        foreach ($where as $column => $value) {
            $sqlWhere[] = "WHEN EXISTS (SELECT 1 FROM " . static::getTable() . " WHERE " . $column . "=:" . $column . ") THEN 'the value for column the " . $column . " already exists'";
        }
        $queryPrepared = self::$connection->prepare("SELECT CASE " . implode("  ", $sqlWhere) . " ELSE 'none_exists' END AS column_exists FROM " . static::getTable() . " LIMIT 1;");
        $queryPrepared->setFetchMode(\PDO::FETCH_ASSOC);
        $queryPrepared->execute($where);

        return $queryPrepared->fetch();
    }

    public function getOneWhere(array $where)
    {
        foreach ($where as $column => $value) {
            $sqlWhere[] = $column . " = :" . $column;
            if (is_bool($value)) {
                $values[$column] = $value ? 1 : 0;
            } else {
                $values[$column] = $value;
            }
        }

        $queryPrepared = self::$connection->prepare("SELECT * FROM " . static::getTable() . " WHERE " . implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        $queryPrepared->execute($values);

        return $queryPrepared->fetch();
    }

    public function getAllWhere(array $where)
    {
        foreach ($where as $column => $value) {
            $sqlWhere[] = $column . " = :" . $column;
            if (is_bool($value)) {
                $values[$column] = $value ? 1 : 0;
            } else {
                $values[$column] = $value;
            }
        }

        $queryPrepared = self::$connection->prepare("SELECT * FROM " . static::getTable() . " WHERE " . implode(" AND ", $sqlWhere) . " ORDER BY creation_date DESC");
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        $queryPrepared->execute($values);

        return $queryPrepared->fetchAll();
    }

    public function selectAll(): array
    {
        $queryPrepared = self::$connection->prepare("SELECT * FROM " . static::getTable());
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
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
        foreach ($fkInfos as $fkInfo) {
            $sqlJoin[] = "JOIN " . $fkInfo["table"] . " ON " . static::getTable() . "." . $fkInfo["foreignKeys"]["originColumn"] . "=" . $fkInfo["table"] . "." . $fkInfo["foreignKeys"]["targetColumn"];
        }
        $queryPrepared = self::$connection->prepare("SELECT * FROM " . static::getTable() . " " . implode(" ", $sqlJoin));
        $queryPrepared->setFetchMode(\PDO::FETCH_ASSOC);
        $queryPrepared->execute();

        return $queryPrepared->fetchAll();
    }

    public function selectWithFkAndWhere(array $fkInfos, array $where): array
    {
        $sqlJoin = [];
        foreach ($fkInfos as $fkInfo) {
            $sqlJoin[] = "JOIN " . $fkInfo["table"] . " ON " . static::getTable() . "." . $fkInfo["foreignKeys"]["originColumn"] . "=" . $fkInfo["table"] . "." . $fkInfo["foreignKeys"]["targetColumn"];
        }
        $sqlWhere = [];
        foreach ($where as $column => $value) {
            $sqlWhere[] = $column . "=:" . $column;
        }
        $queryPrepared = self::$connection->prepare("SELECT * FROM " . static::getTable() . " " . implode(" ", $sqlJoin) . " WHERE " . implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode(\PDO::FETCH_ASSOC);
        $queryPrepared->execute($where);
        return $queryPrepared->fetchAll();
    }

    public function insertIntoJoinTable(): bool
    {
        $columns = get_object_vars($this);
        $columnsToExclude = get_class_vars(get_class());
        $columns = array_diff_key($columns, $columnsToExclude);

        $queryPrepared = self::$connection->prepare("INSERT INTO " . static::getTable() .
            " (" . implode(",", array_keys($columns)) . ") 
            VALUES
            (:" . implode(" , :", array_keys($columns)) . ") ");
        return $queryPrepared->execute($columns);
    }

    public function save(): ResponseSave
    {
        $columns = get_object_vars($this);
        $columnsToExclude = get_class_vars(get_class());
        $columns = array_diff_key($columns, $columnsToExclude);
        $methode = "";

        if ($this->getId() != "0") {
            $methode = "update";
            $sqlUpdate = [];
            foreach ($columns as $column=>$value) {
                $sqlUpdate[] = $column."=:".$column;
            }
            $query = "UPDATE ".static::getTable().
                " SET ".implode(",", $sqlUpdate). " WHERE id='".$this->getId()."'";
            $queryPrepared = self::$connection->prepare($query);
        } else{
            $methode = "insert";
            $queryPrepared = self::$connection->prepare("INSERT INTO " . static::getTable() .
                " (" . implode(",", array_keys($columns)) . ") 
            VALUES
            (:" . implode(" , :", array_keys($columns)) .  ") RETURNING id ");
        }
        foreach ($columns as $key => $value) {
            if (is_bool($value)) {
                $columns[$key] = $value ? 'true' : 'false'; // Convertir la valeur booléenne en chaîne de caractères
            }
        }
        $response = new ResponseSave();
        $response->success = $queryPrepared->execute($columns);
        
        if ($methode == "insert") {
            $id = $queryPrepared->fetchColumn();
            $response->success = !!$id;
            $response->idNewElement = $id;
        } 
        return $response;
    }

    /**
     * Method used for datatable ajax calls, fetches specified columns and return a certain amount of results
     * @param array $params : array that contains query parameters from datatable ajax call and columns with want to select
     * @return array : array for datatable with data's we fetched
     * @throws \Exception
     */
    public function list(array $params): array
    {
        $query = self::$connection->query("SELECT count(id) FROM " . static::getTable());
        $totalRecords = $query->fetch()[0];

        $columns = $params["columns"];
        $start = $params["start"];
        $length = $params["length"];
        $search = $params["search"];
        $columnToSort = $params["columnToSort"];
        $sortOrder = $params["sortOrder"];        
        $uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
        $uriStr = strtolower(trim($uriExploded[0], "/"));
        $uri = explode('/', $uriStr);

        //add join constraint:
        if(isset($params["join"])){
            $sqlJoin = [];
            foreach ($params["join"] as $fkInfo) {
                $sqlJoin[] = "JOIN " . $fkInfo["table"] . " ON " . static::getTable() . "." . $fkInfo["foreignKeys"]["originColumn"] . "=" . $fkInfo["table"] . "." . $fkInfo["foreignKeys"]["targetColumn"];
            }
            implode(" ", $sqlJoin);
            $query = "SELECT " . static::getTable() . ".id, " . implode(", ", array_values($columns)) .
            " FROM " . static::getTable() . " " . implode(" ", $sqlJoin);
        } else{
            $query = "SELECT id, " . implode(", ", array_values($columns)) .
            " FROM " . static::getTable();
        }

        if (strlen($search) > 0) {
            $query .= " WHERE";
            foreach ($columns as $key => $column) {
                $query .= " CAST($column as TEXT) like '%$search%'";
                if ($key != count($columns) - 1) {
                    $query .= " OR";
                }
            }
        }
        if (!empty($columnToSort) && in_array($columnToSort, $columns)) {
            $query .= " ORDER BY $columnToSort";
            if (!empty($sortOrder) && (strtolower($sortOrder) == "asc" || strtolower($sortOrder) == "desc")) {
                $query .= " $sortOrder";
            }
        }
        $query .= " LIMIT $length OFFSET $start";
        $queryPrepared = self::$connection->prepare($query);
        $queryPrepared->execute();
        $result = [];
        $cpt = 0;

        // TODO : put data in object and assign $result[$cpt][$column] with $object->getProperty();
        while ($row = $queryPrepared->fetch()) {
            $result[$cpt]['id'] = $row['id'];
            foreach ($columns as $column) {
                $result[$cpt][trim($column)] = $row[trim($column)];
            }
            $result[$cpt]["action"] = "<a href='/sys/" . $uri[1] . "/list?action=edit&id=" . $row['id'] . "' class='row-edit-button'>Edit</a> | <a href='/sys/" . $uri[1] . "/list?action=delete&id=" . $row['id'] . "''>Delete</a>";
            $cpt++;
        }

        return [
            'draw' => intval($_GET['draw']),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $result,
        ];
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM " . static::getTable() . " WHERE id = '" . $id . "'";
        $queryPrepared = self::$connection->prepare($query);
        return $queryPrepared->execute();
    }

    public function faker($query): void
    {
        $queryPrepared = self::$connection->prepare($query);
        $queryPrepared->execute();
    }

    public function getTotalCount(): int
    {
        $queryPrepared = self::$connection->prepare("SELECT COUNT(*) FROM " . static::getTable());
        $queryPrepared->execute();
        return $queryPrepared->fetch()['count'];
    }
}