<?php

class Project
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Összes projekt lekérése, legújabbak elöl.
    public function getAll(): array
    {
        // SQL-lekérdezés összeállítása az összes projekt lekéréséhez.
        $sql = "SELECT *
        FROM projects
        ORDER BY created_at DESC";

        // Az SQL-lekérdezés végrehajtása a PDO-kapcsolaton keresztül.
        $stmt = $this->pdo->query($sql);

        // A lekérdezés összes eredményének visszaadása asszociatív tömbként.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // Új projekt létrehozása.
    public function create(array $data): int|false
    {
        // SQL utasítás összeállítása az új projekt beszúrásához.
        $sql = "INSERT INTO projects
        (title, description, notes, type, status, deadline, price, github_url, live_url)
        VALUES
        (:title, :description, :notes, :type, :status, :deadline, :price, :github_url, :live_url)";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // Az SQL utasítás végrehajtása a kapott projektadatokkal.
        $success = $stmt->execute([
            "title" => $data["title"],
            "description" => $data["description"] ?? null,
            "notes" => $data["notes"] ?: null,
            "type" => $data["type"],
            "status" => $data["status"] ?? "planning",
            "deadline" => $data["deadline"] ?: null,
            "price" => $data["price"] ?: null,
            "github_url" => $data["github_url"] ?: null,
            "live_url" => $data["live_url"] ?: null
        ]);

        // Sikeres mentés esetén visszaadjuk az új projekt adatbázis-azonosítóját.
        if ($success) {
            return (int) $this->pdo->lastInsertId();
        }

        // Sikertelen mentés esetén false értékkel térünk vissza.
        return false;
    }




    // Egy projekt lekérése azonosító alapján.
    public function getById(int $id): array|false
    {
        // SQL-lekérdezés összeállítása az adott projekt lekéréséhez.
        $sql = "SELECT *
        FROM projects
        WHERE id = :id";

        // Az SQL-lekérdezés előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A lekérdezés végrehajtása a kapott projektazonosítóval.
        $stmt->execute([
            "id" => $id
        ]);

        // A megtalált projekt visszaadása asszociatív tömbként.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }





    // Projekt adatainak módosítása.
    public function update(int $id, array $data): bool
    {
        // SQL utasítás összeállítása a projekt adatainak módosításához.
        $sql = "UPDATE projects
        SET
            title = :title,
            description = :description,
            notes = :notes,
            type = :type,
            status = :status,
            deadline = :deadline,
            price = :price,
            github_url = :github_url,
            live_url = :live_url
        WHERE id = :id";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A módosítás végrehajtása a kapott projektazonosítóval és adatokkal.
        return $stmt->execute([
            "id" => $id,
            "title" => $data["title"],
            "description" => $data["description"] ?? null,
            "notes" => $data["notes"] ?: null,
            "type" => $data["type"],
            "status" => $data["status"] ?? "planning",
            "deadline" => $data["deadline"] ?: null,
            "price" => $data["price"] ?: null,
            "github_url" => $data["github_url"] ?: null,
            "live_url" => $data["live_url"] ?: null
        ]);
    }




    // Projekt törlése.
    public function delete(int $id): bool
    {
        // SQL utasítás összeállítása az adott projekt törléséhez.
        $sql = "DELETE FROM projects
        WHERE id = :id";

        // Az SQL utasítás előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A törlés végrehajtása a kapott projektazonosítóval,
        // majd a művelet sikerességének visszaadása.
        return $stmt->execute([
            "id" => $id
        ]);
    }




    // Összes projekt száma.
    public function getProjectCount(): int
    {
        // SQL-lekérdezés az összes projekt megszámlálásához.
        $sql = "SELECT COUNT(*)
        FROM projects";

        // A lekérdezés végrehajtása.
        $stmt = $this->pdo->query($sql);

        // A lekérdezés egyetlen eredményének visszaadása egész számként.
        return (int) $stmt->fetchColumn();
    }




    // Saját projektek száma.
    public function getPersonalProjectCount(): int
    {
        // SQL-lekérdezés a saját típusú projektek megszámlálásához.
        $sql = "SELECT COUNT(*)
        FROM projects
        WHERE type = 'personal'";

        // A lekérdezés végrehajtása.
        $stmt = $this->pdo->query($sql);

        // A kapott darabszám visszaadása egész számként.
        return (int) $stmt->fetchColumn();
    }




    // Ügyfélprojektek száma.
    public function getClientProjectCount(): int
    {
        // SQL-lekérdezés az ügyfélprojektek megszámlálásához.
        $sql = "SELECT COUNT(*)
        FROM projects
        WHERE type = 'client'";

        // A lekérdezés végrehajtása.
        $stmt = $this->pdo->query($sql);

        // A kapott darabszám visszaadása egész számként.
        return (int) $stmt->fetchColumn();
    }



    
    // Aktív projektek száma.
    public function getActiveProjectCount(): int
    {
        // SQL-lekérdezés az aktív projektek megszámlálásához.
        $sql = "SELECT COUNT(*)
        FROM projects
        WHERE status = 'active'";

        // A lekérdezés végrehajtása.
        $stmt = $this->pdo->query($sql);

        // A kapott darabszám visszaadása egész számként.
        return (int) $stmt->fetchColumn();
    }
}
