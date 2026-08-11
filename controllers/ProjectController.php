<?php

require_once __DIR__ . "/../models/Project.php";
require_once __DIR__ . "/../models/ProjectVersion.php";

class ProjectController
{
    private Project $projectModel; // a projektek adataival kapcsolatos műveletekhez kell.
    private ProjectVersion $versionModel; // a projektek verzióival kapcsolatos műveletekhez kell.

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
        // Az összes projekt lekérése.
        $projects = $this->projectModel->getAll();

        // Minden projekthez hozzáadjuk a legfrissebb verziószámot.
        foreach ($projects as &$project) {
            $latestVersion = $this->versionModel->getLatestVersion((int)$project["id"]);
            $project["latest_version"] = $latestVersion["version_number"] ?? "-";
        }

        // A foreach-ben használt referencia megszüntetése.
        unset($project);

        // A dashboard statisztikai adatainak lekérése.
        $projectCount = $this->projectModel->getProjectCount();
        $personalProjectCount = $this->projectModel->getPersonalProjectCount();
        $clientProjectCount = $this->projectModel->getClientProjectCount();
        $activeProjectCount = $this->projectModel->getActiveProjectCount();

        // A projektlista nézet betöltése.
        require_once __DIR__ . "/../views/projects/index.php";
    }



    // Új projekt létrehozása.
    public function create(): void
    {
        // A validációs hibák tárolására szolgáló tömb.
        $errors = [];

        // Az űrlap feldolgozása csak POST kérés esetén.
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // A projekt címének kiolvasása és a felesleges szóközök eltávolítása.
            $title = trim($_POST["title"] ?? "");

            // A projekt címének ellenőrzése.
            if ($title === "") {
                $errors[] = "A projekt címe kötelező.";
            }

            // Ha nincs validációs hiba, a projekt mentése.
            if (empty($errors)) {
                $projectId = $this->projectModel->create($_POST);

                // Sikeres mentés esetén létrehozzuk a projekt kezdeti verzióját.
                if ($projectId !== false) {
                    $this->versionModel->createInitialVersion($projectId);
                    $this->redirect();
                }

                // Sikertelen adatbázis-művelet esetén hibaüzenet.
                $errors[] = "A projekt mentése nem sikerült.";
            }
        }

        // Az új projekt létrehozására szolgáló nézet betöltése.
        require_once __DIR__ . "/../views/projects/create.php";
    }




    // Projekt törlése.
    public function delete(): void
    {
        // A törlendő projekt azonosítójának lekérése az URL-ből.
        $id = (int) ($_GET["id"] ?? 0);

        // Ha érvényes azonosítót kaptunk, a projekt törlése.
        if ($id > 0) {
            $this->projectModel->delete($id);
        }

        // Visszairányítás a projektlistára.
        $this->redirect();
    }




    // Projekt szerkesztése.
    public function edit(): void
    {
        // A szerkesztendő projekt azonosítójának lekérése az URL-ből.
        $id = (int) ($_GET["id"] ?? 0);

        // Hibás vagy hiányzó azonosító esetén visszairányítás.
        if ($id <= 0) {
            $this->redirect();
        }

        // A projekt és a hozzá tartozó legfrissebb verzió lekérése.
        $project = $this->projectModel->getById($id);
        $latestVersion = $this->versionModel->getLatestVersion($id);

        // Ha a projekt nem található, visszairányítás.
        if (!$project) {
            $this->redirect();
        }

        // A validációs hibák tárolása.
        $errors = [];

        // Az űrlap feldolgozása POST kérés esetén.
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // A projekt címének kiolvasása és tisztítása.
            $title = trim($_POST["title"] ?? "");

            // A projekt címének ellenőrzése.
            if ($title === "") {
                $errors[] = "A projekt címe kötelező.";
            }

            // Ha nincs validációs hiba, a projekt adatainak módosítása.
            if (empty($errors)) {
                $this->projectModel->update($id, $_POST);

                // A megadott verziószám kiolvasása.
                $versionNumber = trim($_POST["version_number"] ?? "");

                // Új verzió létrehozása, ha van verziószám és eltér a legutóbbitól.
                if (
                    $versionNumber !== "" &&
                    (!$latestVersion || $versionNumber !== $latestVersion["version_number"])
                ) {
                    $this->versionModel->createVersion($id, $versionNumber);
                }

                // Sikeres módosítás után visszairányítás a projektlistára.
                $this->redirect();
            }
        }

        // A szerkesztési nézet betöltése.
        require_once __DIR__ . "/../views/projects/edit.php";
    }




    // Egy projekt részleteinek és verziónaplójának megjelenítése.
    public function show(): void
    {
        // A megjelenítendő projekt azonosítójának lekérése az URL-ből.
        $id = (int) ($_GET["id"] ?? 0);

        // Hibás vagy hiányzó azonosító esetén visszairányítás.
        if ($id <= 0) {
            $this->redirect();
        }

        // A kiválasztott projekt adatainak lekérése.
        $project = $this->projectModel->getById($id);

        // Ha a projekt nem található, visszairányítás.
        if (!$project) {
            $this->redirect();
        }

        // A projekthez tartozó verzióbejegyzések lekérése.
        $versions = $this->versionModel->getByProjectId($id);

        // A projekt részletes nézetének betöltése.
        require_once __DIR__ . "/../views/projects/show.php";
    }




    // Új verzióbejegyzés létrehozása egy projekthez.
