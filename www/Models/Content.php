<?php
namespace App\Models;

use App\Core\SQL;

class Content extends SQL
{
    private $db_connexion;
    private int $id = 0;
    protected string $path_content;

    public function __construct(){
        $this->db_connexion = SQL::getInstance()->getConnection();
    }

    public static function getTable(): string
    {
        $classExploded = explode("\\", get_called_class());
        return  "carte_chance_".strtolower(end($classExploded));
    }

    /**
     * @return Int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPathContent(): string
    {
        return $this->path_content;
    }

    /**
     * @param string $path_content
     */
    public function setPathContent(string $path_content): void
    {
        $this->path_content = $path_content;
    }

}