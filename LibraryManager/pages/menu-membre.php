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
      <a class="navbar-brand" >Membres</a>
        <div class="d-flex align-items-center ">

          <img src="../assets/img/avatar/admin.png" height="40px" width="40px"  alt="" srcset="">
          <h6 class="mt-1 text-warning p-2"> <?php echo $_SESSION['email']?></h6>
          <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
          </button>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
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
          <p class="display-3 mx-5">Notification</p>
        </button>
  </div>
  <div class="tab-content shadow rounded m-2 justify-content-center" id="nav-tabContent">

    <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      
      <p class="display-3 mt-4">Livre emprunté</p>
      <hr>
      <table class="table table-dark table-borderless text-center rounded">
          <thead>
          <tr class="h4">
            <th scope="col" >Titre</th>
            <th scope="col" >Auteur</th>
            <th scope="col" >Code</th>
            <th scope="col" >Date limite</th>
            <th scope="col" >Statue</th>
          </tr>
          </thead>
          <tbody>
          <?php
          $sql = "SELECT livres.*,emprunts.* FROM livres,emprunts WHere emprunts.code_barre = livres.code_barre and emprunts.email = '".$_SESSION['email']."'";
          $stmt = $db->query($sql);

            if ($stmt) {
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td class='lead'>" . $row['titre'] . "</td>";
                echo "<td class='lead'>" . $row['auteur'] . "</td>";
                echo "<td class='lead'>" . $row['id_emprunts'] . "</td>";
                echo "<td class='lead'>" . date('Y-m-d', strtotime($row['date_emp'] . '+7 days')) . "</td>";
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
    </div>


    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
      <div >
        <?php
          //$sql = "SELECT * FROM notifications ";
          $sql = "SELECT * FROM notifications WHERE a = '" . $_SESSION['email'] . "'";

          $stmt = $db->query($sql);

            if ($stmt) {
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo '<div class="card bg-dark text-white shadow m-2">
              <div class="card-body m-2"><p class="lead" style="position: relative;top: 15px;">'. $row['de'] .'</p><p class="display-6">'. $row['messages'] .'</p><p class="lead float-left" style="position: relative;top: 0px;">'. $row['date_env'] .'</p>
              </div>
            </div>'  ;
              }
            } 
            else {
              echo "Aucun livre trouvé.";
            }
          ?>
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