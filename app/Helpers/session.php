<?php
function setOldInput($data) {
    $_SESSION['old_input'] = $data;
}

function clearOldInput() {
    unset($_SESSION['old_input'], $_SESSION['validation_errors']);
}

function setValidationErrors($errors) {
    $_SESSION['validation_errors'] = $errors;
}

function validationError($field) {
    return $_SESSION['validation_errors'][$field] ?? null;
}

function validationErrors() {
    return $_SESSION['validation_errors'] ?? [];
}

function hasFlash($type) {
    return isset($_SESSION['flash_' . $type]);
}

function flash($type) {
    $key = 'flash_' . $type;
    $message = $_SESSION[$key] ?? null;
    unset($_SESSION[$key]);
    return $message;
}
