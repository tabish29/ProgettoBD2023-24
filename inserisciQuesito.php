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
                if ($tipoQuesito == 'RC') {
                    $sql_creaQuesitoQuery = "CALL CreazioneQuesitoRispostaChiusa('$titoloTest', '$livDifficolta', '$descrizione', $numeroRisposte, @numeroProgressivoQuesito)";
                    
                } else if ($tipoQuesito == 'COD') {
                    $sql_creaQuesitoQuery = "CALL CreazioneQuesitoCodice('$titoloTest', '$livDifficolta', '$descrizione', $numeroRisposte, @numeroProgressivoQuesito)";
                }
                if ($conn->query($sql_creaQuesitoQuery) === FALSE || mysqli_affected_rows($conn) == 0){
                    
                    echo "<p>Errore nella creazione del quesito: " . $conn->error . "</p>";
                }    
                
                // Recupero del valore di output
                $result = $conn->query("SELECT @NumeroProgressivoQuesito AS NumeroProgressivo");
                $row = $result->fetch_assoc();
                $numeroProgressivoQuesito = $row['NumeroProgressivo'];
                echo "<a href='inserisciQuesitoSpecifico.php?id=" . $numeroProgressivoQuesito. ";" . $tipoQuesito . "' class='btn'>Procedi per configurare le risposte</a> ";
                

            }
        ?>
        
            <form id="quesitoForm" method="post" action="inserisciQuesito.php">
                <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
                <VerticalPanel id="pannello">
                <div class="form-group">
                    <label for="tipoQuesito">Tipo di quesito:</label>
                    <select id="tipoQuesito" name="tipoQuesito">
                        <option value="RC" selected>Quesito a Risposta Chiusa</option>
                        <option value="COD">Quesito di Codice</option>
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
                    <input type="submit" class="btn" id="salvataggioQuesito" value="Salva" data-action="salvataggioQuesito">
                </div>
                </VerticalPanel>

            </form>

    </div>

    
