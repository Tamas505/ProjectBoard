<?php

class Auth
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Admin felhasználó hitelesítése.
    public function login(string $username, string $password): array|false
    {
        $sql = "SELECT *
            FROM admins
            WHERE username = :username";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "username" => $username
        ]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            return false;
        }

        if (!password_verify($password, $admin["password"])) {
            return false;
        }

        return $admin;
    }
}