<?php
    include '../../connessione.php';
    include '../../Condiviso/Messaggio.php';
    include '../../Condiviso/Test.php';
    if (!isset($_SESSION)){
        session_start();
    }
    if ($_SESSION['ruolo'] != 'Studente') {
        echo "Accesso Negato";
        header('Location: ../../Accesso/Logout.php?message=Utente non autorizzato.');
        exit();
    }
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
            background-color: #f9acac;
            display: flex;
            flex-direction: column;
            height: 100vh;
            align-items: center;
        }
        .container {
            width: 80%;
            margin: 10px auto;
            padding: auto;
            background-color: #f9acac;
            border-radius: 5px;
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
        .btnInvia {
            width: auto;
            height: auto;
            border: 1px solid #222222;
            padding: 3px;
            margin: 0px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #7cfc00;
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
            $mongoDBManager = connessioneMongoDB();
            

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST['selectDocente']) && isset($_POST['selectTest']) && isset($_POST['oggetto']) && isset($_POST['testo'])) {
                    $email_docente = $_POST['selectDocente'];
                    $titoloTest = $_POST['selectTest'];
                    $oggetto = $_POST['oggetto'];
                    $testo = $_POST['testo'];

                    $email_login = $_SESSION['email'];

                    $messaggio = new Messaggio();
                    $risultato = $messaggio->inserisciMessaggioStudente($email_login, $email_docente, $titoloTest, $oggetto, $testo);
                    if ($risultato == true) {
                        $document = ['Tipologia Evento' => 'Creazione', 'Evento' => 'Creato nuovo messaggio da parte dello studente: '.$email_login.' con oggetto: '.$oggetto.' verso il docente'.$email_docente, 'Orario' => date('Y-m-d H:i:s')];
                        writeLog($mongoDBManager, $document); 
                        echo '<script>window.alert("Messaggio inviato con successo!");</script>';
                    } else {
                        echo '<script>window.alert("Errore nell\'invio del messaggio.");</script>';
                    }

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
                        $query_test2 = "CALL visualizzaTestDisponibili()";
                        $result_test2 = $conn->query($query_test2);

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
                        $query_doc = "CALL VisualizzaDocenti()";
                        $result_doc = $conn->query($query_doc);

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
                <button type="submit" id="inviaMessaggioBtn" class="btnInvia">Invia Messaggio</button>
            </div>

        </form>
            <button class='btnInvia'onclick="window.location.href='../navBar/messaggiStudenti.php'">Torna alla lista dei messaggi</button>

    </div>
</body>
</html>
