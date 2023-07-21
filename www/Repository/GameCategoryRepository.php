<?php

namespace App\Repository;
use App\Core\Config;
use App\Core\SQL;
use PDO;

class GameCategoryRepository extends AbstractRepository {
    private array $config;

    public function __construct() {
        $this->config = Config::getInstance()->getConfig();
    }

    public function getTotalGamesByCategories(): array
    {
        $queryPrepared = SQL::getInstance()->getConnection()->prepare(
            "SELECT cc.category_game_name, COUNT(cj.id) AS jeux_count
                    FROM ".$this->config['bdd']['prefix']."_game_category cc
                    LEFT JOIN ".$this->config['bdd']['prefix']."_game cj ON cc.id = cj.category_id
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