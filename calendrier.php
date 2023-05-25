<?php
session_start();
if (!isset($_SESSION['medecin'])) {
    header("Location: connexion.php");
    exit();
}
$medecin = $_SESSION['medecin'];

require 'bdd/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $heureDebut = isset($_POST['heure_debut']) ? $_POST['heure_debut'] : '';
    $heureFin = isset($_POST['heure_fin']) ? $_POST['heure_fin'] : '';
    $medecinMatricule = isset($_POST['medecin_matricule']) ? $_POST['medecin_matricule'] : '';

    // Insérer les données dans la base de données
    $query = "INSERT INTO calendrier (date, heure_debut, heure_fin, medecin_matricule) VALUES (:date, :heure_debut, :heure_fin, :medecin_matricule)";
    $stmt = $db->prepare($query);
    $stmt->execute(['date' => $date, 'heure_debut' => $heureDebut, 'heure_fin' => $heureFin, 'medecin_matricule' => $medecinMatricule]);
}


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

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <h2 class="mb-4">Calendrier Medecin</h2>

                <!-- Formulaire de réservation de créneaux horaires -->
                <form method="POST">
                    <div class="form-group mb-3">
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="time" class="form-control" name="heure_debut" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="time" class="form-control" name="heure_fin" required>
                    </div>
                    <input type="hidden" name="medecin_matricule" value="<?php echo $medecin['matricule']; ?>">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <br><br>

    <h2>Vos horaires sélectionnés</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure de début</th>
                <th>Heure de fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>



            <?php
            // Récupération des horaires sélectionnés par le médecin
            $query = "SELECT * FROM calendrier WHERE medecin_matricule = :matricule";
            $stmt = $db->prepare($query);
            $stmt->execute(['matricule' => $medecin['matricule']]);
            $horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($horaires as $horaire) {
                echo "<tr>";
                echo "<td>" . $horaire['date'] . "</td>";
                echo "<td>" . $horaire['heure_debut'] . "</td>";
                echo "<td>" . $horaire['heure_fin'] . "</td>";
                echo "<td><a href='supprimer_horaire.php?id=" . $horaire['id'] . "'>Supprimer</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <br><br>




    <br><br><br>
	<a href="dashboard_medecin.php" class="btn btn-primary py-2 px-4 ms-3">Retourner sur votre tableau de bord</a>




    <br><br><br><br><br><br><br>


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