<?php
$host = "localhost";
$port = "5432";
$dbname = "librarymanager";
$user = "postgres";
$password = "";

$db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");


if ($db === false) {
    die("Erreur de connexion à la base de données");
}


session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT * FROM emprunts WHERE id_emprunts = :id_emprunts";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_emprunts', $_POST['codeemp']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($row['date_retour'] == ""){
                $_SESSION['message'] = "Retours d'emprunt reussi";
                $sql = "UPDATE livres SET stock = stock + :qte WHERE code_barre = :code_barre";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':qte', $row['qte']);
                $stmt->bindParam(':code_barre', $row['code_barre']);
                $stmt->execute();

                $now = date("Y-m-d H:i:s");
                $sql = "UPDATE emprunts SET date_retour = :date_retour WHERE id_emprunts = :id_emprunts";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':date_retour', $now);
                $stmt->bindParam(':id_emprunts', $row['id_emprunts']);
                $stmt->execute();

                $_SESSION['message'] = "Livre emprunter retouner avec succes";
                $msg="Vous devez remetre le livres qui est en retard";
                $sql = "INSERT INTO notifications (De, a, messages, date_env) VALUES (:De, :a, :messages, :date_env)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':De', $_SESSION['email']);
                $stmt->bindParam(':a', $row['email']);
                $stmt->bindParam(':messages', $msg);
                $stmt->bindParam(':date_env', $now);
            }
            else{
                $_SESSION['message'] = "Code deja utilisé";
            }
        }
        
    }
    else {
        $_SESSION['message'] = "Code invalide";
    }

    header('Location: message.php');                          

}
else {
    header('Location: menu-bibli.php');
}
?>