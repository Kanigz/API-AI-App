<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: X-API-Key, Content-Type, Origin, Authorization');
header('Access-Control-Max-Age: 86400');

require_once './scripts/db_connect.php';
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

function validateApiKey($apiKey) {
    global $conn;
    if (!$conn) {
        return false;
    }
    
    $stmt = $conn->prepare("SELECT * FROM api_keys WHERE api_key = ? AND is_active = 1 AND used_count < usage_limit");
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("s", $apiKey);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $conn->query("UPDATE api_keys SET used_count = used_count + 1 WHERE api_key = '$apiKey'");
        return true;
    }
    return false;
}

$headers = getallheaders();
$apiKey = isset($headers['X-API-Key']) ? $headers['X-API-Key'] : '';

if (!validateApiKey($apiKey)) {
    http_response_code(401);
    echo json_encode(['error' => 'Nieprawidłowy klucz API lub przekroczony limit']);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Zmieniony prepare statement, dodane api_key
        $stmt = $conn->prepare("INSERT INTO game_scores (player_name, score, game_name, api_key) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("siss", $data['player'], $data['score'], $data['game'], $apiKey);
            if ($stmt->execute()) {
                $response = ['status' => 'success', 'message' => 'Wynik zapisany'];
            } else {
                $response = ['status' => 'error', 'message' => 'Błąd zapisu'];
            }
        }
        break;
        
    case 'GET':
        // Możemy też filtrować wyniki po api_key jeśli chcemy
        $stmt = $conn->prepare("SELECT * FROM game_scores WHERE api_key = ? ORDER BY score DESC LIMIT 10");
        $stmt->bind_param("s", $apiKey);
        $stmt->execute();
        $result = $stmt->get_result();
        $scores = [];
        while($row = $result->fetch_assoc()) {
            $scores[] = $row;
        }
        $response = ['status' => 'success', 'data' => $scores];
        break;
}

echo json_encode($response);
?>
