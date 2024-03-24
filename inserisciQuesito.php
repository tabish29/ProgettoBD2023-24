<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creazione Quesito</title>
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
        form {
            width: 50%;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .add-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .add-button:hover {
            background-color: #45a049;
        }
        .btn {
            width: 100%;
            height: 40px;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .additional-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Creazione Quesito</h2>
        <?php
            include 'connessione.php';
            if (!isset($_SESSION)){
                session_start();
            }

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $titoloTest = $_GET['id'];
                echo "<h3>Test: $titoloTest</h3>";
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $titoloTest = $_POST['titoloTest'];
                $tipoQuesito = $_POST['tipoQuesito'];
                $livDifficolta = $_POST['livDifficolta'];
                $descrizione = $_POST['descrizione'];
                $numeroRisposte = $_POST['numeroRisposte'];
                $sql_creaQuesitoQuery = '';
                if ($tipoQuesito === 'chiusa') {
                    $sql_creaQuesitoQuery = "CALL CreazioneQuesitoRispostaChiusa('$titoloTest', '$livDifficolta', '$descrizione', '$numeroRisposte')";
                } else {
                    $sql_creaQuesitoQuery = "CALL CreazioneQuesitoCodice('$titoloTest', '$livDifficolta', '$descrizione', '$numeroRisposte')";
                }
                if ($conn->query($sql_creaQuesitoQuery) === FALSE && mysqli_affected_rows($conn) == 0){
                    echo "<p>Errore nella creazione del quesito: " . $conn->error . "</p>";
                }

            }
        ?>
        
            <form id="quesitoForm" method="post" action="inserisciQuesito.php">
                <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
                <VerticalPanel id="pannello">
                <div class="form-group">
                    <label for="tipoQuesito">Tipo di quesito:</label>
                    <select id="tipoQuesito" name="tipoQuesito">
                        <option value="chiusa" selected>Quesito a Risposta Chiusa</option>
                        <option value="codice">Quesito di Codice</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="livelloDifficolta">Livello di difficoltà:</label>
                    <select id="livelloDifficoltaSelect" name="livDifficolta">
                        <option value="Basso" selected>Basso</option>
                        <option value="Medio">Medio</option>
                        <option value="Alto">Alto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descrizione">Descrizione:</label>
                    <input type="text" id="descrizione" name="descrizione">
                </div>

                <div class="form-group">
                    <label for="numeroRisposte">Numero di risposte:</label>
                    <input type="text" id="numeroRisposte" name="numeroRisposte">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn" id="salvataggioQuesito" value="Salva Dati">
                    <button type="button" class="btn" onclick="mostraCampiAggiuntivi()">Procedi all'inserimento delle risposte</button>
                </div>
                </VerticalPanel>

                <div id="campiAggiuntiviContainer" class="additional-fields">
                    <div class="form-group" id="campoTestoContainer" style="display: block;">
                        <label for="campoTesto">Campo testo:</label>
                        <div id="campiTesto">
                            <input type="text" name="campiTesto[]">
                        </div>
                        <button type="button" class="add-button" onclick="aggiungiCampo('campoTestoContainer', 'campiTesto')">Aggiungi Campo</button>
                        
                    </div>

                    <div class="form-group" id="testoSoluzioneContainer">
                        <label for="testoSoluzione">Testo soluzione:</label>
                        <div id="testiSoluzione">
                            <input type="text" name="testiSoluzione[]">
                        </div>
                        <button type="button" class="add-button" onclick="aggiungiCampo('testoSoluzioneContainer', 'testiSoluzione')">Aggiungi Soluzione</button>
                    </div>

                </div>

                

            </form>
            

        
    </div>

    <script>
        function mostraCampiAggiuntivi() {
            var tipoQuesito = document.getElementById('tipoQuesito').value;
            var campiAggiuntiviContainer = document.getElementById('campiAggiuntiviContainer');
            var pannello = document.getElementById('pannello');

        
            if (tipoQuesito === 'codice') {
                document.getElementById('campoTestoContainer').style.display = 'none';
                document.getElementById('testoSoluzioneContainer').style.display = 'block';
                
                // Nascondi i campi comuni
                document.getElementById('tipoQuesito').parentNode.parentNode.style.display = 'none';
                document.getElementById('livelloDifficoltaSelect').parentNode.parentNode.style.display = 'none';
                document.getElementById('descrizione').parentNode.parentNode.style.display = 'none';
                document.getElementById('numeroRisposte').parentNode.parentNode.style.display = 'none';
            } else {
                document.getElementById('campoTestoContainer').style.display = 'block';
                document.getElementById('testoSoluzioneContainer').style.display = 'none';
                
                // Nascondi i campi comuni
                document.getElementById('tipoQuesito').parentNode.parentNode.style.display = 'none';
                document.getElementById('livelloDifficoltaSelect').parentNode.parentNode.style.display = 'none';
                document.getElementById('descrizione').parentNode.parentNode.style.display = 'none';
                document.getElementById('numeroRisposte').parentNode.parentNode.style.display = 'none';
            }

            // Mostra solo i campi aggiuntivi
            campiAggiuntiviContainer.style.display = 'block';
        }


    </script>
