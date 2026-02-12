

<?php
session_start();
require_once('database.php');

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = :email";
$statement = $db->prepare($query);
$statement->bindValue(':email', $email);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['userID'];
    $_SESSION['user_email'] = $user['email'];

    header("Location: index.php");
    exit();

} else {

    echo "Invalid email or password.";
    echo "<br><a href='login.php'>Try again</a>";
}
?>
