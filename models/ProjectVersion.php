<?php

class ProjectVersion
{
    // A PDO adatbázis-kapcsolat tárolása.
    private PDO $pdo;

    // Az adatbázis-kapcsolat átvétele és eltárolása.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    // Egy projekthez tartozó összes verzió lekérése verziószám szerint csökkenő sorrendben.
    public function getByProjectId(int $projectId): array
    {
        // Az adott projekthez tartozó verziók lekérése és verziószám szerinti rendezése.
        $sql = "SELECT *
            FROM project_versions
            WHERE project_id = :project_id
            ORDER BY
                CAST(SUBSTRING_INDEX(version_number, '.', 1) AS UNSIGNED) DESC,
                CAST(SUBSTRING_INDEX(version_number, '.', -1) AS UNSIGNED) DESC";

        // Az SQL-lekérdezés előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A lekérdezés végrehajtása a projekt azonosítójával.
        $stmt->execute([
            "project_id" => $projectId
        ]);

        // Az összes verzió visszaadása asszociatív tömbként.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // A legfrissebb verzió lekérése egy projekthez.
    public function getLatestVersion(int $projectId): array|false
    {
        // Az adott projekt legfrissebb verziójának lekérése.
        $sql = "SELECT *
            FROM project_versions
            WHERE project_id = :project_id
            ORDER BY
                CAST(SUBSTRING_INDEX(version_number, '.', 1) AS UNSIGNED) DESC,
                CAST(SUBSTRING_INDEX(version_number, '.', -1) AS UNSIGNED) DESC
            LIMIT 1";

        // Az SQL-lekérdezés előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A lekérdezés végrehajtása a projekt azonosítójával.
        $stmt->execute([
            "project_id" => $projectId
        ]);

        // A megtalált verzió visszaadása asszociatív tömbként.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    // Új verzióbejegyzés létrehozása űrlapadatokból.
    public function create(array $data): bool
    {
        // SQL utasítás összeállítása az új verzióbejegyzés létrehozásához.
        $sql = "INSERT INTO project_versions
            (project_id, version_number, change_type, description, deployed)
            VALUES
            (:project_id, :version_number, :change_type, :description, :deployed)";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // Az új verzió adatainak mentése.
        return $stmt->execute([
            "project_id" => $data["project_id"],
            "version_number" => $data["version_number"],
            "change_type" => $data["change_type"],
            "description" => $data["description"],
            "deployed" => isset($data["deployed"]) ? 1 : 0
        ]);
    }



    // Projekt létrehozásakor automatikusan elkészíti a kezdő verziót.
    public function createInitialVersion(int $projectId): bool
    {
        // SQL utasítás összeállítása a kezdő verzió létrehozásához.
        $sql = "INSERT INTO project_versions
            (project_id, version_number, change_type, description, deployed)
            VALUES
            (:project_id, :version_number, :change_type, :description, :deployed)";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A 0.1-es kezdő verzió automatikus mentése.
        return $stmt->execute([
            "project_id" => $projectId,
            "version_number" => "0.1",
            "change_type" => "feature",
            "description" => "Projekt létrehozása",
            "deployed" => 0
        ]);
    }



    // Automatikus verzióbejegyzés létrehozása projekt módosításakor.
    public function createVersion(
        int $projectId,
        string $versionNumber,
        string $changeType = "feature",
        string $description = "Projekt módosítása"
    ): bool {
        // SQL utasítás összeállítása az új verzió létrehozásához.
        $sql = "INSERT INTO project_versions
            (project_id, version_number, change_type, description, deployed)
            VALUES
            (:project_id, :version_number, :change_type, :description, 0)";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // Az új verzióbejegyzés mentése.
        return $stmt->execute([
            "project_id" => $projectId,
            "version_number" => $versionNumber,
            "change_type" => $changeType,
            "description" => $description
        ]);
    }



    // Egy konkrét verzió lekérése azonosító alapján.
    public function getById(int $id): array|false
    {
        // Egy verzió lekérése azonosító alapján.
        $sql = "SELECT *
            FROM project_versions
            WHERE id = :id";

        // Az SQL-lekérdezés előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A lekérdezés végrehajtása a verzió azonosítójával.
        $stmt->execute([
            "id" => $id
        ]);

        // A megtalált verzió visszaadása asszociatív tömbként.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    // Verzióbejegyzés módosítása.
    public function update(int $id, array $data): bool
    {
        // SQL utasítás összeállítása a verzió adatainak módosításához.
        $sql = "UPDATE project_versions
            SET
                version_number = :version_number,
                change_type = :change_type,
                description = :description,
                deployed = :deployed
            WHERE id = :id";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A verzió módosított adatainak mentése.
        return $stmt->execute([
            "id" => $id,
            "version_number" => $data["version_number"],
            "change_type" => $data["change_type"],
            "description" => $data["description"],
            "deployed" => isset($data["deployed"]) ? 1 : 0
        ]);
    }


    
    // Verzióbejegyzés törlése.
    public function delete(int $id): bool
    {
        // SQL utasítás összeállítása a verzió törléséhez.
        $sql = "DELETE FROM project_versions
            WHERE id = :id";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A verzió törlése, majd a művelet sikerességének visszaadása.
        return $stmt->execute([
            "id" => $id
        ]);
    }
}
