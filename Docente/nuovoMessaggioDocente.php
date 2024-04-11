<?php
    if (!isset($_SESSION)){
        session_start();
    }
    include '../connessione.php';
    include '../Condiviso/Messaggio.php';
    include '../Condiviso/Test.php';
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
            
        

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Verifica se sono stati inviati i dati del form
                if (isset($_POST['selectTest']) && isset($_POST['oggetto']) && isset($_POST['testo'])) {
                    // Recupera i dati dal form
                    $titoloTest = $_POST['selectTest'];
                    $oggetto = $_POST['oggetto'];
                    $testo = $_POST['testo'];

                    $email_login = $_SESSION['email'];
                    // Esempio: Salva nel database
                    $messaggio = new Messaggio();
                    $risultato = $messaggio->inserisciMessaggioDocente($titoloTest, $oggetto, $testo, $email_login);
                    if ($risultato) {
                        echo '<script>window.alert("Messaggio inviato con successo!");
                                window.location.href = "messaggiDocenti.php";
                            </script>';

                    } else {
                        echo '<script>window.alert("Errore nell\'invio del messaggio.");</script>';
                    }

                } else {
                    echo "Campi del modulo non validi.";
                }
            }
        ?>
        <form action="nuovoMessaggioDocente.php" method="post">
            <h2 class='messH2'>Invia Nuovo Messaggio</h2>
            
            <div class="form-group">
                <label for="selectTest" class="labelMess">Seleziona un test:</label><br>
                <select class='listBox'id="selectTest" name="selectTest">
                    <?php
                        $test = new Test();
                        // Recupera i nomi dei test dal database
                        $testOttenuti = $test->ottieniTuttiITest();
                        // Aggiungi opzioni alla ListBox
                        while ($datiTest = $testOttenuti->fetch_assoc()) {
                            echo "<option value='" . $datiTest['Titolo'] . "'>" . $datiTest['Titolo'] . "</option>";
                        }

                    ?>
                </select>
            </div>

                <label for="oggetto" class="labelMess">Oggetto del messaggio:</label><br>
                <input type="text" class="areaInserimento"id="oggetto" name="oggetto" required><br>
            

                <label for="testo"class="labelMess">Testo del messaggio:</label><br>
                <textarea id="testo" class="areaInserimento"name="testo" rows="5" required></textarea><br>

                <button type="submit" id="inviaMessaggioBtn" class="btnInvia">Invia Messaggio</button>
                <button class='btnInvia'onclick="window.location.href='messaggiDocenti.php'">Torna alla lista dei messaggi</button>
        </form>
    </div>
</body>
</html>
