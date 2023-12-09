<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
session_start();

if(isset($_POST['actionChoix'])) {
    if($_POST['optradio'] == "" && $_POST['actionChoix'] != "Ajouter" ){
        $_SESSION['message'] = "Vous devez selectionné au moin une tache pour y accéder ...";
        header('Location: message.php');
    }
    else if (($_POST['actionChoix'] == "Ajouter" || $_POST['actionChoix'] == "Modifier") && ($_POST['nom'] == "" && $_POST['desc'] == "")){
        $_SESSION['message'] = "Le nom ou le description de tache ne peut pas etre null ...";
        header('Location: message.php');
    }
    else {
        
        $_SESSION['actionChoix'] = $_POST['actionChoix'];
        $_SESSION['_id'] = $_POST['optradio'];
        $_SESSION['nom'] = $_POST['nom'];
        $_SESSION['desc'] = $_POST['desc'];

        header('Location: action.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="overflow-y: hidden">
    <form class="container" style="width:580px;" method="post">
        <div class="d-flex flex-column align-items-stretch flex-shrink-0 mt-5  shadow bg-light rounded-4" >
            <div class="display-6 text-center pt-3">
                Todo List
            </div>
            <hr>

            <div style="height:245px;" id="scroll" class="container list-group rounded scrollarea ps-2 " >
                <?php

                $collection = $client->todolist->tasks;
                $cursor = $collection->find();

                foreach ($cursor as $document) {
                    $id = $document['_id'];
                    $nom = $document['nom'];
                    $description = $document['description'];
                    $statue = $document['statue'];
                    if (!$statue){
                        echo '<div class="list-group list-group-radio d-grid gap-2 mt-1 " >
                    <label class="list-group-item d-flex gap-3 ">
                        <input class="form-check-input flex-shrink-0" type="radio" name="optradio" value="'.$id.'" style="font-size: 1.375em;" >
                            <span class="pt-1 form-checked-content">
                                <strong>'.$nom.'</strong>
                                <small class="d-block text-body-secondary">
                                  <svg class="bi" width="1em" height="1em"><use xlink:href="#calendar-event"></use></svg>
                                  '.$description.'
                                </small>
                          </span>
                    </label>
                </div>';
                    } else {
                        echo '<div class="list-group list-group-radio d-grid gap-2 mt-1">
                                <label class="list-group-item d-flex gap-3 bg-info text-bg-dark">
                                    <input class="form-check-input flex-shrink-0" type="radio" name="optradio" value="'.$id.'" style="font-size: 1.375em;">
                                        <span class="pt-1 form-checked-content">
                                            <strong>'.$nom.'</strong>
                                            <small class="d-block text-body-secondary">
                                            <svg class="bi" width="1em" height="1em"><use xlink:href="#calendar-event"></use></svg>
                                            '.$description.'
                                            </small>
                                    </span>
                                </label>
                            </div>';
                    }

                }


                ?>




            
            </div>

            <hr>
            <div class="input-group input-group-lg rounded-0 justify-content-center mb-3" >
                <input name="actionChoix" class="btn btn-success rounded-0" type="submit" value="Terminer">
                <input name="actionChoix" class="btn btn-primary rounded-0" type="submit" value="Modifier">
                <input name="actionChoix" class="btn btn-danger rounded-0" type="submit" value="Supprimer">
            </div>

            <div class="input-group input-group-lg rounded">
                <input type="text" class="form-control border-light bg-light" name="nom" placeholder="Nom">
                <input type="text" class="form-control border-light bg-light" name="desc" placeholder="Description">
                <input type="submit" name="actionChoix" class="btn btn-primary" value="Ajouter">
            </div>
            
        </div>
        <pre></pre>
        
    </form>
</body>
</html>