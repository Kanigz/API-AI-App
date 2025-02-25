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
    <title>Snake Game</title>
    <link rel="stylesheet" href="./css/main.css">
    <title>Document</title>
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


<div class="container mt-5">
        <h1>Snake Game API Documentation</h1>

        <section class="mt-4">
            <h2>Authentication</h2>
            <p>To access the Snake Game API, you need to authenticate using an API key. Include this key in the header of each request.</p>
            
            <div class="code-block">
                Authorization: Bearer YOUR_API_KEY
            </div>
        </section>

        <section class="mt-4">
            <h2>Generating an API Key</h2>
            <ol>
                <li>Log in to your SuperAPI account</li>
                <li>Navigate to Account Settings</li>
                <li>Click on "Generate API Key"</li>
            </ol>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Never share your API key publicly or commit it to version control.
            </div>
        </section>

    

            

        <section class="mt-4">
            <h2>Response Codes</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>200</td>
                        <td>Success</td>
                    </tr>
                    <tr>
                        <td>401</td>
                        <td>Invalid API key</td>
                    </tr>
                    <tr>
                        <td>403</td>
                        <td>Rate limit exceeded</td>
                    </tr>
                    <tr>
                        <td>404</td>
                        <td>Resource not found</td>
                    </tr>
                    <tr>
                        <td>500</td>
                        <td>Server error</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="mt-4">
            <h2>Example Usage</h2>
            <pre class="code-block">

const API_URL = 'http://server_name/API/MainApp/api_endpoint.php';
const API_KEY = 'Your api key';

async function sendScore(playerName, score) {
    if (!API_KEY) {
        console.error('Brak klucza API');
        return;
    }

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-API-Key': API_KEY
            },
            body: JSON.stringify({
                player: playerName,
                score: score,
                game: 'classic'
            })
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            console.log('Score sent:', data);
        } else {
            console.error('API Error:', data);
        }
    } catch (error) {
        console.error('Error sending score:', error);
    }
}


sendScore('TestPlayer', 12);

console.log('Script loaded');
        }</pre>
        </section>

        <section class="mt-4 mb-5">
            <h2>Security Recommendations</h2>
            <ul class="list-group">
                <li class="list-group-item">Store API keys in secure environment variables</li>
                <li class="list-group-item">Rotate API keys periodically</li>
                <li class="list-group-item">Use HTTPS for all API requests</li>
                <li class="list-group-item">Implement request signing for sensitive operations</li>
            </ul>
        </section>
    </div>
</body>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Switcher Script -->
    <script>
let play_btn = document.querySelector('.navbar-btn');
        play_btn.addEventListener('click', () => {
            console.log('Redirecting to game page...');
            window.location.href = '../SnakeGame/snake.php';
        });
        let ai_btn = document.querySelector('.ai-btn');

if(ai_btn) {
    ai_btn.addEventListener('click', () => {
    console.log('Redirecting to AI page...');
    window.location.href = '../MainApp/ai.php';
});
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

        
    </script>
</html>