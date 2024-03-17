<?php
try {
  $db = new PDO('pgsql:host=localhost;dbname=librarymanager', 'postgres', '');
} catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}

session_start();

if(isset($_POST['login'])) {
  $_SESSION['nom'] = $_POST['nom'];
  $_SESSION['prenom'] = $_POST['prenom'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['mdp'] = $_POST['mdp'];
  $_SESSION['mdp2'] = $_POST['mdp2'];
  $_SESSION['telephone'] = $_POST['telephone'];
  $_SESSION['datedenai'] = $_POST['datedenai'];
    if($_POST['mdp']==$_POST['mdp2']){
      $result = $collection->findOne(['email' => $_POST['email']]);
      if($result) {
        echo '<script>alert("Votre pseudo enter déjas existe, veuillez se connecter...");</script>';
      }
      else {
        $_SESSION['session'] = 1;
        header('location: signin-action.php');
      }
    }
    else {
      echo '<script>alert("Veuillez bien confirmer votre mots de passe...");</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/signin.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="text-dark">
  <form class="login-page" method="post">
    <div class="form2" >
      <div class="form3">
        <div class="login-form" >
          <h2 class=" mb-3">S'inscrire</h2>
          <div class="form-floating mb-3">
            <input type="text" name="nom" class="form-control rounded-3 border-light" id="floatingName" minlength="1" maxlength="100" placeholder="" required>
            <label for="floatingName">Nom</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control rounded-3 border-light" name="prenom" id="floatingSur" placeholder="" >
            <label for="floatingSur">Prenom</label>
          </div>
          <div class="form-floating mb-3">
            <input type="email" class="form-control rounded-3 border-light" name="email" id="floatingEmail" placeholder="" required >
            <label for="floatingEmail">Email</label>
          </div>
          <hr  class="mt-5">
          <input type="submit" class="button mt-4" name="login" value="S'inscrire">
        </div>
      </div>

      <div class="form1">
        <div class="login-form" >
          <hr class="py-3 mt-1">
            <select class="form-select p-3 rounded-3 border-light mb-3" id="exampleSelect">
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select>
          <div class="form-floating mb-3">
            <input type="password" class="form-control rounded-3 border-light" name="mdp" id="floatingPassword" minlength="6" maxlength="8" placeholder="" required>
            <label for="floatingPassword">Mots de passe</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control rounded-3 border-light" name="mdp2" id="floatingPassword" minlength="6" maxlength="8" placeholder="" required>
            <label for="floatingPassword">Confirmer</label>
          </div>
          <a href="../index.php" class="btn btn-link mt-1 ">J'ai deja un compte...</a>
        </div>
      </div>
    </div>
  </form>


  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/menu-client.js"></script>
</body>
</html>