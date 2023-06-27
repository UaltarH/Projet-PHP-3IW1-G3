<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Article_jeux extends SQL
{
    protected int $article_id;
    protected int $jeux_id;

    public function __construct(){
        parent::__construct();
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