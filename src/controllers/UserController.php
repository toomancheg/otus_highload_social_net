<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $input = $this->getJsonInput();

        $requiredFields = [
            'email', 'password', 'first_name', 'last_name',
            'birth_date', 'gender'
        ];

        $this->validateRequiredFields($input, $requiredFields);

        // Проверка email
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['error' => 'Invalid email format'], 400);
        }

        // Проверка пароля
        if (strlen($input['password']) < 6) {
            $this->jsonResponse(['error' => 'Password must be at least 6 characters'], 400);
        }

        // Проверка, что пользователь не существует
        $existingUser = $this->userModel->findByEmail($input['email']);
        if ($existingUser) {
            $this->jsonResponse(['error' => 'User with this email already exists'], 409);
        }

        // Создание пользователя
        try {
            $userId = $this->userModel->create($input);

            $this->jsonResponse([
                'id' => $userId,
                'email' => $input['email'],
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'message' => 'User registered successfully'
            ], 201);

        } catch (Exception $e) {
            $this->jsonResponse(['error' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function getById($id) {
        if (!is_numeric($id) || $id <= 0) {
            $this->jsonResponse(['error' => 'Invalid user ID'], 400);
        }

        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->jsonResponse(['error' => 'User not found'], 404);
        }

        // Убираем чувствительные данные
        unset($user['password_hash']);

        $this->jsonResponse($user);
    }
}