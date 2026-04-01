<?php
// CORS taken from class

header('Access-Control-Allow-Origin: *');

  header('Content-Type: application/json');

  $method = $_SERVER['REQUEST_METHOD'];


  if ($method === 'OPTIONS') {

    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

    exit();

  }



// Parse the URL (handles http://localhost/midterm/api/quotes/?author_id=10 etc.)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$parts = explode('/', $uri);

// Skip "midterm" and "api" segments so we get clean resource + optional id
if (isset($parts[0]) && strtolower($parts[0]) === 'midterm') {
    array_shift($parts);
}
if (isset($parts[0]) && strtolower($parts[0]) === 'api') {
    array_shift($parts);
}

$resource     = $parts[0] ?? null;      // authors, categories, quotes
$id_from_path = $parts[1] ?? null;      // e.g. 5 from /quotes/5

// Support BOTH clean path style (/quotes/5) and query string (?id=5)
$id = $id_from_path ?? ($_GET['id'] ?? null);

$allowed_resources = ['authors', 'categories', 'quotes'];

if (!$resource || !in_array($resource, $allowed_resources)) {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Folder that contains action files (create.php, read.php, etc.)
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
        // /quotes/, /authors/, /categories/ -> create.php
        include $base_path . 'create.php';
        break;

    case 'PUT':
    case 'PATCH':
        // /quotes/?id=..., /authors/?id=..., etc. -> update.php
        include $base_path . 'update.php';
        break;

    case 'DELETE':
        // /quotes/?id=..., /authors/?id=..., etc. -> delete.php
        include $base_path . 'delete.php';
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}


?>