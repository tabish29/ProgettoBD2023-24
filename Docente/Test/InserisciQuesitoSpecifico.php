<?php
    include '../../connessione.php';
    include '../../Condiviso/Quesito.php';
    if (!isset($_SESSION)){
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
            line-height: 1.4;
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
        .salvaBtn {
            color: black;
            background-color: #ffcc00;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .areaInserimento {
            width: 40%;
            display: block;
            margin:auto;
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

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $datiTest = []; // NumProgressivo, Tipo, TitoloTest, NumeroRisposte, NumeroRisposteInserite 
                $campiSchermataPrecedente = explode(";",$_GET['id']);
                array_push($datiTest, $campiSchermataPrecedente[0]);   
                array_push($datiTest, $campiSchermataPrecedente[1]); 
                array_push($datiTest, $campiSchermataPrecedente[2]); 
                array_push($datiTest, $campiSchermataPrecedente[3]);         
                array_push($datiTest, 0);
                
                $_SESSION['datiTestAttuale'] = $datiTest;
                //$conn->next_result();
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['datiTestAttuale']) && !empty($_SESSION['datiTestAttuale'])) {
                
                $quesito = new Quesito();
                $datiTest = $_SESSION['datiTestAttuale'];
                $valoreInserito = $_POST['soluzioneT'];
                $_SESSION['datiTestAttuale'][4] = $datiTest[4] + 1;
                $datiTest = $_SESSION['datiTestAttuale'];
                
                //Scommentare dopo la realizzazione delle query
                $sql_queryNuovaOpzioneOSoluzione = ''; 
                $titolo = $datiTest[2];
                $numProgressivoQuesito = $datiTest[0];
                if ($datiTest[3] - $datiTest[4] > 0){
                    echo "<label>Devi inserire ancora " . ($datiTest[3] - $datiTest[4]) . " risposte</label>";

                    if ($datiTest[1] == "RC"){
                        $sql_queryNuovaOpzioneOSoluzione = $quesito->inserimentoOpzioneRisposta($datiTest[2],$datiTest[0], $valoreInserito);
                    } else if ($datiTest[1] == "COD"){
                        $sql_queryNuovaOpzioneOSoluzione = $quesito->inserimentoSoluzione($datiTest[2],$datiTest[0], $valoreInserito);
                    }

                    if ($sql_queryNuovaOpzioneOSoluzione == false){
                        echo "<p>Errore nell'inserimento: " . $conn->error . "</p>";
                    } else {
                        echo "<label><br>Inserimento avvenuto con successo</label>";
                        $datiTest[4]++;
                    }

                } else if ($datiTest[3] - $datiTest[4] == 0){
                    if ($datiTest[1] == "RC"){
                        $sql_queryNuovaOpzioneOSoluzione = $quesito->inserimentoOpzioneRisposta($datiTest[2],$datiTest[0], $valoreInserito);
                    } else if ($datiTest[1] == "COD"){
                        $sql_queryNuovaOpzioneOSoluzione = $quesito->inserimentoSoluzione($datiTest[2],$datiTest[0], $valoreInserito);
                    }

                    $datiTest[4]++;
                    echo "<p>Numero massimo di risposte raggiunto</p>";
                    $titolo = $datiTest[2];
                    unset($_SESSION['datiTestAttuale']);
                    if ($datiTest[1] == "RC"){
                        header('Location: selezionaRispostaCorretta.php?id=' . $titolo . ";" . $numProgressivoQuesito);
                        exit;
                    }
                    else {
                        header('Location: modificaTest.php?id=' . $titolo);
                        exit;
                    }
                   
                }
            }

        ?>
        
        <form id="quesitoForm" method="post" action="inserisciQuesitoSpecifico.php">
            <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
            <div class="form-group">
                <br></br>
                <label class="label"for="soluzioneT">Testo Risposta:</label>
                <input type="text" class="areaInserimento" id="soluzioneT" name="soluzioneT" required>
            </div>
            <h4 class="quesitoLabel">Devi inserire <?php echo ($datiTest[3]); ?> risposte in totale</h4>
            <input type="submit" class="salvaBtn" id="settaRispostaCorretta" value="Aggiungi Risposta">
        </form>
        
    </div>

    <script>

        function aggiungiCampo() {
            var soluzioneInput = document.getElementById('soluzioneT');
            soluzioneInput.value = ''; // Svuota il campo "soluzioneT"
            var campoTestoInput = document.getElementById('campoTestoT');
            campoTestoInput.value = ''; // Svuota il campo "campoTestoT"
        }
        
        function setSubmitAction(action) {
            document.getElementById('submitAction' + action).value = action;
        }

    </script>
</body>