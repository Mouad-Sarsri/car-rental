<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controller — Classe de base
 */
abstract class Controller
{
    // ── Rendu des vues ────────────────────────────────────

    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data, EXTR_SKIP);

        $viewPath   = BASE_PATH . "/app/Views/{$view}.php";
        $layoutPath = BASE_PATH . "/app/Views/layouts/{$layout}.php";

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : {$view}.php");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $currentView = $view;

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function renderPartial(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = BASE_PATH . "/app/Views/{$view}.php";
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : {$view}.php");
        }
        require $viewPath;
    }

    // ── Redirections ──────────────────────────────────────

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function redirectBack(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    protected function redirectWithSuccess(string $url, string $message): void
    {
        $this->flash('success', $message);
        $this->redirect($url);
    }

    protected function redirectWithError(string $url, string $message): void
    {
        $this->flash('error', $message);
        $this->redirect($url);
    }

    // ── Session & Flash ───────────────────────────────────

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    // ── Auth ──────────────────────────────────────────────

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    protected function authUser(): array|null
    {
        return $_SESSION['user'] ?? null;
    }

    protected function authId(): int|null
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    protected function authRole(): string|null
    {
        return $_SESSION['user_role'] ?? null;
    }

    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->flash('error', 'Veuillez vous connecter.');
            $this->redirect('/login');
        }
    }

    protected function requireRole(string|array $roles): void
    {
        $this->requireAuth();
        if (!in_array($this->authRole(), (array) $roles, true)) {
            http_response_code(403);
            $this->render('errors/403');
            exit;
        }
    }

    // ── Input ─────────────────────────────────────────────

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    // ── JSON ──────────────────────────────────────────────

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── Validation ────────────────────────────────────────

    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value     = $data[$field] ?? null;
            $rulesList = explode('|', $ruleString);

            foreach ($rulesList as $rule) {
                [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

                match ($ruleName) {
                    'required' => (empty($value) && $value !== '0')
                        ? $errors[$field][] = "Le champ {$field} est obligatoire." : null,

                    'email' => (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL))
                        ? $errors[$field][] = "L'email {$field} est invalide." : null,

                    'min' => (!empty($value) && strlen((string) $value) < (int) $param)
                        ? $errors[$field][] = "Le champ {$field} doit contenir au moins {$param} caractères." : null,

                    'max' => (!empty($value) && strlen((string) $value) > (int) $param)
                        ? $errors[$field][] = "Le champ {$field} ne doit pas dépasser {$param} caractères." : null,

                    'numeric' => (!empty($value) && !is_numeric($value))
                        ? $errors[$field][] = "Le champ {$field} doit être un nombre." : null,

                    'in' => (!empty($value) && !in_array($value, explode(',', $param ?? ''), true))
                        ? $errors[$field][] = "La valeur de {$field} n'est pas autorisée." : null,

                    default => null,
                };
            }
        }

        return $errors;
    }
}
