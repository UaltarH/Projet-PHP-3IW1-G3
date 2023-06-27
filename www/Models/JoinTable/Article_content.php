<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Article_content extends SQL
{
    protected int $article_id;
    protected int $content_id;

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
    public function getContentId(): int
    {
        return $this->content_id;
    }

    /**
    * @param Int $content_id
    */
    public function setContentId(int $content_id): void
    {
        $this->content_id = $content_id;
    }
}