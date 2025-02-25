<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: http://localhost/API/MainApp/login.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Snake Game</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        canvas {
            border: 3px solid #343a40;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .game-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 20px auto;
        }
        
        .score-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .score-item {
            background-color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .game-title {
            color: #343a40;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .current-score {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
        }

        .settings {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .input-group-text {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        [data-bs-theme="dark"] {
    /* Ogólne style dla trybu ciemnego */
    body {
        background-color: #212529;
        color: #f8f9fa;
    }

    /* Kontener gry */
    .game-container {
        background-color: #343a40;
        box-shadow: 0 0 30px rgba(0,0,0,0.3);
    }

    /* Canvas */
    canvas {
        border-color: #6c757d;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    /* Karta wyników */
    .score-card {
        background-color: #2c3136;
    }

    .score-item {
        background-color: #343a40;
        color: #f8f9fa;
    }

    /* Ustawienia */
    .settings {
        background-color: #2c3136;
    }

    /* Inputy i selecty */
    .form-control, .form-select {
        background-color: #495057;
        border-color: #6c757d;
        color: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        background-color: #495057;
        border-color: #0d6efd;
        color: #f8f9fa;
    }

    /* Tytuł gry */
    .game-title {
        color: #f8f9fa;
    }

    /* Tekst pomocniczy */
    .text-muted {
        color: #adb5bd !important;
    }
}

[data-bs-theme="dark"] {
    /* Style dla modala */
    .modal-content {
        background-color: #212529;
        border-color: #495057;
    }

    .modal-header {
        border-bottom-color: #495057;
    }

    .modal-footer {
        border-top-color: #495057;
    }

    .modal-title {
        color: #f8f9fa;
    }

    /* Style dla inputów w modalu */
    .modal .input-group-text {
        background-color: #495057;
        border-color: #6c757d;
        color: #f8f9fa;
    }

    .modal .form-control {
        background-color: #343a40;
        border-color: #6c757d;
        color: #f8f9fa;
    }

    .modal .form-control:focus {
        background-color: #343a40;
        border-color: #0d6efd;
        color: #f8f9fa;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
}

        /* Hero Section and General Elements */

    </style>
</head>
<body >
    <a href="http://localhost/API/MainApp/index.php" class="btn btn-primary back-button">
        <i class="fas fa-arrow-left"></i> Back to Home
        
    </a>
    

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="game-container text-center">
                    <h1 class="game-title">
                        <i class="fas fa-snake"></i> Snake Game
                    </h1>

                    <div class="settings">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="playerNameInput" class="form-control" value="Player" placeholder="Your name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </span>
                                    <select id="speedSelect" class="form-select">
                                        <option value="150">Slow</option>
                                        <option value="100" selected>Normal</option>
                                        <option value="70">Fast</option>
                                        <option value="50">Very Fast</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="current-score mb-3" id="currentScore">Score: 0</div>
                    
                    <div class="mb-4">
                        <canvas id="gameCanvas" width="400" height="400"></canvas>
                    </div>

                    
                    <div class="controls mb-4">
                        <p class="text-muted">
                            <i class="fas fa-keyboard"></i> Use arrow keys to control the snake <br>
                            <i class="fas fa-redo"></i> Press R or Enter to restart the game
                        </p>
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
                    
                    <div class="card score-card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">
                                <i class="fas fa-trophy"></i> High Scores
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="scoresList" class="score-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="apiKeyModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter your API key</h5>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-key"></i>
                    </span>
                    <input type="text" id="apiKeyInput" class="form-control" placeholder="Enter API key">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="playWithoutApi">Play without API</button>
                <button type="button" class="btn btn-primary" id="saveApiKey">Save</button>
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap 5 JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        let API_KEY = '';
const modal = new bootstrap.Modal(document.getElementById('apiKeyModal'));

// Pokaż modal przy starcie
document.addEventListener('DOMContentLoaded', function() {
    modal.show();
});

// Obsługa przycisku zapisu
document.getElementById('saveApiKey').addEventListener('click', function() {
    const apiKeyInput = document.getElementById('apiKeyInput');
    API_KEY = apiKeyInput.value;
    if (API_KEY) {
        modal.hide();
        getHighScores(); // Rozpocznij grę po wprowadzeniu klucza
    } else {
        alert('Please, enter your API key.');
    }
});

// Obsługa przycisku gry bez API
document.getElementById('playWithoutApi').addEventListener('click', function() {
    API_KEY = '';
    modal.hide();
    // Ukryj sekcję z wynikami, ponieważ bez API nie będzie można ich pobrać
    document.querySelector('.score-card').style.display = 'none';
});
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const API_URL = 'http://localhost/API/MainApp/api_endpoint.php';
        
        
        let gameSpeed = 100;
        let playerName = "Gracz";
        const gridSize = 20;
        const tileCount = canvas.width / gridSize;
        let snake = [{ x: 10, y: 10 }];
        let food = { x: 15, y: 15 };
        let dx = 0;
        let dy = 0;
        let score = 0;
        let gameOver = false;

        document.getElementById('speedSelect').addEventListener('change', function(e) {
            gameSpeed = parseInt(e.target.value);
        });

        document.getElementById('playerNameInput').addEventListener('input', function(e) {
            playerName = e.target.value || "Gracz";
        });
        
        document.addEventListener('keydown', function(event) {
            switch(event.keyCode) {
                case 37: if (dx === 0) { dx = -1; dy = 0; } break;
                case 38: if (dy === 0) { dx = 0; dy = -1; } break;
                case 39: if (dx === 0) { dx = 1; dy = 0; } break;
                case 40: if (dy === 0) { dx = 0; dy = 1; } break;
            }
        });

        function placeFood() {
            food.x = Math.floor(Math.random() * tileCount);
            food.y = Math.floor(Math.random() * tileCount);
        }

        function draw() {
            ctx.fillStyle = 'black';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.fillStyle = 'green';
            snake.forEach(segment => {
                ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
            });
            
            ctx.fillStyle = 'red';
            ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize - 2, gridSize - 2);
        }



        function update() {
            if (gameOver) return;

            const head = { x: snake[0].x + dx, y: snake[0].y + dy };
            snake.unshift(head);

            if (head.x === food.x && head.y === food.y) {
                score += 10;
                document.getElementById('currentScore').textContent = 'Score: ' + score;
                placeFood();
            } else {
                snake.pop();
            }

            if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) {
                gameOver = true;
            }

            for (let i = 1; i < snake.length; i++) {
                if (head.x === snake[i].x && head.y === snake[i].y) {
                    gameOver = true;
                    break;
                }
            }

            if (gameOver) {
                const inputName = document.getElementById('playerNameInput').value;
                const finalPlayerName = inputName || "Gracz";
                sendScore(finalPlayerName, score);
            }
        }

        async function sendScore(playerName, score) {
            if (!API_KEY) {
                modal.show();
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
                        game: 'snake'
                    })
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    getHighScores();
                }
            } catch (error) {
                console.error('Error sending score:', error);
            }
        }

        async function getHighScores() {
            if (!API_KEY) {
                modal.show();
                return;
            }
            try {
                const response = await fetch(API_URL, {
                    method: 'GET',
                    headers: {
                        'X-API-Key': API_KEY
                    }
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    displayHighScores(data.data);
                }
            } catch (error) {
                console.error('Error getting high scores:', error);
            }
        }

        function displayHighScores(scores) {
            const scoresList = document.getElementById('scoresList');
            scoresList.innerHTML = scores
                .map((score, index) => `
                    <div class="score-item d-flex justify-content-between align-items-center">
                        <span class="position">
                            ${index + 1}. 
                            ${index < 3 ? '<i class="fas fa-crown text-warning"></i>' : ''}
                        </span>
                        <span class="player-name">${score.player_name}</span>
                        <span class="badge bg-primary">${score.score} pts</span>
                    </div>
                `).join('');
        }

        function gameLoop() {
            update();
            draw();
            if (!gameOver) {
                setTimeout(gameLoop, gameSpeed);
            }
        }

        placeFood();
        getHighScores();
        gameLoop();


        function resetGame() {
        // Resetowanie zmiennych gry
        snake = [{ x: 10, y: 10 }];
        food = { x: 15, y: 15 };
        dx = 0;
        dy = 0;
        score = 0;
        gameOver = false;
        
        // Aktualizacja wyświetlanego wyniku
        document.getElementById('currentScore').textContent = 'Score: 0';
        
        // Umieszczenie jedzenia w nowym miejscu
        placeFood();
        
        // Restart pętli gry
     
     
        gameLoop();
    }

    document.addEventListener('keydown', function(event) {
    // Istniejąca obsługa klawiszy strzałek
    switch(event.keyCode) {
        case 37: if (dx === 0) { dx = -1; dy = 0; } break;
        case 38: if (dy === 0) { dx = 0; dy = -1; } break;
        case 39: if (dx === 0) { dx = 1; dy = 0; } break;
        case 40: if (dy === 0) { dx = 0; dy = 1; } break;
        // Dodaj obsługę klawisza R (kod 82) lub Enter (kod 13)
        case 82: // klawisz R
        case 13: // klawisz Enter
            if (gameOver) {
                resetGame();
            }
            break;
    }
});





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
</body>
</html>
