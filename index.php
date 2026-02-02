<?php
require('database.php');
include('header.php');

// Fetch all movies ordered by movieID ascending
$query = 'SELECT * FROM movies ORDER BY movieID ASC';
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
            <td><?= htmlspecialchars($movie['title']) ?></td>
            <td><?= htmlspecialchars($movie['genre']) ?></td>
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
                   class="delete-button"
                   onclick="return confirm('Are you sure you want to delete this movie?');">
                   Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>