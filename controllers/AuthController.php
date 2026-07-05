<?php

require_once __DIR__ . "/../models/Auth.php";

class AuthController
{
    private Auth $authModel;

    public function __construct(PDO $pdo)
    {
        $this->authModel = new Auth($pdo);
    }

    // Admin bejelentkezés kezelése.
    public function login(): void
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"] ?? "");
            $password = $_POST["password"] ?? "";

            if ($username === "" || $password === "") {
                $errors[] = "A felhasználónév és a jelszó megadása kötelező.";
            } else {
                $admin = $this->authModel->login($username, $password);

                if ($admin) {
                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];

                    header("Location: index.php");
                    exit;
                }

                $errors[] = "Hibás felhasználónév vagy jelszó.";
            }
        }

        require_once __DIR__ . "/../views/auth/login.php";
    }

    // Admin kijelentkezés, session törlése.
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: index.php?action=login");
        exit;
    }
}