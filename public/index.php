<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../controllers/ProjectController.php";
require_once __DIR__ . "/../controllers/AuthController.php";

$projectController = new ProjectController($pdo);
$authController = new AuthController($pdo);

$action = $_GET["action"] ?? "index";

if ($action === "login") {
    $authController->login();
} elseif ($action === "logout") {
    $authController->logout();

} elseif (!isset($_SESSION["admin_id"])) {
    header("Location: index.php?action=login");
    exit;

} elseif ($action === "create") {
    $projectController->create();

} elseif ($action === "show") {
    $projectController->show();

} elseif ($action === "createVersion") {
    $projectController->createVersion();

} elseif ($action === "updateVersion") {
    $projectController->updateVersion();

} elseif ($action === "deleteVersion") {
    $projectController->deleteVersion();

} elseif ($action === "edit") {
    $projectController->edit();

} elseif ($action === "delete") {
    $projectController->delete();

} else {
    $projectController->index();
}
