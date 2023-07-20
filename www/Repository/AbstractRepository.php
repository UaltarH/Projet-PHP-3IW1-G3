<?php
//toutes les méthodes communes aux repository
//cette classe represente le CareTaker dans le design pattern Memento
namespace App\Repository;

use App\Core\Config;
use App\Core\ResponseSave;
use App\Core\SQL;

/**
 * Les Repository servent à regrouper les méthodes liées à un model.
 */
abstract class AbstractRepository
{
    /**
     * Returns table name of the model
     *
     * @param $model : the model
     * @return string
     */
    public function getTable($model): string
    {
        $classExploded = explode("\\", $model::class);
        return Config::getConfig()['bdd']['prefix'] . strtolower(end($classExploded));
    }
    /** Le resultat sera sous d'un tableau associatif, l'unique colonne qu'on aura se nomme "column_exists" avec comme contenu soit
     * "the value for column the nom_colonne_concerné already exists" ou "none_exists".
     * Si le resultat de cette methode renvoie un bool (false), ça veut dire que dans la table il n'y a aucune donnée
     */
    public function existOrNot(array $where, mixed $model): array|bool
    {
        if(empty($where))
            return true;
        $sqlWhere = [];
        foreach ($where as $column => $value) {
            $sqlWhere[] = "WHEN EXISTS (SELECT 1 FROM " . self::getTable($model) . " WHERE " . $column . "=:" . $column . ") THEN 'the value for column the " . $column . " already exists'";
        }
        $queryPrepared = SQL::getInstance()->getConnection()->prepare("SELECT CASE " . implode("  ", $sqlWhere) . " ELSE 'none_exists' END AS column_exists FROM " . self::getTable($model) . " LIMIT 1;");
        $queryPrepared->setFetchMode(\PDO::FETCH_ASSOC);
        $queryPrepared->execute($where);

        return $queryPrepared->fetch();
    }

    /**
     * @param array $where
     * @param mixed $model
     * @return mixed
     */
    public function getOneWhere(array $where, mixed $model): mixed
    {
        $sqlWhere = [];
        foreach ($where as $column => $value) {
            $sqlWhere[] = $column . "=:" . $column;
        }
        $queryPrepared = SQL::getInstance()->getConnection()->prepare("SELECT * FROM " . self::getTable($model) . " WHERE " . implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, $model::class);
        $queryPrepared->execute($where);
        return $queryPrepared->fetch();
    }

