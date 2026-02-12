
<?php
session_start();
?>

<?php include 'header.php'; ?>

<h2>Login</h2>

<form action="process_login.php" method="post">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">

</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include 'footer.php'; ?>

