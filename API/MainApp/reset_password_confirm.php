<?php
session_start();
require_once './scripts/db_connect.php';
$conn = connectDB();

if (!isset($_SESSION['reset_email'])) {
    header("Location: reset_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'];

    if ($password !== $confirm_password) {
        $error_message = "Hasła nie są identyczne";
    } else {
        $stmt = $conn->prepare("SELECT otp FROM user WHERE email = ? AND otp = ?");
        $stmt->bind_param("si", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Aktualizuj hasło
            $hashed_password = $password;
            $stmt = $conn->prepare("UPDATE user SET password = ?, otp = NULL WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();

            unset($_SESSION['reset_email']);
            header("Location: login.php?reset=success");
            exit();
        } else {
            $error_message = "Nieprawidłowy kod weryfikacyjny";
        }
    }
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
    <!-- Ten sam navbar co w poprzednim pliku -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Snake Game</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">API Docs</a>
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
                            <h3 class="mb-5">Reset Password</h3>

                            <form action="" method="post">
                                <div class="form-outline mb-4">
                                    <input type="text" id="code" name="code" class="form-control form-control-lg" required />
                                    <label class="form-label" for="code">Verification Code</label>
                                </div>
                                

                                <div class="form-outline mb-4">
                                    <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                                    <label class="form-label" for="password">New Password</label>
                                </div>

                                <div class="form-outline mb-4">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-lg" required />
                                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                                </div>

                                <button type="submit" name="verify" onmouseover="this.style.backgroundColor='#002ead';" onmouseout="this.style.backgroundColor=''; this.style.transition='0.7s';" this.style.transition='0.7s';"  style="" class="btn btn-primary reset-btn rounded-pill w-100">Reset Password</button>
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
    <!-- Te same skrypty co w poprzednim pliku -->
</body>
</html>
