<?php

require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection_users = $client->gestdepro->users;
$collection_tickets = $client->gestdepro->tickets;
$collection_coms = $client->gestdepro->commentaires;
session_start();

if($_SESSION['session'] == 0){
  header("location:../index.php");
}

if(isset($_POST['id'])) {
  $_SESSION['id_tickets'] = $_POST['id'];
  header('Location: menu-select.php');
}

if(isset($_POST['sendmessage'])) {
  $new = [
    'de' => $_SESSION['email'],
    'message' => $_POST['message']
  ];
  $criteria = ['_id_tickets' => $_SESSION['id_tickets']];
  $update = [
      '$addToSet' => ['discution' => $new]
  ];
  $result = $collection_coms->updateOne($criteria, $update);
    header('Location: menu-select.php');
  }

$result_ticket = $collection_tickets->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])]);

if(isset($_POST['search'])) {
  $isajouter = false;
  $isainviter = false;
  $result_user = $collection_users->findOne(['email' => $_POST['assigne_invite']]);
  $i=0;
  if($result_user){
    for($i=0;$i<sizeof($result_ticket['assigne']);$i++){
      if($result_ticket['assigne'][$i]==$_POST['assigne_invite']){
        $isajouter = true;
      }
    }
    if(!$isajouter){
      for($i=0;$i<sizeof($result_ticket['assigne_invite']);$i++){
        if($result_ticket['assigne_invite'][$i]==$_POST['assigne_invite']){
          $isainviter = true;
        }
      }
    }
    if($isajouter){
      echo '<script>alert("Email entrer déja ajouter ...");</script>';
    }
    else if($isainviter){
      echo '<script>alert("Email entrer déja inviter ...");</script>';
    }
    else{
      $_SESSION['id_email'] = $_POST['assigne_invite'];
      $_SESSION['action'] = 'addemail';
      header('Location: menu-select-action.php');
    }
  }
  else {
    echo '<script>alert("Email entrer introuvable ...");</script>';
  }
}

if(isset($_POST['delassigne'])){
  $_SESSION['id_email'] = $_POST['delassigne'];
  $_SESSION['action'] = 'delassigne';
  header('Location: menu-select-action.php');
}

if(isset($_POST['delassigneinvite'])){
  $_SESSION['id_email'] = $_POST['delassigneinvite'];
  $_SESSION['action'] = 'delassigneinvite';
  header('Location: menu-select-action.php');
}

if(isset($_POST['registre'])){
  $checkList = array();

  for ($i = 0 ; $i < sizeof($result_ticket['assigne']) ; $i++) {
    if(isset($_POST[$i])){
      $checkList[] = $_POST[$i];
    }
  }

  if(sizeof($checkList) > 0){ 
    $_SESSION['emailassigne'] = $checkList;
    $_SESSION['nom_projet'] = $_POST['nom_projet'];
    $_SESSION['desc_projet'] = $_POST['desc_projet'];
    $_SESSION['action'] = 'addemailassigne';
    header('Location: menu-select-action.php');
  }
  else {
    echo '<script>alert("Selectionné au moins un assigné ...");</script>';
  }
}


if(isset($_POST['suppticket'])){
  $id = $_POST["suppticket"];
  $collection_tickets -> updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])],
    ['$unset' => ["statue_project.$id" => 1]]
  );
  $collection_tickets -> updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])],
    ['$pull' => ["statue_project" => null]]
  );
  header('Location: menu-select.php');
}

if(isset($_POST['confticket'])){
  $id = $_POST["confticket"];
  $collection_tickets -> updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])],
    ['$set' => ["statue_project.$id.statue" => 'en cours']]
  );
  header('Location: menu-select.php');
}

if(isset($_POST['valticket'])){
  $id = $_POST["valticket"];
  $collection_tickets -> updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])],
    ['$set' => ["statue_project.$id.statue" => 'en validation']]
  );
  header('Location: menu-select.php');
}

if(isset($_POST['terticket'])){
  $id = $_POST["terticket"];
  $collection_tickets -> updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])],
    ['$set' => ["statue_project.$id.statue" => 'terminer']]
  );
  header('Location: menu-select.php');
}

