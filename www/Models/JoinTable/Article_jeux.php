<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Article_jeux extends SQL
{
    private $db_connexion;
    protected int $article_id;
    protected int $jeux_id;

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
    public function getArticleId(): int
    {
        return $this->article_id;
    }

    /**
    * @param Int $article_id
    */
    public function setArticleId(int $article_id): void
    {
        $this->article_id = $article_id;
    }

    /**
     * @return Int
     */
    public function getJeuxId(): int
    {
        return $this->jeux_id;
    }

    /**
    * @param Int $jeux_id
    */
    public function setJeuxId(int $jeux_id): void
    {
        $this->jeux_id = $jeux_id;
    }
}