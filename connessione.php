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
?>
