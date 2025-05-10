<?php
header('Content-Type: application/json');
include 'tmdb.php';

$action = $_GET['action'] ?? '';

switch($action) {
    case 'get_movie':
        $id = $_GET['id'] ?? 0;
        $type = $_GET['type'] ?? 'movie';
        $data = tmdbRequest("$type/$id");
        echo json_encode($data);
        break;

    case 'get_similar':
        $id = $_GET['id'] ?? 0;
        $type = $_GET['type'] ?? 'movie';
        $data = tmdbRequest("$type/$id/similar");
        echo json_encode($data);
        break;

    case 'get_popular':
        $type = $_GET['type'] ?? 'movie';
        $page = $_GET['page'] ?? 1;
        $data = tmdbRequest("$type/popular?page=$page");
        echo json_encode($data);
        break;

    case 'search':
        $query = urlencode($_GET['query'] ?? '');
        $data = tmdbRequest("search/multi?query=$query");
        echo json_encode($data);
        break;

    case 'get_credits':
        $id = $_GET['id'] ?? 0;
        $type = $_GET['type'] ?? 'movie';
        $data = tmdbRequest("$type/$id/credits");
        echo json_encode($data);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?> 