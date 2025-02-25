<?php
function connectDB() {
    $host = 'localhost';      
    $dbname = 'api';    
    $username = 'root';  
    $password = '';       

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");
    
    return $conn;
}
?>
