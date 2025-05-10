<?php
header('Content-Type: application/json');
include 'tmdb.php';

$type = $_GET['type'] ?? 'movie';
$page = max(1, intval($_GET['page'] ?? 1));

if (!in_array($type, ['movie', 'tv'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid content type']);
    exit;
}

$data = tmdbRequest("$type/popular?page=$page");
if ($data === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Popular content not found']);
    exit;
}

echo json_encode($data);
?> 