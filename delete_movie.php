

<?php
require('database.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $query = 'DELETE FROM movies WHERE movieID = :id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $statement->closeCursor();
}

// Redirect back to movie list after deletion
header('Location: index.php');
exit();