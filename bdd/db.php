<?php
// db.php
$host = 'localhost';
$db_name = 'heal me';
$user = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage();
}
?>