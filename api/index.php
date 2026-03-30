<?php
// ======================================================
//  SINGLE API FRONT CONTROLLER (works with /midterm/api/)
// ======================================================

// Common CORS + JSON headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


include_once '../config/Database.php';
include_once '../models/Quote.php';
include_once '../models/Category.php';
include_once '../models/Author.php';

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Parse the requested path (handles /midterm/api/quotes/ etc.)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$parts = explode('/', $uri);

// Skip any leading folder like "midterm" so we always find "api"
if (isset($parts[0]) && strtolower($parts[0]) === 'midterm') {
    array_shift($parts);
}
if (isset($parts[0]) && strtolower($parts[0]) === 'api') {
    array_shift($parts);
}

$resource     = $parts[0] ?? null;      // "quotes", "authors", etc.
$id_from_path = $parts[1] ?? null;      // e.g. 5 from /quotes/5

// Support BOTH /quotes/?id=5 and /quotes/5
$id = $id_from_path ?? ($_GET['id'] ?? null);

$allowed_resources = ['authors', 'categories', 'quotes'];

if (!$resource || !in_array($resource, $allowed_resources)) {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Path to your original operation files
$base_path = __DIR__ . "/{$resource}/";

switch ($method) {
    case 'GET':
        if ($id !== null && $id !== '') {
            include $base_path . 'read_single.php';
        } else {
            include $base_path . 'read.php';
        }
        break;

    case 'POST':
        include $base_path . 'create.php';
        break;

    case 'PUT':
    case 'PATCH':
        include $base_path . 'update.php';
        break;

    case 'DELETE':
        include $base_path . 'delete.php';
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}