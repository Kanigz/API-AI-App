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

// Pobieranie informacji o API dla zalogowanego użytkownika
$user_id = $_SESSION['id'];

// Przygotowanie zapytania
$api_query = "SELECT * FROM api_keys WHERE user_id = ?";
$stmt = $conn->prepare($api_query);
if ($stmt === false) {
    die('Błąd przygotowania zapytania: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die('Błąd wykonania zapytania: ' . $stmt->error);
}

$result = $stmt->get_result();
$api_data = $result->fetch_assoc();

// Wartości domyślne jeśli nie znaleziono danych
if (!$api_data) {
    $api_data = [
        'api_key' => 'Brak klucza API',
        'usage_limit' => 1000,
        'used_count' => 0,
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s')
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie API</title>
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
        .api-key-display {
            font-family: monospace;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            word-break: break-all;
        }
        @media (max-width: 768px) {
            #sidebar {
                min-width: 80px;
                max-width: 80px;
            }
            #sidebar .nav-link span {
                display: none;
            }
        }
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


        [data-bs-theme="light"] {
    body {
        background-color: #f8f9fa;
        color: #212529;
    }

    .wrapper {
        background-color: #ffffff;
    }

    #sidebar {
        background-color: #f8f9fa;
        border-right-color: #dee2e6;
    }

    #sidebar .nav-link {
        color: #333;
    }

    #sidebar .nav-link:hover {
        background-color: #e9ecef;
    }

    .card {
        background-color: #ffffff;
        border-color: #dee2e6;
    }

    .form-control {
        background-color: #ffffff;
        border-color: #ced4da;
        color: #212529;
    }
}

/* Styl dla ciemnego motywu */
[data-bs-theme="dark"] {
    
    body {
        background-color: #212529;
        color: #f8f9fa;
    }

    table td {
        background-color: #2c3034;
        color: #f8f9fa;
        border-color: #495057;
    }

    

    .wrapper {
        background-color: #343a40;
    }

    #sidebar {
        background-color: #2c3034;
        border-right-color: #495057;
    }

    #sidebar .nav-link {
        color: #e9ecef;
    }

    #sidebar .nav-link:hover {
        background-color: #495057;
    }

    #sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #ffffff;
    }

    .card {
        background-color: #2c3034;
        border-color: #495057;
    }

    .form-control {
        background-color: #343a40;
        border-color: #495057;
        color: #f8f9fa;
    }

    .form-control:focus {
        background-color: #343a40;
        border-color: #0d6efd;
        color: #f8f9fa;
    }

    .btn-light {
        background-color: #495057;
        border-color: #495057;
        color: #f8f9fa;
    }

    .nav-tabs {
        border-color: #495057;
    }

    .nav-tabs .nav-link {
        color: #f8f9fa;
    }

    .nav-tabs .nav-link.active {
        background-color: #343a40;
        border-color: #495057;
        color: #f8f9fa;
    }

    .table {
        color: #f8f9fa;
    }

    .text-muted {
        color: #adb5bd !important;
    }

    
}

[data-bs-theme="light"] {
    .api-key-display {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #212529;
    }

    .progress {
        background-color: #e9ecef;
    }

    .progress-bar {
        background-color: #0d6efd;
    }
}

/* Dark theme styling */
[data-bs-theme="dark"] {
    .api-key-display {
        background-color: #2c3034;
        border: 1px solid #495057;
        color: #f8f9fa;
    }

    .progress {
        background-color: #495057;
    }

    .progress-bar {
        background-color: #0d6efd;
    }
}

/* Common styles for both themes */
.progress {
    height: 20px;
    border-radius: 10em;
    transition: all 0.3s ease[2];
}

.progress-bar {
    border-radius: 10em;
    transition: width 300ms ease[4];
}

.api-key-display {
    padding: 1rem;
    border-radius: 4px;
    font-family: monospace;
    word-break: break-all;
    transition: all 0.3s ease;
}

[data-bs-theme="light"] {
    /* Style dla tabeli w jasnym motywie */
    table tr {
        background-color: #ffffff;
        border-bottom: 1px solid #dee2e6;
    }

    table tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    table tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .tr_color {
        color: #212529;
        padding: 0.75rem;
        border-color: #dee2e6;
    }
}

/* Styl dla ciemnego motywu */
[data-bs-theme="dark"] {
    /* Style dla tabeli w ciemnym motywie */
    table tr {
        background-color: #2c3034;
        border-bottom: 1px solid #495057;
    }

    table tr:hover {
        background-color: #495057;
    }

    table tr:nth-child(even) {
        background-color: #343a40;
    }

    .tr_color {
        background-color: #2c3034;
        color: #f8f9fa;
        padding: 0.75rem;
        border-color: #495057;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
    --bs-table-accent-bg: var(--bs-table-striped-bg);
    color: white;
}
}
    </style>
</head>
<body>
    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="account.php">
                            <i class="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="api_settings.php">
                            <i class="bi bi-code-square"></i>
                            <span>API</span>
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
                    <button onclick="window.location.href='index.php'" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
                <div class="theme-controls mb-3">

                <button id="lightModeBtn" class="btn btn-light">
                    <i class="bi bi-sun"></i> Light
                </button>
                <button id="darkModeBtn" class="btn btn-dark">
                    <i class="bi bi-moon"></i> Dark
                </button>
                <button id="autoModeBtn" class="btn btn-secondary">
                    <i class="bi bi-display"></i> Auto
                </button>
            </div>

                <h2 class="mb-4">API management</h2>

                <!-- API Key Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">API Key</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-8">
                        <div class="api-key-display mb-3" id="apiKeyDisplay">
                        <?php if($api_data['api_key'] !== 'API key missing'): ?>
                            <button class="btn btn-outline-secondary" id="copyButton" onclick="copyApiKey()">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        <?php endif; ?>
                        <?= htmlspecialchars($api_data['api_key']);
                        $_SESSION['api'] = $api_data['api_key'];
                        ?>
                    </div>
                        
                    </div>
                            <div class="col-md-4">
                                <p>Status: 
                                    <span class="badge <?= $api_data['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $api_data['is_active'] ? 'Active' : '
