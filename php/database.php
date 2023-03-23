<?php
//  array associativo che contiene le informazioni di configurazione per la connessione al database
$config = [
    'db_engine' => 'mysql',
    'db_host' => '127.0.0.1',
    'db_name' => 'edusogno_db',
    'db_user' => 'root',
    'db_password' => 'root',
];

$db_config = $config['db_engine'] . ":host=".$config['db_host'] . ";dbname=" . $config['db_name'];
//La successiva istruzione try è utilizzata per gestire eventuali eccezioni generate dal tentativo di connessione al database
try {
    $pdo = new PDO($db_config, $config['db_user'], $config['db_password'], [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
        
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //se la connessione è stabilita con successo, viene impostato l'attributo ERRMODE_EXCEPTION del PDO, che fornisce la gestione delle eccezioni per le query SQL che falliscono
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //imposto l'attributo EMULATE_PREPARES su false per disattivare la preparazione emulata delle query
} //Il blocco catch gestisce l'eccezione e mostra un messaggio di errore se non riesce a connettersi al database
catch (PDOException $e) {
    exit("Impossibile connettersi al database: " . $e->getMessage());
}



// $config = [
//     'db_engine' => 'mysql',
//     'db_host' => '127.0.0.1',
//     'db_name' => 'test',
//     'db_user' => 'root',
//     'db_password' => '',
// ];

// $db_config = $config['db_engine'] . ":host=".$config['db_host'] . ";dbname=" . $config['db_name'];

// try {
//     $pdo = new PDO($db_config, $config['db_user'], $config['db_password'], [
//         PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
//     ]);
        
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// } catch (PDOException $e) {
//     exit("Impossibile connettersi al database: " . $e->getMessage());
// }