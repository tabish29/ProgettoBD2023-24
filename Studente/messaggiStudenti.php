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
            width: auto;
            margin: 10px auto;
            padding: auto;
            background-color: #fff8dc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            
        }
        .messH2{
            text-align: center;
            margin-bottom: 20px;
            font:  sans-serif;
            font-style: italic;
            font-size: medium;
        }
        .test-list {
            list-style-type: none;
            padding: 0;
            width: 500px;
        }
        .test-item {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .pMess{
            font:
            1rem 'Fira Sans',
            arial;
            font-size: 16px;
            
            border-width: auto;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            include 'navbarStudente.php';
            include '../connessione.php';

            // Verifica se l'utente è autenticato
            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: index.html");
                exit();
            }

            echo "Valore della variabile di sessione email in messaggiDocenti.php: " . $_SESSION['email']; //ELIMINARE

            $email_login = $_SESSION['email'];
            $ruolo_login = $_SESSION['ruolo'];

            echo "<h2 class='messH2'>Messaggi ricevuti: </h2>";

            // modificato ----> DA CONTROLLARE !
            $sql_all_messagesRicevuti = "SELECT * FROM ricezionestudente as RS, messaggio as M, inviodocente as S WHERE ((RS.EmailStudenteDestinatario = '$email_login') AND (RS.TitoloTest = M.TitoloTest) AND (RS.TitoloTest = S.TitoloTest) AND (M.id = RS.Id))";
                
            $result_all_messagesRicevuti = $conn->query($sql_all_messagesRicevuti);

            if ($result_all_messagesRicevuti->num_rows > 0) {
                // Stampa i valori di tutti i messaggi
                echo "<ul class='test-list'>";
                while ($row = $result_all_messagesRicevuti->fetch_assoc()) {
                    echo "<li class='test-item'>";
                    foreach ($row as $key => $value) {
                        echo ucfirst($key) . ": " . $value . "<br>";
                    }
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='pMess'>Non sono presenti messaggi ricevuti</p>";
            }

            // modificato ----> DA CONTROLLARE !
            echo "<h2 class ='messH2'>Messaggi inviati:</h2>";
            $sql_all_messagesInviati = "SELECT * FROM messaggio as M, inviostudente as D WHERE (M.Id = D.Id) AND ('$email_login' = D.EmailStudenteMittente)";
                
            $result_all_messagesInviati = $conn->query($sql_all_messagesInviati);

            if ($result_all_messagesInviati->num_rows > 0) {
                // Stampa i valori di tutti i messaggi
                echo "<ul class='test-list'>";
                while ($row = $result_all_messagesInviati->fetch_assoc()) {
                    echo "<li class='test-item'>";
                    foreach ($row as $key => $value) {
                        echo ucfirst($key) . ": " . $value . "<br>";
                    }
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class = 'pMess'>Non sono presenti messaggi inviati</p>";
            }

            // Chiusura della connessione
            //$conn->close();
        ?>
        
        <!-- Aggiungi il bottone "Invia Nuovo Messaggio" -->
        <div class="btn-container">
            <a href="nuovoMessaggioStudente.php" class="btn btn-primary">Invia Nuovo Messaggio</a>
        </div>
    </div>
</body>
</html>
