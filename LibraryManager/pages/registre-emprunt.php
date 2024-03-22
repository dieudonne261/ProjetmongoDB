<?php
$host = "localhost";
$port = "5432";
$dbname = "librarymanager";
$user = "postgres";
$password = "";

$db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

if ($db === false) {
    die("Erreur de connexion à la base de données");
}

session_start();

if(isset($_SESSION['codebarre']) && isset($_SESSION['email'])) {
    $codebarre = $_SESSION['codebarre'];
    $email2 = $_SESSION['email2'];
} else {
  header('Location: menu-bibli.php');
}

?>
<html lang="en"><head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link rel="shortcut icon" href="../assets/img/icon1.png" type="image/x-icon">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    @font-face {
    font-family: codebarre;
    src: url(../assets/font/ciacode39_m.ttf);
    }

  </style>
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
<body class="bg-dark text-light modal-open" data-bs-overflow="hidden" data-bs-padding-right="0px" style="overflow: hidden; padding-right: 0px;">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark m-2 shadow rounded">
    <div class="container-fluid">
      <a class="navbar-brand">Library Manager</a>
        <div class="d-flex align-items-center ">

          <img src="../assets/img/avatar/admin.png" height="40px" width="40px" alt="" srcset="">
          <h6 class="mt-1 text-warning p-2"> admin@gmail.com</h6>
          <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
    </button>
    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
      <li><a class="dropdown-item" href="menu.php">Menu de Administrateur</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="signout.php">Se deconnecter</a></li>
    </ul>
        </div>
      </div>
    
  </nav>

<main class="bg-dark text-center" style="padding-top: 75px;">

  <div class="d-flex gap-5 justify-content-center py-2" id="nav-tab" role="tablist">
        <button class="btn btn-dark btn-lg shadow active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
          <p class="display-3 mx-5">Livre</p>
        </button>
        <button class="btn btn-dark btn-lg shadow" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false" tabindex="-1">
          <p class="display-3 mx-5">Emprunt</p>
        </button>
  </div>
  <div class="tab-content shadow rounded m-2 justify-content-center" id="nav-tabContent">

    <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      
      <p class="display-3 mt-4">Menu Livre</p>
      <hr>
      <div class="d-flex gap-5 justify-content-center py-2">
        <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#nouveau">
          <p class="display-5 mx-5">Nouveau</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border">
          <p class="display-5 mx-5">Editer</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border">
          <p class="display-5 mx-5">Recherche</p>
        </button>


        <div class="modal fade show" id="nouveau" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <form action="emprunt-nouveau.php" method="post">
                  
                  <?php

                  $sql = "SELECT * FROM emprunts WHERE email = :email AND date_retour = ''";
                  $stmt = $db->prepare($sql);
                  $stmt->bindParam(':email', $email2);
                  $stmt->execute();
                  if ($stmt->rowCount() > 0) {
                    echo '<p class="display-6 mt-2">Cet utilsateur n'."'".'a pas encore remis un livre</p>';
                    echo '<hr>
                    <div class="d-flex gap-2 mt-2" >
                      <a href="menu-bibli.php" type="button" class="btn btn-danger w-100">Annuler</a>
                      <input type="hidden" name="email" value="'.$email2.'">
                      <input type="hidden" name="isRappel" value="true">
                      <button type="submit" class="btn btn-primary w-100 ">Rappel</button>
                    </div>';
                  }
                  else{

                    $now = date("Y-m-d H:i:s");
                    $random_chars = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
                    $nowgen = $random_chars;
                    $sql = "SELECT * FROM livres WHERE code_barre = :codebarre";
                    $isMety = true;

                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':codebarre', $codebarre);
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            if ($row['stock']==1){
                              $isMety = false;
                            }
                            echo "
                            <div >

                                <div class='d-flex gap-2 mt-3'>
                                    <input type='text' class='form-control form-control-lg form-dark' placeholder='Titre' value='" . $row['titre'] . "' name='titre' disabled>
                                    <input type='text' class='form-control form-control-lg' placeholder='Auteur' value ='" . $row['auteur'] . "' name='auteur' disabled>
                                </div>
                                                
                                <div class='mb-3 mt-3 d-flex gap-2'>
                                <input type='hidden' name='isRappel' value='false'>
                                    <input type='date' class='form-control form-control-lg' placeholder='Date d'édition' value='" . $row['date_ed'] . "' name='dateed' disabled>
                                    <input type='number' class='form-control form-control-lg' min='1'  value='1' max='" . $row['stock']-1 . "' name='qteempr' required>
                                    <input type='hidden' value='" . $row['code_barre'] . "' name='cbempr' required>
                                </div>
                                <h5 class='text-white text-uppercase fw-bold'>Stock restant : ".$row['stock']."</h5>
                                                
                                
                                
                            </div>

                            
                            "
                            ;
                        }
                    }




                    $sql = "SELECT * FROM users WHERE email = :email";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "
                            <div >
                              <input type='text' class='form-control form-control-lg form-dark mt-2' value='" . $row['nom'] . "' name='nom' disabled>
                              <input type='text' class='form-control form-control-lg form-dark mt-2' value='" . $row['email'] . "' name='' disabled>
                              <input type='hidden' class='form-control form-control-lg form-dark mt-2' value='" . $row['email'] . "' name='emailempr'>
                              <input type='hidden' class='form-control form-control-lg form-dark mt-2' value='" . $now . "' name='dateempr' >
                              <input type='hidden' class='form-control form-control-lg form-dark mt-2' value='" . $nowgen . "' name='idempr'>
                            </div>

                            
                            "
                            ;
                        }
                    }
                    echo '<hr>
                    <div class="d-flex gap-2">
                      <a href="menu-bibli.php" type="button" class="btn btn-danger w-100">Annuler</a>

                      <button type="submit"';if (!$isMety) echo 'disabled'; echo 'class="btn btn-primary w-100" value="false" >Enregister</button>
                    </div>';
                    
                  }
                  ?>
                  
                  
                    
                </form>


              </div>


            </div>
          </div>
        </div>

        
      </div>
      <hr>
      <table class="table table-dark table-borderless">
          <thead>
          <tr class="display-6">
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
          </tr>
          </thead>
          <tbody>
          <?php
          $sql = "SELECT titre, auteur, date_ed, code_barre FROM livres";

          $stmt = $db->query($sql);

          if ($stmt) {
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td class='h4'>" . $row['titre'] . "</td>";
                  echo "<td class='h4'>" . $row['auteur'] . "</td>";
                  echo "<td class='h4'>" . $row['date_ed'] . "</td>";
                  echo "<td style='font-family: codebarre; font-size:15px'>" . $row['code_barre'] . "</td>";
                  echo "</tr>";
              }
          } else {
              echo "Aucun livre trouvé.";
          }
          ?>
          </tbody>
        </table>
      

    </div>


    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
    
      <p class="display-3 mt-4">Menu Emprunt</p>
      <hr>
    </div>


  </div>

  
</main>

  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/menu-client.js"></script>


<div class="modal-backdrop fade show"></div><div class="modal-backdrop fade show"></div><div class="modal-backdrop fade show"></div><div class="modal-backdrop fade show"></div></body></html>
