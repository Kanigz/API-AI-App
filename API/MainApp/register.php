<!DOCTYPE html>
<html lang="en">

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once './scripts/db_connect.php';

$conn = connectDB();

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$otp_str = str_shuffle("0123456789");
$otp = substr($otp_str, 0, 5);

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $checkUserQuery = $conn->prepare("SELECT * FROM user WHERE email = '$email'");

        if ($checkUserQuery === false) {
            die('Błąd przygotowania zapytania: ' . $conn->error);
        }
        $checkUserQuery->execute();
        $result = $checkUserQuery->get_result();

        if ($result->num_rows > 0) {
            echo "Ten mail został już wykorzystany";
           
        } else {

            $stmt = $conn->prepare("INSERT INTO user (name, email, password, otp, is_active) VALUES ('$username', '$email', '$password', '$otp', 0)");

            if ($stmt->execute()) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'skszypo@gmail.com';
                    $mail->Password = 'dyxb tbpp npff xhxo';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('twojemail@gmail.com', 'FastAPI');
                    $mail->addAddress($email, $username);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your verify code';
                    $mail->Body = "Hello $username,<br><br>This is your verify code: <b>$otp</b><br>Use this code to activate account.<br><br>Thanks!";
                    
                    $mail->send();
                    echo "<script>alert('Rejestracja udana! Sprawdź swój email, aby odebrać kod OTP.');</script>";
                    header("Location: verify.php?email=" . urlencode($email));
                    exit();
                } catch (Exception $e) {
                    echo "<script>alert('Rejestracja udana, ale nie udało się wysłać wiadomości email z OTP. Błąd: {$mail->ErrorInfo}');</script>";
                }
            } else {
                echo "<script>alert('Rejestracja nieudana. Spróbuj ponownie.');</script>";
            }
        }

        $stmt->close();
        $checkUserQuery->close();
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script
  src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback"
  defer
></script>


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
                <button class="btn btn-outline-light ms-2 navbar-btn login-btn">
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
    <div class="register-area">
        <div class="wrapper">
            <form action="" method="POST">
                <h1>Register</h1>

                <input type="hidden" name="otp" value="<?php echo $otp; ?>">
                <input type="hidden" name="activation_code" value="<?php echo $activation_code; ?>">


                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="confirm-password" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock' ></i>
                </div>

                <div id="password-strength" class="strength-bar"></div>
                <div id="password-feedback">Siła twojego hasła</div>
                <br>

                

                <button type="submit" onmouseover="this.style.backgroundColor='#002ead';" onmouseout="this.style.backgroundColor=''; this.style.transition='0.7s'; this.style.transition='0.7s';" name="register" class="btn">Register</button>

                <div class="login-link">
                    <p>Already have an account?<a href="login.php"> Login</a></p>
                </div>
            </form>
        </div>
    </div>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
window.onloadTurnstileCallback = function () {
  turnstile.render("#myWidget", {
    sitekey: "0x4AAAAAAA0DGidnzBcurj93",
    callback: function (token) {
      console.log(`Challenge Success ${token}`);
      
    },
  });
};


// Funkcja oceniająca siłę hasła
function evaluatePasswordStrength(password) {
  let strength = 0;

  // Warunki sprawdzające długość hasła
  if (password.length >= 8) {
    strength += 1;
  }
  if (password.length >= 12) {
    strength += 1;
  }

  // Sprawdzenie obecności małych liter
  if (/[a-z]/.test(password)) {
    strength += 1;
  }
  
  // Sprawdzenie obecności wielkich liter
  if (/[A-Z]/.test(password)) {
    strength += 1;
  }

  // Sprawdzenie obecności cyfr
  if (/\d/.test(password)) {
    strength += 1;
  }

  // Sprawdzenie obecności znaków specjalnych
  if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
    strength += 1;
  }

  // Zwracamy wartość siły hasła
  return strength;
}

// Funkcja aktualizująca pasek siły hasła
function updatePasswordStrengthBar(password) {
  const strength = evaluatePasswordStrength(password);
  const strengthBar = $('#password-strength');
  const feedback = $('#password-feedback');
  
  let strengthColor = 'red';
  let feedbackText = 'Bardzo słabe';

  // Ustawienie koloru i tekstu w zależności od siły hasła
  if (strength === 1) {
    strengthColor = 'red';
    feedbackText = 'Bardzo słabe';
  } else if (strength === 2) {
    strengthColor = 'orange';
    feedbackText = 'Słabe';
  } else if (strength === 3) {
    strengthColor = 'yellow';
    feedbackText = 'Średnie';
  } else if (strength === 4) {
    strengthColor = 'lightgreen';
    feedbackText = 'Dobre';
  } else if (strength === 5) {
    strengthColor = 'green';
    feedbackText = 'Bardzo dobre';
  }

  // Ustawienie paska siły hasła
  strengthBar.html(`<div style="width: ${strength * 20}%"></div>`);
  strengthBar.find('div').css('background-color', strengthColor);

  // Wyświetlanie informacji zwrotnej
  feedback.text(feedbackText);
}

// Funkcja sprawdzająca hasło w czasie rzeczywistym
$('#password').on('input', function() {
  const password = $(this).val();
  
  // Najpierw oceniamy hasło lokalnie (w czasie rzeczywistym)
  updatePasswordStrengthBar(password);


  $.ajax({
    url: '/check-password-strength',  
    method: 'POST',
    data: { password: password },
    success: function(response) {
      if (response.isWeak) {
        feedback.text('To hasło jest zbyt popularne. Spróbuj coś bardziej unikalnego.');
      }
    }
  });
});


    </script>



    <!-- Theme Switcher Script -->
    <script>

        let login_btn = document.querySelector('.login-btn');
        console.log(login_btn);

        login_btn.addEventListener('click', () => {
            console.log('Redirecting to login page...');
            window.location.href = 'login.php';
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
