

<?php
session_start();

class DatabaseAPI {
    private $apiUrl = 'http://localhost:5000/api';

    public function query($question) {
        return $this->sendRequest('/query', 'POST', ['question' => $question]);
    }

    public function getSchema() {
        return $this->sendRequest('/schema', 'GET');
    }

    public function testConnection() {
        return $this->sendRequest('/test', 'GET');
    }

    private function sendRequest($endpoint, $method, $data = null) {
        $curl = curl_init();
        
        $options = [
            CURLOPT_URL => $this->apiUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ];

        if ($data && $method === 'POST') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }
        
        curl_close($curl);
        
        if ($httpCode >= 400) {
            throw new Exception('Error: Something went wrong, please try again ' . $httpCode);
        }

        return json_decode($response, true);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Database Query</title>

    <style>
        .form-container {
    display: flex;
    flex-direction: column;
    max-width: 600px;
    margin: 20px auto;
    padding: 2rem;
    border-radius: 8px;
    background-color: var(--bs-body-bg);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.5rem;
}

.form-group label {
    margin-bottom: 0.5rem;
    color: var(--bs-body-color);
    font-weight: 500;
}

.form-group textarea, 
.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--bs-border-color);
    border-radius: 4px;
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
    transition: border-color 0.3s ease;
}

.form-group textarea:focus,
.form-group input:focus {
    outline: none;
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.25);
}

.loading {
    color: var(--bs-primary);
    text-align: center;
    padding: 1rem;
}

.success {
    color: var(--bs-success);
    padding: 1rem;
    border-radius: 4px;
    background-color: rgba(var(--bs-success-rgb), 0.1);
}

.error {
    color: var(--bs-danger);
    padding: 1rem;
    border-radius: 4px;
    background-color: rgba(var(--bs-danger-rgb), 0.1);
}

button[type="submit"] {
    background-color: var(--bs-primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: var(--bs-primary-hover);
}

@media (max-width: 768px) {
    .form-container {
        margin: 10px;
        padding: 1rem;
    }
    
    .form-group input,
    .form-group textarea {
        padding: 0.5rem;
    }
}
.form-container {
    display: flex;
    flex-direction: column;
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    border-radius: 16px;
    background-color: var(--bs-body-bg);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--bs-border-color);
    border-radius: 12px;
    background-color: var(--bs-body-bg);
    color: var(--bs-body-color);
    font-size: 1rem;
    line-height: 1.5;
    transition: all 0.3s ease;
    resize: vertical;
    min-height: 120px;
}

.form-group textarea:focus {
    outline: none;
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.15);
}

.form-group button[type="submit"] {
    background: linear-gradient(45deg, var(--bs-primary), #2c7be5);
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.form-group button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
}

.form-group button[type="submit"] i {
    font-size: 1.2rem;
}

.loading {
    display: none;
    padding: 2rem;
    text-align: center;
    color: var(--bs-primary);
    font-weight: 500;
}

.loading::after {
    content: '';
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    border: 3px solid currentColor;
    border-radius: 50%;
    border-right-color: transparent;
    animation: spin 1s linear infinite;
    margin-left: 0.5rem;
    vertical-align: middle;
}

#results {
    margin-top: 2rem;
}

.results-container {
    background-color: var(--bs-body-bg);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    margin-top: 2rem;
}

.results-container table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1rem;
}

.results-container th {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    color: var(--bs-primary);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid rgba(var(--bs-primary-rgb), 0.2);
}

.results-container td {
    padding: 1rem;
    border-bottom: 1px solid var(--bs-border-color);
}

.results-container tr:last-child td {
    border-bottom: none;
}

