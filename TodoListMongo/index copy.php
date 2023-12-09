<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');

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
    <div class="container" style="width:580px;">
        <div class="d-flex flex-column align-items-stretch flex-shrink-0 mt-5  shadow bg-light rounded-4" >
            <div class="display-6 text-center pt-3">
                Todo List
            </div>
            <hr>

            <div style="height:360px;" id="scroll" class="container list-group rounded scrollarea ps-2 " >
                <?php

                $collection = $client->todolist->tasks;
                $cursor = $collection->find();

                foreach ($cursor as $document) {
                    $id = $document['_id'];
                    $nom = $document['nom'];
                    $description = $document['description'];
                    $statue = $document['statue'];

                    echo '<div class="list-group-item text-center align-items-stretch">
                    <h4 class="">' . $nom . '</h4>
                    <h6>' . $description . '</h6>';
                    echo '<div action="" method="post" class="input-group justify-content-center rounded">';
                    if (!$statue){
                        echo '<button class="btn btn-success" onclick="terminer(\'' . $id . '\')">Terminer</button>
                              <button class="btn btn-primary" onclick="modifier(\'' . $id . '\')">Modifier</button>';

                    }
                    else{
                        echo '<input type="button" class="btn btn-dark" value="Déja Terminé" disabled>
                              <input type="button" class="btn btn-dark" value="Modifier" disabled>';
                    }


                    echo '<button class="btn btn-primary" onclick="supprimer(\'' . $id . '\')">Supprimer</button>';
                    echo '</div></div>';
                }


                ?>


            
            </div>

            <hr>
            <div class="input-group input-group-lg rounded">
                <input type="text" class="form-control border-light bg-light" id="resultat" placeholder="Nom">
                <input type="text" class="form-control border-light bg-light" placeholder="Description">
                <input type="submit" class="btn btn-primary" value="Ajouter">
            </div>
            
        </div>
        
    </div>
    <script>
        function terminer(value) {
            <?php
            session_start();
            $_SESSION["value"]=value;
            $_SESSION["action"]="terminer";
            header("Location: action.php");
            ?>

            /*
            $resultat = confirm("Voulez-vous vraiment terminer cette tâche ?");
            if ($resultat === true) {
                <?php
                /*echo "alert(value);";

                $collection = $client->todolist->tasks;
                $result = $collection->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId("value")],
                    ['$set' => ['statue' => 1]]
                );
                location.reload();
                */
                ?>
            }*/
        }
        function modifier(value){

        }

    </script>
</body>
</html>