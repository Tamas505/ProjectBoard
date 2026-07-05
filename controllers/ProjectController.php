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

    public function index(): void
    {
        $projects = $this->projectModel->getAll();

        foreach ($projects as &$project) {
            $latestVersion = $this->versionModel->getLatestVersion($project["id"]);

            $project["latest_version"] = $latestVersion["version_number"] ?? "-";
        }
        unset($project);


        $projectCount = $this->projectModel->getProjectCount();
        $personalProjectCount = $this->projectModel->getPersonalProjectCount();
        $clientProjectCount = $this->projectModel->getClientProjectCount();
        $activeProjectCount = $this->projectModel->getActiveProjectCount();

        require_once __DIR__ . "/../views/projects/index.php";
    }

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

                    header("Location: index.php");
                    exit;
                }

                $errors[] = "A projekt mentése nem sikerült.";
            }
        }

        require_once __DIR__ . "/../views/projects/create.php";
    }

    public function delete(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if ($id > 0) {
            $this->projectModel->delete($id);
        }

        header("Location: index.php");
        exit;
    }

    public function edit(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $project = $this->projectModel->getById($id);
        $latestVersion = $this->versionModel->getLatestVersion($id);

        if (!$project) {
            header("Location: index.php");
            exit;
        }

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $title = trim($_POST["title"] ?? "");

            if ($title === "") {
                $errors[] = "A projekt címe kötelező.";
            }

            if (empty($errors)) {

                // Projekt alapadatainak mentése
                $this->projectModel->update($id, $_POST);

                // Verzió mentése csak akkor, ha tényleg változott
                $versionNumber = trim($_POST["version_number"] ?? "");

                if (
                    $versionNumber !== "" &&
                    (!$latestVersion || $versionNumber !== $latestVersion["version_number"])
                ) {
                    $this->versionModel->createVersion($id, $versionNumber);
                }

                header("Location: index.php");
                exit;
            }
        }

        require_once __DIR__ . "/../views/projects/edit.php";
    }

    public function show(): void
    {
        $id = (int) ($_GET["id"] ?? 0);

        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $project = $this->projectModel->getById($id);

        if (!$project) {
            header("Location: index.php");
            exit;
        }

        $versions = $this->versionModel->getByProjectId($id);

        require_once __DIR__ . "/../views/projects/show.php";
    }

    public function createVersion(): void
    {
        $projectId = (int) ($_GET["project_id"] ?? 0);

        if ($projectId <= 0) {
            header("Location: index.php");
            exit;
        }

        $project = $this->projectModel->getById($projectId);

        if (!$project) {
            header("Location: index.php");
            exit;
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

                header("Location: index.php?action=show&id=" . $projectId);
                exit;
            }
        }

        require_once __DIR__ . "/../views/versions/create.php";
    }

    public function updateVersion(): void
    {
        $versionId = (int) ($_POST["id"] ?? 0);

        if ($versionId <= 0) {
            header("Location: index.php");
            exit;
        }

        $version = $this->versionModel->getById($versionId);

        if (!$version) {
            header("Location: index.php");
            exit;
        }

        $this->versionModel->update($versionId, $_POST);

        header("Location: index.php?action=show&id=" . $version["project_id"]);
        exit;
    }
    public function deleteVersion(): void
    {
        $versionId = (int) ($_POST["id"] ?? 0);

        if ($versionId <= 0) {
            header("Location: index.php");
            exit;
        }

        $version = $this->versionModel->getById($versionId);

        if (!$version) {
            header("Location: index.php");
            exit;
        }

        $projectId = (int) $version["project_id"];

        $this->versionModel->delete($versionId);

        header("Location: index.php?action=show&id=" . $projectId);
        exit;
    }
}
