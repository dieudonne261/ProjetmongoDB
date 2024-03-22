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
        <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#editer">
          <p class="display-5 mx-5">Editer</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border">
          <p class="display-5 mx-5">Recherche</p>
        </button>


        <div class="modal fade " id="nouveau">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Nouveau Livres</p>
                <hr>
                <form action="registre-livre.php" method="POST">
                  <div class="d-flex gap-2 mt-3">
                      <input type="text" class="form-control form-control-lg form-dark" placeholder="Titre" name="titre" required="">
                      <input type="text" class="form-control form-control-lg" placeholder="Auteur" name="auteur" required="">
                  </div>
                                
                  <div class="mb-3 mt-3 d-flex gap-2">
                      <input type="date" class="form-control form-control-lg" placeholder="Date d'édition" name="dateed" required="">
                      <input type="number" class="form-control form-control-lg" min="1" value="1" name="total" required="">
                  </div>
                  
                  <div class="pt-3 mb-4 ">
                      <h4 id="codebarre" style="font-family: codebarre; font-size:30px">1234567891011</h4>
                      <input type="hidden" name="codebarre" id="codebarre_hidden" value="">
                  </div>
                                
                  <button type="button" id="rudomButton" class="btn btn-light">Generer code barre</button>
                  <hr>
                  <button type="submit" id="submitButton" class="btn btn-primary" style="display:none;">Registrer</button>
                  
              </form>

              <script>
                  document.getElementById('rudomButton').addEventListener('click', function() {
                      var randomCode = '';
                      for (var i = 0; i < 13; i++) {
                          randomCode += Math.floor(Math.random() * 10);
                      }
                      document.getElementById('codebarre').innerText = randomCode;
                      document.getElementById('codebarre_hidden').value = randomCode;
                      document.getElementById('submitButton').style.display = 'inline-block';
                  });
              </script>


              </div>


            </div>
          </div>
        </div>


        <div class="modal fade show" id="editer" aria-modal="true" role="dialog" style="display: block;">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Edition Livres</p>

                <hr>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (!empty($_POST['code_barre'])) {
                        $code_barre = htmlspecialchars($_POST['code_barre']);
                        $sql = "SELECT * FROM livres WHERE code_barre = :code_barre";

                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':code_barre', $code_barre);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                echo "
                                <form action='registre-livre.php' method='POST'>
                                    <div class='d-flex gap-2 mt-3'>
                                        <input type='text' class='form-control form-control-lg form-dark' placeholder='Titre' value='" . $row['titre'] . "' name='titre' required>
                                        <input type='text' class='form-control form-control-lg' placeholder='Auteur' value ='" . $row['auteur'] . "' name='auteur' required>
                                    </div>
                                                    
                                    <div class='mb-3 mt-3 d-flex gap-2'>
                                        <input type='date' class='form-control form-control-lg' placeholder='Date d'édition' value='" . $row['date_ed'] . "' name='dateed' required>
                                        <input type='number' class='form-control form-control-lg' min='1' value='" . $row['stock'] . "' name='total' required>
                                    </div>
                                    
                                    <div class='pt-3 mb-4 '>
                                        <h4 id='codebarre' style='font-family: codebarre; font-size:30px'>" . $row['code_barre'] . "</h4>
                                        <input type='hidden' name='codebarre' id='codebarre_hidden' value='" . $row['code_barre'] . "'>
                                    </div>
                                                    
                                    <hr>
                                    <button type='submit' id='submitButton' class='btn btn-primary' value='mod' name='mod'>Modifier</button>
                                    
                                </form>

                                
                                "
                                ;
                            }
                        } else {
                            echo "<p class='display-6'>Aucun résultat trouvé pour le code barre: " . $code_barre . "</p>";
                        }
                    } else {
                        echo "<p class='display-6'>Veuillez entrer un code barre valide.</p>";
                    }
                    
                }
                else {
                    header('Location: menu-bibli.php');
                }
                ?>


                <a href="menu-bibli.php" type="button" class="btn-close btn-light"></a>
              </div>


            </div>
          </div>
        </div>



        
      </div>
      <hr>
      <table class="table table-dark table-borderless  justify-content-center">
          <thead>
          <tr class="display-6">
            <th scope="col" >Titre</th>
            <th scope="col" >Autre</th>
            <th scope="col" >Date d'edition</th>
            <th scope="col" >Code barre</th>
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