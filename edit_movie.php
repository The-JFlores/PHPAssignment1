

<?php
require('database.php');
include('header.php');

$id = $_GET['id'] ?? null;

// Redirect if no ID provided
if (!$id) {
    header('Location: index.php');
    exit();
}

// Fetch the movie details by ID
$query = 'SELECT * FROM movies WHERE movieID = :id';
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$movie = $statement->fetch();
$statement->closeCursor();

// Show message if movie not found
if (!$movie) {
    echo "<p>Movie not found.</p>";
    include('footer.php');
    exit();
}
?>

<h2>Edit Movie</h2>

<form action="process_edit.php" method="post">
    <input type="hidden" name="movieID" value="<?= htmlspecialchars($movie['movieID']) ?>">

    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required><br><br>

    <label for="genre">Genre:</label><br>
    <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($movie['genre']) ?>"><br><br>

    <label for="director">Director:</label><br>
    <input type="text" id="director" name="director" value="<?= htmlspecialchars($movie['director']) ?>"><br><br>

    <label for="releaseYear">Release Year:</label><br>
    <input type="number" id="releaseYear" name="releaseYear" min="1900" max="2099" value="<?= htmlspecialchars($movie['releaseYear']) ?>"><br><br>

    <label for="rating">Rating (0.0 - 10.0):</label><br>
    <input type="number" step="0.1" min="0" max="10" id="rating" name="rating" value="<?= htmlspecialchars($movie['rating']) ?>"><br><br>

    <input type="submit" value="Update Movie">
</form>

<?php include('footer.php'); ?>