<?php
session_start();
require_once('database.php');
require_once('user.php');
//L'istruzione if (isset($_SESSION['session_id'])) verifica se la variabile di sessione session_id è stata impostata. Se la variabile è stata impostata, 
//il nome dell'utente corrente, l'ID della sessione e gli eventi associati all'utente vengono recuperati dal database utilizzando la classe User e la funzione getName() e getEvents().
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_id = htmlspecialchars($_SESSION['session_id']);
    $name = User::getName($pdo, $session_user);
    $events = User::getEvents($pdo, $session_user);

    echo json_encode(array('ret' => 0, 'name' =>$name, 'events'=>$events));
    
} // se la variabile di sessione session_id non è stata impostata, viene restituito un JSON con un valore di ret pari a 1
else {
    echo json_encode(array('ret' => 1));
}


// <!-- <!DOCTYPE html>
// <html lang="en">
// <head>
//     <meta charset="UTF-8">
//     <link rel="stylesheet" href="/assets/styles/style.css">
//     <meta http-equiv="X-UA-Compatible" content="IE=edge">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Document</title>
// </head>
// <body>
//     <h1>Personal Page</h1>
// </body>
// </html> -->