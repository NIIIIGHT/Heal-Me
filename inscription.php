<?php
require 'bdd/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num_carte = $_POST['num_carte'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $birthdate = $_POST['birthdate'];
    $vaccin = isset($_POST['vaccin']) ? 1 : 0;
    $medecin_matricule = $_POST['medecin_matricule'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier si le numéro de carte vitale existe déjà dans la base de données
    $query_check = "SELECT num_carte FROM Patient WHERE num_carte = :num_carte";
    $stmt_check = $db->prepare($query_check);
    $stmt_check->execute(['num_carte' => $num_carte]);

    if ($stmt_check->rowCount() > 0) {
        // Le numéro de carte vitale existe déjà, afficher un message d'erreur
        $message = "Le numéro de carte vitale est déjà utilisé.";
    } else {
        // Insérer le nouveau patient dans la base de données
        $query_insert = "INSERT INTO Patient (num_carte, nom, prenom, birthdate, vaccin, medecin_matricule, email, password) VALUES (:num_carte, :nom, :prenom, :birthdate, :vaccin, :medecin_matricule, :email, :password)";
        $stmt_insert = $db->prepare($query_insert);
        $stmt_insert->execute([
            'num_carte' => $num_carte,
            'nom' => $nom,
            'prenom' => $prenom,
            'birthdate' => $birthdate,
            'vaccin' => $vaccin,
            'medecin_matricule' => $medecin_matricule,
            'email' => $email,
            'password' => $password
        ]);

        header("Location: connexion.php");
        exit();
    }
}
?>

<?php if (isset($message)) : ?>
        <div class="container">
            <div class="alert alert-danger"><?php echo $message; ?></div>
        </div>
    <?php endif; ?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HealMe</title>
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


    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" class="form-control bg-transparent border-primary p-3"
                            placeholder="Type search keyword">
                        <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->

    <!-- Appointment Start -->
    <div class="container-fluid bg-primary bg-appointment mb-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-6 py-5">
                    <div class="py-5">
                        <h1 class="display-5 text-white mb-4">Nous sommes un site profesionnel et certifié en lequel
                            vous pouvez avoir confiance</h1>
                        <p class="text-white mb-0">HealMe est le premier site français de prise de rendez-vous en ligne
                            pour tout type de soucis médical. Nos médecins sauront vous prendre en charge, quelques soit
                            la raison de votre venu. (Les test covids et vaccinations sont désormais
                            disponibles)<br>Votre santé, notre priorité. </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="appointment-form h-100 d-flex flex-column justify-content-center text-center p-5 wow zoomIn"
                        data-wow-delay="0.6s">
                        <h1 class="text-white mb-4">Inscrivez-vous</h1>




                        <form method="post">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" id="nom" name="nom" required
                                        class="form-control bg-light border-0 datetimepicker-input" placeholder="Nom"
                                        style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" id="prenom" name="prenom" required
                                        class="form-control bg-light border-0 datetimepicker-input" placeholder="Prénom"
                                        style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" id="email" name="email" required
                                        class="form-control bg-light border-0" placeholder="Adresse mail"
                                        style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" id="num_carte" name="num_carte" required
                                        class="form-control bg-light border-0 datetimepicker-input"
                                        placeholder="Carte vitale" style="height: 55px;">
                                </div>

                                <div class="col-12 col-sm-6">
                                    <select id="medecin_matricule" class="form-control bg-light border-0 datetimepicker-input" style="height: 55px;" name="medecin_matricule" required>
                                        <option value="">Sélectionner un médecin</option>
                                        <?php
                                        $query = "SELECT * FROM Medecin";
                                        $stmt = $db->query($query);
                                        while ($medecin = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $medecin['matricule'] . '">' . $medecin['nom'] . ' ' . $medecin['prenom'] . '</option>';
                                        }
                                        ?>
                                    </select><br>
                                </div>



                                <div class="col-12 col-sm-6">
                                    <input type="date" id="birthdate" name="birthdate" required
                                        class="form-control bg-light border-0 datetimepicker-input"
                                        placeholder="Date de naissance" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="password" id="password" name="password" required
                                        class="form-control bg-light border-0 datetimepicker-input"
                                        placeholder="Mot de passe" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="password" id="password" name="password" required
                                        class="form-control bg-light border-0 datetimepicker-input"
                                        placeholder="Vérification du mot de passe" style="height: 55px;">
                                </div>
                                <div class="col-12">
                                    <a href="connexion.php">Vous possédez un compte ?</a>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-dark w-100 py-3" type="submit">Valider votre
                                        inscription</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Appointment End -->

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
                    <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>HealMe@medecin-france.fr</p>
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
                    <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">HEAL ME</a>. Tout droit réservé.</p>
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