<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
session_start();
if($_SESSION['session'] == 1){

    $collection = $client->gestdepro->tickets;
    $collection_coms = $client->gestdepro->commentaires;
    $newTask = [
        'nom' => $_SESSION['nom'] ,
        'description' => $_SESSION['desc'],
        'assigne' => [$_SESSION['email']],
        'date_est' => $_SESSION['dateest'],
        'createur' => $_SESSION['email'],
        'assigne_invite' => [],
        'statue_project' => []
        
    ];

    $insert = $collection->insertOne($newTask);
    $getid = $insert->getInsertedId();
    $getids = $getid->__toString();
    $newTask = [
        '_id_tickets' => $getids,
        'discution' => []
    ];
    $collection_coms->insertOne($newTask);


    $_SESSION['message'] = "Nouveau ticket est ajouter avec succes ...";
    header('Location: message.php');
}
?>
