<?php
require('database.php');

// Recoger datos del formulario
$title = $_POST['title'] ?? '';
$genreID = $_POST['genreID'] ?? null;
$director = $_POST['director'] ?? '';
$releaseYear = $_POST['releaseYear'] ?? null;
$rating = $_POST['rating'] ?? null;
$status = $_POST['status'] ?? 'No active';

// Manejar la imagen subida
$posterPath = null;
if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['poster']['tmp_name'];
    $fileName = basename($_FILES['poster']['name']);
    $uploadDir = 'images/';  // Asegúrate que esta carpeta exista y tenga permisos
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $newFilePath = $uploadDir . uniqid() . '_' . $fileName;
    if (move_uploaded_file($fileTmpPath, $newFilePath)) {
        $posterPath = $newFilePath;
    }
}

// Insertar la película en movies (sin genre)
$query = "INSERT INTO movies (title, director, releaseYear, rating, poster, status)
          VALUES (:title, :director, :releaseYear, :rating, :poster, :status)";

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':director', $director);
$statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
$statement->bindValue(':rating', $rating);
$statement->bindValue(':poster', $posterPath);
$statement->bindValue(':status', $status);
$statement->execute();

// Obtener el ID insertado
$movieID = $db->lastInsertId();

// Insertar la relación movie_genres
if ($genreID) {
    $query2 = "INSERT INTO movie_genres (movieID, genreID) VALUES (:movieID, :genreID)";
    $statement2 = $db->prepare($query2);
    $statement2->bindValue(':movieID', $movieID, PDO::PARAM_INT);
    $statement2->bindValue(':genreID', $genreID, PDO::PARAM_INT);
    $statement2->execute();
}

$statement->closeCursor();

header('Location: index.php');
exit();