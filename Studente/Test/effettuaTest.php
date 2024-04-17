<?php
include '../../connessione.php';
include '../../Condiviso/Test.php';

if (!isset($_SESSION)){
    session_start();
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
            width: 100%;
            height: 100%;
            background-color: #f9acac;
        }
        
        .container {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            border-radius: 5px;
            word-wrap: break-word; /* Imposta il wrapping del testo */

        }

        .containerDomande{
            width: 50%;
            background-color: #edeeee;
            margin: 20px auto;
            align-content: center;
            padding: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .test-details {
            list-style-type: none;
            padding: 0;
        }
        .test-item {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .classQuesito{
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
        }
        .btnVerifica{
            width: auto;
            height: auto;
            border: 1px solid #222222;
            padding: 3px;
            margin: 5px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #acf9ba; 
        }
        .btnSalva{
            width: auto;
            height: auto;
            border: 1px solid #222222;
            padding: 3px;
            margin: 5px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #7cfc00; 
        }
        .testH2{
            text-align: center;
            margin-bottom: 20px;
            font:  sans-serif;
            font-style: bold;
        }
        .classInserimento{
            text-align: left;
            margin-bottom: 20px;
            font:  sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        .areaCodice{
            width: 80%;
            display: block;
            margin:auto;
        }
        .labelVerifica{
            text-align: center;
            font: sans-serif;
            font-weight: bold;
            font-size: medium;
            
            color: black;
            height: auto;
            width: auto;
            display: block;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class='testH2'>Effettua il Test</h2>
        <ul class="test-details">
            <?php

                    $test = new Test();    
                    $primaRisposta = true;

                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        if (isset($_GET['id'])) {
                            $testId = $_GET['id'];
                            $_SESSION['datiQuesito'] = array(); // contatore - numero progressivo (si aggiunge con array_push($datiQuesito, array("numero progressivo 2", "numero domanda 2"));                        )
                            $_SESSION['titoloTest'] = $testId;
                            // Verifica se il completamento esiste e aprilo se necessario
                            $test->creaOApriCompletamento($testId, $_SESSION['email']);
                            mostraDatiTest($_SESSION['titoloTest'], '', -1);
                            creaGrafica($_SESSION['titoloTest']);
                            
                        }
                    }

                    
                    /* TO DO:
                    - Salvare i dati quando viene premuto "Salva Test"
                    - Capire perchè non funziona la verifica della risposta di codice
                    */
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['salvaTest'])) {
                        $testId = $_POST['titoloTest'];
                        $contatore = $_POST['numeroDomanda'];
                        salvaDatiTest($testId, $contatore);
                    } else if (isset($_POST['verificaRisposta'])) {
                        // Ottengo i dati
                        $testId = $_POST['titoloTest'];
                        $domanda = $_POST['verificaRisposta'];
                        echo "richiesta su domanda: $domanda<br>";
                        $numQuesito = $_POST['numeroQuesito' . ";" . $domanda];
                        $tipologiaQuesito = $_POST['tipologiaQuesito' . ";" . $domanda];
                        $rispostaData = $_POST['codice'];

                        $idCompletamento = $test->trovaIdCompletamento($testId, $_SESSION['email']);

                        //verifico l'esito della risposta Data
                        $quesitoOgg = new Quesito();
                        // TODO: chiamare metodo da realizzare (input: $rispostaData, output: $risultatoVerifica)
                        //$risultatoVerifica = $quesitoOgg->ottieniRispostaCorrettaCodice($testId, $numQuesito);
                        $risultatoVerifica = true;
                        $aggiuntaRisposta = $test->inserisciRispostaQuesitoCodice($idCompletamento, $testId, $rispostaData, $numQuesito, $risultatoVerifica);
                        if ($aggiuntaRisposta == 1) {
                            if ($risultatoVerifica) {
                                $esito = "Risposta corretta b";
                            } else {
                                $esito = "Risposta sbagliata b";
                            }
                            // Aggiorno la label con l'esito (non capisco perchè non funziona)
                            echo "<script>
                                    window.alert('Esito: " . $esito . "');
                                </script>";
    
    
                            $numDomanda = $_POST['numeroDomanda']; //Serve solo per la stampa delle domande
                            mostraDatiTest($testId, $esito, $numDomanda);
                            creaGrafica($testId);
                        }
                        else {
                            echo "<script>
                                    window.alert('Errore nell'inserimento della risposta');
                                </script>";
                        }
                        
                    }
                }

            function mostraDatiTest($testId, $testoVerifica, $numDomanda)
            {
                global $test;
                $testDetails = $test->ottieniTest($testId);
                echo "<form method='post' action='effettuaTest.php?id='".$_SESSION['titoloTest'].">";
                echo "<h2 class='testH2'>" . $testDetails['Titolo'] . "</h2><br>";
                echo "<div class='containerDomande'>";
                ottieniQuesiti($testDetails['Titolo'], $testoVerifica, $numDomanda);
                echo "</div>";
            }

            function ottieniQuesiti($titoloTest, $esito, $numDomanda)
            {
                global $test;
                $quesiti = $test->ottieniQuesitiPerTest($titoloTest);
                
                // Verifica se ci sono quesiti
                if (empty($quesiti)) {
                    echo "<br><p class='classQuesito'>Nessun quesito presente</p>";
                } else {
                    $contatore = 0; // Contatore per il numero di domande
                    foreach ($quesiti as $quesito) {
                        $contatore++;

                        echo "<br><p class='classQuesito'>Quesito nr." . $contatore . "</p>";
                        $numeroProgressivo = $quesito['NumeroProgressivo'];
                        $livelloDifficolta = $quesito['LivelloDifficolta'];
                        $descrizione = $quesito['Descrizione'];
                        $numeroRisposte = $quesito['NumeroRisposte'];
                        //array_push($_SESSION['datiQuesito'], array($contatore, $numeroProgressivo));


                        $quesitoOgg = new Quesito();
                        $tipologiaQuesito = $quesitoOgg->ottieniTipologiaQuesito($titoloTest, $numeroProgressivo);


                        echo "<p>Domanda:\n" . $descrizione . "</p>";
                        echo "<input type='hidden' name='numeroQuesito" . ";" . $contatore . "' value='$numeroProgressivo'>";
                        echo "<input type='hidden' name='tipologiaQuesito" . ";" . $contatore . "' value='$tipologiaQuesito'>";
                        echo "<input type='hidden' name='numeroDomanda' value='$contatore'>";
                        echo "<input type='hidden' name='titoloTest' value='$titoloTest'>";

                        // Gestione grafica delle risposte
                        if ($tipologiaQuesito == "Risposta Chiusa") {
                            echo "<br><p class='classInserimento'>Seleziona la risposta corretta:</p>";
                            $soluzioni = $test->ottieniRisposte($numeroProgressivo, $titoloTest);
                            if (!empty($soluzioni)) {
                                
                                foreach ($soluzioni as $soluzione) {
                                    $risposta = $soluzione['CampoTesto'];
                                    echo "<input type='radio' name='risposta" . ";" . $contatore . "' value='$risposta' data-quesito='$numeroProgressivo'>$risposta<br>";
                                }
                            }
                        } elseif ($tipologiaQuesito == "Codice") {
                            echo "<p class='classInserimento'>Inserisci il codice:</p>";
                            echo "<textarea class='areaCodice' id='codice' name='codice' rows='10' cols='50'></textarea>";
                            echo "<button type='submit' class='btnVerifica' name='verificaRisposta' value='$contatore' >Verifica Risposta</button>";
                            echo "<label id='messaggioDiVerifica" . $contatore . "' class='labelVerifica'>.</label>";
                            echo "</form>";

                        }

                        // Visualizza l'esito se disponibile
                        if ($contatore == $numDomanda && !empty($esito)) {
                            echo "<br><label class='labelVerifica'>$esito</label>";

                        }

                        // Visualizza il pulsante per salvare il test se si è all'ultimo quesito
                        if ($contatore == count($quesiti)) {
                            echo "<br><br>";
                            echo "<form method='post' action='effettuaTest.php?id=".$_SESSION['titoloTest']."'>";
                            echo "<input class='btnSalva' type='submit' name='salvaTest' value='Salva Test'>";
                            echo "<input type='hidden' name='titoloTest' value='$titoloTest'>";
                            echo "</form>";

                        }
                    }
                }
            }


            function creaGrafica($testId)
            {
                echo "<input type='hidden' id='titoloTest' name='titoloTest' value=" . $testId . ">";
                echo "<br>";
            }
            
            function salvaDatiTest($titoloTest, $numeroDomandePresenti){
                global $test;
                
                for ($i = 1; $i <= $numeroDomandePresenti; $i++) {
                    $numeroQuesito = $_POST['numeroQuesito' . ";" . $i];
                    $tipologiaQuesito = $_POST['tipologiaQuesito' . ";" . $i];
                    $idCompletamento = $test->trovaIdCompletamento($titoloTest, $_SESSION['email']);
                    if ($tipologiaQuesito == "Risposta Chiusa") {
                        $rispostaData = $_POST['risposta' . ";" . $i];
                        $test->inserisciRispostaQuesitoRispostaChiusa($idCompletamento, $titoloTest, $rispostaData, $numeroQuesito);
                    } else if ($tipologiaQuesito == "Codice") {
                        $rispostaData = $_POST['risposta' . ";" . $i]; // Sbagliato, ma non so come fare
                        $test->inserisciRispostaQuesitoCodice($idCompletamento, $titoloTest, $rispostaData, $numeroQuesito);
                    }
                }
                $_SESSION['titoloTest'] = "";
                $_SESSION['datiQuesito'] = array();
                echo "<script>alert('Test salvato con successo.');</script>";
                header("Location: ../navBar/testStudenti.php");
                exit();
            }
         ?>
        </ul>
    </div>
</body>
</html>




