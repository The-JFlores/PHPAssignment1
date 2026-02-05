<?php
// Debugging upload - remove after confirming it works
// echo '<pre>';
// print_r($_FILES);
// exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('database.php');

// Collect form data
$title = $_POST['title'] ?? '';
$genreID = $_POST['genreID'] ?? null;
$director = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating = $_POST['rating'] ?? null;
$status = $_POST['status'] ?? 'No active';

// Handle uploaded image
$posterPath = null;
if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['poster']['tmp_name'];
    $fileName = basename($_FILES['poster']['name']);
    
    // Absolute path to the images folder
    $uploadDir = __DIR__ . '/images/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Create new unique file name
    $newFileName = uniqid() . '_' . $fileName;
    $newFilePath = $uploadDir . $newFileName;
    
    // Move the uploaded file to the images folder
    if (move_uploaded_file($fileTmpPath, $newFilePath)) {
        // Save relative path to store in database
        $posterPath = 'images/' . $newFileName;
    }
}

// Insert movie record (without genre)
$query = "INSERT INTO movies (title, director, releaseYear, rating, poster, status)
          VALUES (:title, :director, :releaseYear, :rating, :poster, :status)";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);
$statement->bindValue(':poster', $posterPath);
$statement->bindValue(':status', $status);
$statement->execute();

// Get the last inserted movieID
$movieID = $db->lastInsertId();

// Insert movie-genre relation if genreID provided
if ($genreID) {
    $query2 = "INSERT INTO movie_genres (movieID, genreID) VALUES (:movieID, :genreID)";
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':movieID', $movieID, PDO::PARAM_INT);
    $statement2->bindValue(':genreID', $genreID, PDO::PARAM_INT);
    $statement2->execute();
}

$statement->closeCursor();

// Redirect to index page after insertion
header('Location: index.php');
exit();