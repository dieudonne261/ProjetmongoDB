<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
session_start();
if($_SESSION['session'] == 1){
    $collection = $client->gestdepro->users;
    $newTask = [
        'nom' => $_SESSION['nom'] ,
        'prenom' => $_SESSION['prenom'],
        'email' => $_SESSION['email'],
        'mdp' => $_SESSION['mdp'],
        'datedenai' => $_SESSION['datedenai'],
        'avatar' => 'orange'
    ];
    $collection->insertOne($newTask);
    $_SESSION['message'] = "Inscription de nouveau membre reussi ...";
    header('Location: message.php');
}

?>
