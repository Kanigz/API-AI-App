<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.php');
    exit;
}

require_once './scripts/db_connect.php';
$conn = connectDB();
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$name = $_SESSION['name'];
$email = $_SESSION['mail'];
$password = $_SESSION['password'];

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie kontem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .wrapper {
            min-height: 100vh;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            border-right: 1px solid #dee2e6;
        }

        #sidebar .nav-link {
            color: #333;
            padding: 0.8rem 1.5rem;
        }

        #sidebar .nav-link:hover {
            background-color: #e9ecef;
        }

        #sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }

        #sidebar .bi {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                min-width: 80px;
                max-width: 80px;
            }
            
            #sidebar .nav-link span {
                display: none;
            }
            
            #sidebar .bi {
                margin-right: 0;
                font-size: 1.2rem;
            }
        }


        .nav-item .nav-link[href="api_login.php"].visible {
            display: flex;
        }


        /* Styl dla jasnego motywu */
[data-bs-theme="light"] {
    body {
        background-color: #f8f9fa;
        color: #212529;
    }

    .wrapper {
        background-color: #ffffff;
    }

    #sidebar {
        background-color: #f8f9fa;
        border-right-color: #dee2e6;
    }

    #sidebar .nav-link {
        color: #333;
    }

    #sidebar .nav-link:hover {
        background-color: #e9ecef;
    }

    .card {
        background-color: #ffffff;
        border-color: #dee2e6;
    }

    .form-control {
        background-color: #ffffff;
        border-color: #ced4da;
        color: #212529;
    }
}

/* Styl dla ciemnego motywu */
[data-bs-theme="dark"] {
    body {
        background-color: #212529;
        color: #f8f9fa;
    }

    .wrapper {
        background-color: #343a40;
    }

    #sidebar {
        background-color: #2c3034;
        border-right-color: #495057;
    }

    #sidebar .nav-link {
        color: #e9ecef;
    }

    #sidebar .nav-link:hover {
        background-color: #495057;
    }

    #sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #ffffff;
    }

    .card {
        background-color: #2c3034;
        border-color: #495057;
    }

    .form-control {
        background-color: #343a40;
        border-color: #495057;
        color: #f8f9fa;
    }

    .form-control:focus {
        background-color: #343a40;
        border-color: #0d6efd;
        color: #f8f9fa;
    }

    .btn-light {
        background-color: #495057;
        border-color: #495057;
        color: #f8f9fa;
    }

    .nav-tabs {
        border-color: #495057;
    }

    .nav-tabs .nav-link {
        color: #f8f9fa;
    }

    .nav-tabs .nav-link.active {
        background-color: #343a40;
        border-color: #495057;
        color: #f8f9fa;
    }

    .table {
        color: #f8f9fa;
    }

    .text-muted {
        color: #adb5bd !important;
    }
}






    </style>
</head>
<body>
    <div class="wrapper d-flex">
        
        <!-- Sidebar -->
        <nav id="sidebar" class="">
            
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="#profile">
                            <i class="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </a>
                        
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="api_settings.php">
                            <i class="bi bi-code-square"></i>
                            <span>API</span>
                        </a>
                    </li>
                              
                </ul>
            </div>
            

        </nav>

        <!-- Main Content -->
        <div class="content w-100">
            <div class="container py-4">
                <!-- Navigation buttons -->
                <div class="d-flex justify-content-between mb-4">
                <button onclick="window.location.href='index.php'" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                        
                    </button>
                    
                    
                    <a href="logout.php" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
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
                            <h2 class="mb-4">
                Account management panel</h2>
                
                <!-- Tabs navigation -->
                <ul class="nav nav-tabs mb-4" id="accountTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="security-tab" data-bs-toggle="tab" href="#security" role="tab">

                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="preferences-tab" data-bs-toggle="tab" href="#preferences" role="tab">

                        </a>
                    </li>
                </ul>

                <!-- Tabs content -->
                <div class="tab-content" id="accountTabsContent">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" id="name-current" class="form-control" name="username" value="<?= htmlspecialchars($name) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="email-current" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save changes</button>

                                    <?php
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                        $newName = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
                                        $newEmail = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

                                        if ($newName && $newEmail) {
                                            $updateStmt = $conn->prepare('UPDATE user SET name = ?, email = ? WHERE name = ?');
                                            $updateStmt->bind_param('sss', $newName, $newEmail, $name);
                                            $updateStmt->execute();

                                            $_SESSION['name'] = $newName;
                                            $_SESSION['mail'] = $newEmail;
                                            echo 'Changes has been saved.';

                                        } else {
                                            echo 'Please fill all fields.';
                                        }

                                        
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    

                    <!-- Preferences Tab -->
                    
                    
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                document.querySelectorAll('#sidebar .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Show corresponding tab
                const tabId = this.getAttribute('href').substring(1);
                const tabEl = document.querySelector(`#${tabId}-tab`);
                if (tabEl) {
                    const tab = new bootstrap.Tab(tabEl);
                    tab.show();
                }
            });
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
