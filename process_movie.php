

<?php
require('database.php');

// Get POST data from form
$title = $_POST['title'] ?? '';
$genre = $_POST['genre'] ?? '';
$director = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating = $_POST['rating'] ?? null;

// Prepare insert query
$query = "INSERT INTO movies (title, genre, director, releaseYear, rating)
          VALUES (:title, :genre, :director, :releaseYear, :rating)";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);

$statement->execute();
$statement->closeCursor();

// Redirect back to index.php after insert
header('Location: index.php');
exit();