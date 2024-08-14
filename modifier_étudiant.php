<?php
include('connexion.php');
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}
$email_connecte = $_SESSION['email']; 
$sql_fetch_user = "SELECT * FROM accounts WHERE email='$email_connecte'";
$result_user = $conn->query($sql_fetch_user);

if ($result_user->num_rows > 0) {
    $row = $result_user->fetch_assoc();
    $role = $row['role'];

    if ($role !== 'admin') {
        header("Location: unauthorized.php"); 
        exit();
    }
}

$message = "";
$nom_modif = "";
$prenom_modif = "";
$cin = "";
$image = "";
$date_naissance = "";
$telephone = "";
$adresse = "";
$classe = "";
$email_modif = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_etudiant = $_POST['id'];
    $nouveau_nom = $_POST['nouveau_nom'];
    $nouveau_prenom = $_POST['nouveau_prenom'];
    $n_cin = $_POST['n_cin'];
    $n_date_naissance = $_POST['n_date_naissance'];
    $n_telephone = $_POST['n_telephone'];
    $n_adresse = $_POST['n_adresse'];
    $n_classe = $_POST['n_classe'];
    $nouveau_email = $_POST['nouveau_email'];

    $sql_select_image = "SELECT image FROM accounts WHERE id = $id_etudiant";
    $resultat_de_la_requete = $conn->query($sql_select_image);
    $row = $resultat_de_la_requete->fetch_assoc();
    $ancienne_image = $row['image'];


    if (isset($_FILES['nouvelle_image']) && $_FILES['nouvelle_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['nouvelle_image']['tmp_name'];
        $image_nom = $_FILES['nouvelle_image']['name'];
        $destination = "uploads/" . $image_nom;


        if (move_uploaded_file($image_tmp, $destination)) {

            if (!empty($ancienne_image) && file_exists($ancienne_image)) {
                unlink($ancienne_image);
            }

            $sql_update_image = "UPDATE accounts SET image = '$destination' WHERE id = $id_etudiant";
            $conn->query($sql_update_image);
        } else {

        }
    }

    $sql_update = "UPDATE accounts SET nom = '$nouveau_nom', prenom = '$nouveau_prenom', email = '$nouveau_email', cin = '$n_cin', date_naissance = '$n_date_naissance', numero_telephone = '$n_telephone', adresse = '$n_adresse', classe = '$n_classe' WHERE id = $id_etudiant";
    $conn->query($sql_update);

    $message = "Etudiant a été modifié avec succès!";
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_etudiant = $_GET['id'];

    $sql_select = "SELECT * FROM accounts WHERE id = $id_etudiant";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom_modif = $row['nom'];
        $prenom_modif = $row['prenom'];
        $cin = $row['cin'];
        $image = $row['image'];
        $date_naissance = $row['date_naissance'];
        $telephone = $row['numero_telephone'];
        $adresse = $row['adresse'];
        $classe = $row['classe'];
        $email_modif = $row['email'];
    } else {

    }
} else {

    $nom = "";
    $prenom = "";
    $cin = "";
    $image = "";
    $date_naissance = "";
    $telephone = "";
    $adresse = "";
    $classe = "";
    $email = "";
}
$sql_classes = "SELECT id, nom FROM classe";
$result_classes = $conn->query($sql_classes);
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

    <div class="wrapper">
        <?php include('sidenav.php'); ?>

        <div class="main-panel">
            <?php include('navtop.php'); ?>

            <div class="content">
                <div class="container-fluid">
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
                                window.location.href = 'étudiants.php';
                            });
                        </script>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title"><i class="fas fa-edit" style="margin-right: 10px;"></i>Modifier cet étudiant</h4>
                                </div>
                                <div class="content">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $id_etudiant; ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nom">Nom :</label>
                                                    <input type="text" class="form-control" name="nouveau_nom" id="nom" value="<?php echo htmlspecialchars($nom_modif, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="prenom">Prénom :</label>
                                                    <input type="text" class="form-control" name="nouveau_prenom" id="prenom" value="<?php echo htmlspecialchars($prenom_modif, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email :</label>
                                                    <input type="email" class="form-control" name="nouveau_email" id="email" value="<?php echo htmlspecialchars($email_modif, ENT_QUOTES); ?>" required>
                                                </div>





                                                <div class="form-group">
                                                    <label for="cin">CIN :</label>
                                                    <input type="number" class="form-control" name="n_cin" id="cin" value="<?php echo htmlspecialchars($cin, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image">Image :</label>
                                                    <input type="file" class="form-control" name="nouvelle_image" id="image">
                                                </div>
                                                <div class="form-group">
                                                    <label for="date_naissance">Date de naissance :</label>
                                                    <input type="date" class="form-control" name="n_date_naissance" id="date_naissance" value="<?php echo htmlspecialchars($date_naissance, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="numero_telephone">Téléphone :</label>
                                                    <input type="number" class="form-control" name="n_telephone" id="numero_telephone" value="<?php echo htmlspecialchars($telephone, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="adresse">Adresse :</label>
                                                    <input type="text" class="form-control" name="n_adresse" id="adresse" value="<?php echo htmlspecialchars($adresse, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="classe">Classe :</label>
                                                    <select class="form-control" name="n_classe" id="classe" required>
                                                        <?php
                                                        while ($row = $result_classes->fetch_assoc()) {
                                                            $selected = ($classe == $row['nom']) ? "selected" : "";
                                                            echo "<option value='" . $row['nom'] . "' $selected>" . $row['nom'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                


                                            </div>
                                        </div>
                                        <div>
                                            <a href="étudiants.php" class="btn btn-info btn-fill pull-right">Annuler</a>
                                            <button type="submit" class="btn btn-success btn-fill" style="margin-left: 82%;">Modifier</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('footer.php'); ?>
        </div>
    </div>

</body>
<?php include('index.js'); ?>

</html>