public function createVersion(): void
{
    // A projekthez tartozó azonosító lekérése az URL-ből.
    $projectId = (int) ($_GET["project_id"] ?? 0);

    // Hibás vagy hiányzó projektazonosító esetén visszairányítás.
    if ($projectId <= 0) {
        $this->redirect();
    }

    // A kiválasztott projekt adatainak lekérése.
    $project = $this->projectModel->getById($projectId);

    // Ha a projekt nem található, visszairányítás.
    if (!$project) {
        $this->redirect();
    }

    // A validációs hibák tárolása.
    $errors = [];

    // Az űrlap feldolgozása POST kérés esetén.
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // A verziószám kötelező mező ellenőrzése.
        if (trim($_POST["version_number"] ?? "") === "") {
            $errors[] = "A verziószám kötelező.";
        }

        // A leírás kötelező mező ellenőrzése.
        if (trim($_POST["description"] ?? "") === "") {
            $errors[] = "A leírás kötelező.";
        }

        // Ha nincs validációs hiba, az új verzió mentése.
        if (empty($errors)) {

            // A verzióhoz hozzárendeljük az aktuális projekt azonosítóját.
            $_POST["project_id"] = $projectId;

            // Az új verzióbejegyzés mentése a modellen keresztül.
            $this->versionModel->create($_POST);

            // Visszairányítás a projekt részletes oldalára.
            $this->redirect("index.php?action=show&id=" . $projectId);
        }
    }

    // Az új verzió létrehozására szolgáló nézet betöltése.
    require_once __DIR__ . "/../views/versions/create.php";
}




    // Verzióbejegyzés módosítása.
public function updateVersion(): void
{
    // A módosítandó verzió azonosítójának lekérése a POST adatokból.
    $versionId = (int) ($_POST["id"] ?? 0);

    // Hibás vagy hiányzó verzióazonosító esetén visszairányítás.
    if ($versionId <= 0) {
        $this->redirect();
    }

    // A módosítandó verzió adatainak lekérése.
    $version = $this->versionModel->getById($versionId);

    // Ha a verzió nem található, visszairányítás.
    if (!$version) {
        $this->redirect();
    }

    // A verzió adatainak módosítása a modellen keresztül.
    $this->versionModel->update($versionId, $_POST);

    // Visszairányítás a verzióhoz tartozó projekt részletes oldalára.
    $this->redirect("index.php?action=show&id=" . $version["project_id"]);
}



   // Verzióbejegyzés törlése.
public function deleteVersion(): void
{
    // A törlendő verzió azonosítójának lekérése a POST adatokból.
    $versionId = (int) ($_POST["id"] ?? 0);

    // Hibás vagy hiányzó verzióazonosító esetén visszairányítás.
    if ($versionId <= 0) {
        $this->redirect();
    }

    // A törlendő verzió adatainak lekérése.
    $version = $this->versionModel->getById($versionId);

    // Ha a verzió nem található, visszairányítás.
    if (!$version) {
        $this->redirect();
    }

    // A verzióhoz tartozó projekt azonosítójának eltárolása.
    $projectId = (int) $version["project_id"];

    // A verzióbejegyzés törlése a modellen keresztül.
    $this->versionModel->delete($versionId);

    // Visszairányítás a projekthez tartozó részletes oldalra.
    $this->redirect("index.php?action=show&id=" . $projectId);
}
}
