<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../controllers/ProjectController.php";
require_once __DIR__ . "/../controllers/AuthController.php";

$projectController = new ProjectController($pdo);
$authController = new AuthController($pdo);

// Az URL-ben megadott művelet.
// Ha nincs action paraméter, akkor a projektlista jelenik meg.
$action = $_GET["action"] ?? "index";

// A bejelentkezési oldal az egyetlen,
// amely admin bejelentkezés nélkül is elérhető.
if ($action === "login") {
    $authController->login();
    exit;
}

// Minden más művelethez admin bejelentkezés szükséges.
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php?action=login");
    exit;
}

// A megfelelő vezérlő metódus meghívása.
switch ($action) {

    case "logout":
        $authController->logout();
        break;

    case "create":
        $projectController->create();
        break;

    case "edit":
        $projectController->edit();
        break;

    case "delete":
        $projectController->delete();
        break;

    case "show":
        $projectController->show();
        break;

    case "createVersion":
        $projectController->createVersion();
        break;

    case "updateVersion":
        $projectController->updateVersion();
        break;

    case "deleteVersion":
        $projectController->deleteVersion();
        break;

    case "index":
    default:
        $projectController->index();
        break;
}