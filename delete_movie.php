<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('database.php');

$id = $_GET['id'] ?? null;

if ($id) {

    // 1️⃣ Get the poster path before deleting
    $query = "SELECT poster FROM movies WHERE movieID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $movie = $statement->fetch();
    $statement->closeCursor();

    if ($movie && !empty($movie['poster'])) {

        $posterPath = $movie['poster'];

        // 2️⃣ Do not delete placeholder image
        if (basename($posterPath) !== 'avatar.png' && file_exists($posterPath)) {
            unlink($posterPath);
        }
    }

    // 3️⃣ Delete movie from database
    $query = 'DELETE FROM movies WHERE movieID = :id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $statement->closeCursor();
}

// 4️⃣ Redirect
header('Location: index.php');
exit();
