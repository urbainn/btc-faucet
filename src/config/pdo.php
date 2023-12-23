<?php

ini_set("display_errors", 0);
ini_set("track_errors", 0);

/* Connexion à une base MySQL avec l'invocation de pilote */
$dsn = 'mysql:host=localhost;dbname=your_database_name';
$user = 'yourusername';
$password = 'yourpassword';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'PDOExeption error, could not connect to database. We are working on the issue, please come back later..<br>Une erreur est survenue lors de la connexion avec la base de données; nous travaillons sur le problème. Merci de revenir plus tard. '.$e;
}

$dsn = null;
$password = null;
?>
