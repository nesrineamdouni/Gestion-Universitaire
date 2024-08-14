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

$message = "";
$titre = "";
$cours = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_cours = $_POST['id'];
    $n_titre = $_POST['n_titre'];


    if (!empty($id_cours)) {

        if ($_FILES['n_cours']['size'] > 0) {
            $n_cours = $_FILES['n_cours'];

            // Récupérer le chemin du rapport actuel
            $sql_select_path = "SELECT cours FROM cours WHERE id = $id_cours";
            $result_path = $conn->query($sql_select_path);

            if ($result_path->num_rows > 0) {
                $row_path = $result_path->fetch_assoc();
                $current_pdf_path = $row_path['cours'];

                // Supprimer l'ancien fichier PDF
                if (file_exists($current_pdf_path)) {
                    unlink($current_pdf_path);
                }

                // Sauvegarder le nouveau fichier PDF dans le dossier "pdf"
                $pdf_name = $_FILES['n_cours']['name'];
                $pdf_tmp = $_FILES['n_cours']['tmp_name'];
                $pdf_path = "pdf/" . $pdf_name; 
                move_uploaded_file($pdf_tmp, $pdf_path);


                $sql_update = $conn->prepare("UPDATE cours SET titre = ?, cours = ? WHERE id = ?");
                $sql_update->bind_param("ssi", $n_titre, $pdf_path, $id_cours);
                $sql_update->execute();

                $message = "Le cours et le PDF ont été modifiés avec succès!";
            }
        } else {
         
            $sql_update = $conn->prepare("UPDATE cours SET titre = ? WHERE id = ?");
            $sql_update->bind_param("si", $n_titre, $id_cours);
            $sql_update->execute();

            $message = "Le cours a été modifié avec succès!";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_cours = $_GET['id'];


    $sql_select = "SELECT * FROM cours WHERE id = $id_cours";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titre = $row['titre'];
        $cours = $row['cours'];
    } else {
     
    }
} else {

}
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
                                window.location.href = 'liste_cours.php';
                            });
                        </script>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title"><i class="fas fa-edit" style="margin-right: 10px;"></i>Modifier ce cours</h4>
                                </div>
                                <div class="content">
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $id_cours; ?>">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="form-group">
                                                    <label for="titre">Titre :</label>
                                                    <input type="text" class="form-control" name="n_titre" id="titre" value="<?php echo htmlspecialchars($titre, ENT_QUOTES); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cours">Cours :</label>
                                                    <input type="file" class="form-control" name="n_cours" id="cours" accept=".pdf">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="liste_cours.php" class="btn btn-info btn-fill pull-right">Annuler</a>
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