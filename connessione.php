<?php

    if (!isset($_SESSION)){
        session_start();
    }
    
    $servername = "localhost"; // Il tuo server
    $username = "root"; // Il tuo username
    $password = ""; // La tua password (di solito è vuota di default in ambiente di sviluppo come XAMPP)
    $dbname = "esql"; // Il nome del tuo database

    // Connessione al database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }
?>