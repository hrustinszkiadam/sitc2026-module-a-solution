<?php
// C2 — API Request Logger.
//
// The endpoint is   POST /api/log   with a Content-Type: application/json body.
// The PHP built-in server serves this index.php for every URL that does not exist
// as a real file, so the endpoint needs no .php in its address and no router script:
//
//     php -S localhost:8080          started in this folder  ->  POST /api/log
//     php -S localhost:8080          started in the root     ->  POST /C2/api/log

const LOG_DIR = __DIR__ . '/log';

$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Anything that is not the endpoint: the test page on GET, otherwise 404.
if (!str_ends_with($path, '/api/log')) {
    if ($method === 'GET') {
        readfile(__DIR__ . '/page.html');
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found, the endpoint is POST /api/log.']);
    }
    exit;
}

header('Content-Type: application/json');

// Only POST is allowed on the endpoint.
if ($method !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Method Not Allowed, use POST.']);
    exit;
}

// Only application/json bodies are accepted.
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(415);
    echo json_encode(['error' => 'Unsupported Media Type, use Content-Type: application/json.']);
    exit;
}

$body = file_get_contents('php://input');

// The body has to be valid JSON.
json_decode($body);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request, the body is not valid JSON.']);
    exit;
}

if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0777, true);
}

$fileName = date('H-i-s') . '-request.txt';
file_put_contents(LOG_DIR . '/' . $fileName, $body);

http_response_code(201);
echo json_encode(['status' => 'logged', 'file' => 'log/' . $fileName]);
