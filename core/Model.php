<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Model — Classe de base CRUD
 */
abstract class Model
{
    protected PDO    $db;
    protected string $table    = '';
    protected string $pk       = 'id';
    protected array  $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Lecture ───────────────────────────────────────────

    public function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $stmt = $this->db->query(
            "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$direction}"
        );
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$this->pk}` = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findOrFail(int $id): array
    {
        $record = $this->find($id);
        if ($record === false) {
            http_response_code(404);
            throw new \RuntimeException("Enregistrement #{$id} introuvable dans {$this->table}.");
        }
        return $record;
    }

    public function findBy(string $column, mixed $value): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = ? LIMIT 1"
        );
        $stmt->execute([$value]);
        return $stmt->fetch();
    }

    public function where(string $column, mixed $value, string $orderBy = 'id'): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = ? ORDER BY `{$orderBy}`"
        );
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne(string $sql, array $params = []): array|false
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function count(string $column = null, mixed $value = null): int
    {
        if ($column !== null) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM `{$this->table}` WHERE `{$column}` = ?"
            );
            $stmt->execute([$value]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) FROM `{$this->table}`");
        }
        return (int) $stmt->fetchColumn();
    }

    // ── Écriture ──────────────────────────────────────────

    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        $columns      = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})"
        );
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $data = $this->filterFillable($data);
        $set  = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));

        $stmt = $this->db->prepare(
            "UPDATE `{$this->table}` SET {$set} WHERE `{$this->pk}` = ?"
        );
        $stmt->execute([...array_values($data), $id]);
        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `{$this->table}` WHERE `{$this->pk}` = ?"
        );
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function exists(string $column, mixed $value, int $excludeId = 0): bool
    {
        $sql    = "SELECT COUNT(*) FROM `{$this->table}` WHERE `{$column}` = ?";
        $params = [$value];
        if ($excludeId > 0) {
            $sql     .= " AND `{$this->pk}` != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    // ── Pagination ────────────────────────────────────────

    public function paginate(int $page, int $perPage = 15, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $offset    = ($page - 1) * $perPage;
        $total     = $this->count();

        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$direction} LIMIT ? OFFSET ?"
        );
        $stmt->execute([$perPage, $offset]);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    // ── Interne ───────────────────────────────────────────

    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) return $data;
        return array_intersect_key($data, array_flip($this->fillable));
    }
}
