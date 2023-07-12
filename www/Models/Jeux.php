<?php

namespace App\Models;

use App\Core\SQL;

class Jeux extends SQL
{
    private $db_connexion;
    private string $id = "0";
    protected string $title_game;
    protected string $category_id = "0";

    public function __construct(){
        $this->db_connexion = SQL::getInstance()->getConnection();
    }

    public static function getTable(): string
    {
        $classExploded = explode("\\", get_called_class());
        return  "carte_chance_".strtolower(end($classExploded));
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
    * @param string $id
    */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
    * @return String
    */
    public function getTitle(): string
    {
        return $this->title_game;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title_game): void
    {
        $this->title_game = $title_game;
    }

    /**
     * @return string
     */
    public function getCategory_id(): string
    {
        return $this->category_id;
    }

    /**
    * @param string $category_id
    */
    public function setCategory_id(string $category_id): void
    {
        $this->category_id = $category_id;
    }
}

