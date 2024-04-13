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

        .button {
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
        $tabella = new Tabella();
        $quesito = new Quesito();
        $numeroProgressivoQuesito;
        $booleanCollegamento = false;
        $resultTabelle = $tabella->ottieniTutteTabelle();


        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $datiSchermataPrecedente = explode(';', $_GET['id']);
            $titoloTest = $datiSchermataPrecedente[0];
            $numeroProgressivoQuesito = $datiSchermataPrecedente[1];
            $numeroRisposte = $datiSchermataPrecedente[2];
            echo "<h3>Test: $titoloTest</h3>";

        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['salvataggioCollegamento'])) {

                $titoloTest = $_POST['titoloTest'];
                $numeroProgressivoQuesito = $_POST['numeroProgressivoQuesito'];
                $numeroRisposte = $_POST['numeroRisposte'];
                
                $TabDaCollegare = $_POST['TabDaCollegare'];
                 // Collegamento del quesito alla tabella
                try{
                    $sql_collegaTabella = $quesito->collegaTabella($numeroProgressivoQuesito, $titoloTest, $TabDaCollegare); 
                    if ($sql_collegaTabella){
                        $booleanCollegamento = true;
                        echo "Tabella collegata con successo";
                    }
                } catch (Exception $e) {
                    echo "Errore: tabella già collegata al quesito";
                    $resultTabelle = $tabella->ottieniTutteTabelle();
                }

                
            } else if (isset($_POST['continua'])) {
                //Controllo che sia stata collegata almeno una tabella
                $titoloTest = $_POST['titoloTest'];
                $numeroProgressivoQuesito = $_POST['numeroProgressivoQuesito'];
                $numeroRisposte = $_POST['numeroRisposte'];
                $sql = $quesito->verificaPresenzaCollegamento($titoloTest, $numeroProgressivoQuesito);
                
                if($sql){
                    $tipoQuesito = $quesito->ottieniTipologiaQuesito($titoloTest, $numeroProgressivoQuesito);

                    if ($tipoQuesito == "Risposta Chiusa"){
                        $tipoQuesito = "RC";
                    } else {
                        $tipoQuesito = "COD";
                    }
                    echo "numeroProgressivoQuesito: " . $numeroProgressivoQuesito . " tipoQuesito: " . $tipoQuesito . " titoloTest: " . $titoloTest . " numeroRisposte: " . $numeroRisposte;
                    header('Location: inserisciQuesitoSpecifico.php?id=' . $numeroProgressivoQuesito . ';' . $tipoQuesito . ';' . $titoloTest . ';' . $numeroRisposte);
                    exit;
                } else {
                    echo "Collega almeno una tabella";
                }
            }

 
        }
        ?>

        <form id="quesitoForm" method="post" action="collegaTabelle.php">
            <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
            <input type="hidden" name="numeroProgressivoQuesito" value="<?php echo $numeroProgressivoQuesito; ?>">
            <input type="hidden" name="numeroRisposte" value="<?php echo $numeroRisposte; ?>">
            <VerticalPanel id="pannello">
                <div>
                    <h4>Collegamento Quesito a Tabella</h4>
                    <h5>Dopo aver salvato il Quesito, seleziona una o più tabelle a cui vuoi collegare il quesito</h5>
                    <label class="label" for="LabelTab">A quale tabella vuoi collegare il quesito:</label>
                    <select class="listBox" id="TabelleDaCollegare" name="TabDaCollegare">
                        <?php while ($row = $resultTabelle->fetch_assoc()) {
                            echo "<option value='" . $row['Nome'] . "'>" . $row['Nome'] . "</option>";
                        } if (mysqli_num_rows($resultTabelle) == 0) {
                            echo "<option value='Nessuna tabella disponibile'>Nessuna tabella disponibile</option>";
                        } 
                        ?>
                    </select>
                    <input type="submit" class="button" name="salvataggioCollegamento" value="Collega" data-action="salvataggioCollegamento">
                    </div>

                <div>
                    <h5>Dopo aver collegato il quesito ad almeno una tabella, clicca su continua</h5>
                    <input type="submit" class="button" name="continua" value="Continua" data-action="Continua">
                </div>

            </VerticalPanel>

        </form>

    </div>
    
</body>