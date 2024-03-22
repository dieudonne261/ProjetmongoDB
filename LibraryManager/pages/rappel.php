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
    $_SESSION['message'] = "Message de rappel de retard envoyer";
    $msg="Vous devez remetre le livres qui est en retard";
    $sql = "INSERT INTO notifications (De, a, messages, date_env) VALUES (:De, :a, :messages, :date_env)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':De', $_SESSION['email']);
    $stmt->bindParam(':a', $_POST['rap']);
    $stmt->bindParam(':messages', $msg);
    $stmt->bindParam(':date_env', $date('Y-m-d'));
    header('Location: message.php');
}
else {
    header('Location: menu-bibli.php');
}
?>