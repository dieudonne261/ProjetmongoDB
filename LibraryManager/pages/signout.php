<?php
session_start();
session_unset();
session_destroy();
session_start();
$_SESSION['session']=false;
header("location:../index.php");
?>