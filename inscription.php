<?php
include('connexion.php');

session_start();

// Vérifier si l'utilisateur est déjà connecté et rediriger vers le tableau de bord
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit();
}
$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $cin = $_POST['cin'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
 
    $sql = "INSERT INTO accounts (nom, prenom, cin, email, mot_de_passe, role, date_de_creation) VALUES ('$nom', '$prenom', '$cin', '$email', '$mot_de_passe', '$role', NOW())";

    if ($conn->query($sql) === TRUE) {
        $message = "Inscription avec succès!";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!doctype html>
<html lang="en">

<head>
    <?php include('index.css'); ?>
    <style>
        .swal2-popup {
            font-size: 14px !important;
        }
    </style>
</head>

<body>



    <br />
    <br />




    <div class="content">
        <div class="container-fluid" style="width: 80%; margin: 0 auto;">

            <?php if (!empty($message)) { ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script>
                    Swal.fire({
                        title: 'Succès!',
                        text: '<?php echo $message; ?>',
                        icon: 'success',
                        showConfirmButton: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'login.php';
                    });
                </script>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="width: 70%;margin: 0 auto;">
                        <div class="header">
                            <h3 class="title" style="font-weight: bold;text-align: center;">Créer un compte</h3>
                        </div>
                        <div class="content">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="cin">CIN :</label>
                                            <input type="number" class="form-control" name="cin" id="cin" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nom">Nom :</label>
                                            <input type="text" class="form-control" name="nom" id="nom" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="prenom">Prénom :</label>
                                            <input type="text" class="form-control" name="prenom" id="prenom" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email :</label>
                                            <input type="email" class="form-control" name="email" id="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="mot_de_passe">Mot de passe :</label>
                                            <input type="password" class="form-control" name="mot_de_passe" id="mot_de_passe" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Rôle :</label>
                                            <select class="form-control" name="role" id="role" required>
                                                <option value="etudiant">Étudiant</option>
                                                <option value="admin">Administrateur</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" id="terms" name="terms" required>
                                            <label for="terms">J'accepte les termes et conditions</label>
                                        </div>




                                    </div>

                                </div>



                                <div>

                                    <button type="submit" class="btn btn-success btn-fill" style="margin-left: 42%;">Inscription</button>
                                    <br /><br />
                                    <a href="login.php" style="margin-left: 38%;">Avez-vous déjà un compte</a>

                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>








</body>

<?php include('index.js'); ?>


</html>