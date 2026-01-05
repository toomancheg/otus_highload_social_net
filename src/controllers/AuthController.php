<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $input = $this->getJsonInput();
        $this->validateRequiredFields($input, ['email', 'password']);

        $user = $this->userModel->findByEmail($input['email']);

        if (!$user || !$this->userModel->verifyPassword($input['password'], $user['password_hash'])) {
            $this->jsonResponse(['error' => 'Invalid email or password'], 401);
        }

        // В реальном приложении здесь бы генерировался JWT токен
        $token = base64_encode(json_encode([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'expires' => time() + 3600
        ]));

        $this->jsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name']
            ]
        ]);
    }
}
