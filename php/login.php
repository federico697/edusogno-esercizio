<?php
session_start();
require_once('database.php');
require_once('user.php');


// verifico se una variabile di sessione chiamata "session_id" è già stata impostata. Se sì, 
// il client viene reindirizzato alla pagina di dashboard.php e l'esecuzione dello script termina tramite exit
if (isset($_SESSION['session_id'])) {
    header('Location: ../personalpage.html');
    exit;
}

// vengono verificate le credenziali dell'utente che ha tentato di effettuare l'accesso. 
// vengono quindi recuperati i valori email e password dal form di login con $_POST['email'] e $_POST['password'], rispettivamente, e vengono assegnati a due variabili.
if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $ret = 1;
    // verifico che entrambi i valori siano stati inseriti dall'utente, altrimenti viene assegnato il messaggio di errore "Inserisci email e password" alla variabile $msg. 
    // se entrambi i valori sono stati inseriti, viene eseguita una query sul database per recuperare l'utente con l'email inserita.
    if (empty($email) || empty($password)) {
        $msg = 'Inserisci email e password';
    } else {
        
        if (User::login($pdo, $email, $password)===false) {
            $msg = 'Credenziali utente errate';
            User::logout();
        } else {
            $ret = 0;
        }
    }
    // verificato il valore della variabile $ret. se è uguale a 0, l'utente viene reindirizzato alla sua pagina personale
    // altrimenti viene reindirizzato alla pagina di login con un messaggio di errore
    if ($ret == 0){
        header('Location: ../personalpage.html');
    }else{
        header('Location: ../index.html?msg='.$msg);
    }
    
    
}
