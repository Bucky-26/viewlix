<?php
header('Content-Type: application/json');
include 'tmdb.php';

$query = $_GET['query'] ?? '';

if (empty($query)) {
    http_response_code(400);
    echo json_encode(['error' => 'Search query is required']);
    exit;
}

$data = tmdbRequest("search/multi?query=" . urlencode($query));
if ($data === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Search failed']);
    exit;
}

echo json_encode($data);
?> 