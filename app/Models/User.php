<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class User extends Model
{
    protected string $table = 'users';
    protected array  $fillable = [
        'nom', 'prenom', 'email', 'password',
        'role', 'telephone', 'avatar', 'actif',
    ];

    // ── Authentification ──────────────────────────────────

    public function findByEmail(string $email): array|false
    {
        return $this->findBy('email', $email);
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Créer un utilisateur avec le mot de passe hashé.
     * Retourne le nouvel ID.
     */
    public function register(array $data): int
    {
        $data['password'] = $this->hashPassword($data['password']);
        $data['role']     = $data['role'] ?? 'client';
        $data['actif']    = 1;
        return $this->create($data);
    }

    // ── Requêtes métier ───────────────────────────────────

    public function allClients(): array
    {
        return $this->query(
            "SELECT * FROM users WHERE role = 'client' ORDER BY nom, prenom"
        );
    }

    public function allManagers(): array
    {
        return $this->query(
            "SELECT * FROM users WHERE role = 'agency_manager' ORDER BY nom, prenom"
        );
    }

    /** Managers sans agence assignée */
    public function availableManagers(): array
    {
        return $this->query(
            "SELECT u.*
             FROM users u
             LEFT JOIN agencies a ON a.manager_id = u.id
             WHERE u.role = 'agency_manager'
               AND a.id IS NULL
             ORDER BY u.nom, u.prenom"
        );
    }

    public function countByRole(): array
    {
        return $this->query(
            "SELECT role, COUNT(*) AS total
             FROM users
             GROUP BY role"
        );
    }

    /** Activer / désactiver un compte */
    public function toggleActif(int $id): void
    {
        $this->db->prepare(
            "UPDATE users SET actif = NOT actif WHERE id = ?"
        )->execute([$id]);
    }

    /** Mettre à jour le mot de passe */
    public function updatePassword(int $id, string $newPassword): void
    {
        $hash = $this->hashPassword($newPassword);
        $this->db->prepare(
            "UPDATE users SET password = ? WHERE id = ?"
        )->execute([$hash, $id]);
    }

    /** Retirer les infos sensibles avant d'exposer l'objet */
    public function sanitize(array $user): array
    {
        unset($user['password']);
        return $user;
    }
}
