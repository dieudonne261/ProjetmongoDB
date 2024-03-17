<?php

try {
    $db = new PDO('pgsql:host=localhost;dbname=librarymanager', 'postgres', '');
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

session_start();

if(is_null($_SESSION['session'])){
    $_SESSION['session'] = 0;
}

if($_SESSION['session'] == 1){
    header('Location: pages/menu.php');
}

if(isset($_POST['login'])) {
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND mdp = :mdp");
    $stmt->execute(array('email' => $_POST['email'], 'mdp' => $_POST['mdp']));
    $result = $stmt->fetch();
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
<body class="text-dark" style="background-color: rgb(51, 51, 51);">
    <div class="login-page">
        <div class="form1">
            <form method="POST" class="login-form" >
                <h2 class="text-dark mb-5">S'identifier</h2>
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control rounded-3 border-light" id="floatingName" placeholder="" required>
                    <label for="floatingName">Email</label>
                </div>
                <div class="form-floating mb-5">
                    <input type="password" class="form-control rounded-3 border-light" name="mdp" id="mdp" minlength="6" maxlength="8" placeholder="" required >
                    <label for="floatingPassword">Mots de passe</label>
                </div>
                <input type="submit" class="button mb-3" name="login" value="Se connecter">
                <a class="text-dark">Mot de passe oublier ?</a>
                <hr  class="mt-3">
            </form>
        </div>
        <img src="assets/img/testback.png" width="400px" height="500px" alt="" sizes="" srcset="">
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu-client.js"></script>
</body>
</html>