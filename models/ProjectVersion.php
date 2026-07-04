<?php

class ProjectVersion
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getByProjectId(int $projectId): array
    {
        $sql = "SELECT *
            FROM project_versions
            WHERE project_id = :project_id
            ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "project_id" => $projectId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function getLatestVersion(int $projectId): array|false
    {
        $sql = "SELECT *
            FROM project_versions
            WHERE project_id = :project_id
            ORDER BY created_at DESC
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "project_id" => $projectId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLatestVersion(int $projectId, string $versionNumber): bool
    {
        $latestVersion = $this->getLatestVersion($projectId);

        if (!$latestVersion) {
            return false;
        }

        $sql = "UPDATE project_versions
            SET version_number = :version_number
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "version_number" => $versionNumber,
            "id" => $latestVersion["id"]
        ]);
    }

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
            "change_type" => "Létrehozás",
            "description" => "Projekt létrehozása",
            "deployed" => 0
        ]);
    }
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
}
