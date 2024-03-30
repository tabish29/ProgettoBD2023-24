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
        .invia-messaggio-form {
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }
        textarea {
            resize: vertical;
        }
        .btn-container {
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            include 'navbarDocente.php';
            include 'connessione.php';
            
        
            // Verifica se l'utente è autenticato
            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: index.html");
                exit();
            }
        

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Verifica se sono stati inviati i dati del form
                if (isset($_POST['selectTest']) && isset($_POST['oggetto']) && isset($_POST['testo'])) {
                    // Recupera i dati dal form
                    $titoloTest = $_POST['selectTest'];
                    $oggetto = $_POST['oggetto'];
                    $testo = $_POST['testo'];

                    $email_login = $_SESSION['email'];
                    // Esempio: Salva nel database
                    $sql = "CALL inserimentoMessaggioDocente('$titoloTest', '$oggetto', '$testo', NOW(), '$email_login')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Messaggio inviato con successo!";
                    } else {
                        echo "Errore durante l'invio del messaggio: " . $conn->error;
                    }

                    // Chiudi la connessione al database
                   // $conn->close();
                } else {
                    echo "Campi del modulo non validi.";
                }
            }
        ?>
    </div>

    <div class="invia-messaggio-form">
        <form action="nuovoMessaggioDocente.php" method="post">
            <h2>Invia Nuovo Messaggio</h2>
            
            <div class="form-group">
                <label for="selectTest">Seleziona un test:</label>
                <select id="selectTest" name="selectTest">
                    <?php
                        // Recupera i nomi dei test dal database
                        $query_test = "CALL visualizzaTestDisponibili()";
                        $result_test = $conn->query($query_test);

                        // Aggiungi opzioni alla ListBox
                        while ($row_test = $result_test->fetch_assoc()) {
                            echo "<option value='" . $row_test['Titolo'] . "'>" . $row_test['Titolo'] . "</option>";
                        }

                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="oggetto">Oggetto del messaggio:</label>
                <input type="text" id="oggetto" name="oggetto" required>
            </div>

            <div class="form-group">
                <label for="testo">Testo del messaggio:</label>
                <textarea id="testo" name="testo" rows="5" required></textarea>
            </div>

            <div class="btn-container">
                <button type="submit" id="inviaMessaggioBtn" class="btn btn-primary">Invia Messaggio</button>
            </div>
        </form>
    </div>
</body>
</html>
