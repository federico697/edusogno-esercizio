<?php
// se esiste una variabile di sessione chiamata "session_id" viene rimossa con il comando unset($_SESSION['session_id']) 
// ed infine reindirizzo l'utente alla pagina principale del sito (index.html), dopo di che il programma termina con il comando exit
session_start();
require_once('user.php');

User::logout();
header('Location: ../index.html');
exit;