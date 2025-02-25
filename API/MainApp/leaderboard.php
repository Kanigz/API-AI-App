<?php

session_start();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <style>
        .table-responsive {
            max-width: 90%;
            margin: 2rem auto;
            padding: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: var(--bs-body-bg);
        }

        


    </style>
    <title>API Keys Dashboard</title>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
    <a class="navbar-brand" href="index.php">SuperAPI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="api_docs.php">API Docs</a>
                </li>
                <li class="nav-item">
                    <a href="../SnakeGame/snake.php" class="text-decoration-none">
                    <button class="btn btn-success ms-2 navbar-btn">
                        <i class="bi bi-play-fill"></i> Play Now
                    </button>
                    </a>
                </li>
                
                <?php if (isset($_SESSION['name'])) {?>
                    <li class="nav-item">
                    <a href="account.php" class="text-decoration-none">
                        <button class="btn btn-outline-light ms-2 navbar-btn login-btn">
                            <i class="bi bi-person-fill"></i> Witaj <?php echo $_SESSION['name'] ?>
                        </button>
                    </a>
                </li>
                <?php if ($_SESSION['name'] === 'admin') { ?>
                    <li class="nav-item">
                        <button class="btn btn-success ms-2 navbar-btn ai-btn">
                            <i class="bi bi-robot"></i> AI Assistant
                        </button>
                    </li>
                <?php } ?>
                
    
    <?php } else {?>
        <li class="nav-item">
    <a href="login.php" class="btn btn-outline-light ms-2 navbar-btn login-btn">
        <i class="bi bi-person-fill"></i> Login
    </a>
</li>
    <?php } ?>
                
            </ul>
            <!-- Theme Switcher -->
            <div class="theme-switcher d-flex align-items-center ms-3">
                <button id="lightModeBtn" title="Light Mode"><i class="bi bi-sun-fill"></i></button>
                <button id="darkModeBtn" title="Dark Mode"><i class="bi bi-moon-fill"></i></button>
                <button id="autoModeBtn" title="Auto Mode"><i class="bi bi-display"></i></button>
            </div>
        </div>
    </div>
</nav>

    <?php
    // Połączenie z bazą danych
    $db = new PDO("mysql:host=localhost;dbname=api", "root", "");

    // Zapytanie SQL
    $query = "SELECT id, user_id, api_key, usage_limit, used_count, is_active, created_at 
              FROM api_keys 
              ORDER BY id ASC";

    // Wykonanie zapytania
    $stmt = $db->prepare($query);
    $stmt->execute();

    // Pobranie wyników
    $api_keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>API Key</th>
                    <th>Usage Limit</th>
                    <th>Used Count</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($api_keys as $key): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($key['id']); ?></td>
                        <td><?php echo htmlspecialchars($key['user_id']); ?></td>
                        <td class="text-truncate" style="max-width: 200px;">
                            <?php echo htmlspecialchars($key['api_key']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($key['usage_limit']); ?></td>
                        <td><?php echo htmlspecialchars($key['used_count']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $key['is_active'] ? 'success' : 'danger'; ?>">
                                <?php echo $key['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($key['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>


        let ai_btn = document.querySelector('.ai-btn');

        if(ai_btn) {
            ai_btn.addEventListener('click', () => {
            console.log('Redirecting to AI page...');
            window.location.href = '../MainApp/ai.php';
        });

        }
        

        let play_btn = document.querySelector('.navbar-btn');
        play_btn.addEventListener('click', () => {
            console.log('Redirecting to game page...');
            window.location.href = '../SnakeGame/snake.php';
        });

        function setTheme(mode = 'auto') {
            const userMode = localStorage.getItem('bs-theme');
            const sysMode = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
            
            const useSystem = mode === 'auto' || (!userMode && mode === 'auto');
            const chosenTheme = useSystem ? sysMode : mode;

            if (mode === 'auto') {
                localStorage.removeItem('bs-theme');
            } else {
                localStorage.setItem('bs-theme', chosenTheme);
            }
            
            document.documentElement.setAttribute('data-bs-theme', chosenTheme);
        }

        document.getElementById('lightModeBtn').addEventListener('click', () => setTheme('light'));
        console.log('Redirecting to AI page...');
        
        document.getElementById('darkModeBtn').addEventListener('click', () => setTheme('dark'));
        document.getElementById('autoModeBtn').addEventListener('click', () => setTheme('auto'));

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('bs-theme') || 'auto';
            setTheme(savedTheme);
        });
    </script>
</body>
</html>
