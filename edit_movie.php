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

<form action="process_edit.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movie['movieID']); ?>">

    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title"
           value="<?php echo htmlspecialchars($movie['title']); ?>" required><br><br>

    <label for="genre">Genre:</label><br>
    <input type="text" id="genre" name="genre"
           value="<?php echo htmlspecialchars($movie['genre']); ?>"><br><br>

    <label for="director">Director:</label><br>
    <input type="text" id="director" name="director"
           value="<?php echo htmlspecialchars($movie['director']); ?>"><br><br>

    <label for="releaseYear">Release Year:</label><br>
    <input type="number" id="releaseYear" name="releaseYear"
           min="1900" max="2099"
           value="<?php echo htmlspecialchars($movie['releaseYear']); ?>"><br><br>

    <label for="rating">Rating (0.0 - 10.0):</label><br>
    <input type="number" step="0.1" min="0" max="10"
           id="rating" name="rating"
           value="<?php echo htmlspecialchars($movie['rating']); ?>"><br><br>

           <input type="hidden" name="status" value="No active">

    <!-- Status checkbox -->
    <label>
        <input type="checkbox" name="status" value="Active"
            <?php if ($movie['status'] === 'Active') echo 'checked'; ?>>
        Active
    </label>
    <br><br>

    <input type="submit" value="Update Movie">
</form>

<?php include('footer.php'); ?>
    <label for="image">Image:</label><br>
    <input type="file" id="image" name="image" accept="image/*"><br>
    <?php if (!empty($movie['image'])): ?>
        <img src="<?php echo htmlspecialchars($movie['image']); ?>" alt="Current Image" style="max-width: 200px; max-height: 200px;"><br>
    <?php else: ?>
        <img src="placeholder.png" alt="No Image" style="max-width: 200px; max-height: 200px;"><br>
    <?php endif; ?>
    <br>