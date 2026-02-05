<?php
require('database.php');
include('header.php');

// Consulta con JOIN para traer género y poster
$query = "
SELECT m.*, g.name AS genre
FROM movies m
LEFT JOIN movie_genres mg ON m.movieID = mg.movieID
LEFT JOIN genres g ON mg.genreID = g.genreID
ORDER BY m.movieID ASC";

$statement = $db->prepare($query);
$statement->execute();
$movies = $statement->fetchAll();
$statement->closeCursor();
?>

<h2>Movie List</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Poster</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Director</th>
            <th>Release Year</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($movies as $movie): ?>
        <tr>
            <td><?= htmlspecialchars($movie['movieID']) ?></td>

            <td>
                <?php if (!empty($movie['poster'])): ?>
                    <img src="<?= htmlspecialchars($movie['poster']) ?>" 
                         alt="Poster" 
                         style="width:60px; height:auto;">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($movie['title']) ?></td>
            <td><?= htmlspecialchars($movie['genre'] ?? 'No genre') ?></td>
            <td><?= htmlspecialchars($movie['director']) ?></td>
            <td><?= htmlspecialchars($movie['releaseYear']) ?></td>
            <td><?= htmlspecialchars($movie['rating']) ?></td>

            <td>
                <?php if ($movie['status'] === 'Active'): ?>
                    <strong style="color: green;">Active</strong>
                <?php else: ?>
                    <span style="color: red;">No active</span>
                <?php endif; ?>
            </td>

            <td>
                <a href="edit_movie.php?id=<?= $movie['movieID'] ?>">Edit</a> | 
                <a href="delete_movie.php?id=<?= $movie['movieID'] ?>"
                   onclick="return confirm('Are you sure you want to delete this movie?');">
                   Delete
                </a> |
                <a href="movie_details.php?id=<?= $movie['movieID'] ?>">Details</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>