
<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection = $client->todolist->tasks;

session_start();
$action = $_SESSION['actionChoix'] ;
$_id = $_SESSION['_id'] ;
$nom = $_SESSION['nom'] ;
$desc = $_SESSION['desc'] ;
if ($action == "Ajouter"){

    $newTask = [
        'nom' => $nom,
        'description' => $desc,
        'statue' => 0
    ];

    $collection->insertOne($newTask);

    $_SESSION['message'] = "Nouveau task à été avec succes ...";
    header('Location: message.php');
}
else if ($action == "Modifier"){

    $newTask = [
        '$set' => [
            'nom' => $nom,
            'description' => $desc
        ]
    ];

    $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_id)], $newTask);

    $_SESSION['message'] = "Modification du tark reussi ...";
    header('Location: message.php');
}
else if ($action == "Supprimer"){

    $collection = $client->todolist->tasks;
    $result = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_id)]);

    $_SESSION['message'] = "Suppression du task terminer ...";
    header('Location: message.php');
}
else if ($action == "Terminer"){

    $task = $collection->findOne([
        '_id' => new MongoDB\BSON\ObjectId($_id),
        'statue' => 1
    ]);

    if ($task) {
        $_SESSION['message'] = "Ce task sélectionné est déja terminé ...";

    }
    else {
        $newTask = [
            '$set' => [
                'statue' => 1
            ]
        ];

        $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($_id)], $newTask);
        $_SESSION['message'] = "Task términer avec succés ...";
    }

    header('Location: message.php');
}

?>