    /**
     * @param $model
     * @return array
     */
    public function selectAll($model): array
    {
        $queryPrepared = SQL::getInstance()->getConnection()->prepare("SELECT * FROM " . self::getTable($model));
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, $model::class);
        $queryPrepared->execute();
        return $queryPrepared->fetchAll();
    }

    /**
     * @param array $where
     * @param $model
     * @return array|false
     */
    public function getAllWhere(array $where, $model): false|array
    {
        $sqlWhere = [];
        $values = [];
        foreach ($where as $column => $value) {
            $sqlWhere[] = $column . " = :" . $column;
            if (is_bool($value)) {
                $values[$column] = $value ? 1 : 0;
            } else {
                $values[$column] = $value;
            }
        }

        $queryPrepared = SQL::getConnection()->prepare("SELECT * FROM " . self::getTable($model) . " WHERE " . implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, $model::class);
        $queryPrepared->execute($values);

        return $queryPrepared->fetchAll();
    }

    public function getAllWhereInsensitiveLike(array $where, $model): false|array
    {
        $sqlWhere = [];
        $values = [];
        foreach ($where as $column => $value) {
            $sqlWhere[] = $column . " ILIKE :" . $column;
            if (is_bool($value)) {
                $values[$column] = $value ? 1 : 0;
            } else {
                $values[$column] = '%' . $value . '%';
            }
        }

        $queryPrepared = SQL::getConnection()->prepare("SELECT * FROM " . self::getTable($model) . " WHERE " . implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, $model::class);
        $queryPrepared->execute($values);

        return $queryPrepared->fetchAll();
    }
    /**
     *
     * Exemple de $fkInfos:
     *   $FkInfos = [
     *      [0]=>[
     *          "table"=>"article_category",
     *          "fkColumns"=> [
     *              "fkOriginId"=>category_id,
     *              "idTargetTable"=>"id",
     *          ]
     *      ]
     *   ]
     * @param array $fkInfos
     * @param $model
     * @return array
     */
    public function selectWithFk(array $fkInfos, $model): array
    {
        $sqlJoin = [];
        foreach ($fkInfos as $fkInfo) {
            $sqlJoin[] = "JOIN " . $fkInfo["table"] . " ON " . self::getTable($model) . "." . $fkInfo["foreignKeys"]["originColumn"] . "=" . $fkInfo["table"] . "." . $fkInfo["foreignKeys"]["targetColumn"];
        }
        $queryPrepared = SQL::getInstance()->getConnection()->prepare("SELECT * FROM " . self::getTable($model) . " " . implode(" ", $sqlJoin));
        $queryPrepared->setFetchMode(\PDO::FETCH_ASSOC);
        $queryPrepared->execute();

        return $queryPrepared->fetchAll();
    }

    /**
     * @param array $fkInfos
     * @param array $where
     * @param $model
     * @return array
     */
    public function selectWithFkAndWhere(array $fkInfos, array $where, $model): array
    {
        $sqlJoin = [];
        foreach($fkInfos as $fkInfo){
            $sqlJoin[] = "JOIN ".$fkInfo["table"]." ON ".self::getTable($model).".".$fkInfo["foreignKeys"]["originColumn"]."=".$fkInfo["table"].".".$fkInfo["foreignKeys"]["targetColumn"];
        }
        $sqlWhere = [];
        foreach ($where as $column=>$value) {
            $sqlWhere[] = $column."=:".$column;
        }
        $queryPrepared = SQL::getInstance()->getConnection()->prepare("SELECT * FROM ".self::getTable($model)." ".implode(" ", $sqlJoin)." WHERE ".implode(" AND ", $sqlWhere));
        $queryPrepared->setFetchMode( \PDO::FETCH_ASSOC);
        $queryPrepared->execute($where);
        return $queryPrepared->fetchAll();
    }

    /**
     * @param $model
     * @return bool
     */
    public function insertIntoJoinTable($model):bool
    {
        $columns = $model->getColumns();

        $queryPrepared = SQL::getInstance()->getConnection()->prepare("INSERT INTO " . self::getTable($model) .
            " (" . implode(",", array_keys($columns)) . ") 
            VALUES
            (:" . implode(" , :", array_keys($columns)) . ") ");
        return $queryPrepared->execute($columns);
    }

    /**
     * Add or edit an entry
     * @param $model
     * @return ResponseSave
     */
    public function save($model): ResponseSave
    {
        $columns = $model->getColumns();
        $methode = "";

        if ($model->getId() != "0") {
            $methode = "update";
            $sqlUpdate = [];
            foreach ($columns as $column=>$value) {
                $sqlUpdate[] = $column."=:".$column;
            }
            $query = "UPDATE ".self::getTable($model).
                " SET ".implode(",", $sqlUpdate). " WHERE id='".$model->getId()."'";
            $queryPrepared = SQL::getInstance()->getConnection()->prepare($query);
        } else{
            $methode = "insert";
            $queryPrepared = SQL::getInstance()->getConnection()->prepare("INSERT INTO " . self::getTable($model) .
                " (" . implode(",", array_keys($columns)) . ") 
            VALUES
            (:" . implode(" , :", array_keys($columns)) .  ") RETURNING id ");
        }
        foreach ($columns as $key => $value) {
            if (is_bool($value)) {
                $columns[$key] = $value ? 'true' : 'false'; // Convertir la valeur booléenne en chaîne de caractères
            }
            //securisé les injections xss
            if(is_string($value) ){
                if(self::getTable($model) != Config::getConfig()['bdd']['prefix'] . "article"){
                    $columns[$key] = htmlspecialchars($value);
                } else{
                    if($key != "content"){
                        $columns[$key] = htmlspecialchars($value);
                    }
                }
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
    public function list(array $params, $model): array
    {
        $query = SQL::getConnection()->query("SELECT count(id) FROM " . $this->getTable($model));
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
                $sqlJoin[] = "JOIN " . $fkInfo["table"] . " ON " . $fkInfo["foreignKeys"]["originColumn"]["table"] . "." . $fkInfo["foreignKeys"]["originColumn"]["id"] . "=" . $fkInfo["table"] . "." . $fkInfo["foreignKeys"]["targetColumn"];
            }
            implode(" ", $sqlJoin);
            $query = "SELECT " . $this->getTable($model) . ".id, " . implode(", ", array_values($columns)) .
                " FROM " . $this->getTable($model) . " " . implode(" ", $sqlJoin);
        } else{
            $query = "SELECT id, " . implode(", ", array_values($columns)) .
                " FROM " . $this->getTable($model);
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
        $queryPrepared = SQL::getConnection()->prepare($query);
        $queryPrepared->execute();
        $result = [];
        $cpt = 0;
        while ($row = $queryPrepared->fetch()) {
            $result[$cpt]['id'] = $row['id'];
            foreach ($columns as $column) {
                $result[$cpt][trim($column)] = $row[trim($column)];
            }
            $result[$cpt]["action"] = "<span class='row-edit-button crud-button'>Edit</span> | <span class='row-delete-button crud-button'>Delete</a>";
            $cpt++;
        }

        return [
            'draw' => intval($_GET['draw']),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $result,
        ];
    }

    /**
     * Deletes an entry by id
     * @param $id
     * @param $model
     * @return bool
     */
    public function delete($model): bool
    {
        $query = "DELETE FROM " . self::getTable($model) . " WHERE id = '" . $model->getId() . "'";
        $queryPrepared = SQL::getInstance()->getConnection()->prepare($query);
        return $queryPrepared->execute();
    }

    /**
     * @param $model
     * @return int
     */
    public function getTotalCount($model): int
    {
        $queryPrepared = SQL::getInstance()->getConnection()->prepare("SELECT COUNT(*) FROM " . self::getTable($model));
        $queryPrepared->execute();
        return $queryPrepared->fetch()['count'];
    }

    public function multipleDelete(string $columnWhere, array $values, $model): bool
    {
        $query = "DELETE FROM " . self::getTable($model) . " WHERE " . $columnWhere . " IN ('" . implode("' , '", $values) . "')";
        $queryPrepared = SQL::getInstance()->getConnection()->prepare($query);
        return $queryPrepared->execute();
    }

}