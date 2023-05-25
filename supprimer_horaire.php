<?php
session_start();
if (!isset($_SESSION['medecin'])) {
    header("Location: connexion.php");
    exit();
}
$medecin = $_SESSION['medecin'];

require 'bdd/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "DELETE FROM calendrier WHERE id = :id AND medecin_matricule = :matricule";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id, 'matricule' => $medecin['matricule']]);

        header("Location: calendrier.php");
        exit();
    }
}
?>