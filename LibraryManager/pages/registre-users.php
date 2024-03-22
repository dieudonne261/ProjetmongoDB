<?php

try {
  $db = new PDO('pgsql:host=localhost;dbname=librarymanager', 'postgres', '');
} catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}

session_start();

if($_SESSION['session'] == 0){
  header("location:../index.php");
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = $_POST["nompre"];
    $email = $_POST["emailreg"];
    $mdp = $_POST["mdp"];
    $role = $_POST["role"];
    $sql = "INSERT INTO users (nom, email, mdp, role) VALUES ( :nom, :email, :mdp, :role)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
}
else{

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link rel="shortcut icon" href="../assets/img/icon1.png" type="image/x-icon">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <script>
  const toastTrigger = document.getElementById('liveToastBtn')
  const toastLiveExample = document.getElementById('liveToast')

  if (toastTrigger) {
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastTrigger.addEventListener('click', () => {
      toastBootstrap.show()
    })
  }
</script>
</head>
<body class="bg-dark text-light">
    
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark m-2 shadow rounded">
    <div class="container-fluid">
      <a class="navbar-brand" >Library Manager</a>
        <div class="d-flex align-items-center ">

          <img src="../assets/img/avatar/admin.png" height="40px" width="40px"  alt="" srcset="">
          <h6 class="mt-1 text-warning p-2"> <?php echo $_SESSION['email']?></h6>
          <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
    </button>
    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
      <li><a class="dropdown-item" href="menu-bibli.php">Menu de Bibliothécaire</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="signout.php">Se deconnecter</a></li>
    </ul>
        </div>
      </div>
    </div>
  </nav>

<main class="m-2"  style="padding-top: 75px;">
  <div class="p-5 rounded shadow">
    <div class="text-center">
      <div class="row">
        <div class="col text-start">
          <h4 class="display-5">Total livres</h4>
          <h1 class="display-1">
          <?php
          $totalstock = $db->query("select sum(stock) from livres")->fetchColumn();
          $totalemprunts = $db->query("select sum(qte) from emprunts")->fetchColumn();
          $totalmembres = $db->query("select count(*) from users where role='membre'")->fetchColumn();
          $totalbiblis = $db->query("select count(*) from users where role='bibli'")->fetchColumn();
          echo '<h1 class="display-1">' . sprintf("%04d", $totalstock + $totalemprunts) . '</h1>';
          ?>
          </h1>
        </div>
        <div class="col text-start">
          <h4 class="display-5">Livres disponibles</h4>
          <?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totalstock) . '</h1>';
          ?>
        </div>
        <div class="col text-start">
          <h4 class="display-5">Livres empruntées</h4>
          <?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totalemprunts) . '</h1>';
          ?>
        </div>
      </div>
    </div> 



  </div>

  <div class="p-5 rounded shadow">
    <div class="text-center">
      <div class="row">
        <div class="col text-start">
          <h4 class="display-5">Total Membres</h4>
          <h1 class="display-1"><?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totalmembres) . '</h1>';
          ?></h1>
          <hr>
        </div>
        <div class="col text-start">
          <h4 class="display-5">Livres Bibliothécaire</h4>
          <h1 class="display-1"><?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totalbiblis) . '</h1>';
          ?></h1>
          <hr>
        </div>
        <div class="col text-start">
          <div class="d-grid gap-2 px-5">
            <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#nouveau">
              <p class="display-6 mt-2">Nouveau</p>
            </button>

            <div class="modal fade show" id="nouveau" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
              <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content bg-dark">
                    <div class="modal-body text-center">
                        <hr>
                        <p class="display-6">Nouveau Membre [ role : <?php echo $role ?> ] à ete ajouter avec succes</p>
                        <hr>
                        <a href="menu.php" type="button" class="btn btn-dark shadow w-100">Fermer</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/menu-client.js"></script>

</body>
</html><div class="modal-backdrop fade show"></div><div class="modal-backdrop fade show"></div><div class="modal-backdrop fade show"></div><div class="modal-backdrop fade show"></div></body></html>