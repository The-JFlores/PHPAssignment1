<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('database.php');

// Get POST data from form
$movieID = $_POST['movieID'] ?? null;
$title = $_POST['title'] ?? '';
$genre = $_POST['genre'] ?? '';
$director = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating = $_POST['rating'] ?? null;

// Status from checkbox
$status = isset($_POST['status']) ? 'Active' : 'No active';

// Redirect if no movieID
if (!$movieID) {
    header('Location: index.php');
    exit();
}

// Get current image filename from database
$query = "SELECT image FROM movies WHERE movieID = :movieID";
$statement = $db->prepare($query);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
$statement->execute();
$currentMovie = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

$currentImage = $currentMovie['image'] ?? 'placeholder.png';

// Handle image upload
$newImageName = $currentImage;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'images/';
    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $newImageName = uniqid('img_', true) . '.' . $extension;
    $destination = $uploadDir . $newImageName;

    if (move_uploaded_file($tmpName, $destination)) {
        // Delete old image if not placeholder
        if ($currentImage && $currentImage !== 'placeholder.png' && file_exists($uploadDir . $currentImage)) {
            unlink($uploadDir . $currentImage);
        }
    } else {
        // If upload failed, keep current image
        $newImageName = $currentImage;
    }
}

// Prepare update query
$query = "UPDATE movies
        SET title = :title,
            genre = :genre,
            director = :director,
            releaseYear = :releaseYear,
            rating = :rating,
            status = :status,
            image = :image
        WHERE movieID = :movieID";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);
$statement->bindValue(':status', $status);
$statement->bindValue(':image', $newImageName);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);

$statement->execute();
$statement->closeCursor();

// Redirect back to index.php after update
header('Location: index.php');
exit();