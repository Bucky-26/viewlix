<?php
header('Content-Type: application/json');
include 'tmdb.php';

$id = $_GET['id'] ?? 0;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid TV show ID']);
    exit;
}

$data = tmdbRequest("tv/$id");
if ($data === null) {
    http_response_code(404);
    echo json_encode(['error' => 'TV show not found']);
    exit;
}

echo json_encode($data);
?> 