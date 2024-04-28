<?php
    if (!isset($_SESSION)){
        session_start();
    }

    $servername = "localhost"; // Il tuo server
    $username = "root"; // Il tuo username
    $password = ""; // La tua password (di solito è vuota di default)
    $dbname = "ESQL"; // Il nome del tuo database

    // Connessione al database
    $conn = new mysqli($servername, $username, $password, $dbname);
    $_SESSION['conn'] = $conn;
    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }


    if (!function_exists('connessioneMongoDB')) {
        function connessioneMongoDB() {
            date_default_timezone_set("Europe/Rome");
    
            $mongoHost = "localhost";
            $mongoPort = 27017;
    
            $manager = new MongoDB\Driver\Manager("mongodb://$mongoHost:$mongoPort");
            return $manager;
        }
    }
    
    if (!function_exists('writeLog')) {
        function writeLog($manager, $document) {
            $mongoDatabase = "ESQL";
            $mongoCollection = "logs";
    
            $bulkWrite = new MongoDB\Driver\BulkWrite;
            $bulkWrite -> insert($document);
    
            try {
                $manager -> executeBulkWrite("$mongoDatabase.$mongoCollection", $bulkWrite);
            } catch (Exception $e) {
                echo "Eccezione ".$e -> getMessage()."<br>";
            }
        }
    }
   
    
?>
