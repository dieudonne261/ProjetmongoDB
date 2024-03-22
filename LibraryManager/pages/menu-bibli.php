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


if (isset($_POST['emp'])) {
  $codebarre = $_POST['codebarreemp'];
  $email = $_POST['emailemp'];
  $stmt_livres = $db->prepare("SELECT * FROM livres WHERE code_barre = :codebarre");
  $stmt_livres->execute(array(':codebarre' => $codebarre));
  $livre_exist = $stmt_livres->fetch();
  $stmt_users = $db->prepare("SELECT * FROM users WHERE email = :email");
  $stmt_users->execute(array(':email' => $email));
  $user_exist = $stmt_users->fetch();

  if ($livre_exist && $user_exist) {
    $_SESSION['codebarre'] = $codebarre;
    $_SESSION['email2'] = $email;
    header("Location: registre-emprunt.php");
  } else {
    echo "alert('Code barre ou email non trouvée');</script>";
  }
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
  <script src="../assets/js/pdf.js"></script>
  <script src="../assets/js/pdf2.js"></script>
  
  <script src=""></script>
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
            <?php
            if(($_SESSION['role']) == 'Admin') {
                echo '<li><a class="dropdown-item" href="menu.php">Menu de Administrateur</a></li>';
                echo '<li><hr class="dropdown-divider"></li>';
            }
            ?>
            <li><a class="dropdown-item" href="signout.php">Se deconnecter</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

<main class="bg-dark text-center"  style="padding-top: 75px;">

  <div class="d-flex gap-5 justify-content-center py-2" id="nav-tab" role="tablist">
        <button class="btn btn-dark btn-lg shadow active"  id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
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
      <div class="d-flex gap-5 justify-content-center py-2" >
        <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#nouveau">
          <p class="display-5 mx-5">Nouveau</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#editer">
          <p class="display-5 mx-5">Editer</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border"  type="button" data-bs-toggle="modal" data-bs-target="#recherche">
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
                      <input type="text" class="form-control form-control-lg form-dark" placeholder="Titre" name="titre" required>
                      <input type="text" class="form-control form-control-lg" placeholder="Auteur" name="auteur" required>
                  </div>
                                
                  <div class="mb-3 mt-3 d-flex gap-2">
                      <input type="date" class="form-control form-control-lg" placeholder="Date d'édition" name="dateed" required>
                      <input type="number" class="form-control form-control-lg" min="1" value="1" name="total" required>
                  </div>
                  
                  <div class="pt-3 mb-4 ">
                      <h4 id="codebarre" style="font-family: codebarre; font-size:30px">1234567891011</h4>
                      <input type="hidden" name="codebarre" id="codebarre_hidden" value="">
                  </div>
                                
                  <button type="button" id="rudomButton" class="btn btn-light">Generer code barre</button>
                  <hr>
                  <button type="submit" id="submitButton" name="mod" value="" class="btn btn-primary" style="display:none;">Registrer</button>
                  
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


        <div class="modal fade" id="editer">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Edition Livres</p>
                <hr>
                <form action="recherche-livre.php" method="POST">
                  <div class="input-group mb-3 input-group-lg">
                    <input type="text" class="form-control form-control-lg" placeholder="Code barre" name="code_barre" id="">
                    <button class="btn btn-dark shadow" type="submit">Rechercher</button>
                  </div>
                </form>



              </div>


            </div>
          </div>
        </div>

        <div class="modal fade" id="recherche">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Recherche Livres</p>
                <hr>
                <form action="recherche-livre-filtre.php" method="POST">
                  <div class="input-group mb-3 input-group-lg">
                    <input type="text" class="form-control form-control-lg" placeholder="" name="code_barre" id="" required>
                    <button class="btn btn-dark shadow" type="submit">Rechercher</button>
                  </div>
                  <div class="d-flex gap-3 text-center justify-content-center">
                  <div class="form-check">
                    <input type="radio" class="form-check-input" id="radio1" name="optradio" value="WHERE titre like " checked>Nom
                    <label class="form-check-label" for="radio1"></label>
                  </div>
                  <div class="form-check">
                    <input type="radio" class="form-check-input" id="radio2" name="optradio" value="WHERE auteur like ">Auteur
                    <label class="form-check-label" for="radio2"></label>
                  </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>



        
      </div>
      <hr>
      <table class="table table-dark table-borderless text-center rounded" id="invoice">
          <thead>
          <tr class="h4">
            <th scope="col" >Titre</th>
            <th scope="col" >Auteur</th>
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
              echo "<td class='lead'>" . $row['titre'] . "</td>";
              echo "<td class='lead'>" . $row['auteur'] . "</td>";
              echo "<td class='lead'>" . $row['date_ed'] . "</td>";
              echo "<td style='font-family: codebarre; font-size:15px'>" . $row['code_barre'] . "</td>";
              echo "</tr>";
            }
          } 
          else {
            echo "Aucun livre trouvé.";
          }
          ?>
          </tbody>
      </table>


        

        <button type="button" class="btn btn-primary m-2"  id="download">Enregister en pdf</button>
      

    </div>


    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
    
      <p class="display-3 mt-4">Menu Emprunt</p>
      <hr>
      <div class="d-flex gap-4 justify-content-center py-2" >
        <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#nouveau2">
          <p class="display-5 mx-5">Nouveau</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#retour">
          <p class="display-5 mx-5">Retour</p>
        </button>
        <button class="btn btn-dark btn-lg rounded border"  type="button" data-bs-toggle="modal" data-bs-target="#retard">
          <p class="display-5 mx-5">Retard</p>
        </button>


        <div class="modal fade " id="nouveau2">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Nouveau Livres</p>
                <hr>
                <form method="POST">
                  <div class="d-flex gap-2 mt-3">
                      <input type="text" class="form-control form-control-lg form-dark" placeholder="Code barre" maxlength="13" name="codebarreemp" required>
                  </div>  
                  <div class="mb-3 mt-3 d-flex gap-2">
                      <input type="mail" class="form-control form-control-lg" placeholder="Email" name="emailemp" required>
                  </div>      
                  <hr>
                  <button type="submit" name="emp" value="" class="btn btn-primary" >Suivant</button>
                  
                </form>

              </div>


            </div>
          </div>
        </div>


        <div class="modal fade" id="retour">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Retour Livres</p>
                <hr>
                <form action="retour.php" method="POST">
                  <div class="input-group mb-3 input-group-lg">
                    <input type="text" class="form-control form-control-lg" placeholder="Code" name="codeemp" id="">
                    <button class="btn btn-dark shadow" type="submit">Rechercher</button>
                  </div>
                </form>



              </div>


            </div>
          </div>
        </div>

        

        <div class="modal fade" id="retard">
              <div class="modal-dialog modal-xl modal-dialog-centered" >
                <div class="modal-content bg-dark">
                  <div class="modal-body text-center">
                    <table class="table table-dark" id="invoice3">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Email</th>
                                <th>Date limite</th>
                            </tr>
                        </thead>
                        <tbody>
                          <form action="rappel.php" method="post">
                            <?php
                                $sql = "SELECT * FROM emprunts where date_retour=''";
                                $stmt = $db->query($sql);
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $newdate = date('Y-m-d', strtotime($row['date_emp'] . ' + 7 days'));
    
                                    if(strtotime($newdate) < strtotime(date('Y-m-d'))){
                                      echo "<tr>";
                                      echo "<td>".$row['id_emprunts']."</td>";
                                      echo "<td>".$row['email']."</td>";
                                      echo "<td>".$newdate."</td>";
                                      echo "<td><button type='submit' class='btn btn-dark btn-sm' name='rap' value='".$row['email']."'>Rappel</button></td>";
                                      echo "</tr>";
                                    }
                                    
                                }
                            ?>
                            
                            </form>
                        </tbody>
                    </table>
                  </div>


                </div>
              </div>
            </div>



        <div class="modal fade" id="rech">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content bg-dark">
              <div class="modal-body">
                <p class="display-5 mx-5">Recherche Emprunt</p>
                <hr>
                <form action="recherche-emprunt.php" method="POST">
                  <div class="input-group mb-3 input-group-lg">
                    <input type="text" class="form-control form-control-lg" placeholder="Code emprunt" name="code_emprunt" maxlength="5" required>
                    <button class="btn btn-dark shadow" type="submit">Rechercher</button>
                  </div>
                </form>



              </div>


            </div>
          </div>
        </div>



        
      </div>
      <hr>
      <table class="table table-dark table-borderless text-center rounded" id="invoice2">
          <thead>
          <tr class="h4">
            <th scope="col" >Email</th>
            <th scope="col" >Code</th>
            <th scope="col" >Date d'emprunt</th>
            <th scope="col" >Statues</th>
          </tr>
          </thead>
          <tbody>
          <?php
          $sql = "SELECT * FROM emprunts";
          $stmt = $db->query($sql);

          if ($stmt) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr >";
              echo "<td class='lead'>" . $row['email'] . "</td>";
              echo "<td class='lead'>" . $row['id_emprunts'] . "</td>";
              echo "<td class='lead'>" . $row['date_emp'] . "</td>";
              echo "<td class='lead'>";
              if ($row['date_retour'] == "") {
                $dateLimite = date('Y-m-d', strtotime($row['date_emp'] . '+7 days'));
                if (strtotime($dateLimite) < strtotime(date('Y-m-d'))) {
                    echo "En retard";
                } else {
                    echo "En cours";
                }
              } else {
                  echo "Remis";
              }
              
              echo "</td>";
              echo "</tr>";
            }
          } 
          else {
            echo "Aucun livre trouvé.";
          }
          ?>
          </tbody>
      </table>

      <button type="button" class="btn btn-primary mb-4"  id="download2">Enregister en pdf</button>
      
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