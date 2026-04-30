<?php
function url($page, $params = []) {
    $query = array_merge(['page' => $page], $params);
    return BASE_URL . '/index.php?' . http_build_query($query);
}

function asset($path) {
    return BASE_URL . '/' . ltrim($path, '/');
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function old($field, $default = '') {
    return e($_SESSION['old_input'][$field] ?? $default);
}
