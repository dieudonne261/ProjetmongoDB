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

if(isset($_POST['addinvite'])) {
  $criteria = ['_id' => new MongoDB\BSON\ObjectId($_POST['addinvite'])];
  $update = [
    '$addToSet' => ['assigne' => $_SESSION['email']],
    '$pull' => ['assigne_invite' => $_SESSION['email']]
  ];
  $collection_tickets->updateOne($criteria, $update);
  header('Location: menu.php');
}

if(isset($_POST['delinvite'])) {
  $criteria = ['_id' => new MongoDB\BSON\ObjectId($_POST['delinvite'])];
  $update = [
    '$pull' => ['assigne_invite' => $_SESSION['email']]
  ];
  $collection_tickets->updateOne($criteria, $update);
  header('Location: menu.php');
}

if(isset($_POST['updateper'])) {
  $criteria = ['email' => $_SESSION['email']];
  $update = [
    '$set' => ['nom' => $_POST['nom'] ,
              'prenom' => $_POST['prenom'],
              'email' => $_SESSION['email'],
              'mdp' => $_POST['mdp'],
              'datedenai' => $_POST['datedenai'],
              'avatar' => $_POST['avatar']
    ]
    
];
$collection_users->updateOne($criteria, $update);
}



$result = $collection_users->findOne(['email' => $_SESSION['email']]);



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link rel="shortcut icon" href="../assets/img/icon1.png" type="image/x-icon">
  <link rel="stylesheet" href="../assets/css/menu-client.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="body-menu" style="background-image: url(../assets/img/back1.jpg);background-size: cover;">
  <main class="d-flex flex-nowrap">
    <div class="d-flex flex-column align-items-center text-align-center flex-shrink-0 rounded m-3 shadow" style="width:240px;background-color: rgba(0, 0, 0, 0.2);">
      <div class="btn d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light bg-warning text-decoration-none shadow mt-2 mb-2 rounded">
        <div class="d-flex align-items-center justify-content-center text-dark text-center text-decoration-none" style="width:190px">
          <img src="../assets/img/home.png" alt="" width="25" height="25" class=" me-2 ">
          <strong>Menu Principal</strong>
        </div>
      </div>
      <a href="menu-newtic.php" class="btn d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light bg-light text-decoration-none shadow mb-2 rounded">
        <div class="d-flex align-items-center justify-content-center text-dark text-center text-decoration-none" style="width:190px">
          <img src="../assets/img/add.png" alt="" width="27" height="27" class=" me-2 ">
          <strong>Nouveau ticket</strong>
        </div>
      </a>
      <div style="height:1400px;width:220px;scrollbar-width: thin;,z-index: 0;" class="list-group bg-light list-group-flush scrollarea rounded pt-3 pb-3">
        <div class="container">
          <form class="d-grid gap-2" method="POST">
            <?php
            $tot = 0;
            $cursor = $collection_tickets->find(['assigne' => $_SESSION['email']]);
            foreach ($cursor as $document) {
              $tot++;
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

    <div class="d-flex flex-column align-items-stretch text-center flex-shrink-0 mt-4" style="width:1080px;height:100%">
      <div class="container justify-content-center align-items-center">
        <img src="../assets/img/avatar/<?php echo $result['avatar']; ?>.png" alt="" width="200" height="200" style="background-color: rgba(0, 0, 0, 0.2);border: 10px solid rgb(252, 252, 252);" class="rounded-circle align-items-center mt-5 align-items-center shadow">
        <div class="container rounded bg-light" style="padding-top:120px;margin-top:-100px;">
          <div class="text-center mb-3">
            <h3 class="text-dark">Bienvenue , <?php echo $result['nom'].' '.$result['prenom'] ?> </h3>
            <h5 class="text-warning">
              <?php echo $result['email']; ?>
              <button class="btn" data-bs-toggle="modal" data-bs-target="#settingacc">
                <img src="../assets/img/edit.png" alt="" width="25" height="25" class=" me-2 ">
              </button>
            </h5>

            <div class="modal" id="settingacc">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Mon compte</h4>
                  </div>
                  <div class="modal-body">
                  <form class="row g-3 text-start" method="POST">
                    
                    <div class="col-md-6">
                      <label class="form-label">Nom</label>
                      <input type="text" class="form-control" name="nom" value="<?php echo $result['nom'] ?>" required>
                      
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Prenom</label>
                      <input type="text" class="form-control" name="prenom" value=" <?php echo $result['prenom'] ?>">
                    </div>
                    <div class="col-md-12">
                      <label class="form-label ">Email</label>
                      <input type="text" class="form-control disabled" value="<?php echo $result['email'] ?>" disabled>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Mot de passe</label>
                      <input type="text" class="form-control" name="mdp" value="<?php echo $result['mdp'] ?>" required>
                      
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Date de naissance</label>
                      <input type="date" class="form-control" name="datedenai" value="<?php echo $result['datedenai'] ?>" required>
                    </div>
                    <?php 
                     $avatar_icon = ["blue","green","orange","purple","red","yellow"];
                    
                     for($i=0;$i<sizeof($avatar_icon);$i++){
                      echo '<div class="col-md-2 text-center">
                      <img src="../assets/img/avatar/'.$avatar_icon[$i].'.png" alt="" width="60" height="60">';

                      if($result["avatar"] == $avatar_icon[$i]){
                        echo '<input type="radio" class="form-check-input" id="radio" name="avatar" value="'.$avatar_icon[$i].'" checked></div>';
                      }
                      else{
                        echo '<input type="radio" class="form-check-input" id="radio" name="avatar" value="'.$avatar_icon[$i].'"></div>';
                      }
                      
                      
                     }
                    ?>
                    <input type="submit" name="updateper" class="form-control btn btn-primary" value="Enregitrer">

                    

                  </form>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
            <div class="text-dark text-center align-items-center justify-content-center" style="display:flex; gap:50px">
              <a  class="btn gap-3 align-items-center p-3 link-body-light bg-light text-decoration-none shadow mt-2 mb-2 rounded" style="width:300px;">
                <h5>Total de projet</h5>
                <hr>
                <h1><?php echo $tot ?></h1>
              </a>

              
              <div class="modal" id="aaa">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-body">
                      <?php
                      $totin = 0 ;


                      $cursor = $collection_tickets->find(["assigne_invite" => $_SESSION["email"]]);


                      foreach ($cursor as $ticket) {
                        $totin++;
                          echo '<div class="col mt-2">
                                  <div class="card bg-light" >
                                    <div class="card-header p-0 pe-2">
                                      '.$ticket["createur"].'
                        </div>
                        <div class="card-body  m-2">
                        <p class="card-text text-start">Nom du projet : ' . $ticket["nom"] . '</p>
                        <p class="card-text text-start">Description : ' . $ticket["description"] . '</p>
                        <form class="d-flex gap-2 justify-content-end " method="POST">
                            <button type="submit" name="delinvite" value="'. $ticket["_id"].'" class="btn shadow text-light btn-danger btn-sm">Supprimer</button>
                            <button type="submit" name="addinvite" value="'. $ticket["_id"].'" class="btn shadow text-light btn-success btn-sm">Confirmer</button>
                          </form>
                        </div>
                        </div>
                        </div>';
                      }
                      if($totin == 0){
                        echo "Aucun invitation pour l'instants ...";
                      }

                      ?>
                        

                    </div>
                  </div>
                </div>
              </div>

              <button class=" btn gap-3 align-items-center p-3 link-body-light bg-light text-decoration-none shadow mt-2 mb-2 rounded" style="width:300px;"  data-bs-toggle="modal" data-bs-target="#aaa">
                <h5>Invitation</h5>
                <hr>
                <h1><?php echo $totin?></h1>
              </button>

              

            </div>
            <hr>
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
</html>