<?php
require_once('database.php');
require_once('user.php');
//se l'utente ha inviato il modulo di registrazione, il codice recupera le informazioni inserite (nome, cognome, email e password), 
//verificoche tutti i campi siano stati inseriti e che l'email non sia già presente nel database
if (isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $userlastname = $_POST['userlastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
   
    $ret = 1;
    if (empty($username) || empty($userlastname) || empty($email) || empty($password)) {
        $msg = 'Tutti i campi devono essere riempiti';
    } else if (User::emailExists($pdo, $email)) {
        $msg = 'Email già in uso';
    } else if (strlen($password) <= 8){
        $msg = 'La password deve essere piu lunga di 8 caratteri';
    } else {  
        //la funzione User::register() viene chiamata per inserire i dati dell'utente nel database      
        if (User::register($pdo, $username, $userlastname, $email, $password)) {
            $msg = 'Registrazione eseguita con successo';
            $ret = 0;
        } else {
            $msg = 'Problemi con l\'inserimento dei dati';
        }
    }
    if($ret == 0){
        header('Location: ../index.html?msg='.$msg);
    }else{
        header('Location: ../register.html?msg='.$msg);
    }
}


// <!-- <!DOCTYPE html>
// <html>
//     <head>
//         <title>Registrazione</title>
//         <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">
//         <link rel="stylesheet" href="/css/style.css">
//         <style>
//             body{
//                 display: flex;
//                 justify-content: center;
//             }

//             form{
//                 display: flex;
//                 flex-direction: column;
//                 width: 300px;
//             }

//             input{
//                 margin-bottom: 20px;
//             }
//         </style>
//     </head>
//     <body>
//         <form method="post" action="/php/register.php">
//             <h1>Registrazione</h1>

//             <label for="username">username</label>
//             <input type="text" id="username" placeholder="Username" name="username" maxlength="50" required>

//             <label for="userlastname">userlastname</label>
//             <input type="text" id="userlastname" placeholder="UserLastname" name="userlastname" maxlength="50" required>

//             <label for="email">email</label>
//             <input type="email" id="email" placeholder="Email" name="email" required>

//             <label for="password">password</label>
//             <input type="password" id="password" placeholder="Password" name="password" required>

//             <button type="submit" name="register">invia</button>
//         </form>
//     </body>
// </html> -->