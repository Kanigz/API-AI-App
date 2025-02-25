<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <title>Snake Game API Documentation</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Snake Game</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="api_docs.php">API Docs</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-success ms-2 navbar-btn">
                            <i class="bi bi-play-fill"></i> Play Now
                        </button>
                    </li>
                    <?php if (isset($_SESSION['name'])) { ?>
                        <li class="nav-item">
                            <a href="account.php" class="text-decoration-none">
                                <button class="btn btn-outline-light ms-2 navbar-btn login-btn">
                                    <i class="bi bi-person-fill"></i> Witaj <?php echo $_SESSION['name'] ?>
                                </button>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-outline-light ms-2 navbar-btn login-btn">
                                <i class="bi bi-person-fill"></i> Login
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="theme-switcher d-flex align-items-center ms-3">
                    <button id="lightModeBtn" title="Light Mode"><i class="bi bi-sun-fill"></i></button>
                    <button id="darkModeBtn" title="Dark Mode"><i class="bi bi-moon-fill"></i></button>
                    <button id="autoModeBtn" title="Auto Mode"><i class="bi bi-display"></i></button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Tu wstaw zawartość dokumentacji API -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme switcher script
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
        document.getElementById('darkModeBtn').addEventListener('click', () => setTheme('dark'));
        document.getElementById('autoModeBtn').addEventListener('click', () => setTheme('auto'));

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('bs-theme') || 'auto';
            setTheme(savedTheme);
        });

        let play_btn = document.querySelector('.navbar-btn');
        play_btn.addEventListener('click', () => {
            window.location.href = '../SnakeGame/snake.php';
        });
    </script>
</body>
</html>
