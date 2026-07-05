<?php

require_once __DIR__ . "/../models/Project.php";
require_once __DIR__ . "/../models/ProjectVersion.php";

class ProjectController
{
    private Project $projectModel;
    private ProjectVersion $versionModel;

    public function __construct(PDO $pdo)
    {
        $this->projectModel = new Project($pdo);
        $this->versionModel = new ProjectVersion($pdo);
    }

    // Közös átirányító metódus, hogy ne ismétlődjön sokszor a header + exit.
    private function redirect(string $url = "index.php"): void
    {
        header("Location: " . $url);
        exit;
    }

    // Projektlista megjelenítése dashboard adatokkal és legfrissebb verzióval.
    public function index(): void
    {
        $projects = $this->projectModel->getAll();

        foreach ($projects as &$project) {
            $latestVersion = $this->versionModel->getLatestVersion((int)$project["id"]);
            $project["latest_version"] = $latestVersion["version_number"] ?? "-";
        }

        unset($project);

        $projectCount = $this->projectModel->getProjectCount();
        $personalProjectCount = $this->projectModel->getPersonalProjectCount();
        $clientProjectCount = $this->projectModel->getClientProjectCount();
        $activeProjectCount = $this->projectModel->getActiveProjectCount();

        require_once __DIR__ . "/../views/projects/index.php";
    }

    // Új projekt létrehozása.
    public function create(): void
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim($_POST["title"] ?? "");

            if ($title === "") {
                $errors[] = "A projekt címe kötelező.";
            }

            if (empty($errors)) {
                $projectId = $this->projectModel->create($_POST);

                if ($projectId !== false) {
                    $this->versionModel->createInitialVersion($projectId);
                    $this->redirect();
                }

                $errors[] = "A projekt mentése nem sikerült.";
            }
        }

        require_once __DIR__ . "/../views/projects/create.php";
    }

    // Projekt törlése.
    public function delete(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if ($id > 0) {
            $this->projectModel->delete($id);
        }

        $this->redirect();
    }

    // Projekt szerkesztése.
    public function edit(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if ($id <= 0) {
            $this->redirect();
        }

        $project = $this->projectModel->getById($id);
        $latestVersion = $this->versionModel->getLatestVersion($id);

        if (!$project) {
            $this->redirect();
        }

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim($_POST["title"] ?? "");

            if ($title === "") {
                $errors[] = "A projekt címe kötelező.";
            }

            if (empty($errors)) {
                $this->projectModel->update($id, $_POST);

                $versionNumber = trim($_POST["version_number"] ?? "");

                if (
                    $versionNumber !== "" &&
                    (!$latestVersion || $versionNumber !== $latestVersion["version_number"])
                ) {
                    $this->versionModel->createVersion($id, $versionNumber);
                }

                $this->redirect();
            }
        }

        require_once __DIR__ . "/../views/projects/edit.php";
    }

    // Egy projekt részleteinek és verziónaplójának megjelenítése.
    public function show(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if ($id <= 0) {
            $this->redirect();
        }

        $project = $this->projectModel->getById($id);

        if (!$project) {
            $this->redirect();
        }

        $versions = $this->versionModel->getByProjectId($id);

        require_once __DIR__ . "/../views/projects/show.php";
    }

    // Új verzióbejegyzés létrehozása egy projekthez.
    public function createVersion(): void
    {
        $projectId = (int) ($_GET["project_id"] ?? 0);

        if ($projectId <= 0) {
            $this->redirect();
        }

        $project = $this->projectModel->getById($projectId);

        if (!$project) {
            $this->redirect();
        }

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (trim($_POST["version_number"] ?? "") === "") {
                $errors[] = "A verziószám kötelező.";
            }

            if (trim($_POST["description"] ?? "") === "") {
                $errors[] = "A leírás kötelező.";
            }

            if (empty($errors)) {
                $_POST["project_id"] = $projectId;

                $this->versionModel->create($_POST);

                $this->redirect("index.php?action=show&id=" . $projectId);
            }
        }

        require_once __DIR__ . "/../views/versions/create.php";
    }

    // Verzióbejegyzés módosítása.
    public function updateVersion(): void
    {
        $versionId = (int) ($_POST["id"] ?? 0);

        if ($versionId <= 0) {
            $this->redirect();
        }

        $version = $this->versionModel->getById($versionId);

        if (!$version) {
            $this->redirect();
        }

        $this->versionModel->update($versionId, $_POST);

        $this->redirect("index.php?action=show&id=" . $version["project_id"]);
    }

    // Verzióbejegyzés törlése.
    public function deleteVersion(): void
    {
        $versionId = (int) ($_POST["id"] ?? 0);

        if ($versionId <= 0) {
            $this->redirect();
        }

        $version = $this->versionModel->getById($versionId);

        if (!$version) {
            $this->redirect();
        }

        $projectId = (int) $version["project_id"];

        $this->versionModel->delete($versionId);

        $this->redirect("index.php?action=show&id=" . $projectId);
    }
}