Inactive' ?>
                                    </span>
                                </p>
                                <p>Created at: <?= $api_data['created_at'] ?></p>
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="regenerateApiKey()">
                            <i class="bi bi-key"></i> Generate new key
                        </button>
                    </div>
                </div>

                <!-- Usage Statistics -->
                            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Use statistic</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3">
                        <div class="progress-bar <?= ($api_data['used_count'] >= $api_data['usage_limit']) ? 'bg-danger' : '' ?>" 
                            role="progressbar" 
                            style="width: <?= (($api_data['used_count'])/$api_data['usage_limit'])*100 ?>%">
                        </div>
                    </div>
                    <p><?php echo $api_data['used_count']?> out of <?= $api_data['usage_limit'] ?> queries were used</p>
                    
                    <?php if($api_data['used_count'] >= $api_data['usage_limit']): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Limit exceeded!</strong> You have used up your available API request limit. 
                            Increase your limit or wait for your limit to renew.
                        </div>
                    <?php elseif($api_data['used_count'] >= $api_data['usage_limit'] * 0.7): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Attention!</strong> You are approaching your API request limit.
                        </div>
                    <?php endif; ?>
                    
                    <button class="btn btn-outline-primary" onclick="increaseLimit()">
                        <i class="bi bi-plus-circle"></i> 
                        Increase the limit
                    </button>
                </div>
            </div>

            

                <!-- API Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">API Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="apiSettingsForm" method="POST" action="update_api_settings.php">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">
                                Query limit</label>
                                <input type="number" class="form-control" name="usage_limit" 
                                       value="<?= $api_data['usage_limit'] ?>">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" 
                                       id="apiStatus" <?= $api_data['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="apiStatus">
                                    API active
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>





                <?php
require_once './scripts/db_connect.php';
$conn = connectDB();

$api_key = $_SESSION['api'];

$sql = "SELECT player_name, score, game_name, created_at 
FROM game_scores 
WHERE api_key = '$api_key'
ORDER BY score DESC 
LIMIT 10";

$result = $conn->query($sql);
?>
<div class="card">
    <div class="card-header">
        <h5>API query results</h5>
    </div>
    <div class="card-body">
        <?php if($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Place</th>
                        <th>Player</th>
                        <th>Score</th>
                        <th>Game</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $place = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='tr_color'>" . $place . "</td>";
                    echo "<td class='tr_color'>" . htmlspecialchars($row['player_name']) . "</td>";
                    echo "<td class='tr_color'>" . htmlspecialchars($row['score']) . "</td>";
                    echo "<td class='tr_color'>" . htmlspecialchars($row['game_name']) . "</td>";
                    echo "<td class='tr_color'>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                    $place++;
                }
                ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>Brak danych lub nieprawidłowy klucz API
            </div>
        <?php endif; ?>
    </div>
</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function regenerateApiKey() {
            if (confirm('Czy na pewno chcesz wygenerować nowy klucz API? Stary klucz przestanie działać.')) {
                fetch('regenerate_api_key.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ user_id: <?= $user_id ?> })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Wystąpił błąd podczas generowania klucza');
                    }
                });
            }
        }

        function increaseLimit() {
            const newLimit = prompt('Podaj nowy limit zapytań:', <?= $api_data['usage_limit'] ?>);
            if (newLimit && !isNaN(newLimit)) {
                document.querySelector('input[name="usage_limit"]').value = newLimit;
                document.getElementById('apiSettingsForm').submit();
            }
        }


        function setTheme(mode = 'auto') {
            const userMode = localStorage.getItem('bs-theme');
            const sysMode = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            
            const useSystem = mode === 'auto' || (!userMode && mode === 'auto');
            const chosenTheme = useSystem ? sysMode : mode;

            // Update localStorage and data attribute
            if (mode === 'auto') {
                localStorage.removeItem('bs-theme');
            } else {
                localStorage.setItem('bs-theme', chosenTheme);
            }
            
            document.documentElement.setAttribute('data-bs-theme', chosenTheme);
        }

        // Event listeners for theme buttons
        document.getElementById('lightModeBtn').addEventListener('click', () => setTheme('light'));
        document.getElementById('darkModeBtn').addEventListener('click', () => setTheme('dark'));
        document.getElementById('autoModeBtn').addEventListener('click', () => setTheme('auto'));

        // Apply saved or default theme on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('bs-theme') || 'auto';
            setTheme(savedTheme);

        
        });


        function copyApiKey() {
    const apiKey = document.getElementById('apiKeyDisplay').innerText;
    
    // Tworzenie tymczasowego elementu textarea
    const textarea = document.createElement('textarea');
    textarea.value = apiKey;
    document.body.appendChild(textarea);
    
    // Zaznaczenie i kopiowanie tekstu
    textarea.select();
    textarea.setSelectionRange(0, 99999); // Dla urządzeń mobilnych
    
    // Kopiowanie tekstu do schowka
    document.execCommand('copy');
    
    // Usunięcie tymczasowego elementu
    document.body.removeChild(textarea);
    
    // Opcjonalnie: pokaż komunikat o skopiowaniu

}

    </script>
</body>
</html>
