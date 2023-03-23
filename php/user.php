<?php 
    class User {
        //il metodo "login" controlla le credenziali dell'utente e, se corrette, registra l'utente nella sessione
        public static function login($pdo, $email, $password){
            
            if (User::checkCredentials($pdo, $email, $password)===true) {
                $ret = true;
                session_regenerate_id();
                $_SESSION['session_id'] = session_id();
                $_SESSION['session_user'] = $email;
            } else {
                $ret = false;
            }
            return $ret;
        }
        //  il metodo "checkCredentials" controlla se l'utente esiste nel database e se la password corrisponde a quella registrata nel database
        public static function checkCredentials($pdo, $email, $password){
            $query = "
                SELECT email, password
                FROM utenti
                WHERE email = :email
            ";
            
            //Il codice utilizza PDO (PHP Data Objects) per interagire con il database, utilizzando l'interfaccia generica fornita da PDO per eseguire le query SQL e recuperare i risultati
            $check = $pdo->prepare($query); 
            $check->bindParam(':email', $email, PDO::PARAM_STR); 
            $check->execute(); 
            

            $user = $check->fetch(PDO::FETCH_ASSOC);
            // la funzione password_verify() per verificare se la password inserita dall'utente corrisponde a quella registrata nel database
            if (!$user || password_verify($password, $user['password']) === false) {
                $ret = false;
            } else {
                $ret = true;
            }
            return $ret;
        }
        //  il metodo "logout" cancella l'utente dalla sessione.
        public static function logout(){
            session_regenerate_id();

            if (isset($_SESSION['session_id'])) {
                unset($_SESSION['session_id']);
            }
        }
        //  Il metodo "register" registra un nuovo utente nel database
        public static function register($pdo, $username, $userlastname, $email, $password){
            $query = "
                INSERT INTO utenti (nome, cognome, email, password)
                VALUES (:username, :userlastname, :email, :password)
            ";
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
            $check = $pdo->prepare($query);
            $check->bindParam(':username', $username, PDO::PARAM_STR);
            $check->bindParam(':userlastname', $userlastname, PDO::PARAM_STR);
            $check->bindParam(':email', $email, PDO::PARAM_STR);
            $check->bindParam(':password', $password_hash, PDO::PARAM_STR);
            $check->execute();
            
            return $check->rowCount() > 0;
        }
        // il metodo "emailExists" controlla se un utente con l'indirizzo email specificato esiste già nel database
        public static function emailExists($pdo, $email){
            $query = "
                    SELECT id
                    FROM utenti
                    WHERE email = :email
                ";
                
            $check = $pdo->prepare($query);
            $check->bindParam(':email', $email, PDO::PARAM_STR);
            $check->execute();
            
            $user = $check->fetchAll(PDO::FETCH_ASSOC);
            
            return count($user) > 0;
        }
        // Il metodo "setPassword" accetta tre parametri: un oggetto PDO che rappresenta la connessione al database, 
        // l'indirizzo email dell'utente a cui si desidera cambiare la password e la nuova password
        public static function setPassword($pdo, $email, $password){
            $query = "
                UPDATE utenti
                SET password=:password
                WHERE email = :email
            ";
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $check = $pdo->prepare($query);
            // La funzione "bindParam" viene utilizzata per preparare la query SQL e la funzione "execute" viene utilizzata per eseguirla
            $check->bindParam(':email', $email, PDO::PARAM_STR);
            $check->bindParam(':password', $password_hash, PDO::PARAM_STR);
            $check->execute();
        }
        //Il metodo "getName" accetta due parametri: un oggetto PDO che rappresenta la connessione al database e l'indirizzo email dell'utente di cui si desidera ottenere il nome e il cognome
        public static function  getName($pdo, $email){
            $query = "
                    SELECT nome, cognome
                    FROM utenti
                    WHERE email = :email
            ";
                
            $user_query = $pdo->prepare($query);
            $user_query->bindParam(':email', $email, PDO::PARAM_STR);
            $user_query->execute();
                
            $user = $user_query->fetch(PDO::FETCH_ASSOC);
            $username = $user['nome'];
            $userlastname = $user['cognome'];
            return $username . ' '. $userlastname;
            
        }
        // Il metodo "getEvents" accetta due parametri: il primo per la connessione al db, il secondo per l'indirizzo email dell'utente di cui si desidera ottenere gli eventi a cui partecipa
        public static function  getEvents($pdo, $email){
            $query = "
            SELECT nome_evento, data_evento, attendees
            FROM eventi
            WHERE attendees LIKE Concat('%',:email,'%')
            ";

            $events_query = $pdo->prepare($query);
            $events_query->bindParam(':email', $email, PDO::PARAM_STR);
            $events_query->execute();

            $eventi = $events_query->fetchAll(PDO::FETCH_ASSOC);
            $arr_eventi = array();
            foreach($eventi as $evento){
                array_push($arr_eventi, array('name'=>$evento['nome_evento'], 'date'=>$evento['data_evento']));
            }
            return $arr_eventi;
        }
    }