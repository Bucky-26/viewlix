<?php
require_once 'config.php';
require_once 'tmdb.php';

// Get and validate parameters
$id = $_GET['id'] ?? 0;
$type = $_GET['type'] ?? 'movie';

// Validate required parameters
validateRequired($_GET, ['id']);

// Validate ID is numeric
if (!is_numeric($id)) {
    sendError('Invalid movie ID format', 400);
}

// Make API request
$data = tmdbRequest("$type/$id");

// Handle response
if ($data === null) {
    sendError('Movie not found', 404);
}

// Send successful response
sendResponse($data);
?> 