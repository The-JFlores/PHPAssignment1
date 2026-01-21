

<?php
include('header.php');
?>

<h2>Add Movie</h2>

<form action="process_movie.php" method="post">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="genre">Genre:</label><br>
    <input type="text" id="genre" name="genre"><br><br>

    <label for="director">Director:</label><br>
    <input type="text" id="director" name="director"><br><br>

    <label for="releaseYear">Release Year:</label><br>
    <input type="number" id="releaseYear" name="releaseYear" min="1900" max="2099"><br><br>

    <label for="rating">Rating (0.0 - 10.0):</label><br>
    <input type="number" step="0.1" min="0" max="10" id="rating" name="rating"><br><br>

    <input type="submit" value="Save Movie">
</form>

<?php
include('footer.php');
?>