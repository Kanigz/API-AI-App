<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit;
}

require_once './scripts/db_connect.php';
$conn = connectDB();
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Przykładowe dane prywatności (w rzeczywistej aplikacji pobierałbyś je z bazy danych)
$privacy_settings = [
    'data_sharing' => true,
    'analytics' => true,
    'marketing' => false,
    'third_party' => false,
    'account_visibility' => 'public'
];

$connected_apps = [
    ['name' => 'Google', 'connected_date' => '2024-01-15', 'status' => 'active'],
    ['name' => 'Facebook', 'connected_date' => '2024-02-01', 'status' => 'active'],
    ['name' => 'GitHub', 'connected_date' => '2024-01-20', 'status' => 'inactive']
];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ustawienia Prywatności</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .wrapper {
            min-height: 100vh;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            border-right: 1px solid #dee2e6;
        }

        #sidebar .nav-link {
            color: #333;
            padding: 0.8rem 1.5rem;
        }

        #sidebar .nav-link:hover {
            background-color: #e9ecef;
        }

        #sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }

        #sidebar .bi {
            margin-right: 0.5rem;
        }

        .privacy-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .connected-app {
            border-left: 4px solid #0d6efd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .connected-app.inactive {
            border-left-color: #6c757d;
        }

        @media (max-width: 768px) {
            #sidebar {
                min-width: 80px;
                max-width: 80px;
            }
            
            #sidebar .nav-link span {
                display: none;
            }
            
            #sidebar .bi {
                margin-right: 0;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-light">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="account.php">
                            <i class="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="api_settings.php">
                            <i class="bi bi-code-square"></i>
                            <span>API</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="privacy.php">
                            <i class="bi bi-lock"></i>
                            <span>Prywatność</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="content w-100">
            <div class="container py-4">
                <!-- Navigation buttons -->
                <div class="d-flex justify-content-between mb-4">
                    <button onclick="history.back()" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Powrót
                    </button>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Wyloguj
                    </a>
                </div>

                <h2 class="mb-4">Ustawienia Prywatności</h2>

                <!-- Privacy Settings Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Podstawowe ustawienia prywatności</h5>
                    </div>
                    <div class="card-body">
                        <form id="privacySettingsForm">
                            <div class="mb-3">
                                <label class="form-label">Widoczność konta</label>
                                <select class="form-select" name="account_visibility">
                                    <option value="public" <?= $privacy_settings['account_visibility'] == 'public' ? 'selected' : '' ?>>Publiczne</option>
                                    <option value="private" <?= $privacy_settings['account_visibility'] == 'private' ? 'selected' : '' ?>>Prywatne</option>
                                    <option value="friends" <?= $privacy_settings['account_visibility'] == 'friends' ? 'selected' : '' ?>>Tylko znajomi</option>
                                </select>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="dataSharing" 
                                       <?= $privacy_settings['data_sharing'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dataSharing">
                                    Udostępnianie danych analitycznych
                                </label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="analytics"
                                       <?= $privacy_settings['analytics'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="analytics">
                                    Zezwalaj na śledzenie aktywności
                                </label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="marketing"
                                       <?= $privacy_settings['marketing'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="marketing">
                                    Otrzymuj spersonalizowane reklamy
                                </label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="thirdParty"
                                       <?= $privacy_settings['third_party'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="thirdParty">
                                    Udostępniaj dane partnerom zewnętrznym
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">Zapisz ustawienia</button>
                        </form>
                    </div>
                </div>

                <!-- Connected Apps Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Połączone aplikacje</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($connected_apps as $app): ?>
                            <div class="connected-app <?= $app['status'] == 'inactive' ? 'inactive' : '' ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($app['name']) ?></h6>
                                        <small class="text-muted">Połączono: <?= htmlspecialchars($app['connected_date']) ?></small>
                                    </div>
                                    <div>
                                        <?php if ($app['status'] == 'active'): ?>
                                            <button class="btn btn-sm btn-danger" onclick="revokeAccess('<?= htmlspecialchars($app['name']) ?>')">
                                                Odłącz
                                            </button>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nieaktywne</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Data Export Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Eksport i usuwanie danych</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Eksportuj swoje dane</h6>
                            <p class="text-muted">Pobierz kopię wszystkich swoich danych w formacie ZIP</p>
                            <button class="btn btn-primary" onclick="exportData()">
                                <i class="bi bi-download"></i> Eksportuj dane
                            </button>
                        </div>
                        <div class="border-top pt-3">
                            <h6 class="text-danger">Usuń konto</h6>
                            <p class="text-muted">Ta operacja jest nieodwracalna i spowoduje usunięcie wszystkich Twoich danych</p>
                            <button class="btn btn-danger" onclick="deleteAccount()">
                                <i class="bi bi-trash"></i> Usuń konto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Potwierdź usunięcie konta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna.</p>
                    <div class="mb-3">
                        <label class="form-label">Wpisz "USUŃ" aby potwierdzić</label>
                        <input type="text" class="form-control" id="deleteConfirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        Usuń konto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handler
        document.getElementById('privacySettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Implementacja zapisywania ustawień
            alert('Ustawienia prywatności zostały zapisane!');
        });

        // Revoke app access
        function revokeAccess(appName) {
            if (confirm(`Czy na pewno chcesz odłączyć aplikację ${appName}?`)) {
                // Implementacja odłączania aplikacji
                alert(`Aplikacja ${appName} została odłączona!`);
            }
        }

        // Export data
        function exportData() {
            // Implementacja eksportu danych
            alert('Eksport danych rozpoczęty. Otrzymasz email z linkiem do pobrania.');
        }

        // Delete account
        function deleteAccount() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            deleteModal.show();
        }

        // Delete confirmation handling
        document.getElementById('deleteConfirmation').addEventListener('input', function(e) {
            document.getElementById('confirmDeleteBtn').disabled = e.target.value !== 'USUŃ';
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            // Implementacja usuwania konta
            alert('Konto zostało usunięte.');
            window.location.href = 'logout.php';
        });
    </script>
</body>
</html>
