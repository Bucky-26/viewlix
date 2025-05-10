<?php
header('Content-Type: application/json');
include 'tmdb.php';

$id = $_GET['id'] ?? 0;
$type = $_GET['type'] ?? 'movie';

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

$data = tmdbRequest("$type/$id/similar");
if ($data === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Similar content not found']);
    exit;
}

echo json_encode($data);
?> 