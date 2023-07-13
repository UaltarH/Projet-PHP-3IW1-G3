<?php

namespace App\Models\JoinTable;

use App\Models\AbstractModel;

class Game_Article extends AbstractModel
{
    protected string $article_id;
    protected string $jeux_id;

    public function __construct(){
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