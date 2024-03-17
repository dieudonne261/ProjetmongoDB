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
          <h1 class="display-1">0000</h1>
        </div>
        <div class="col text-start">
          <h4 class="display-5">Livres disponibles</h4>
          <h1 class="display-1">0000</h1>
        </div>
        <div class="col text-start">
          <h4 class="display-5">Livres empruntées</h4>
          <h1 class="display-1">0000</h1>
        </div>
      </div>
    </div> 

    <button type="button" class="btn btn-dark btn-lg border" data-bs-toggle="modal" data-bs-target="#myModal">
      Voir plus
    </button>

    <div class="modal fade " id="myModal">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content bg-dark">
      <div class="modal-body">
        Modal body..
      </div>
    </div>
  </div>
</div>



  </div>

  <div class="p-5 rounded shadow">
    <div class="text-center">
      <div class="row">
        <div class="col text-start">
          <h4 class="display-5">Total Membres</h4>
          <h1 class="display-1">0000</h1>
          <hr>
        </div>
        <div class="col text-start">
          <h4 class="display-5">Livres Bibliothécaire</h4>
          <h1 class="display-1">0000</h1>
          <hr>
        </div>
        <div class="col text-start">
          <div class="d-grid gap-2 px-5">
            <p class="display-6 mt-2">Nouveau</p>
            <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#membre">
              <p class="display-6 mt-2">Membre</p>
            </button>
            <button class="btn btn-dark btn-lg rounded border"  type="button" data-bs-toggle="modal" data-bs-target="#bibli">
              <p class="display-6 mt-2">Bibliothécaire</p>
            </button>

            <div class="modal fade" id="membre">
              <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content bg-dark">
                  <div class="modal-body text-center">
                    <p class="display-5 mx-5">Edition Livres</p>
                    <hr>
                    <form action="recherche-livre.php" method="POST">
                      <div class="input-group mb-3 input-group-lg">
                        <input type="text" class="form-control form-control-lg" placeholder="Code barre" name="code_barre" id="" required>
                        <button class="btn btn-dark shadow" type="submit">Rechercher</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="bibli">
              <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content bg-dark">
                  <div class="modal-body text-center">
                    <p class="display-5 mx-5">Edition Livres</p>
                    <hr>
                    <form action="recherche-livre.php" method="POST">
                      <div class="input-group mb-3 input-group-lg">
                        <input type="text" class="form-control form-control-lg" placeholder="Code barre" name="code_barre" id="" required>
                        <button class="btn btn-dark shadow" type="submit">Rechercher</button>
                      </div>
                    </form>
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
</html>