<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection = $client->gestdepro->users;

session_start();

if(is_null($_SESSION['session'])){
    $_SESSION['session'] = 0;
}

if($_SESSION['session'] == 1){
  header('Location: pages/menu.php');
}

if(isset($_POST['login'])) {
    $filter = ['email' => $_POST['email'], 'mdp' => $_POST['mdp']];
    $result = $collection->findOne($filter);

    if ($result) {
        $_SESSION['email']=$_POST['email'];
        $_SESSION['session'] = 1;
        header('Location: pages/menu.php');
    } else {
        echo '<script>alert("Votre pseudo ou mot de passe est incorrect ...");</script>';
        $_SESSION['session'] = 0;
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
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="login-page">
        <div class="form1" style="background-color: rgba(0, 0, 0, 0.2);">
            <form method="POST" class="login-form " >
                <h2 class="text-light mb-3">S'identifier</h2>
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control rounded-3 border-light" id="floatingName" placeholder="" required>
                    <label for="floatingName">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3 border-light" name="mdp" id="mdp" minlength="6" maxlength="8" placeholder="" required >
                    <label for="floatingPassword">Mots de passe</label>
                </div>
                <input type="submit" class="button mb-3" name="login" value="Se connecter">
                <a class="text-light">Mot de passe oublier ?</a>
                <hr  class="mt-3">
                <a type="button" href="pages/signin.php" class="button text-decoration-none mt-4" name="login">S'inscrire</a>
            </form>
        </div>
        <img src="assets/img/test.jpg" width="400px" height="500px" alt="" sizes="" srcset="">

    </div>
    
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu-client.js"></script>
</body>
</html>