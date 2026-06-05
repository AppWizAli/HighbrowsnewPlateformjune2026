<?php

function pafAdminStartSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function pafAdminRequireLogin(): void
{
    pafAdminStartSession();

    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit();
    }
}

function pafAdminEsc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function pafAdminSetFlash(string $type, string $message): void
{
    pafAdminStartSession();
    $_SESSION['admin_flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function pafAdminPullFlash(): ?array
{
    pafAdminStartSession();

    if (!isset($_SESSION['admin_flash']) || !is_array($_SESSION['admin_flash'])) {
        return null;
    }

    $flash = $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);

    return $flash;
}

function pafAdminAvatar(string $picture = '', int $size = 56): string
{
    $picture = trim($picture);
    if ($picture !== '') {
        if (
            strpos($picture, 'data:image') !== 0
            && strpos($picture, 'http://') !== 0
            && strpos($picture, 'https://') !== 0
            && strpos($picture, 'uploads/') !== 0
            && strpos($picture, '/') !== 0
        ) {
            $picture = 'uploads/' . ltrim($picture, '/');
        }

        return $picture;
    }

    return 'data:image/svg+xml;utf8,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">
            <defs>
                <linearGradient id="g" x1="0%" x2="100%" y1="0%" y2="100%">
                    <stop offset="0%" stop-color="#f7f9fc"/>
                    <stop offset="100%" stop-color="#e2e8f0"/>
                </linearGradient>
            </defs>
            <rect width="' . $size . '" height="' . $size . '" rx="' . max(14, (int) floor($size / 4)) . '" fill="url(#g)"/>
            <circle cx="' . (int) floor($size / 2) . '" cy="' . (int) floor($size * 0.36) . '" r="' . (int) floor($size * 0.18) . '" fill="#8ea2b8"/>
            <path d="M' . (int) floor($size * 0.22) . ' ' . (int) floor($size * 0.82) . 'c4-' . (int) floor($size * 0.18) . ' ' . (int) floor($size * 0.17) . '-' . (int) floor($size * 0.28) . ' ' . (int) floor($size * 0.28) . '-' . (int) floor($size * 0.28) . 's' . (int) floor($size * 0.24) . ' ' . (int) floor($size * 0.1) . ' ' . (int) floor($size * 0.28) . ' ' . (int) floor($size * 0.28) . '" fill="#8ea2b8"/>
        </svg>'
    );
}