.results-container tr:hover td {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.success, .error {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.success {
    background-color: rgba(var(--bs-success-rgb), 0.1);
    color: var(--bs-success);
    border-left: 4px solid var(--bs-success);
}

.error {
    background-color: rgba(var(--bs-danger-rgb), 0.1);
    color: var(--bs-danger);
    border-left: 4px solid var(--bs-danger);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .form-container {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    .form-group button[type="submit"] {
        padding: 0.75rem 1.5rem;
    }
}
    </style>

        
       
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">SuperAPI</a>
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
    <div class="container">
        <br><br><br>
        <h1>AI Database Query</h1>
        
        <?php
        // Inicjalizacja API i test połączenia
        try {
            $api = new DatabaseAPI();
            $testConnection = $api->testConnection();
            echo '<div class="success">API connection works correctly!</div>';
        } catch (Exception $e) {
            echo '<div class="error">API connection error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>

        <div class="form-group">
            <form id="queryForm" method="POST">
                <textarea name="question" rows="4" placeholder="Add user Thomas with password 'abcd12'" required></textarea>
                <button type="submit">
    <i class="bi bi-send-fill"></i>
Execute the query
</button>
            </form>
        </div>

        <div class="loading" id="loading">
            
Processing the query...
        </div>

        <div id="results" class="results-container">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['question'])) {
        try {
            $results = $api->query($_POST['question']);
            
            if (isset($results['error'])) {
                echo '<div class="error">' . htmlspecialchars($results['error']) . '</div>';
            } else if (isset($results['results']) && is_array($results['results'])) {
                echo '<h3>Wyniki zapytania:</h3>';
                
                if (empty($results['results'])) {
                    echo '<p>Brak wyników.</p>';
                } else {
                    // Check if results[0] exists and is an array
                    if (isset($results['results'][0]) && is_array($results['results'][0])) {
                        echo '<table>';
                        // Nagłówki tabeli
                        echo '<tr>';
                        foreach (array_keys($results['results'][0]) as $header) {
                            echo '<th>' . htmlspecialchars($header) . '</th>';
                        }
                        echo '</tr>';
                        
                        // Dane
                        foreach ($results['results'] as $row) {
                            echo '<tr>';
                            foreach ($row as $value) {
                                echo '<td>' . htmlspecialchars($value) . '</td>';
                            }
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        // Handle non-SELECT queries (INSERT, UPDATE, DELETE)
                        if (isset($results['results']['operation'])) {
                            echo '<div class="success">';
                            echo 'Operation ' . htmlspecialchars($results['results']['operation']) . ' completed successfully. ';
                            echo 'Number of lines modified: ' . htmlspecialchars($results['results']['affected_rows']);
                            echo '</div>';
                        } else {
                            echo '<p>No results to display.</p>';
                        }
                    }
                }
            }
        } catch (Exception $e) {
            echo '<div class="error">Błąd: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
    ?>
</div>
    </div>
    <div class="container mt-5">
        <h1>MySQL Admin Assistant</h1>

        <section class="mt-4">
    <h2>Authentication</h2>
    <p>This API provides a natural language interface to a database system, allowing users to query and manipulate data using plain English or Polish. The API leverages OpenAI's GPT-4 model to translate natural language queries into SQL statements.</p>
</section>

<section class="mt-4">
    <h2>API Endpoints</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>Method</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>/api/test</td>
                <td>GET</td>
                <td>Verifies if the API is operational</td>
            </tr>
            <tr>
                <td>/api/query</td>
                <td>POST</td>
                <td>Converts natural language questions into SQL queries and executes them</td>
            </tr>
            <tr>
                <td>/api/direct-query</td>
                <td>POST</td>
                <td>Executes raw SQL queries directly against the database</td>
            </tr>
            <tr>
                <td>/api/schema</td>
                <td>GET</td>
                <td>Retrieves the database schema including tables and their columns</td>
            </tr>
        </tbody>
    </table>
</section>

<section class="mt-4">
    <h2>Use Example</h2>
    <table class="table">
        <thead>
            <tr>
                <th>SQL Code</th>
                <th>User prompt</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>SELECT * FROM User;</td>
                <td>Show me all users</td>
            </tr>
            <tr>
                <td>SELECT * FROM User WHERE user_id = &id;</td>
                <td>Find user with specific ID: [Your ID]</td>
            </tr>
            <tr>
                <td>DELETE FROM User WHERE name = &name;</td>
                <td>Remove user by username</td>
            </tr>
            <tr>
                <td>SELECT * FROM game_scores;</td>
                <td>Show all game scores</td>
            </tr>
            <tr>
                <td>SELECT a.used_count FROM api_keys a JOIN user u ON a.user_id = u.id WHERE u.name = 'admin';</td>
                <td>How many times has the admin's API key been used?</td>
            </tr>
        </tbody>
    </table>
</section>

<section class="mt-4">
    <h2>Database Schema</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Table</th>
                <th>Columns</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>user</td>
                <td>id, name, email, password, otp, is_active</td>
            </tr>
            <tr>
                <td>api_keys</td>
                <td>id, user_id, api_key, usage_limit, used_count, is_active, created_at</td>
            </tr>
            <tr>
                <td>game_scores</td>
                <td>id, player_name, score, game_name, created_at, api_key</td>
            </tr>
        </tbody>
    </table>
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

<section class="mt-4">
    <h2>Error Handling</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Status Code</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>200</td>
                <td>Success</td>
            </tr>
            <tr>
                <td>400</td>
                <td>Bad Request (missing parameters)</td>
            </tr>
            <tr>
                <td>500</td>
                <td>Server Error (database errors, query errors)</td>
            </tr>
        </tbody>
    </table>
</section>

    </div>

    <script>
let ai_btn = document.querySelector('.ai-btn');

if(ai_btn) {
    ai_btn.addEventListener('click', () => {
    console.log('Redirecting to AI page...');
    window.location.href = '../MainApp/ai.php';
});

}
        document.getElementById('queryForm').addEventListener('submit', function(e) {
            document.getElementById('loading').style.display = 'block';
        });
    </script>
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