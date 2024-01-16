<?php
session_start();
if($_SESSION['session'] == 0){
    header('Location: ../index.php');
}
$message = $_SESSION['message'] ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-body-secondary">
    <div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" tabindex="-1" role="dialog" id="modalChoice">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-3 shadow">
        <div class="modal-body p-4 text-center">
            <h5 class="mb-0">Information</h5>
            <hr>
            <p class="mb-0"><?php echo $message?></p>
        </div>
        <div class="text-center">
        <?php
        if($message == "Inscription de nouveau membre reussi ..."){
            echo '<a href="signout.php" type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"><strong>Menu principal</strong></a>';
        }
        else {
            echo '<a href="menu.php" type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"><strong>Menu principal</strong></a>';
        }
        ?>
        </div>
        </div>
    </div>
    </div>
</body>
</html>


