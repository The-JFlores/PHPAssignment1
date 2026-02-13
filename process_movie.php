<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('database.php');

// Collect form data
$title       = $_POST['title'] ?? '';
$genreID     = $_POST['genreID'] ?? null;
$director    = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating      = $_POST['rating'] ?? null;
$status      = $_POST['status'] ?? 'No active';

// Default placeholder
$posterName = 'avatar.png';

// Handle uploaded image
if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    $fileTmpPath = $_FILES['poster']['tmp_name'];
    $fileName    = $_FILES['poster']['name'];
    $extension   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate extension
    if (in_array($extension, $allowedTypes)) {

        $uploadDir = __DIR__ . '/images/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate clean unique file name
        $newFileName = uniqid('img_', true) . '.' . $extension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $posterName = $newFileName;
        }
    }
}

// Insert movie
$query = "INSERT INTO movies 
          (title, director, releaseYear, rating, poster, status)
          VALUES 
          (:title, :director, :releaseYear, :rating, :poster, :status)";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);
$statement->bindValue(':poster', $posterName);
$statement->bindValue(':status', $status);
$statement->execute();

$movieID = $db->lastInsertId();

// Insert genre relation
if (!empty($genreID)) {
    $query2 = "INSERT INTO movie_genres (movieID, genreID)
               VALUES (:movieID, :genreID)";
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':movieID', $movieID, PDO::PARAM_INT);
    $statement2->bindValue(':genreID', $genreID, PDO::PARAM_INT);
    $statement2->execute();
}

$statement->closeCursor();

header('Location: index.php');
exit();
