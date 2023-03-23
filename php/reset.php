<?php
session_start();
require_once('database.php');
require_once('user.php');
require_once('phpmailer.php');

// La funzione "generateRandomString" genera una stringa casuale di lunghezza specificata, utilizzando caratteri alfanumerici
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
// controllo se l'email inserita dall'utente esiste nel database degli utenti. Se l'email esiste, viene generata una nuova password casuale e 
//viene modificata la password dell'utente nel database utilizzando il metodo "setPassword" della classe User (definita nel file "user.php").
if (isset($_POST['request'])) {
    $email = $_POST['email'] ?? '';
   
    $query = "
        SELECT email
        FROM utenti
        WHERE email = :email
    ";
    
    $check = $pdo->prepare($query);
    $check->bindParam(':email', $email, PDO::PARAM_STR);
    $check->execute();
    $ret = 1;
    if ($check->rowCount() > 0) {
        $newpw = generateRandomString(20);
        $newpw_hash = password_hash($newpw, PASSWORD_BCRYPT);
        User::setPassword($pdo, $email, $newpw);
        //  viene creato un link personalizzato che l'utente dovrà seguire per impostare la nuova password
        $link = 'http://localhost/edusogno-esercizio/reset.html?email='.$email.'&key='.$newpw;
        $to      = $email;
        $subject = 'Reimposta Password';
        $message = 'Per reimpostare la tua password usa il seguente link: <a href="'.$link.'">'.$link.'</a>';
        // $header = "From: noreply@example.com\r\n";
        // $header.= "MIME-Version: 1.0\r\n";
        // $header.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        // $header.= "X-Priority: 1\r\n";
        // creo un oggetto Mailer utilizzando la libreria PHPMailer
        $mailer->setFrom('edusogno-esercizio@libero.it', 'Edusogno Esercizio');
        $mailer->addReplyTo('edusogno-esercizio@libero.it', 'Edusogno Esercizio');
        $mailer->addAddress($email, $email);
        //https://www.wpoven.com/tools/free-smtp-server-for-testing questo è il link per visualizzare email fittizzie come nel nostro caso
        // $prova = mail($to, $subject, $message, $header);
        // invio una email all'utente contenente il link personalizzato
        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body=$message;
        $mailer->send();
        
    }
    //Infine, il codice redirige l'utente alla pagina di login del sito, 
    // fornendo un messaggio di successo che indica di controllare la propria email per procedere con il reset della password
    header('Location: ../index.html?msg=Controlla la tua email per proseguire con il reset della password');
    
}
// la seconda parte del codice si attiva quando l'utente segue il link personalizzato ricevuto tramite email e inserisce una nuova password
// il codice controlla che l'email e la chiave inseriti dall'utente corrispondano a quelli salvati nel database
// se la corrispondenza viene trovata, viene modificata la password dell'utente nel database 
// con la nuova password scelta dall'utenteutilizzando il metodo "setPassword" della classe User
else if (isset($_POST['reset'])) {
    $email = $_POST['email'] ?? '';
    $key = $_POST['key'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $query = "
            SELECT email, password
            FROM utenti
            WHERE email = :email
        ";
        
    $check = $pdo->prepare($query);
    $check->bindParam(':email', $email, PDO::PARAM_STR);
    $check->execute();
        
    $user = $check->fetch(PDO::FETCH_ASSOC);
    if (User::checkCredentials($pdo, $email, $key)) {
        User::setPassword($pdo, $email, $password);
    }
    header('Location: ../index.html?msg=Password Reimpostata correttamente');
}