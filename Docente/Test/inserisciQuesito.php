<?php
include '../../connessione.php';
include '../../Condiviso/Tabella.php';
include '../../Condiviso/Quesito.php';
    if (!isset($_SESSION)) {
        session_start();
    }
?>
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
            background-color: #f9acac;
        }

        .container {
            text-align: center;
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9acac;
            border-radius: 5px;
        }

        .divQuesiti {
            background-color: #fcfcf0;
            margin: auto;
            width: 30%;
            height: 30%;
        }

        .buttonQuesiti {
            width: auto;
            height: auto;
            border: 1px solid #222222;
            padding: 3px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #acf9ba;
        }

        .areaInserimento {
            width: 40%;
            display: block;
            margin: auto;
        }

        .label {
            text-align: center;
            font-family: sans-serif;
            font-weight: bold;
            font-size: medium;
            color: black;
            display: block;
        }

        .test-item {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 10px;
        }

        .quesitoLabel {
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
        }


        .listBox {
            width: auto;
        }

        .areaInserimento {
            width: 50%;
            height: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Creazione Quesito</h2>
        <?php

        // Ottenere tutte le tabelle di esercizio
        $quesito = new Quesito();


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

                if ($tipoQuesito == 'RC') {
                    $numeroProgressivoQuesito = $quesito->creaQuesitoRispostaChiusa($titoloTest, $livDifficolta, $descrizione);
                } else if ($tipoQuesito == 'COD') {
                    $numeroProgressivoQuesito = $quesito->creaQuesitoCodice($titoloTest, $livDifficolta, $descrizione);
                }

                if (isset($numeroProgressivoQuesito)) {
                    
                    echo '<script>
                        window.alert("Quesito salvato con successo. Procedi ora a collegare le tabelle.");
                        window.location.href = "collegaTabelle.php?id=' . $titoloTest . ';' . $numeroProgressivoQuesito . '";
                    </script>';
                    


                
                }

            

 
        }
        ?>

        <form id="quesitoForm" method="post" action="inserisciQuesito.php">
            <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
            <VerticalPanel id="pannello">
                <div name='creazioneQuesito'>
                    <div class="form-group">
                        <label class="label" for="tipoQuesito">Tipo di quesito:</label>
                        <select class="listBox" id="tipoQuesito" name="tipoQuesito">
                            <option value="RC" selected>Quesito a Risposta Chiusa</option>
                            <option value="COD">Quesito di Codice</option>
                        </select>
                    </div>

                    <div>
                        <label class="label" for="livelloDifficolta">Livello di difficoltà:</label>
                        <select class="listBox" id="livelloDifficoltaSelect" name="livDifficolta">
                            <option value="Basso" selected>Basso</option>
                            <option value="Medio">Medio</option>
                            <option value="Alto">Alto</option>
                        </select>
                    </div>

                    <div>
                        <label class="label" for="descrizione">Descrizione:</label>
                        <input class="areaInserimento" type="text" id="descrizione" name="descrizione">
                    </div>

                    <div>
                        <label class="label" for="numeroRisposte">Quante soluzioni vuoi inserire:</label>
                        <input class="areaInserimento" type="number" id="numeroRisposte" name="numeroRisposte" min="1" max="5" step="1">
                    </div>

                    <div>
                        <input type="submit" class="buttonQuesiti" name="salvataggioQuesito" value="Salva" data-action="salvataggioQuesito">
                    </div>
                </div>
            </VerticalPanel>

        </form>
        <button id="modificaTest" class="buttonQuesiti" onclick="window.location.href='modificaTest.php?id=<?php echo $titoloTest; ?>'">Back</button>

    </div>
    
</body>