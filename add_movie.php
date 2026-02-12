<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('header.php');
require('database.php');

// Traer géneros de la tabla genres
$query = "SELECT * FROM genres ORDER BY name";
$statement = $db->prepare($query);
$statement->execute();
$genres = $statement->fetchAll();
?>

<h2>Add Movie</h2>

<form action="process_movie.php" method="post" enctype="multipart/form-data">

    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="genreID">Genre:</label><br>
    <select name="genreID" id="genreID" required>
        <option value="">-- Select Genre --</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre['genreID'] ?>">
                <?= htmlspecialchars($genre['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="director">Director:</label><br>
    <input type="text" id="director" name="director"><br><br>

    <label for="releaseYear">Release Year:</label><br>
    <input type="number" id="releaseYear" name="releaseYear" min="1900" max="2099"><br><br>

    <label for="rating">Rating (0.0 - 10.0):</label><br>
    <input type="number" step="0.1" min="0" max="10" id="rating" name="rating"><br><br>

    <label for="poster">Poster (image):</label><br>
    <input type="file" name="poster" id="poster" accept="image/*"><br><br>

    <label>Status:</label><br>
    <label>
        <input type="radio" name="status" value="Active" checked> Active
    </label>
    <label>
        <input type="radio" name="status" value="No active"> No active
    </label>
    <br><br>

    <input type="submit" value="Save Movie">

</form>

<?php
include('footer.php');
?>