
<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Snake Game</title>
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">SuperAPI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="api_docs.php">API Docs</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-success ms-2 navbar-btn">
                        <i class="bi bi-play-fill"></i> Play Now
                    </button>
                </li>
                
                <?php if (isset($_SESSION['name'])) {?>
                    <li class="nav-item">
                    <a href="account.php" class="text-decoration-none">
                        <button class="btn btn-outline-light ms-2 navbar-btn login-btn">
                            <i class="bi bi-person-fill"></i> Hello <?php echo $_SESSION['name'] ?>
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
<section class="hero-section">
    <div class="container text-center py-5">
        <h1 class="display-4 mb-3">Build your API skills</h1>
        <p class="lead mb-4">Practice and refine your API skills with guided examples.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="../SnakeGame/snake.php"><button class="btn btn-primary btn-lg">Play Now</button></a>
            <a href="leaderboard.php"><button class="btn btn-outline-secondary btn-lg">View Leaderboard</button></a>
            
        </div>
    </div>
</section>



    <!-- Bootstrap JS -->
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
</body>
</html>
