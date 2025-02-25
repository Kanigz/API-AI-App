

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
                            <h3 class="mb-5">Enter your email to reset password</h3>

                            <form action="" method="post">
                                <div class="form-outline mb-4">
                                    <input type="email" id="mail" name="mail" class="form-control form-control-lg" required />
                                    <label class="form-label" for="mail">Email</label>
                                </div>

                                <button type="submit" name="verify" onmouseover="this.style.backgroundColor='#002ead';" onmouseout="this.style.backgroundColor=''; this.style.transition='0.7s';" this.style.transition='0.7s';"  style="" class="btn btn-primary reset-btn rounded-pill w-100">Reset</button>
                            </form>

                            <?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once './scripts/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['mail'];
    
    // Sprawdź czy email istnieje w bazie
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generuj 6-cyfrowy kod
        $reset_code = rand(100000, 999999);
        
        // Zapisz kod w bazie
        $stmt = $conn->prepare("UPDATE user SET otp = ? WHERE email = ?");
        $stmt->bind_param("is", $reset_code, $email);
        $stmt->execute();

        // Wyślij email z kodem
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'skszypo@gmail.com';
                    $mail->Password = 'dyxb tbpp npff xhxo';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
            $mail->setFrom('twoj_email@gmail.com', 'SuperAPI');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body = "Your password reset code: <b>$reset_code</b>";

            $mail->send();
            
            $_SESSION['reset_email'] = $email;
            header("Location: reset_password_confirm.php");
            exit();
        } catch (Exception $e) {
            $error_message = "Błąd wysyłania maila: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "Podany email nie istnieje w bazie.";
    }
}
?>

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
