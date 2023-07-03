<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Article_content extends SQL
{
    private $db_connexion;
    protected string $article_id;
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