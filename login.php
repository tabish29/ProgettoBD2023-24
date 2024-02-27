<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage Docente</title>
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
        <h2>HomePage docente</h2>
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

             // Recupera l'email dal form di login
             $email_login = $_POST['email_login'];
             // Recupera il ruolo dal form di login
             $ruolo_login = $_POST['ruolo_login'];

            // Query per verificare se l'email esiste nella tabella del ruolo selezionato
            $sql_check_email = "";
            if ($ruolo_login === "docente") {
                $sql_check_email = "SELECT email FROM docente WHERE email = '$email_login'";
            } else if ($ruolo_login === "studente") {
                $sql_check_email = "SELECT email FROM studente WHERE email = '$email_login'";
            }

            $result_check_email = $conn->query($sql_check_email);
            ?>
            <h3>Lista test:</h3>
            <?php
            // Verifica se l'email esiste nella tabella del ruolo selezionato
            if ($result_check_email->num_rows > 0) {
                
                // Query per selezionare tutti i test
                $sql_all_tests = "";
                if ($ruolo_login === "docente") {
                    $sql_all_tests = "CALL visualizzaTestDisponibili()";
                } else if ($ruolo_login === "studente") {
                    $sql_all_tests = "CALL visualizzaTestDisponibili()";
                }

                $result_all_tests = $conn->query($sql_all_tests);

                // Verifica se ci sono test 
                if ($result_all_tests->num_rows > 0) {
                    // Stampa i valori di tutti i test
                    while ($row = $result_all_tests->fetch_assoc()) {
                        echo "<li class='test-item'>";
                        foreach ($row as $key => $value) {
                            echo ucfirst($key) . ": " . $value . "<br>";
                        }
                        echo "</li>";
                    }
                } else {
                    echo "<li class='test-item'>Nessun risultato trovato</li>";
                }
            } else {
                echo "<li class='test-item'>Non sono presenti test.</li>";
            }

            // Chiudi la connessione
            $conn->close();
            ?>
        </ul>
    </div>
</body>
</html>
