<?php
function tmdbRequest($endpoint) {
    $api_key = "your-api-key";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.themoviedb.org/3/" . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        error_log('TMDB API Error: ' . curl_error($ch));
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("TMDB API Error: HTTP $httpCode - Response: $response");
        return null;
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('TMDB API Error: JSON decode failed - ' . json_last_error_msg());
        return null;
    }
    
    return $data;
}

// Rate limiting function
function checkRateLimit() {
    $rateLimitFile = __DIR__ . '/rate_limit.json';
    $currentTime = time();
    $window = 60; // 1 minute window
    $maxRequests = 40; // Maximum requests per window
    
    if (file_exists($rateLimitFile)) {
        $data = json_decode(file_get_contents($rateLimitFile), true);
        if ($currentTime - $data['timestamp'] > $window) {
            $data = ['count' => 1, 'timestamp' => $currentTime];
        } else if ($data['count'] >= $maxRequests) {
            return false;
        } else {
            $data['count']++;
        }
    } else {
        $data = ['count' => 1, 'timestamp' => $currentTime];
    }
    
    file_put_contents($rateLimitFile, json_encode($data));
    return true;
}

// Cache function
function getCachedResponse($key) {
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.json';
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if ($data['expires'] > time()) {
            return $data['content'];
        }
        unlink($cacheFile);
    }
    return null;
}

function cacheResponse($key, $content, $ttl = 3600) {
    $cacheDir = __DIR__ . '/cache';
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }
    
    $cacheFile = $cacheDir . '/' . md5($key) . '.json';
    $data = [
        'content' => $content,
        'expires' => time() + $ttl
    ];
    
    file_put_contents($cacheFile, json_encode($data));
}
?>
