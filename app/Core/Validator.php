<?php
class Validator {
    private $data;
    private $errors = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function required($field, $label) {
        if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
            $this->errors[$field] = $label . ' is required.';
        }
        return $this;
    }

    public function email($field, $label) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $label . ' must be a valid email address.';
        }
        return $this;
    }

    public function minLength($field, $length, $label) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $label . ' must be at least ' . $length . ' characters.';
        }
        return $this;
    }

    public function match($field, $confirmField, $label) {
        if (($this->data[$field] ?? null) !== ($this->data[$confirmField] ?? null)) {
            $this->errors[$confirmField] = $label . ' does not match.';
        }
        return $this;
    }

    public function inList($field, $allowed, $label) {
        if (!in_array($this->data[$field] ?? null, $allowed, true)) {
            $this->errors[$field] = $label . ' is invalid.';
        }
        return $this;
    }

    public function unique($field, $table, $column, $label) {
        if (!empty($this->data[$field])) {
            $db = Database::getInstance();
            $exists = $db->count("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?", [$this->data[$field]]);
            if ($exists > 0) {
                $this->errors[$field] = $label . ' is already registered.';
            }
        }
        return $this;
    }

    public function fails() {
        return !empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }
}
