<?php

require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection_users = $client->gestdepro->users;
$collection_tickets = $client->gestdepro->tickets;
$collection_coms = $client->gestdepro->commantaires;
session_start();

if($_SESSION['session'] == 0){
  header("location:../index.php");
}

if(isset($_POST['id'])) {
  $_SESSION['id_tickets'] = $_POST['id'];
  header('Location: menu-select.php');

}

if(isset($_POST['registre'])) {
  $_SESSION['nom'] = $_POST['nom'];
  $_SESSION['desc'] = $_POST['desc'];
  $_SESSION['dateest'] = $_POST['dateest'];
  $result = $collection_tickets->findOne(['createur' => $_SESSION['email'], 'nom' => $_POST['nom']]);
  if($result) {
    echo '<script>alert("Nom du ticket enter déjas existe, veuillez se renommer...");</script>';
  }
  else {
    header('location: menu-newtic-action.php');
  }
}


$result = $collection_users->findOne(['email' => $_SESSION['email']]);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouveau Ticket</title>
  <link rel="shortcut icon" href="../assets/img/icon1.png" type="image/x-icon">
  <link rel="stylesheet" href="../assets/css/menu-client.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="body-menu" style="background-image: url(../assets/img/back1.jpg);background-size: cover;">
  <main class="d-flex flex-nowrap">
    <div class="d-flex flex-column align-items-center text-align-center flex-shrink-0 rounded m-3 shadow" style="width:240px;background-color: rgba(0, 0, 0, 0.2);">
      <a href="menu.php" class="btn d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light bg-light text-decoration-none shadow mt-2 mb-2 rounded">
        <div class="d-flex align-items-center justify-content-center text-dark text-center text-decoration-none" style="width:190px">
          <img src="../assets/img/home.png" alt="" width="25" height="25" class=" me-2 ">
          <strong>Menu Principal</strong>
        </div>
      </a>
      <a class="btn d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light bg-warning text-decoration-none shadow mb-2 rounded">
        <div class="d-flex align-items-center justify-content-center text-dark text-center text-decoration-none" style="width:190px">
          <img src="../assets/img/add.png" alt="" width="27" height="27" class=" me-2 ">
          <strong>Nouveau ticket</strong>
        </div>
      </a>
      <div style="height:1400px;width:220px;scrollbar-width: thin;,z-index: 0;" class="list-group bg-light list-group-flush scrollarea rounded pt-3 pb-3">
        <div class="container">
          <form class="d-grid gap-2" method="POST">
            <?php
            
            $cursor = $collection_tickets->find(['assigne' => $_SESSION['email']]);
            foreach ($cursor as $document) {
              echo'<button type="submit" name="id" value="'.$document["_id"].'" class="btn border-dark bg-light">'.substr($document["nom"], 0, 14).'...</button>';
            }

            ?>
          </form>
        </div>
      </div>
      <div class="btn dropdown justify-content-between d-flex align-items-center flex-shrink-0 link-body-emphasis bg-light text-decoration-none shadow mt-2 mb-2 rounded dropdown-toggle" style="width: 220px;">
        <a class="d-flex align-items-center text-dark text-center text-decoration-none "   data-bs-toggle="dropdown" aria-expanded="false" >
          <strong>
            <div>
              <h style="color:orange">Pseudo</h>
              <h6 class="text-dark"><?php echo $result['email']; ?></h6>
            </div>
          </strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark align-items-center text-small shadow">
          <li ><a  class="dropdown-item align-items-center text-center me-5" href="signout.php">Se deconnecter</a></li>
        </ul>
      </div>
    </div>

    <div class="container d-flex flex-column bg-light text-center align-items-stretch flex-shrink-0 m-3 p-4 rounded" style="width:1050px;">
      <h1>Création de nouveau ticket</h1>
      <hr>
      <form class="row justify-content-between" method="POST">
        <div class="d-grid gap-3 col-sm-6 text-start">
          <div>
            <label  class="form-label">Nom du Projet</label>
            <input type="text" name="nom" class="form-control"  placeholder="" value="" required>
          </div>
          <div>
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="5" id="comment" name="desc" required></textarea>
          </div>
          <div>
            <label class="form-label">Date d'estimation</label>
            <input type="date" name="dateest" class="form-control"  placeholder="" value="" required>
          </div>
        </div>

        <div class="d-grid gap-3 col-sm-6 text-start rounded" style="background-image:url('../assets/img/100.jpg');background-size:contain;">
        </div>
        <div class="text-center">
          <input type="submit" class="btn mt-3 btn-primary" name="registre" value="Enregistrer" style="width: 200px;">
        </div>
      </form>
    </div>
  </main>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/menu-client.js"></script>
</body>
</html>