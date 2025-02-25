<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit;
}

require_once './scripts/db_connect.php';
$conn = connectDB();

// Get user's API key
$user_id = $_SESSION['id'];
$api_query = "SELECT * FROM api_keys WHERE user_id = ?";
$stmt = $conn->prepare($api_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$api_data = $result->fetch_assoc();

// Handle API key verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_key = trim($_POST['api_key'] ?? '');
    
    if ($api_data && $submitted_key === $api_data['api_key'] && $api_data['is_active'] == 1) {
        $_SESSION['api_verified'] = true;
        header('Location: api_managment.php');
        exit;
    } else {
        $error_message = "Nieprawidłowy klucz API lub klucz jest nieaktywny.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weryfikacja API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .api-login-container {
            max-width: 500px;
            margin: 50px auto;
        }
        .api-key-input {
            font-family: monospace;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="api-login-container">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-key-fill"></i> Weryfikacja dostępu API
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error_message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="api_key" class="form-label">Klucz API</label>
                            <input type="text" 
                                   class="form-control api-key-input" 
                                   id="api_key" 
                                   name="api_key" 
                                   placeholder="Wprowadź swój klucz API"
                                   required>
                            <div class="form-text">
                                Wprowadź klucz API aby uzyskać dostęp do panelu zarządzania.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Weryfikuj
                            </button>
                            <button type="button" onclick="history.back()" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Powrót
                            </button>
                        </div>
                    </form>

                    <?php if (!$api_data): ?>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle"></i> Nie masz jeszcze klucza API. 
                            <a href="api_settings.php" class="alert-link">Wygeneruj swój pierwszy klucz</a>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Potrzebujesz pomocy? <a href="#">Sprawdź dokumentację API</a>
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-select API key on focus
        document.getElementById('api_key').addEventListener('focus', function() {
            this.select();
        });

        // Copy API key to clipboard
        function copyApiKey() {
            const apiKeyInput = document.getElementById('api_key');
            apiKeyInput.select();
            document.execCommand('copy');
        }
    </script>
</body>
</html>
