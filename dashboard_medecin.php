<?php
session_start();
if (!isset($_SESSION['medecin'])) {
    header("Location: connexion.php");
    exit();
}
$medecin = $_SESSION['medecin'];

require 'bdd/db.php';



// Gestion des actions d'acceptation et de refus de rendez-vous
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_consultation = isset($_POST['id_consultation']) ? $_POST['id_consultation'] : '';
    $note_accepter = isset($_POST['note_accepter']) ? $_POST['note_accepter'] : '';
    if (isset($_POST['accepter'])) {
        $query = "UPDATE Consultation SET etat = 'accepter', note_accepter = :note_accepter WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id_consultation, 'note_accepter' => $note_accepter]);
    } elseif (isset($_POST['refuser'])) {
        $note_refus = isset($_POST['note_refus']) ? $_POST['note_refus'] : '';
        $query = "UPDATE Consultation SET etat = 'refuser', note_refus = :note_refus, note_accepter = :note_accepter WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id_consultation, 'note_refus' => $note_refus, 'note_accepter' => $note_accepter]);
    } elseif (isset($_POST['supprimer'])) {
        $query = "DELETE FROM Consultation WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id_consultation]);
    }
}

// Récupération des rendez-vous du médecin
$query = "SELECT Consultation.*, Patient.prenom AS patient_prenom, Patient.nom AS patient_nom, email, Patient.birthdate AS patient_birthdate FROM Consultation 
          INNER JOIN Patient ON Consultation.patient_num_carte = Patient.num_carte
          WHERE Consultation.medecin_matricule = :matricule";
$stmt = $db->prepare($query);
$stmt->execute(['matricule' => $medecin['matricule']]);
$rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php
if (isset($message)) {
    echo '<div class="alert alert-danger" role="alert">';
    echo $message;
    echo '</div>';
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>HealMe | Tableau Bord </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <style>
        body,
        table,
        label {
            margin: 0 auto;
            text-align: center;
        }
    </style>


    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-dark m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-secondary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
        <a href="index.html" class="navbar-brand p-0">
            <h1 class="m-0 text-primary"></i>HealMe</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.html" class="nav-item nav-link">Accueil</a>
            </div>
            <a href="connexion.php" class="btn btn-primary py-2 px-4 ms-3">Connexion</a>
        </div>
    </nav>
    <!-- Navbar End -->

    <br><br>

    <h1>Bienvenue,
        <?php echo $medecin['prenom'] . ' ' . $medecin['nom']; ?>
    </h1>
    <h2>Vos informations:</h2>
    <ul>
        Matricule:
        <?php echo $medecin['matricule']; ?>
        <br>

        Nom:
        <?php echo $medecin['nom']; ?>
        <br>

        Prénom:
        <?php echo $medecin['prenom']; ?>
        <br>

        Mail:
        <?php echo $medecin['email']; ?>

        <br><br>

    </ul>
    <h2>Vos rendez-vous</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Patient</th>
                <th>Date et heure de consultation</th>
                <th>Mail</th>
                <th>Age</th>
                <th>Etat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rdvs as $rdv): ?>
                <tr>
                    <td>
                        <?php echo $rdv['type']; ?>
                    </td>
                    <td>
                        <?php echo $rdv['patient_prenom'] . ' ' . $rdv['patient_nom']; ?>
                    </td>
                    <td>
                        <?php echo $rdv['consultation_heure']; ?>
                    </td>
                    <td>
                        <?php echo $rdv['email']; ?>
                    </td>
                    <td>
                        <?php echo $rdv['patient_birthdate']; ?>
                    </td>
                    <td>
                        <?php echo $rdv['etat']; ?>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id_consultation" value="<?php echo $rdv['id']; ?>">
                            <button class="btn btn-primary py-2 px-4 ms-3" type="submit" name="accepter">Accepter</button>
                            <textarea name="note_accepter" placeholder="Note d'acceptation"></textarea>
                            <button class="btn btn-primary py-2 px-4 ms-3" type="submit" name="refuser">Refuser</button>
                            <textarea name="note_refus" placeholder="Raison de refus"></textarea>
                            <button  class="btn btn-danger" type="submit" name="supprimer">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <br><br>



    <h2>Vos patients</h2>
    <button class="btn btn-primary py-2 px-4 ms-3"><a href="liste_des_patients.php">Voir la liste de vos
            patients</a></button>



    <br><br>


    <h2 class="mb-4">Calendrier Medecin</h2>
    <button class="btn btn-primary py-2 px-4 ms-3"><a href="calendrier.php">Gerez votre planning</a></button>




    <br><br>



    <h2>Options</h2>
    <br>
    <a href="modifier_mot_de_passe_medecin.php" class="btn btn-primary py-2 px-4 ms-3">Modifier mon mot de passe</a>
    <br><br>
    <a href="deconnexion.php" class="btn btn-primary py-2 px-4 ms-3">Déconnexion</a>



    <br><br><br><br><br><br>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light py-5 wow fadeInUp" data-wow-delay="0.3s" style="margin-top: -75px;">
        <div class="container pt-5">
            <div class="row g-5 pt-4">
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Liens rapides</h3>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="index.html"><i
                                class="bi bi-arrow-right text-primary me-2"></i>Accueil</a>
                        <a class="text-light mb-2" href="connexion.php"><i
                                class="bi bi-arrow-right text-primary me-2"></i>Connexion</a>
                        <a class="text-light mb-2" href="inscription.php"><i
                                class="bi bi-arrow-right text-primary me-2"></i>inscription</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Nos Contact</h3>
                    <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i>7 rue des lilas, Lille, 59000</p>
                    <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>HealMe@medecin-france.fr
                    </p>
                    <p class="mb-0"><i class="bi bi-telephone text-primary me-2"></i>03 27 86 95 45</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="text-white mb-4">Suivez-Nous</h3>
                    <div class="d-flex">
                        <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i
                                class="fab fa-twitter fw-normal"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i
                                class="fab fa-facebook-f fw-normal"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i
                                class="fab fa-linkedin-in fw-normal"></i></a>
                        <a class="btn btn-lg btn-primary btn-lg-square rounded" href="#"><i
                                class="fab fa-instagram fw-normal"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-light py-4" style="background: #051225;">
        <div class="container">
            <div class="row g-0">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">HEAL ME</a>. Tout droit
                        réservé.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">By <a target="_blank" href="https://mael-laurent.fr/">Mael </a>et <a target="_blank"
                            href="https://Kylian-chaboche.fr/">Kylian</a></p>

                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/twentytwenty/jquery.event.move.js"></script>
    <script src="lib/twentytwenty/jquery.twentytwenty.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>