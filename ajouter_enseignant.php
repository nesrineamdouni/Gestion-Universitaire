<?php
include('connexion.php');
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}
$email = $_SESSION['email'];
$sql_fetch_user = "SELECT * FROM accounts WHERE email='$email'";
$result_user = $conn->query($sql_fetch_user);

if ($result_user->num_rows > 0) {
    $row = $result_user->fetch_assoc();
    $role = $row['role'];


    if ($role !== 'admin') {
        header("Location: unauthorized.php"); 
        exit();
    }
}
// Sélection des départements depuis la base de données
$sql_departements = "SELECT id, nom_departement FROM departement";
$result_departements = $conn->query($sql_departements);

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $cin = $_POST['cin'];
    $date_naissance = $_POST['date_naissance'];
    $numero_telephone = $_POST['numero_telephone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $departement = $_POST['departement'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_nom = $_FILES['image']['name'];
        $destination = "uploads/" . $image_nom;

        if (move_uploaded_file($image_tmp, $destination)) {
            $image = $destination;
        } else {
        }
    }

    $sql = "INSERT INTO enseignant (nom, prenom, cin, image, date_de_naissance, numero_telephone, email, adresse, departement, date_de_creation) 
VALUES ('$nom', '$prenom', '$cin', '$image', '$date_naissance', '$numero_telephone', '$email', '$adresse', '$departement', NOW())";

    if ($conn->query($sql) === TRUE) {
        $message = "Enseignant a été ajouté avec succès!";
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
                                window.location.href = 'enseignants.php';
                            });
                        </script>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title"><i class="fas fa-plus" style="margin-right: 10px;"></i>Ajouter un nouveau enseignant</h4>
                                </div>
                                <div class="content">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nom">Nom :</label>
                                                    <input type="text" class="form-control" name="nom" id="nom" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="prenom">Prénom :</label>
                                                    <input type="text" class="form-control" name="prenom" id="prenom" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cin">CIN :</label>
                                                    <input type="number" class="form-control" name="cin" id="cin" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="image">Image :</label>
                                                    <input type="file" class="form-control" name="image" id="image" accept="image/*" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="date_naissance">Date de naissance :</label>
                                                    <input type="date" class="form-control" name="date_naissance" id="date_naissance" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email :</label>
                                                    <input type="email" class="form-control" name="email" id="email" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="numero_telephone">Téléphone :</label>
                                                    <input type="number" class="form-control" name="numero_telephone" id="numero_telephone" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="adresse">Adresse :</label>
                                                    <input type="text" class="form-control" name="adresse" id="adresse" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="departement">Département :</label>
                                                    <select class="form-control" name="departement" id="departement" required>
                                                        <?php
                                                        while ($row = $result_departements->fetch_assoc()) {
                                                            echo "<option value='" . $row['nom_departement'] . "'>" . $row['nom_departement'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>



                                            </div>

                                        </div>



                                        <div>
                                            <a href="enseignants.php" class="btn btn-info btn-fill pull-right">Annuler</a>
                                            <button type="submit" class="btn btn-success btn-fill" style="margin-left: 82%;">Ajouter</button>
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