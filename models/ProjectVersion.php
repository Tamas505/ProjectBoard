<?php

class ProjectVersion
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Egy projekthez tartozó összes verzió lekérése verziószám szerint csökkenő sorrendben.
    public function getByProjectId(int $projectId): array
    {
        $sql = "SELECT *
            FROM project_versions
            WHERE project_id = :project_id
            ORDER BY
                CAST(SUBSTRING_INDEX(version_number, '.', 1) AS UNSIGNED) DESC,
                CAST(SUBSTRING_INDEX(version_number, '.', -1) AS UNSIGNED) DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "project_id" => $projectId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // A legfrissebb verzió lekérése egy projekthez.
    public function getLatestVersion(int $projectId): array|false
    {
        $sql = "SELECT *
            FROM project_versions
            WHERE project_id = :project_id
            ORDER BY
                CAST(SUBSTRING_INDEX(version_number, '.', 1) AS UNSIGNED) DESC,
                CAST(SUBSTRING_INDEX(version_number, '.', -1) AS UNSIGNED) DESC
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "project_id" => $projectId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Új verzióbejegyzés létrehozása űrlapadatokból.
    public function create(array $data): bool
    {
        $sql = "INSERT INTO project_versions
            (project_id, version_number, change_type, description, deployed)
            VALUES
            (:project_id, :version_number, :change_type, :description, :deployed)";

        $stmt = $this->pdo->prepare($sql);

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
        $sql = "INSERT INTO project_versions
            (project_id, version_number, change_type, description, deployed)
            VALUES
            (:project_id, :version_number, :change_type, :description, :deployed)";

        $stmt = $this->pdo->prepare($sql);

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
        $sql = "INSERT INTO project_versions
            (project_id, version_number, change_type, description, deployed)
            VALUES
            (:project_id, :version_number, :change_type, :description, 0)";

        $stmt = $this->pdo->prepare($sql);

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
        $sql = "SELECT *
            FROM project_versions
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verzióbejegyzés módosítása.
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE project_versions
            SET
                version_number = :version_number,
                change_type = :change_type,
                description = :description,
                deployed = :deployed
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

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
        $sql = "DELETE FROM project_versions
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id
        ]);
    }
}