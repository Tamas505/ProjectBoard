<?php

require_once __DIR__ . "/../models/Auth.php";

class AuthController
{
    private Auth $authModel;

    public function __construct(PDO $pdo)
    {
        $this->authModel = new Auth($pdo);
    }

    public function login(): void
    {
        

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"] ?? "");
            $password = $_POST["password"] ?? "";

            $admin = $this->authModel->login($username, $password);

            if ($admin) {
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_username"] = $admin["username"];

                header("Location: index.php");
                exit;
            }

            $errors[] = "Hibás felhasználónév vagy jelszó.";
        }

        require_once __DIR__ . "/../views/auth/login.php";
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header("Location: index.php?action=login");
        exit;
    }
}