if(isset($_POST['suppProject'])){
  $_SESSION['action'] = 'suppProject';
  header('Location: menu-select-action.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $result_ticket['nom']; ?></title>
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
            $cursor = $collection_tickets->find(['assigne' => $_SESSION['email']]);
            foreach ($cursor as $document) {
              if($document["_id"] == $_SESSION['id_tickets']){
                echo '<button type="submit" name="id" value="'.$document["_id"].'" class="btn border-dark bg-warning">'.substr($document["nom"], 0, 14).'...</button>';
              }
              else{
                echo '<button type="submit" name="id" value="'.$document["_id"].'" class="btn border-dark bg-light">'.substr($document["nom"], 0, 14).'...</button>';
              }
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
              <h6 class="text-dark"><?php echo $_SESSION['email']; ?></h6>
            </div>
          </strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark align-items-center text-small shadow">
          <li ><a  class="dropdown-item align-items-center text-center me-5" href="signout.php">Se deconnecter</a></li>
        </ul>
      </div>
    </div>

    <div class="container d-flex flex-column text-center align-items-stretch flex-shrink-0 m-3 p-2 rounded" style="width:1050px;background-color: rgba(0, 0, 0, 0.2);">
      <div class="navbar navbar-expand navbar-light bg-light rounded">
        <div class="container">
          <a class="navbar-brand"><?php echo $result_ticket['nom']; ?></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="d-flex gap-2 navbar-nav me-auto mb-md-0 ">
              <div class="dropdown ">

                <button class="btn border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  A propos
                </button>
                <ul class="dropdown-menu border shadow" style="width: 225px;align-items: baseline;text-align: justify;">
                  <li><h4 class="dropdown-header">Description</h4></li>
                  <li class="ms-3 me-3"><p><?php echo $result_ticket['description']; ?></p></li>
                  <li><h4 class="dropdown-header">Date d'estimation</h4></li>
                  <li class="ms-3 me-3"><p><?php echo date("d F Y",strtotime( $result_ticket['date_est'])); ?></p></li>
                  
                </ul>
              </div>

              <div class="dropdown">
                <button class="btn border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Membres
                </button>
                <div class="dropdown-menu shadow" style="width: 300px;align-items: baseline;text-align: justify;">
                  <li><h4 class="dropdown-header">Administrateur</h4></li>
                  <div class="d-flex gap-2 ms-3 me-3">
                  <?php
                      $result_avatar = $collection_users->findOne(['email' => $result_ticket['createur']]);
                    echo '<img src="../assets/img/avatar/'.$result_avatar['avatar'].'.png" width="30" height="30" alt="" srcset="">
                    <li class="mt-1"><p>'.$result_ticket['createur'].'</p></li>';
                    ?>
                  </div>
                  <li><hr class="dropdown-divider"></li>
                  <li><h4 class="dropdown-header">Assigné</h4></li>
                  <?php
                    for ($i = 1 ; $i < sizeof($result_ticket['assigne']) ; $i++) {
                      $result_avatar = $collection_users->findOne(['email' => $result_ticket['assigne'][$i]]);
                      echo '
                      <div class="d-flex ms-3 justify-content-between">
                        <div class="d-flex gap-2 mt-2">
                          <img src="../assets/img/avatar/'.$result_avatar['avatar'].'.png" width="30" height="30" alt="" srcset="">
                          <li class="mt-1"><p>'.$result_ticket['assigne'][$i].'</p></li>
                        </div>';
                        if($result_ticket['createur'] == $_SESSION['email'] ){
                          echo '<form class="" method="post">
                            <button class="btn mt-1 me-2" value="'.$result_ticket['assigne'][$i].'" type="submit" name="delassigne">
                              <img src="../assets/img/del.png" width="20" height="20" alt=""  srcset="">
                            </button>
                          </form>';
                        }
                       echo '</div>
                      ';
                    }
                  
                  ?>
                  
                  <li><hr class="dropdown-divider"></li>
                  <li><h4 class="dropdown-header">Invité</h4></li>
                  <?php 
                    for ($i = 0 ; $i < sizeof($result_ticket['assigne_invite']) ; $i++) {
                      $result_avatar = $collection_users->findOne(['email' => $result_ticket['assigne_invite'][$i]]);
                      echo '
                      <div class="d-flex ms-3 justify-content-between">
                        <div class="d-flex gap-2 mt-2">
                          <img src="../assets/img/avatar/'.$result_avatar['avatar'].'.png" width="30" height="30" alt="" srcset="">
                          <li class=" mt-1 "><p>'.$result_ticket['assigne_invite'][$i].'</p></li>
                        </div>';
                        if($result_ticket['createur'] == $_SESSION['email'] ){
                          echo '<form class="" method="post">
                            <button class="btn mt-1 me-2 " value="'.$result_ticket['assigne_invite'][$i].'" type="submit" name="delassigneinvite">
                              <img src="../assets/img/del.png" width="20" height="20" alt=""  srcset="">
                            </button>
                          </form>';
                        }
                        echo'
                      </div>
                      ';
                    }
                  
                  
                  if($result_ticket['createur'] == $_SESSION['email'] ){
                   echo '<div class="ms-2 me-2" >
                    <form class="input-group" method="post">
                      <input type="email" class="form-control form-control-sm" placeholder="Invitation Email" name="assigne_invite" aria-label="" required>
                      <button class="btn btn-outline border" type="submit" name="search">
                        <img src="../assets/img/add-friend-24.png" width="30" height="30" alt=""  srcset="">
                      </button>
                    </form>
                  </div>';
                }
                  ?>

                </div>
              </div>



            </ul>
            <?php 

            
            if($result_ticket['createur'] == $_SESSION['email'] ){
              echo '<input type="button" data-bs-toggle="modal" data-bs-target="#myModaldel" class ="btn btn-danger me-5" value="Supprimer ce projet">';
            }
             ?>
             <div class="modal" id="myModaldel">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-body">
                    Etes-vous sur de vouloir supprimer ce projet ("<?php echo $result_ticket['nom'] ?>") ?
                  </div>
                  <form class="modal-footer p-1" method="POST">
                    <button type="submit" name="suppProject" class="btn btn-danger" >Supprimer</button>
                  </form>
                </div>
              </div>
            </div>
             
            <div class="dropdown">
              <button title="Source" class="btn dropdown-toggle shadow" onclick="boutonpourscl()" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../assets/img/25360.png" width="25" height="25" alt="" srcset="">

              </button>
              
              <ul class="dropdown-menu dropdown-menu-end shadow" style="width:458px;height:450px;">
                <main class="" style="width:458px;height:458px;overflow-x: hidden;">
                  <h2 class="text-center mt-2">Discutions</h2>
                  <hr>
                  <div method="post" class="ms-1 gap-1 scrollarea rounded" id="scrollmessage" style="width:450px;height:300px;overflow-x: hidden;";>
                  <div class="container d-grid gap-2">
                    <?php

                    $result_coms = $collection_coms->findOne(['_id_tickets' => $_SESSION['id_tickets']]);
                   
                    for ($i = 0 ; $i < sizeof($result_coms['discution']); $i++) {
                      if($result_coms['discution'][$i]['de'] == $_SESSION['email']){
                        echo '<div class="col text-end ms-5 text-white">
                        <div class="card bg-dark text-white" >
                          <div class="card-header p-0 pe-2">
                          Vous
                          </div>
                          <div class="card-body  m-2">
                            <p class="card-text">'.$result_coms['discution'][$i]['message'].'</p>
                          </div>
                        </div>
                      </div>';
                      }
                      else {
                        echo '<div class="col me-5">
                      <div class="card">
                        <div class="card-header p-0 ps-2">
                        '.$result_coms['discution'][$i]['de'].'
                        </div>
                        <div class="card-body m-2">
                          <p class="card-text">'.$result_coms['discution'][$i]['message'].'</p>
                        </div>
                      </div>
                    </div>';
                      }
                      
                  }

                    
                  ?>
                    </div>
                  </div>
                  <form class="input-group m-2 pe-3 rounded" method="post">
                    <input type="text" name="message" class="form-control form-control-sm" placeholder="Votre message ..." aria-label="" required>
                    <button class="btn btn-outline border" name="sendmessage" type="submit">
                      <img src="../assets/img/send.png" width="30" height="30" alt="" srcset="">
                    </button>
                  </form>
                </main>
              </ul>
              
            </div>
          </div>
        </div>
      </div>


      <main class="d-flex flex-nowrap gap-2 mt-2 pb-2">

        

        <div class="d-flex flex-column align-items-center bg-light text-align-center flex-shrink-0 rounded shadow" style="width:252px;">
          <div class=" d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light text-decoration-none mt-2 mb-2 rounded" style="background-image: url(../assets/img/back1.jpg);background-size: cover;">
            <div class="d-flex align-items-center justify-content-center text-light text-center text-decoration-none" style="width:202px;">
              
              <strong  class="display-6">A faire</strong>
            </div>
          </div>
          <div style="height:1000px;width:235px;scrollbar-width: thin;" class="list-group bg-light list-group-flush scrollarea rounded">
            <div class="">
              <form class="d-grid" method="POST">
                <?php
                    for ($i = 0 ; $i < sizeof($result_ticket['statue_project']); $i++) {

                      $bg = "bg-light";
                      for($k=0;$k<sizeof($result_ticket['statue_project'][$i]['assigne_projet']);$k++){
                        if($result_ticket['statue_project'][$i]['assigne_projet'][$k]==$_SESSION['email']){
                          $bg = "bg-primary text-white";
                        }
                      }

                        echo '<button type="button"  class="btn m-0 col text-center " data-bs-toggle="modal" data-bs-target="#myModal'. $i.'">
                        <div class="card '.$bg.'" >
                          <div class="card-header p-0">
                        '.$result_ticket['statue_project'][$i]['nom_project'].'
                          </div>
                          <div class="card-body m-2">
                            <p class="card-text">'.substr($result_ticket['statue_project'][$i]['description_projet'], 0, 20).'...</p>
                          </div>
                        </div>
                        </button>
                
                        <div class="modal" id="myModal'. $i.'">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">

                              <div class="modal-header">
                                <h4 class="modal-title">'.$result_ticket['statue_project'][$i]['nom_project'].'</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>

                              <div class="modal-body text-start">
                                      <div class="card-body">
                                        <h5 class="card-title">Description</h5>
                                        <p class="card-text">'.$result_ticket['statue_project'][$i]['description_projet'].'</p>
                                      </div>
                                      <div class="card-body mt-2">
                                        <h5 class="card-title">Assigné</h5>


                                      <p class="card-text">';

                                      $hita_ato = false;
                                      for ($j = 0 ; $j < sizeof($result_ticket['statue_project'][$i]['assigne_projet']); $j++) {
                                        echo $result_ticket['statue_project'][$i]['assigne_projet'][$j] .', ';
                                        if($result_ticket['statue_project'][$i]['assigne_projet'][$j] == $_SESSION['email'] && $result_ticket['statue_project'][$i]['statue'] == 'a faire'){
                                          $hita_ato = true;
                                        }
                                      }     
                                        echo '</p>
                                      </div>
                                      <div class="card-body">
                                        <h5 class="card-title mt-2">Carte posté</h5>
                                        <p class="card-text">'.date("d F Y",strtotime($result_ticket['statue_project'][$i]['date_post'])).'</p>
                                      </div>
                                      <div class="card-body">
                                        <h5 class="card-title mt-2">Créé par</h5>
                                        <p class="card-text">'.$result_ticket['statue_project'][$i]['crepar'].'</p>
                                      </div>
                                      <div class="card-body d-flex justify-content-between mt-2" method="POST">
                                      ';

                                      if($result_ticket['statue_project'][$i]['crepar'] == $_SESSION['email'] || $result_ticket['createur'] ==  $_SESSION['email']){
                                        echo'<button type="submit" name="suppticket" value="'.$i.'" class="btn shadow text-light btn-danger">Supprimer</button>';
                                        
                                      }

                                      if($hita_ato){
                                        echo'<button type="submit" name="confticket" value="'.$i.'" class="btn shadow btn-success">Mettre en cours</button>';
                                      }

                              echo '</div></div>

                            </div>
                          </div>
                        </div>
                      ';
                  }
                ?>
              </form>
            </div>
          </div>

          <button class="btn d-flex gap-2 align-items-center flex-shrink-0 p-3 link-body-light bg-light text-decoration-none" data-bs-toggle="modal" data-bs-target="#myModal">
            <img src="../assets/img/add.png" alt="" width="20" height="20" >
            <strong style="color:black">Nouveau carte</strong>
          </button>

<div class="modal" id="myModal">
  <div class="modal-dialog modal-md shadow">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nouveau carte</h5>
      </div>

      <form class="d-grid gap-2 text-start modal-body" method="post">

          <div class="">
            <label  class="form-label">Nom du Projet</label>
            <input type="text" name="nom_projet" class="form-control"  placeholder="" value="" required>
          </div>
          <div>
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="5" id="comment" name="desc_projet" required></textarea>
          </div>
          <div>
            <label class="form-label">Assigne</label>

            <?php
                    for ($i = 0 ; $i < sizeof($result_ticket['assigne']) ; $i++) {
                      echo '<div class="form-check">
                        <input class="form-check-input" type="checkbox" id="'.$i.'" name="'.$i.'" value="'.$result_ticket['assigne'][$i].'">
                        <label class="form-check-label">'.$result_ticket['assigne'][$i].'</label>
                    </div>';
                    }
                  
                  ?>



            
          </div>
          <div class="text-center">
          <input type="submit" class="btn mt-3 btn-primary" name="registre" value="Enregistrer" style="width: 200px;">
        </div>
      </form>
    </div>
  </div>
</div>
        </div>


        <div class="d-flex flex-column align-items-center bg-light text-align-center flex-shrink-0 rounded shadow" style="width:252px;">
          <div class=" d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light text-decoration-none mt-2 mb-2 rounded" style="background-image: url(../assets/img/back1.jpg);background-size: cover;">
            <div class="d-flex align-items-center justify-content-center text-light text-center text-decoration-none" style="width:202px;">
              
              <strong class="display-6" >En cours</strong>
            </div>
          </div>
          <div style="height:1000px;width:235px;scrollbar-width: thin;" class="list-group bg-light list-group-flush scrollarea rounded">
            <div class="">

            <form class="d-grid" method="POST">
                <?php
                    for ($i = 0 ; $i < sizeof($result_ticket['statue_project']); $i++) {

                      if($result_ticket['statue_project'][$i]['statue'] == "en cours"){

                        $bg = "bg-light";
                      for($k=0;$k<sizeof($result_ticket['statue_project'][$i]['assigne_projet']);$k++){
                        if($result_ticket['statue_project'][$i]['assigne_projet'][$k]==$_SESSION['email']){
                          $bg = "bg-primary text-white";
                        }
                      }

                        echo '<button type="button"  class="btn m-0 col text-center " data-bs-toggle="modal" data-bs-target="#myModal2'. $i.'">
                        <div class="card '.$bg.'" >
                          <div class="card-header p-0">
                        '.$result_ticket['statue_project'][$i]['nom_project'].'
                          </div>
                          <div class="card-body m-2">
                            <p class="card-text">'.substr($result_ticket['statue_project'][$i]['description_projet'], 0, 20).'...</p>
                          </div>
                        </div>
                        </button>
                
                        <div class="modal" id="myModal2'. $i.'">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">

                              <div class="modal-header">
                                <h4 class="modal-title">'.$result_ticket['statue_project'][$i]['nom_project'].'</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>

                              <div class="modal-body text-start">
                                      <div class="card-body">
                                        <h5 class="card-title">Description</h5>
                                        <p class="card-text">'.$result_ticket['statue_project'][$i]['description_projet'].'</p>
                                      </div>
                                      <div class="card-body mt-2">
                                        <h5 class="card-title">Assigné</h5>


                                      <p class="card-text">';

                                      $hita_ato = false;
                                      for ($j = 0 ; $j < sizeof($result_ticket['statue_project'][$i]['assigne_projet']); $j++) {
                                        echo $result_ticket['statue_project'][$i]['assigne_projet'][$j] .', ';
                                        if($result_ticket['statue_project'][$i]['assigne_projet'][$j] == $_SESSION['email'] && $result_ticket['statue_project'][$i]['statue'] == 'en cours'){
                                          $hita_ato = true;
                                        }
                                      }     
                                        echo '</p>
                                      </div>
                                      <div class="card-body">
                                        <h5 class="card-title mt-2">Carte posté</h5>
                                        <p class="card-text">'.date("d F Y",strtotime($result_ticket['statue_project'][$i]['date_post'])).'</p>
                                      </div>
                                      <div class="card-body">
                                        <h5 class="card-title mt-2">Créé par</h5>
                                        <p class="card-text">'.$result_ticket['statue_project'][$i]['crepar'].'</p>
                                      </div>
                                      <div class="card-body d-flex justify-content-between mt-2" method="POST">
                                      ';
                                      if($result_ticket['statue_project'][$i]['crepar'] == $_SESSION['email'] || $result_ticket['createur'] ==  $_SESSION['email']){
                                        echo'<button type="submit" name="suppticket" value="'.$i.'" class="btn shadow text-light btn-danger">Supprimer</button>';
                                        
                                      }
                                      if($hita_ato){
                                        echo'<button type="submit" name="valticket" value="'.$i.'" class="btn shadow btn-success">Mis en validation</button>';

                                      }


                                      

                              echo '</div></div>

                            </div>
                          </div>
                        </div>
                      ';

                      }
                      
                    }
                ?>
              </form>

            </div>
          </div>
          
          <a class="d-flex gap-2 align-items-center flex-shrink-0 p-3 link-body-light bg-light text-decoration-none">
            <img src="../assets/img/add.png" alt="" width="20" height="20" >
            <strong style="color:black">Nouveau carte</strong>
          </a>

        </div>


        <div class="d-flex flex-column align-items-center bg-light text-align-center flex-shrink-0 rounded shadow" style="width:252px;">
          <div class=" d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light text-decoration-none mt-2 mb-2 rounded" style="background-image: url(../assets/img/back1.jpg);background-size: cover;">
            <div class="d-flex align-items-center justify-content-center text-light text-center text-decoration-none" style="width:202px;">
              
              <strong  class="display-6">Validation</strong>
            </div>
          </div>
          <div style="height:1000px;width:235px;scrollbar-width: thin;" class="list-group bg-light list-group-flush scrollarea rounded">
            <div class="">
            <form class="d-grid" method="POST">
                <?php
                    for ($i = 0 ; $i < sizeof($result_ticket['statue_project']); $i++) {

                      if($result_ticket['statue_project'][$i]['statue'] == "en validation"){

                        
                        $bg = "bg-light";
                        for($k=0;$k<sizeof($result_ticket['statue_project'][$i]['assigne_projet']);$k++){
                          if($result_ticket['statue_project'][$i]['assigne_projet'][$k]==$_SESSION['email']){
                            $bg = "bg-primary text-white";
                          }
                        }

                          echo '<button type="button"  class="btn m-0 col text-center " data-bs-toggle="modal" data-bs-target="#myModal3'. $i.'">
                          <div class="card '.$bg.'" >
                            <div class="card-header p-0">
                          '.$result_ticket['statue_project'][$i]['nom_project'].'
                            </div>
                            <div class="card-body m-2">
                              <p class="card-text">'.substr($result_ticket['statue_project'][$i]['description_projet'], 0, 20).'...</p>
                            </div>
                          </div>
                          </button>
                  
                          <div class="modal" id="myModal3'. $i.'">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">

                                <div class="modal-header">
                                  <h4 class="modal-title">'.$result_ticket['statue_project'][$i]['nom_project'].'</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body text-start">
                                        <div class="card-body">
                                          <h5 class="card-title">Description</h5>
                                          <p class="card-text">'.$result_ticket['statue_project'][$i]['description_projet'].'</p>
                                        </div>
                                        <div class="card-body mt-2">
                                          <h5 class="card-title">Assigné</h5>


                                        <p class="card-text">';

                                        $hita_ato = false;
                                        if($result_ticket['createur'] == $_SESSION['email'] ){
                                          $hita_ato = true;
                                        }
                                        for ($j = 0 ; $j < sizeof($result_ticket['statue_project'][$i]['assigne_projet']); $j++) {
                                          echo $result_ticket['statue_project'][$i]['assigne_projet'][$j] .', ';
                                          
                                        }     
                                          echo '</p>
                                        </div>
                                        <div class="card-body">
                                          <h5 class="card-title mt-2">Carte posté</h5>
                                          <p class="card-text">'.date("d F Y",strtotime($result_ticket['statue_project'][$i]['date_post'])).'</p>
                                        </div>
                                        <div class="card-body">
                                          <h5 class="card-title mt-2">Créé par</h5>
                                          <p class="card-text">'.$result_ticket['statue_project'][$i]['crepar'].'</p>
                                        </div>
                                        <div class="card-body d-flex justify-content-between mt-2" method="POST">
                                        ';

                                        if($hita_ato){
                                          echo'<button type="submit" name="terticket" value="'.$i.'" class="btn shadow btn-success">Mise en terminer</button>';
                                        }

                                echo '</div></div>

                              </div>
                            </div>
                          </div>
                        ';

                      }
                    }
                ?>
              </form>
            </div>
          </div>
          <a class="d-flex gap-2 align-items-center flex-shrink-0 p-3 link-body-light bg-light text-decoration-none">
            <img src="../assets/img/add.png" alt="" width="20" height="20" >
            <strong style="color:black">Nouveau carte</strong>
          </a>
        </div>

        <div class="d-flex flex-column align-items-center bg-light text-align-center flex-shrink-0 rounded shadow" style="width:252px;">
          <div class=" d-flex gap-3 align-items-center flex-shrink-0 p-3 link-body-light text-decoration-none mt-2 mb-2 rounded" style="background-image: url(../assets/img/back1.jpg);background-size: cover;">
            <div class="d-flex align-items-center justify-content-center text-light text-center text-decoration-none" style="width:202px;">
              
              <strong  class="display-6">Terminer</strong>
            </div>
          </div>
          <div style="height:1000px;width:235px;scrollbar-width: thin;" class="list-group bg-light list-group-flush scrollarea rounded">
            <div class="">
              

            <form class="d-grid" method="POST">
                <?php
                    for ($i = 0 ; $i < sizeof($result_ticket['statue_project']); $i++) {

                      if($result_ticket['statue_project'][$i]['statue'] == "terminer"){

                        
                        $bg = "bg-light";
                        for($k=0;$k<sizeof($result_ticket['statue_project'][$i]['assigne_projet']);$k++){
                          if($result_ticket['statue_project'][$i]['assigne_projet'][$k]==$_SESSION['email']){
                            $bg = "bg-primary text-white";
                          }
                        }

                          echo '<button type="button"  class="btn m-0 col text-center " data-bs-toggle="modal" data-bs-target="#myModal3'. $i.'">
                          <div class="card '.$bg.'" >
                            <div class="card-header p-0">
                          '.$result_ticket['statue_project'][$i]['nom_project'].'
                            </div>
                            <div class="card-body m-2">
                              <p class="card-text">'.substr($result_ticket['statue_project'][$i]['description_projet'], 0, 20).'...</p>
                            </div>
                          </div>
                          </button>
                  
                          <div class="modal" id="myModal3'. $i.'">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">

                                <div class="modal-header">
                                  <h4 class="modal-title">'.$result_ticket['statue_project'][$i]['nom_project'].'</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body text-start">
                                        <div class="card-body">
                                          <h5 class="card-title">Description</h5>
                                          <p class="card-text">'.$result_ticket['statue_project'][$i]['description_projet'].'</p>
                                        </div>
                                        <div class="card-body mt-2">
                                          <h5 class="card-title">Assigné</h5>


                                        <p class="card-text">';

                                        $hita_ato = false;
                                        if($result_ticket['createur'] == $_SESSION['email'] ){
                                          $hita_ato = true;
                                        }
                                        for ($j = 0 ; $j < sizeof($result_ticket['statue_project'][$i]['assigne_projet']); $j++) {
                                          echo $result_ticket['statue_project'][$i]['assigne_projet'][$j] .', ';
                                          
                                        }     
                                          echo '</p>
                                        </div>
                                        <div class="card-body">
                                          <h5 class="card-title mt-2">Carte posté</h5>
                                          <p class="card-text">'.date("d F Y",strtotime($result_ticket['statue_project'][$i]['date_post'])).'</p>
                                        </div>
                                        <div class="card-body">
                                          <h5 class="card-title mt-2">Créé par</h5>
                                          <p class="card-text">'.$result_ticket['statue_project'][$i]['crepar'].'</p>
                                        </div>
                                        <div class="card-body d-flex justify-content-between mt-2" method="POST">
                                        ';

                                echo '</div></div>

                              </div>
                            </div>
                          </div>
                        ';

                      }
                    }
                ?>
              </form>


            </div>
          </div>
          <a class="d-flex gap-2 align-items-center flex-shrink-0 p-3 link-body-light bg-light text-decoration-none">
            <img src="../assets/img/add.png" alt="" width="20" height="20" >
            <strong style="color:black">Nouveau carte</strong>
          </a>
        </div>
      </main>
    </div>
  </main>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/menu-client.js"></script>
  <script>
    function boutonpourscl() {
      const textBox = document.getElementById("scrollmessage");
      textBox.scrollTo(0, textBox.scrollHeight);
    }
  </script>
</body>
</html>