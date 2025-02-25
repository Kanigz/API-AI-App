<?php
require_once './scripts/db_connect.php';

$conn = connectDB();

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if (isset($_POST['verify'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT otp FROM user WHERE email = '$email' AND is_active = 0");
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['otp'] === $otp) {
        $updateStmt = $conn->prepare("UPDATE user SET is_active = 1, otp = NULL WHERE email = '$email'");
        $updateStmt->execute();

        echo "<script>alert('Konto zostało pomyślnie aktywowane!');</script>";
        header("Location: login.php");
        exit();
    } else {
        echo "<script>alert('Nieprawidłowy kod OTP. Spróbuj ponownie.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Weryfikacja OTP</title>
    <link rel="stylesheet" href="./css/main.css">
    <style>
        .otp-field {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }

        .otp-field input {
            width: 40px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        
        }

        .otp-field input:focus {
            outline: none;
            border-color: #4CAF50;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .reset-btn:hover {
            --bs-btn-hover-color: #0d6efd;
}

    </style>
</head>
<body>
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
                <div class="theme-switcher d-flex align-items-center ms-3">
                    <button id="lightModeBtn" title="Light Mode"><i class="bi bi-sun-fill"></i></button>
                    <button id="darkModeBtn" title="Dark Mode"><i class="bi bi-moon-fill"></i></button>
                    <button id="autoModeBtn" title="Auto Mode"><i class="bi bi-display"></i></button>
                </div>
            </div>
        </div>
    </nav>
    <div class="register-area">
        <div class="wrapper">

            <form action="" method="POST">
                <h1>Enter Code from mail</h1>

                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">

                <div class="input-box otp-field">
                    <input type="text" maxlength="1" class="otp-input" />
                    <input type="text" maxlength="1" class="otp-input" />
                    <input type="text" maxlength="1" class="otp-input" />
                    <input type="text" maxlength="1" class="otp-input" />
                    <input type="text" maxlength="1" class="otp-input" />
                    <input type="hidden" name="otp" id="otp-hidden" />
                </div>

                <button type="submit" name="verify" onmouseover="this.style.backgroundColor='#002ead';" onmouseout="this.style.backgroundColor=''; this.style.transition='0.7s';" this.style.transition='0.7s';"  style="" class="btn btn-primary reset-btn rounded-pill w-100">Verify</button>
            </form>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- OTP Input Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp-hidden');

            inputs.forEach((input, index) => {
                // Auto-focus next input
                input.addEventListener('input', function() {
                    if (this.value.length === 1) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }
                    updateHiddenInput();
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value) {
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });
            });

            // Update hidden input with complete OTP
            function updateHiddenInput() {
                const otp = Array.from(inputs).map(input => input.value).join('');
                hiddenInput.value = otp;
            }
        });
    </script>

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
