<?php

namespace App\Models;

use App\Core\SQL;

class Jeux extends SQL
{
    private $db_connexion;
    private string $id = "0";
    protected string $title;
    protected int $category_id = 1;

    public function __construct(){
        $this->db_connexion = SQL::getInstance()->getConnection();
    }

    public static function getTable(): string
    {
        $classExploded = explode("\\", get_called_class());
        return  "carte_chance_".strtolower(end($classExploded));
    }

    /**
     * @return String
     */
    public function getId(): String
    {
        return $this->id;
    }

    /**
    * @param String $id
    */
    public function setId(String $id): void
    {
        $this->id = $id;
    }

    /**
    * @return String
    */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Int
     */
    public function getCategory_id(): int
    {
        return $this->category_id;
    }

    /**
    * @param Int $category_id
    */
    public function setCategory_id(int $category_id): void
    {
        $this->category_id = $category_id;
    }
}

