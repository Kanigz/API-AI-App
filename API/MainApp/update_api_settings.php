<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit;
}

require_once './scripts/db_connect.php';
$conn = connectDB();

// Pobierz dane z formularza
$user_id = $_POST['user_id'] ?? $_SESSION['id'];
$usage_limit = $_POST['usage_limit'] ?? 1000;
$is_active = isset($_POST['is_active']) ? 1 : 0;

// Sprawdź czy istnieje rekord dla użytkownika
$check_query = "SELECT id FROM api_keys WHERE user_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // Aktualizuj istniejący rekord
    $update_query = "UPDATE api_keys SET usage_limit = ?, is_active = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iii", $usage_limit, $is_active, $user_id);
} else {
    // Utwórz nowy rekord
    $api_key = bin2hex(random_bytes(32)); // Generuj nowy klucz API
    $insert_query = "INSERT INTO api_keys (user_id, api_key, usage_limit, used_count, is_active) VALUES (?, ?, ?, 0, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isii", $user_id, $api_key, $usage_limit, $is_active);
}

if ($stmt->execute()) {
    $_SESSION['message'] = "Ustawienia API zostały zaktualizowane.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Wystąpił błąd podczas aktualizacji ustawień API.";
    $_SESSION['message_type'] = "danger";
}



$stmt->close();
$conn->close();

// Przekieruj z powrotem do strony ustawień API
header('Location: api_settings.php');
exit;
