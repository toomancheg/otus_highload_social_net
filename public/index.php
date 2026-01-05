<?php
require_once __DIR__ . '/../src/vendor/autoload.php';

// Настройка CORS заголовков
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Подключаем контроллеры
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/UserController.php';

// Устанавливаем заголовок Content-Type по умолчанию
header('Content-Type: application/json; charset=utf-8');

// Получаем путь запроса
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Убираем базовый путь если приложение в поддиректории
$basePath = '/';
$path = str_replace($basePath, '', $requestUri);
$path = trim($path, '/');

// Маршрутизация
try {
    switch (true) {
        case $path === 'login' && $requestMethod === 'POST':
            $controller = new AuthController();
            $controller->login();
            break;

        case $path === 'user/register' && $requestMethod === 'POST':
            $controller = new UserController();
            $controller->register();
            break;

        case preg_match('#^user/get/(\d+)$#', $path, $matches) && $requestMethod === 'GET':
            $controller = new UserController();
            $controller->getById($matches[1]);
            break;

        default:
            http_response_code(404);
            echo json_encode([
                'error' => 'Route not found',
                'path' => $path,
                'method' => $requestMethod
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}