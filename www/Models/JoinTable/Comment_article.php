<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Comment_article extends SQL
{
    private $db_connexion;
    protected string $article_id;
    protected string $comment_id;

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
    public function getCommentId(): string
    {
        return $this->comment_id;
    }

    /**
    * @param string $comment_id
    */
    public function setCommentId(string $comment_id): void
    {
        $this->comment_id = $comment_id;
    }
}