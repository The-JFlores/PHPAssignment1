<?php
session_start();

// Redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('database.php');

// Get movie ID from URL
$id = $_GET['id'] ?? null;

if ($id) {

    // 1️⃣ Retrieve the poster filename from the database
    $query = "SELECT poster FROM movies WHERE movieID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $movie = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if ($movie) {

        $poster = $movie['poster'];

        // 2️⃣ Delete the image file from the server
        // Only delete if it is NOT the default placeholder
        if (!empty($poster) && $poster !== 'avatar.png') {

            $filePath = __DIR__ . '/images/' . $poster;

            // Check if file exists before attempting deletion
            if (file_exists($filePath)) {
                unlink($filePath); // Remove file from server
            }
        }

        // 3️⃣ Delete the movie record from the database
        $deleteQuery = "DELETE FROM movies WHERE movieID = :id";
        $deleteStatement = $db->prepare($deleteQuery);
        $deleteStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $deleteStatement->execute();
        $deleteStatement->closeCursor();
    }
}

// Redirect back to movie list after deletion
header('Location: index.php');
exit();
