<?php
header('Content-Type: application/json');
require_once '../config.php';

// CORS dinámico con whitelist desde .env (ALLOWED_ORIGINS)
$allowed = getenv('ALLOWED_ORIGINS') ?: 'http://localhost:8080';
$allowedOrigins = array_map('trim', explode(',', $allowed));
$origin = $_SERVER['HTTP_ORIGIN'] ?? null;
if ($origin && in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Si no coincide, permitir same-origin cuando se sirve backend y frontend juntos
    $selfOrigin = (isset($_SERVER['HTTP_HOST']) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] : null);
    if ($selfOrigin) header("Access-Control-Allow-Origin: $selfOrigin");
}
header('Vary: Origin');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


switch ($_SERVER['REQUEST_METHOD']) {
case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($id <= 0) { http_response_code(400); echo json_encode(['error' => 'id inválido']); exit; }
            try {
                $stmt = $pdo->prepare('SELECT * FROM notes WHERE id = ?');
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) { http_response_code(404); echo json_encode(['error' => 'Nota no encontrada']); exit; }
                echo json_encode($row);
            } catch (Throwable $e) {
                http_response_code(500); echo json_encode(['error' => 'Error interno']);
            }
        } else {
            try {
                $stmt = $pdo->query('SELECT * FROM notes ORDER BY id DESC');
                echo json_encode($stmt->fetchAll());
            } catch (Throwable $e) {
                http_response_code(500); echo json_encode(['error' => 'Error interno']);
            }
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $title = isset($data['title']) ? trim($data['title']) : '';
        $content = isset($data['content']) ? trim($data['content']) : '';
        if ($title === '' || $content === '') { http_response_code(400); echo json_encode(['error' => 'Faltan campos']); exit; }
        if (mb_strlen($title) > 255) { http_response_code(422); echo json_encode(['error' => 'Título demasiado largo']); exit; }
        if (mb_strlen($content) > 5000) { http_response_code(422); echo json_encode(['error' => 'Contenido demasiado largo']); exit; }
        try {
            $stmt = $pdo->prepare('INSERT INTO notes (title, content) VALUES (?, ?)');
            $stmt->execute([$title, $content]);
            echo json_encode(['id' => $pdo->lastInsertId()]);
        } catch (Throwable $e) {
            http_response_code(500); echo json_encode(['error' => 'Error interno']);
        }
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $id = isset($data['id']) ? (int)$data['id'] : 0;
        $title = isset($data['title']) ? trim($data['title']) : '';
        $content = isset($data['content']) ? trim($data['content']) : '';
        if ($id <= 0 || $title === '' || $content === '') { http_response_code(400); echo json_encode(['error' => 'Faltan campos']); exit; }
        if (mb_strlen($title) > 255) { http_response_code(422); echo json_encode(['error' => 'Título demasiado largo']); exit; }
        if (mb_strlen($content) > 5000) { http_response_code(422); echo json_encode(['error' => 'Contenido demasiado largo']); exit; }
        try {
            $stmt = $pdo->prepare('UPDATE notes SET title = ?, content = ? WHERE id = ?');
            $stmt->execute([$title, $content, $id]);
            if ($stmt->rowCount() === 0) { http_response_code(404); echo json_encode(['error' => 'Nota no encontrada']); exit; }
            echo json_encode(['updated' => $id]);
        } catch (Throwable $e) {
            http_response_code(500); echo json_encode(['error' => 'Error interno']);
        }
        break;
    case 'DELETE':
        if (!isset($_GET['id'])) { http_response_code(400); echo json_encode(['error' => 'Falta el id']); exit; }
        $id = (int)$_GET['id'];
        if ($id <= 0) { http_response_code(400); echo json_encode(['error' => 'id inválido']); exit; }
        try {
            $stmt = $pdo->prepare('DELETE FROM notes WHERE id = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() === 0) { http_response_code(404); echo json_encode(['error' => 'Nota no encontrada']); exit; }
            echo json_encode(['deleted' => $id]);
        } catch (Throwable $e) {
            http_response_code(500); echo json_encode(['error' => 'Error interno']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
