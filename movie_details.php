
<?php
require('database.php');
include('header.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit();
}

// Query to fetch the movie with its genre
$query = "
SELECT m.*, g.name AS genre
FROM movies m
LEFT JOIN movie_genres mg ON m.movieID = mg.movieID
LEFT JOIN genres g ON mg.genreID = g.genreID
WHERE m.movieID = :id
";

$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$movie = $statement->fetch();
$statement->closeCursor();

if (!$movie) {
    echo "<p>Movie not found.</p>";
    include('footer.php');
    exit();
}
?>

<h2>Movie Details</h2>

<?php if (!empty($movie['poster']) && file_exists($movie['poster'])): ?>
    <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="Poster" style="max-width:200px;"><br><br>
<?php endif; ?>

<p><strong>Title:</strong> <?= htmlspecialchars($movie['title']) ?></p>
<p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre'] ?? 'No genre') ?></p>
<p><strong>Director:</strong> <?= htmlspecialchars($movie['director']) ?></p>
<p><strong>Release Year:</strong> <?= htmlspecialchars($movie['releaseYear']) ?></p>
<p><strong>Rating:</strong> <?= htmlspecialchars($movie['rating']) ?></p>
<p><strong>Status:</strong> <?= $movie['status'] === 'Active' ? '<span style="color:green;">Active</span>' : '<span style="color:red;">No active</span>' ?></p>

<p><a href="index.php">Back to Movie List</a></p>

<?php include('footer.php'); ?>