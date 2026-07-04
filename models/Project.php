<?php

class Project
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM projects ORDER BY created_at DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO projects 
            (title, description, notes,project_type, status, deadline, price, github_url, live_url)
            VALUES
            (:title, :description, :notes, :project_type, :status, :deadline, :price, :github_url, :live_url)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "title" => $data["title"],
            "description" => $data["description"],
            "project_type" => $data["project_type"],
            "status" => $data["status"],
            "deadline" => $data["deadline"] ?: null,
            "price" => $data["price"] ?: null,
            "github_url" => $data["github_url"] ?: null,
            "live_url" => $data["live_url"] ?: null,
            "notes" => $data["notes"] ?: null,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM projects WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id
        ]);
    }

    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM projects WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE projects SET
                title = :title,
                description = :description,
                notes = :notes,
                project_type = :project_type,
                status = :status,
                deadline = :deadline,
                price = :price,
                github_url = :github_url,
                live_url = :live_url
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "title" => $data["title"],
            "description" => $data["description"],
            "notes" => $data["notes"] ?: null,
            "project_type" => $data["project_type"],
            "status" => $data["status"],
            "deadline" => $data["deadline"] ?: null,
            "price" => $data["price"] ?: null,
            "github_url" => $data["github_url"] ?: null,
            "live_url" => $data["live_url"] ?: null
        ]);
    }
    public function getProjectCount(): int
    {
        $sql = "SELECT COUNT(*) FROM projects";

        $stmt = $this->pdo->query($sql);

        return (int) $stmt->fetchColumn();
    }
    public function getPersonalProjectCount(): int
    {
        $sql = "SELECT COUNT(*) 
            FROM projects 
            WHERE type = 'personal'";

        $stmt = $this->pdo->query($sql);

        return (int) $stmt->fetchColumn();
    }

    public function getClientProjectCount(): int
    {
        $sql = "SELECT COUNT(*)
            FROM projects
            WHERE type = 'client'";

        $stmt = $this->pdo->query($sql);

        return (int) $stmt->fetchColumn();
    }

    public function getActiveProjectCount(): int
    {
        $sql = "SELECT COUNT(*)
            FROM projects
            WHERE status = 'active'";

        $stmt = $this->pdo->query($sql);

        return (int) $stmt->fetchColumn();
    }
}
