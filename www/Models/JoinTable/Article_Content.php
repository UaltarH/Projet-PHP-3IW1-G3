<?php

namespace App\Models\JoinTable;

use App\Models\AbstractModel;

class Article_Content extends AbstractModel
{
    protected string $article_id;
    protected string $content_id;

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