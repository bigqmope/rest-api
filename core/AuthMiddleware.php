<?php
class AuthMiddleware {
    private $validToken;

    public function __construct() {
        // Token bisa kamu ganti sesuai kebutuhan atau ambil dari env file
        $this->validToken = "12345ABCDEF";
    }

    public function verify() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            $this->unauthorized("Authorization header tidak ditemukan");
        }

        if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $this->unauthorized("Format Authorization tidak valid");
        }

        $token = $matches[1];
        if ($token !== $this->validToken) {
            $this->forbidden("Token tidak valid");
        }
    }

    private function unauthorized($message) {
        http_response_code(401);
        echo json_encode(["error" => $message]);
        exit;
    }

    private function forbidden($message) {
        http_response_code(403);
        echo json_encode(["error" => $message]);
        exit;
    }
}
