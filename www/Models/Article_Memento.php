<?php
//cette classe represente le memento dans le design pattern Memento
namespace App\Models;


class Article_Memento extends AbstractModel
{
    private string $id = "0";
    protected string $title;
    protected string $content;
    protected string $created_date;
    protected string $article_id;

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
     * return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title 
     */ 
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content 
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * return string
     */
    public function getCreatedDate(): string
    {
        return $this->created_date;
    }

    /**
     * @param string $created_date 
     */
    public function setCreatedDate(string $created_date): void
    {
        $this->created_date = $created_date;
    }

    /**
     * return string
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
}