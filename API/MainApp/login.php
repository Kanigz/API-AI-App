<?php
session_start();

require_once './scripts/db_connect.php';

// Sprawdzanie logowania
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectDB();
    
    if ($conn->connect_error) {
        $error_message = "Błąd połączenia z bazą danych: " . $conn->connect_error;
    } else {
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if ($password == $row['password']) { // W przyszłości użyj password_verify()
                $_SESSION["name"] = htmlspecialchars($row['name']);
                $_SESSION["mail"] = htmlspecialchars($row['email']);
                $_SESSION["id"] = htmlspecialchars($row['id']);
                $_SESSION["password"] = htmlspecialchars($row['password']);
                header('Location: index.php');
                exit();
            } else {
                $error_message = 'Błędny login lub hasło';
            }
        } else {
            $error_message = 'Błędny login lub hasło';
        }
        $stmt->close();
    }
    $conn->close();
}
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">API Docs</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-success ms-2 navbar-btn">
                            <i class="bi bi-play-fill"></i> Play Now
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-outline-light ms-2 navbar-btn">
                            <i class="bi bi-person-fill"></i> Login
                        </button>
                    </li>
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

    <section class="login-area vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-lg">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-5">Sign in to Snake Game</h3>

                            <form action="" method="post">
                                <div class="form-outline mb-4">
                                    <input type="email" id="mail" name="mail" class="form-control form-control-lg" required />
                                    <label class="form-label" for="mail">Email</label>
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                                    <label class="form-label" for="password">Password</label>
                                </div>

                                <div class="d-flex justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" />
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <a href="reset.php" class="text-decoration-none">Forgot password?</a>
                                </div>

                                <button type="submit" name="verify" onmouseover="this.style.backgroundColor='#002ead';" onmouseout="this.style.backgroundColor=''; this.style.transition='0.7s';" this.style.transition='0.7s';"  style="" class="btn btn-primary reset-btn rounded-pill w-100">Login</button> <br>
                                <br>

                                <div class="text-center">
                                    <p class="mb-0">Don't have an account? <a href="register.php" class="text-decoration-none">Sign up</a></p>
                                </div>
                            </form>

                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Switcher Script -->
    <script>
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
    </script>
</body>
</html>
