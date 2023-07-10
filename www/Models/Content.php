<?php
namespace App\Models;

use App\Core\SQL;

class Content
{
    private string $id = "0";
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