
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
        .btnTermina{
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
                include '../connessione.php';
                include '../Condiviso/Test.php';
                
                if (!isset($_SESSION)){
                    session_start();
                }

                $test = new Test();
                
                $primaRisposta = true;

                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    if (isset($_GET['id'])) {
                        $testId = $_GET['id'];
                        // Verifica se il completamento esiste e aprilo se necessario
                        $test->creaOApriCompletamento($testId, $_SESSION['email']);
                        mostraDatiTest($testId, '', -1);
                        creaGrafica($testId);
                    }
                }

                
                /*TO DO:
                - Salvare i dati quando viene premuto "Termina Test"
                - Capire perchè non funziona la verifica della risposta di codice
                */
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['terminaTest'])) {
                    $testId = $_POST['titoloTest'];
                    // Chiudi il test
                    $test->chiudiTest($testId, $_SESSION['email']);
                    echo "<p>Test terminato con successo.</p>";
                    header("Location: testStudenti.php");
                    exit();
                } else if (isset($_POST['verificaRisposta'])) {
                    // Verifica la risposta
                    $testId = $_POST['titoloTest'];
                    $numQuesito = $_POST['numeroQuesito'];
                    $tipologiaQuesito = $_POST['tipologiaQuesito'];
                    $rispostaData = $_POST['codice'];

                    $rispostaData = "";

                    $idCompletamento = $test->trovaIdCompletamento($testId, $_SESSION['email']);

                    $quesitoOgg = new Quesito();

                    $risultatoVerifica = $quesitoOgg->verificaRispostaQuesitoCodice($testId, $numQuesito, $rispostaData);
                    if ($risultatoVerifica) {
                        $esito = "Risposta corretta";
                    } else {
                        $esito = "Risposta errata";
                    }

                    $numDomanda = $_POST['numeroDomanda'];
                    mostraDatiTest($testId, $esito, $numDomanda);
                    creaGrafica($testId);
                }
            }

        function mostraDatiTest($testId, $testoVerifica, $numDomanda)
        {
            
            global $test;
            $testDetails = $test->ottieniTest($testId);
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
                    $quesitoOgg = new Quesito();
                    $tipologiaQuesito = $quesitoOgg->ottieniTipologiaQuesito($titoloTest, $numeroProgressivo);

                    echo "<p>Domanda:\n" . $descrizione . "</p>";

                    // Gestione grafica delle risposte
                    if ($tipologiaQuesito == "Risposta Chiusa") {
                        echo "<br><p class='classInserimento'>Seleziona la risposta corretta:</p>";
                        $soluzioni = $test->ottieniRisposte($numeroProgressivo, $titoloTest);
                        if (!empty($soluzioni)) {
                            foreach ($soluzioni as $soluzione) {
                                $risposta = $soluzione['CampoTesto'];
                                echo "<input type='radio' name='risposta' value='$risposta' data-quesito='$numeroProgressivo'>$risposta<br>";
                            }
                        }
                    } elseif ($tipologiaQuesito == "Codice") {
                        echo "<form method='post' action='effettuaTest.php'>";
                        echo "<p class='classInserimento'>Inserisci il codice:</p>";
                        echo "<textarea class='areaCodice' id='codice' name='codice' rows='10' cols='50'></textarea>";
                        echo "<input type='hidden' name='tipologiaQuesito' value='$tipologiaQuesito'>";
                        echo "<input type='hidden' name='numeroQuesito' value='$numeroProgressivo'>";
                        echo "<input type='hidden' name='numeroDomanda' value='$contatore'>";
                        echo "<input type='hidden' name='titoloTest' value='$titoloTest'><br>";
                        echo "<button type='submit' class='btnVerifica' name='verificaRisposta'>Verifica Risposta</button>";
                        echo "</form>";
                    }

                    // Visualizza l'esito se disponibile
                    if ($contatore == $numDomanda && !empty($esito)) {
                        echo "<br><label class='labelVerifica'>$esito</label>";
                    }

                    // Visualizza il pulsante per terminare il test se si è all'ultimo quesito
                    if ($contatore == count($quesiti)) {
                        echo "<br><br>";
                        echo "<form method='post' action='effettuaTest.php'>";
                        echo "<input class='btnTermina' type='submit' name='terminaTest' value='Termina Test'>";
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
            ?>
        </ul>
    </div>
</body>
</html>




