

<?php
require_once('database.php');

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Basic validation
if (empty($email) || empty($password)) {
    echo "All fields are required.";
    echo "<br><a href='register.php'>Go back</a>";
    exit();
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {

    $query = "INSERT INTO users (email, password)
              VALUES (:email, :password)";

    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $hashedPassword);
    $statement->execute();
    $statement->closeCursor();

    header("Location: login.php");
    exit();

} catch (PDOException $e) {

    echo "Email already exists or error occurred.";
    echo "<br><a href='register.php'>Try again</a>";
}
?>
