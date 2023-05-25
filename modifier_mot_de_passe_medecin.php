<?php
session_start();
if (!isset($_SESSION['medecin'])) {
    header("Location: connexion.php");
    exit();
}

$medecin = $_SESSION['medecin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

    require 'bdd/db.php';

    // Vérifie si l'ancien mot de passe est correct
    $query = "SELECT mot_de_passe FROM Medecin WHERE matricule = :matricule";
    $stmt = $db->prepare($query);
    $stmt->execute(['matricule' => $medecin['matricule']]);
    $stored_password = $stmt->fetchColumn();

    if (password_verify($ancien_mot_de_passe, $stored_password)) {
        if ($nouveau_mot_de_passe === $confirmer_mot_de_passe) {
            // Met à jour le mot de passe du médecin
            $hashed_password = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
            $query = "UPDATE Medecin SET mot_de_passe = :mot_de_passe WHERE matricule = :matricule";
            $stmt = $db->prepare($query);
            $stmt->execute(['mot_de_passe' => $hashed_password, 'matricule' => $medecin['matricule']]);
            $message = "Votre mot de passe a été modifié avec succès.";
        } else {
            $message = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
        }
    } else {
        $message = "L'ancien mot de passe est incorrect. Veuillez réessayer.";
    }
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
    <title>HealMe | Modifier de mot de passe </title>
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

<body>

<style>
		body {
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

    <br><br><br><br>


    <h1>Modifier le mot de passe</h1>
    <?php if (isset($message)) { ?>
        <p>
            <?php echo $message; ?>
        </p>
    <?php } ?>
    <form method="post">
        <label for="ancien_mot_de_passe">Ancien mot de passe :</label>
        <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required><br>

        <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
        <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required><br>

        <label for="confirmer_mot_de_passe">Confirmer le nouveau mot de passe :</label>
        <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required><br>

        <input type="submit" value="Modifier le mot de passe">
    </form>

    <br><br>
    <a class="btn btn-primary py-2 px-4 ms-3" href="dashboard_medecin.php">Retour au tableau de bord</a>

    <br><br><br><br><br><br><br><br><br><br><br><br>


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
				<p class="mb-0">By <a target="_blank" href="https://mael-laurent.fr/">Mael </a>et <a target="_blank" href="https://Kylian-chaboche.fr/">Kylian</a></p>

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