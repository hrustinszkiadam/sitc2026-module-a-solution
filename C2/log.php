<?php
// C2 — API Request Logger. Every POST request with a JSON body is written into
// log/HH-MM-SS-request.txt.

const LOG_DIR = __DIR__ . '/log';

header('Content-Type: application/json');

// Only POST is allowed.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
