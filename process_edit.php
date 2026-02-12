<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('database.php');

// Get POST data
$movieID = $_POST['movieID'] ?? null;
$title = $_POST['title'] ?? '';
$genre = $_POST['genre'] ?? '';
$director = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating = $_POST['rating'] ?? null;

$status = isset($_POST['status']) ? 'Active' : 'No active';

if (!$movieID) {
    header('Location: index.php');
    exit();
}

// Get current poster
$query = "SELECT poster FROM movies WHERE movieID = :movieID";
$statement = $db->prepare($query);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
$statement->execute();
$currentMovie = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

$currentPoster = $currentMovie['poster'] ?? 'avatar.png';

$newPoster = $currentPoster;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $uploadDir = 'images/';
    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    $newPoster = uniqid('img_', true) . '.' . $extension;
    $destination = $uploadDir . $newPoster;

    if (move_uploaded_file($tmpName, $destination)) {

        if ($currentPoster !== 'avatar.png' && file_exists($uploadDir . $currentPoster)) {
            unlink($uploadDir . $currentPoster);
        }

    } else {
        $newPoster = $currentPoster;
    }
}

// Update movie
$query = "UPDATE movies
        SET title = :title,
            genre = :genre,
            director = :director,
            releaseYear = :releaseYear,
            rating = :rating,
            status = :status,
            poster = :poster
        WHERE movieID = :movieID";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);
$statement->bindValue(':status', $status);
$statement->bindValue(':poster', $newPoster);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);

$statement->execute();
$statement->closeCursor();

header('Location: index.php');
exit();
