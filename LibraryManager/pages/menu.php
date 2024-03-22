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
  <script src="../assets/js/pdf2.js"></script>
  <script src="../assets/js/pdf.js"></script>

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
          $totalmembres = $db->query("select count(*) from users where role='Membre'")->fetchColumn();
          $totalbiblis = $db->query("select count(*) from users where role='Bibli'")->fetchColumn();
          $totaladmi = $db->query("select count(*) from users where role='Admin'")->fetchColumn();
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
        <hr>
          <h4 class="lead">Total Membres</h4>
          <h1 class="display-1"><?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totalmembres) . '</h1>';
          ?></h1>
          <hr>
        </div>
        <div class="col text-start">
        <hr>
          <h4 class="lead">Livres Bibliothécaire</h4>
          <h1 class="display-1"><?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totalbiblis) . '</h1>';
          ?></h1>
          <hr>
        </div>
        <div class="col text-start">
        <hr>
          <h4 class="lead">Livres Administrateur</h4>
          <h1 class="display-1"><?php
          echo '<h1 class="display-1">' . sprintf("%04d", $totaladmi) . '</h1>';
          ?></h1>
          <hr>
        </div>
        <div class="col text-start">
          <div class="d-grid gap-2 px-5">
            <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#nouveau">
              <p class="display-6 mt-2">Nouveau</p>
            </button>
            <button class="btn btn-dark btn-lg rounded border" type="button" data-bs-toggle="modal" data-bs-target="#voir">
              <p class="display-6 mt-2">Voir plus</p>
            </button>

            <div class="modal fade" id="voir">
              <div class="modal-dialog modal-xl modal-dialog-centered" >
                <div class="modal-content bg-dark">
                  <div class="modal-body text-center">
                    <table class="table table-dark" id="invoice3">
                        <thead>
                            <tr class="display-6">
                                <th>ID</th>
                                <th>Nom et Prenom</th>
                                <th>Email</th>
                                <th>Mdp</th>
                                <th>Rôle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT * FROM users";
                                $stmt = $db->query($sql);
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr class='lead'>";
                                    echo "<td>".$row['id']."</td>";
                                    echo "<td>".$row['nom']."</td>";
                                    echo "<td>".$row['email']."</td>";
                                    echo "<td>".$row['mdp']."</td>";
                                    echo "<td>".$row['role']."</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                  </div>


                </div>
              </div>
            </div>

            <div class="modal fade" id="nouveau">
              <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content bg-dark">
                  

                  


                <div class="modal-body text-center">
                <p class="display-5">Nouveau</p>
                  <hr>
                <form action="registre-users.php" method="POST">
                  <div class="d-flex gap-2 mt-3">
                      <input type="text" class="form-control form-control-lg form-dark" placeholder="Nom et Prenom" name="nompre" required>
                  </div>
                                
                  <div class="mb-3 mt-3 d-flex gap-2">
                      <input type="email" class="form-control form-control-lg" placeholder="Email" name="emailreg" required>
                  </div>
                  
                  <div class="d-flex gap-2 mt-3">
                    <input type="password" class="form-control form-control-lg" maxlength="6" linlength="4" name="mdp"  placeholder="Mots de passe" required>
                    <select class="form-select form-select-lg" id="role" name="role">     
                      <option value="Admin">Administrateur</option>
                      <option value="Bibli">Bibliothécaire</option>
                      <option value="Membre">Membres</option>
                    </select>
                  </div>
                                
                  <hr>
                  <button type="submit"  name="registre" class="btn btn-primary w-100" >Registrer</button>
                  
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