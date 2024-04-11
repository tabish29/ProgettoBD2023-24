<?php

    if (!isset($_SESSION)){
        session_start();
    }
    //commetto test del git ignore fvddv
    $servername = "localhost"; // Il tuo server
    $username = "root"; // Il tuo username
    $password = "root"; // La tua password (di solito è vuota di default in ambiente di sviluppo come XAMPP)
    $dbname = "ESQL"; // Il nome del tuo database

    // Connessione al database
    $conn = new mysqli($servername, $username, $password, $dbname, 8889);

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }
?>