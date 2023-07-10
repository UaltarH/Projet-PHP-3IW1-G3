<?php
namespace App\Repository;
use App\Models\User;

class UserRepository extends User
{
    public static function fetchUserRole(): array
    {
        $query = (new UserRepository)->getConnection()->query("SELECT id FROM carte_chance_role WHERE role_name='user'");
        return $query->fetch();
    }

    public static function fetchRoles(): array
    {
        $query = (new UserRepository)->getConnection()->query("SELECT * FROM carte_chance_role");
        return $query->fetchAll();
    }

    public static function userFaker(): void
    {
        $query = "INSERT INTO carte_chance_user (pseudo, first_name, last_name, email, password, email_confirmation, phone_number, date_inscription, role_id) VALUES";
        for ($i = 0; $i < 100; $i++) {
            $query .= "('pseudo$i', 'firstname$i', 'lastname$i', 'email$i@email.com', 'Test$i', true, '0123456" . str_pad($i, 3, "0", STR_PAD_LEFT) . "', '" . date("Y-m-d H:i:s") . "', (SELECT id FROM carte_chance_role WHERE role_name = 'user'))";
            if ($i !== 99) $query .= ",";
        }
        $query .= ";";
        $queryPrepared = (new UserRepository)->getConnection()->prepare($query);
        $queryPrepared->execute();
    }
}