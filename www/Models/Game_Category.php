<?php

namespace App\Models;

use App\Core\SQL;
use PDO;

class Game_Category extends AbstractModel
{
    private string $id = "0";
    protected string $category_name;
    protected string $description;

    public function __construct()
    {
    }

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
     * @return String
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * @param String $category_name
     */
    public function setCategoryName(string $category_name): void
    {
        $this->category_name = $category_name;
    }

    /**
     * @return String
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param String $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getTotalGamesByCategories(): array
    {
        $queryPrepared = SQL::getConnection()->prepare(
            "SELECT cc.category_name, COUNT(cj.id) AS jeux_count
                    FROM carte_chance_category_jeux cc
                    LEFT JOIN carte_chance_game cj ON cc.id = cj.category_id
                    GROUP BY cc.category_name;"
        );
        $queryPrepared->execute();
        $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
        $totalGamesByCategories = $result;
        foreach ($totalGamesByCategories as &$row) {
            $row['jeux_count'] = intval($row['jeux_count']);
        }

        return $totalGamesByCategories;
    }
}