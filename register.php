

<?php
require_once('header.php');
?>

<h2>Register</h2>

<form action="process_register.php" method="post">

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" value="Register">

</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include('footer.php'); ?>
