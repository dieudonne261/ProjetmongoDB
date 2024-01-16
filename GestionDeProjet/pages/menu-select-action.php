<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection_users = $client->gestdepro->users;
$collection_tickets = $client->gestdepro->tickets;
$collection_coms = $client->gestdepro->commantaires;

session_start();
if($_SESSION['session'] == 1){
    $criteria = ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id_tickets'])];

    if($_SESSION['action']=='delassigne'){
        $update = [
            '$pull' => ['assigne' => $_SESSION['id_email']]
        ];
        $result = $collection_tickets->updateOne($criteria, $update);
        $_SESSION['message'] = "Email assigné sumprimer ...";
    }

    if($_SESSION['action']=='delassigneinvite'){
        $update = [
            '$pull' => ['assigne_invite' => $_SESSION['id_email']]
        ];
        $result = $collection_tickets->updateOne($criteria, $update);
        $_SESSION['message'] = "Invitation annuler ...";

    }

    if($_SESSION['action']=='addemail'){
        $update = [
            '$addToSet' => ['assigne_invite' => $_SESSION['id_email']]
        ];
        $result = $collection_tickets->updateOne($criteria, $update);
        $_SESSION['message'] = "Invitation envoyer ...";

    }

    if($_SESSION['action']=='addemailassigne'){
        $datemain = new DateTime();
        $new = [
            'nom_project' => $_SESSION['nom_projet'],
            'description_projet' => $_SESSION['desc_projet'],
            'date_post' => $datemain->format('Y-m-d'),
            'assigne_projet' => $_SESSION['emailassigne'],
            'statue' => 'a faire',
            'crepar' => $_SESSION['email']
        ];


        $update = [
            '$addToSet' => ['statue_project' => $new]
        ];
        $collection_tickets->updateOne($criteria, $update);
        $_SESSION['message'] = "Nouveau carte est ajoutée ...";

    }
    

    if($_SESSION['action']=='suppProject'){
        $collection_tickets->deleteOne($criteria);
        $_SESSION['message'] = "Suppression du projet terminer ...";
        header('Location: message.php');

    }

    

    /*
    $collection = $client->gestdepro->users;
    $newTask = [
        'nom' => $_SESSION['nom'] ,
        'prenom' => $_SESSION['prenom'],
        'email' => $_SESSION['email'],
        'mdp' => $_SESSION['mdp'],
        'datedenai' => $_SESSION['datedenai'],
        'avatar' => 'orange'
    ];
    $collection->insertOne($newTask);*/
    header('Location: message.php');
}


?>
