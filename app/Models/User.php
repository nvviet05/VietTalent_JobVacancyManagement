<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email) {
        return $this->db->fetch('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function findById($id) {
        return $this->db->fetch('SELECT * FROM users WHERE id = ?', [(int)$id]);
    }

    public function countByRole($role) {
        return $this->db->count(
            'SELECT COUNT(*) FROM users WHERE role = ?',
            [$role]
        );
    }

    public function create($data) {
        return $this->db->insert('users', $data);
    }

    public function updatePassword($userId, $passwordHash) {
        return $this->db->execute(
            'UPDATE users SET password_hash = ? WHERE id = ?',
            [$passwordHash, (int)$userId]
        );
    }

    /* ---------- Password reset (forgot password feature) ---------- */

    /**
     * Generates a single-use reset token, stores its SHA-256 hash, and returns
     * the plain token (caller is responsible for delivering it to the user).
     */
    public function createPasswordResetToken($userId, $ttlMinutes = 30) {
        $plain = bin2hex(random_bytes(24)); // 48 hex chars
        $hash  = hash('sha256', $plain);
        $this->db->insert('password_resets', [
            'user_id'    => (int)$userId,
            'token_hash' => $hash,
            'expires_at' => date('Y-m-d H:i:s', time() + $ttlMinutes * 60),
        ]);
        return $plain;
    }

    /**
     * Returns the matching unused, unexpired reset row (with user_id) or null.
     */
    public function findValidResetToken($plainToken) {
        $hash = hash('sha256', $plainToken);
        return $this->db->fetch(
            'SELECT pr.*, u.email, u.full_name
               FROM password_resets pr
               INNER JOIN users u ON u.id = pr.user_id
              WHERE pr.token_hash = ?
                AND pr.used_at IS NULL
                AND pr.expires_at > NOW()
              LIMIT 1',
            [$hash]
        );
    }

    public function markResetTokenUsed($resetId) {
        return $this->db->execute(
            'UPDATE password_resets SET used_at = NOW() WHERE id = ?',
            [(int)$resetId]
        );
    }
}
