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
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .messH2{
            text-align: center;
            margin-bottom: 20px;
            font:  sans-serif;
            font-style: italic;
            font-size: medium;
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
            background-color: #7cfc00; 
            color: #222222;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .listBox{
            width: auto;
        }
        .areaInserimento{
            width: 60%;
        }
        .labelMess{
            font-weight: bold;
            font-size: medium;
            padding: 1%;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            include 'navbarStudente.php';
            include 'connessione.php';
            
        
            // Verifica se l'utente è autenticato
            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: index.html");
                exit();
            }


            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Verifica se sono stati inviati i dati del form
                if (isset($_POST['selectDocente']) && isset($_POST['selectTest']) && isset($_POST['oggetto']) && isset($_POST['testo'])) {
                    // Recupera i dati dal form
                    $email_docente = $_POST['selectDocente'];
                    $titoloTest = $_POST['selectTest'];
                    $oggetto = $_POST['oggetto'];
                    $testo = $_POST['testo'];

                    $email_login = $_SESSION['email'];
                    // Esempio: Salva nel database
                    $sql = "CALL inserisciMessaggioStudente('$email_login','$email_docente','$titoloTest', '$oggetto', '$testo')";
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
    <form action="nuovoMessaggioStudente.php" method="post">
            <h2 class='messH2'>Invia Nuovo Messaggio</h2>

            <div class="form-group">
                <label class="labelMess" for="selectTest">Seleziona un test:</label><br>
                <select class="listBox" id="selectTest" name="selectTest"><br>
                    <?php
                        // Recupera i nomi dei test dal database
                        $query_test2 = "CALL visualizzaTestDisponibili()";
                        $result_test2 = $conn->query($query_test2);

                        // Aggiungi opzioni alla ListBox
                        while ($row_test = $result_test2->fetch_assoc()) {
                            echo "<option value='" . $row_test['Titolo'] . "'>" . $row_test['Titolo'] . "</option>";
                        }
                        $conn-> next_result();

                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="selectDocente" class="labelMess">Seleziona un docente:</label><br>
                <select class='listBox' id="selectDocente" name="selectDocente"><br>
                    <?php
                        // Recupera i nomi dei docenti dal database
                        $query_doc = "CALL VisualizzaDocenti()";
                        $result_doc = $conn->query($query_doc);

                        // Aggiungi opzioni alla ListBox
                        while ($row_test = $result_doc->fetch_assoc()) {
                            echo "<option value='" . $row_test['Email'] . "'>" . $row_test['Email'] . "</option>";
                        }
                        $conn-> next_result();

                    ?>
                </select>
            </div>
        
            <div class="form-group">
                <label for="oggetto"class="labelMess">Oggetto del messaggio:</label><br>
                <input type="text" class="areaInserimento" id="oggetto" name="oggetto" required>
            </div>

            <div class="form-group">
                <label for="testo"class="labelMess">Testo del messaggio:</label><br>
                <textarea id="testo"class="areaInserimento" name="testo" rows="5" required></textarea><br>
            </div>

            <div class="btn-container">
                <button type="submit" id="inviaMessaggioBtn" class="btn btn-primary">Invia Messaggio</button>
            </div>

            
        </form>
    </div>
</body>
</html>
