

<?php
// Database connection settings
$dsn = 'mysql:host=localhost;dbname=movie_manager;charset=utf8';
$username = 'root';
$password = '';

try {
    // Create PDO instance for database connection
    $db = new PDO($dsn, $username, $password);
    // Set error mode to exception for better error handling
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, show database error page
    include('database_error.php');
    exit();
}
?>