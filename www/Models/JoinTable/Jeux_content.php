<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Jeux_content extends SQL
{
    private $db_connexion;
    protected string $jeux_id;
    protected string $content_id;

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
    public function getJeuId(): string
    {
        return $this->jeux_id;
    }

    /**
    * @param string $jeux_id
    */
    public function setJeuId(string $jeux_id): void
    {
        $this->jeux_id = $jeux_id;
    }

    /**
     * @return string
     */
    public function getContentId(): string
    {
        return $this->content_id;
    }

    /**
    * @param string $content_id
    */
    public function setContentId(string $content_id): void
    {
        $this->content_id = $content_id;
    }
}