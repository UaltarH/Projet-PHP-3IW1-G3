<?php

namespace App\Repository;
use App\Core\SQL;
use PDO;

class GameCategoryRepository extends AbstractRepository {
    public function __construct() {}



    public function getTotalGamesByCategories(): array
    {
        $queryPrepared = SQL::getInstance()->getConnection()->prepare(
            "SELECT cc.category_game_name, COUNT(cj.id) AS jeux_count
                    FROM carte_chance_game_category cc
                    LEFT JOIN carte_chance_game cj ON cc.id = cj.category_id
                    GROUP BY cc.category_game_name;"
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