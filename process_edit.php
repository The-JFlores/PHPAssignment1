

<?php
require('database.php');

// Get POST data from form
$movieID = $_POST['movieID'] ?? null;
$title = $_POST['title'] ?? '';
$genre = $_POST['genre'] ?? '';
$director = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating = $_POST['rating'] ?? null;

// Redirect if no movieID
if (!$movieID) {
    header('Location: index.php');
    exit();
}

// Prepare update query
$query = "UPDATE movies
          SET title = :title,
              genre = :genre,
              director = :director,
              releaseYear = :releaseYear,
              rating = :rating
          WHERE movieID = :movieID";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);

$statement->execute();
$statement->closeCursor();

// Redirect back to index.php after update
header('Location: index.php');
exit();