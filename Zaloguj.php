<?php
session_start();
if((!isset($_POST['login']))||(!isset($_POST['haslo'])))
{
    header("Location: index.php");
    exit();
}
require_once 'connect.php';
include 'User.php';
$connection = new mysqli($host,$user,$password,$db);
$user1 = new User($_POST['login'],$_POST['haslo'],$connection);
$user1->logowanie();
?>

