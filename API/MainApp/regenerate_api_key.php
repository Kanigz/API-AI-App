<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit;
}

require_once './scripts/db_connect.php';
$conn = connectDB();

// Odbierz dane JSON
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? $_SESSION['id'];

// Generuj nowy klucz API
$new_api_key = bin2hex(random_bytes(32));

// Przygotuj i wykonaj zapytanie
$update_query = "UPDATE api_keys SET api_key = ?, created_at = CURRENT_TIMESTAMP WHERE user_id = ?";
$stmt = $conn->prepare($update_query);

if ($stmt === false) {
    echo json_encode(['success' => false, 'error' => 'Błąd przygotowania zapytania']);
    exit;
}

$stmt->bind_param("si", $new_api_key, $user_id);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Błąd wykonania zapytania']);
    exit;
}

// Jeśli nie znaleziono rekordu, utwórz nowy
if ($stmt->affected_rows === 0) {
    $insert_query = "INSERT INTO api_keys (user_id, api_key, usage_limit, used_count, is_active) VALUES (?, ?, 1000, 0, 1)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("is", $user_id, $new_api_key);
    
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Błąd tworzenia nowego klucza']);
        exit;
    }
}



$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'api_key' => $new_api_key]);
