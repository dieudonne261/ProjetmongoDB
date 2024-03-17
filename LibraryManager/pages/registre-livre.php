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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $auteur = $_POST["auteur"];
    $date_edition = $_POST["dateed"];
    $code_barre = $_POST["codebarre"];
    $total = $_POST["total"];
    if($_POST["mod"] == "mod"){
        $sql = "UPDATE livres SET titre = :titre, auteur = :auteur, date_ed = :date_edition, stock = :total WHERE code_barre = :code_barre";
    }
    else{
        $sql = "INSERT INTO livres (titre, auteur,date_ed,code_barre,stock) VALUES (:titre, :auteur, :date_edition, :code_barre,:total)";
    }
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':auteur', $auteur);
    $stmt->bindParam(':date_edition', $date_edition);
    $stmt->bindParam(':code_barre', $code_barre);
    $stmt->bindParam(':total', $total);
    $stmt->execute();
}
else {
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

              <?php
              if($_POST["mod"] == "mod"){
            echo '<p class="display-5 mx-5">Nouveau Livres à été modifier</p>';
                
            }
        else{
            echo '<p class="display-5 mx-5">Un Livres à été enregistrer</p>';


        }
        ?>
                
                <hr>
                <h4 id="codebarre" class="py-4" style="font-family: codebarre; font-size:30px"><?php echo $code_barre?></h4>

                <hr>

                <a href="menu-bibli.php" type="button" class="btn-close btn-light"></a>
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
