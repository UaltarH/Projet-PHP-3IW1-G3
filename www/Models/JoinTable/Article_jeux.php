<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Article_jeux
{
    protected string $article_id;
    protected string $jeux_id;

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
    public function getArticleId(): string
    {
        return $this->article_id;
    }

    /**
    * @param string $article_id
    */
    public function setArticleId(string $article_id): void
    {
        $this->article_id = $article_id;
    }

    /**
     * @return string
     */
    public function getJeuxId(): string
    {
        return $this->jeux_id;
    }

    /**
    * @param string $jeux_id
    */
    public function setJeuxId(string $jeux_id): void
    {
        $this->jeux_id = $jeux_id;
    }
}