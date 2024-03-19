<?php
    session_start(); // Avvia la sessione
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-list {
            list-style-type: none;
            padding: 0;
        }
        .test-item {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <ul class="email-list">
        <?php

        $servername = "localhost"; // Il tuo server
        $username = "root"; // Il tuo username
        $password = "Alessia123!"; // La tua password (di solito è vuota di default in ambiente di sviluppo come XAMPP)
        $dbname = "esql"; // Il nome del tuo database

        // Connessione al database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica della connessione
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupera l'email dal form di login
        
        $email_login = isset($_POST['email_login']) ? $_POST['email_login'] : '';
        $ruolo_login = isset($_POST['ruolo_login']) ? $_POST['ruolo_login'] : '';

    // Verifica se email_login e ruolo_login sono presenti
    if (empty($email_login) || empty($ruolo_login)) {
        //echo "<li class='test-item'>Email e ruolo devono essere specificati.</li>";
        //echo '<a href="index.html">Torna alla schermata principale</a>';
    } else {
        // Query per verificare se l'email esiste nella tabella del ruolo selezionato
        // Query per verificare se l'email esiste nella tabella del ruolo selezionato
        $sql_check_email = "";
        if ($ruolo_login === "docente") {
            $sql_check_email = "SELECT email FROM docente WHERE email = '$_SESSION[email_login]'";
        } else if ($ruolo_login === "studente") {
            $sql_check_email = "SELECT email FROM studente WHERE email = '$_SESSION[email_login]'";
        }

        $result_check_email = $conn->query($sql_check_email);

        // Verifica se l'email esiste nella tabella del ruolo selezionato
        if ($result_check_email->num_rows <= 0) {
            echo "<li class='test-item'>Email errata.</li>";
            echo '<a href="index.html">Torna alla schermata principale</a>';
        } else {
            echo "Accesso effettuato";
        }

            // Imposta le variabili di sessione
            $_SESSION['email'] = $email_login;
            $_SESSION['ruolo'] = $ruolo_login;
            
            header("Location: testDocenti.php");
            exit();
        }
    }
        
    
    ?>

        </ul>
    </div>
</body>
</html>
