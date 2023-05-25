<?php
session_start();
if (!isset($_SESSION['patient'])) {
    header("Location: connexion.php");
    exit();
}
$patient = $_SESSION['patient'];

require 'bdd/db.php';

$medecin_matricule = $patient['medecin_matricule'];
$query = "SELECT nom, prenom FROM Medecin WHERE matricule = :matricule";
$stmt = $db->prepare($query);
$stmt->execute(['matricule' => $medecin_matricule]);
$medecin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reserver'])) {
        $type = $_POST['type_consultation'];
        $heure = $_POST['heure'];
        $medecin_matricule = $patient['medecin_matricule']; // Utilisation du médecin traitant du patient

        if (empty($type) || empty($heure)) {
            $message = "Veuillez remplir tous les champs obligatoires.";
        } else {
            // Vérification si le patient a déjà un rendez-vous
            $query = "SELECT COUNT(*) FROM Consultation WHERE patient_num_carte = :patient_num_carte";
            $stmt = $db->prepare($query);
            $stmt->execute(['patient_num_carte' => $patient['num_carte']]);
            $count = $stmt->fetchColumn();

            if ($count >= 1) {
                $message = "Vous avez déjà un rendez-vous.";
            } else {
                // Vérification si l'heure de consultation est déjà prise
                $query = "SELECT COUNT(*) FROM Consultation WHERE consultation_heure = :consultation_heure";
                $stmt = $db->prepare($query);
                $stmt->execute(['consultation_heure' => $heure]);
                $count = $stmt->fetchColumn();

                if ($count >= 1) {
                    $message = "L'heure de consultation est déjà prise. Veuillez choisir une autre heure.";
                } else {
                    $query = "INSERT INTO Consultation (type, medecin_matricule, patient_num_carte, consultation_heure) VALUES (:type, :medecin_matricule, :patient_num_carte, :consultation_heure)";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        'type' => $type,
                        'medecin_matricule' => $medecin_matricule,
                        'patient_num_carte' => $patient['num_carte'],
                        'consultation_heure' => $heure,
                    ]);
                    $message = "Votre réservation a été enregistrée avec succès.";
                }
            }
        }
    }

    if (isset($_POST['supprimer'])) {
        $id_consultation = $_POST['id_consultation'];
        $query = "DELETE FROM Consultation WHERE id = :id AND patient_num_carte = :patient_num_carte";
        $stmt = $db->prepare($query);
        $stmt->execute([
            'id' => $id_consultation,
            'patient_num_carte' => $patient['num_carte']
        ]);
        $message = "Le rendez-vous a été supprimé avec succès.";
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
        input {
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

    <br>

    <div class="recherche">
        <h1>Bienvenue,
            <?php echo $patient['prenom'] . ' ' . $patient['nom']; ?>
        </h1>
    </div>

    <h2>Vos informations:</h2>
    <ul>
        Numéro de carte:
        <?php echo $patient['num_carte']; ?>
        <br>

        Nom:
        <?php echo $patient['nom']; ?>
        <br>

        Prénom:
        <?php echo $patient['prenom']; ?>
        <br>

        Date de naissance:
        <?php echo $patient['birthdate']; ?>
        <br>

        Vaccin:
        <?php echo $patient['vaccin'] ? 'Oui' : 'Non'; ?>
        <br>

        Médecin:
        <?php echo $medecin['prenom'] . ' ' . $medecin['nom']; ?>
        <br>

        Mail:
        <?php echo $patient['email']; ?>
        <br>

    </ul>

    <h2>Réservation de consultation</h2>
    <?php if (isset($message)) { ?>
        <p>
            <?php echo $message; ?>
        </p>
    <?php } ?>
    <form method="post">
        <label for="type_consultation">Type de consultation :</label>
        <select id="type_consultation" style="" name="type_consultation" required>
            <option value="">Sélectionner un type de consultation</option>
            <option value="Consultation générale">Consultation générale</option>
            <option value="Consultation Covid">Consultation Covid</option>
            <option value="Consultation PCR">Consultation PCR</option>
        </select>

        <br><br>

        <label for="heure">Choisissez un horaire :</label>
        <select id="heure" name="heure" required>
            <option value="">Sélectionner votre horaire</option>
            <?php
            $query = "SELECT c.date, c.heure_debut, c.heure_fin FROM calendrier c JOIN Medecin m ON c.medecin_matricule = m.matricule WHERE m.matricule = :medecin_matricule";
            $stmt = $db->prepare($query);
            $stmt->execute(['medecin_matricule' => $medecin_matricule]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $date = $row['date'];
                $heureDebut = $row['heure_debut'];
                $heureFin = $row['heure_fin'];
                echo '<option value="' . $date . ' ' . $heureDebut . ' à ' . $heureFin . '">' . $date . ' - ' . $heureDebut . ' à ' . $heureFin . '</option>';
            }
            ?>
        </select>

        <br><br>
        
        <input type="submit" value="Réserver" name="reserver" class="btn btn-primary py-2 px-4 ms-3">

    </form>

    <br><br>

    <h2>Liste des rendez-vous</h2>
<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Date et heure de consultation</th>
            <th>Médecin</th>
            <th>Statut</th>
            <th>Note Refus</th>
            <th>Note Accepter</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT c.id, c.type, c.etat, c.note_refus, c.note_accepter, c.consultation_heure, m.nom, m.prenom FROM Consultation c JOIN Medecin m ON c.medecin_matricule = m.matricule WHERE patient_num_carte = :patient_num_carte";
        $stmt = $db->prepare($query);
        $stmt->execute(['patient_num_carte' => $patient['num_carte']]);
        $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($consultations as $consultation) {
            echo '<tr>';
            echo '<td>' . $consultation['type'] . '</td>';
            echo '<td>' . $consultation['consultation_heure'] . '</td>';
            echo '<td>' . $consultation['nom'] . ' ' . $consultation['prenom'] . '</td>';
            echo '<td>' . $consultation['etat'] . '</td>';
            echo '<td>' . $consultation['note_refus'] . '</td>';
            echo '<td>' . $consultation['note_accepter'] . '</td>';
            echo '<td>';
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="id_consultation" value="' . $consultation['id'] . '">';
            echo '<button type="submit" name="supprimer" class="btn btn-danger">Supprimer</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
    <br>
    <h2>Options</h2>
    <br>
    <a href="modifier_mot_de_passe_patient.php" class="btn btn-primary py-2 px-4 ms-3 w-40">Modifier mon mot de passe</a>
    <br><br>
    <a href="deconnexion.php" class="btn btn-primary py-2 px-4 ms-3">Déconnexion</a>
    </div>
    


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
                    <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">HEAL ME</a>. Tout droit
                        réservé.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">By <a target="_blank" href="https://mael-laurent.fr/">Mael </a>et <a target="_blank"
                            href="https://Kylian-chaboche.fr/">Kylian</a></p>
                    </a></p>
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