
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
        .btn {
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
        p{
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
            margin: 0px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #acf9ba; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Effettua il Test</h2>
        <ul class="test-details">
            <?php
                include 'connessione.php';
                if (!isset($_SESSION)){
                    session_start();
                }

                
                $primaRisposta = true;

                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $testId = $_GET['id'];
                    // Se non esiste il completamento, lo creo
                    $sql_cercaCompletamento = "SELECT * FROM COMPLETAMENTO WHERE TitoloTest = '$testId' AND EmailStudente = '" . $_SESSION['email'] . "'";
                    $result_cercaCompletamento = $conn->query($sql_cercaCompletamento);
                    if ($result_cercaCompletamento->num_rows == 0) {
                        //CRea il completamento se non esiste
                        $sql_creaCompletamento = "INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente) VALUES ('Aperto', '$testId', '" . $_SESSION['email'] . "')";
                        $result_creaCompletamento = $conn->query($sql_creaCompletamento);
                        $conn->next_result();
                    } else {
                        //APre il completamento se era stato concluso
                        $stato = $result_cercaCompletamento->fetch_assoc()['Stato'];
                        if ($stato == 'Concluso') {
                            $sql_apriCompletamento = "UPDATE COMPLETAMENTO SET Stato = 'Aperto' WHERE TitoloTest = '$testId' AND EmailStudente = '" . $_SESSION['email'] . "'";
                            $result_apriCompletamento = $conn->query($sql_apriCompletamento);
                            $conn->next_result();
                        }
                        
                        
                    }
                    mostraDatiTest($testId,'',-1);
                    creaGrafica($testId);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['terminaTest'])) {
                        $testId = $_POST['titoloTest'];
                        
                        $sql_settaConcluso = "UPDATE COMPLETAMENTO SET Stato = 'Concluso' WHERE NumeroProgressivo = (SELECT NumeroProgressivo FROM COMPLETAMENTO WHERE Stato <> 'Concluso' AND TitoloTest = '$testId' AND EmailStudente = '" . $_SESSION['email'] . "')";
                        $conn->query($sql_settaConcluso);
                        $conn->next_result();
                        echo "<p>Test terminato con successo.</p>";
                        header("Location: testStudenti.php");
                        exit();
                    }
                    else if(isset($_POST['verificaRisposta'])){
                        $esito = "";
                        echo "Esito inizio: " . $esito . "<br>";
                        $testId = $_POST['titoloTest'];
                        $numQuesito = $_POST['numeroQuesito'];
                        $tipologiaQuesito = $_POST['tipologiaQuesito'];
                        $rispostaData = "";
                        

                        $testoVerifica = ""; 
                        //PASSO 1: cerco l'id del completamento
                        $sql_cercaCompletamento = "SELECT NumeroProgressivo FROM COMPLETAMENTO WHERE Stato <> 'Concluso' AND TitoloTest = '$testId' AND EmailStudente = '" . $_SESSION['email'] . "'";
                        $idCompletamento = $conn->query($sql_cercaCompletamento)->fetch_assoc()['NumeroProgressivo'];
                        echo "ID Completamento: " . $idCompletamento . "<br>";


                        //PASSO 2: inserisco la risposta
                        $sql_inserimentoRisposta = "";
                        if ($tipologiaQuesito == "Codice") {
                            $sql_inserimentoRisposta = "CALL inserisciRispostaQuesitoCodice(?, ?, ?, ?)";
                        } else if ($tipologiaQuesito == "Risposta Chiusa") {
                            $sql_inserimentoRisposta = "CALL inserisciRispostaQuesitoRispostaChiusa(?, ?, ?, ?)";
                        }
                        
                        if ($tipologiaQuesito == "Risposta Chiusa"){
                            $rispostaData = $_POST['rispostaData'];
                        } else if ($tipologiaQuesito == "Codice") {
                            $rispostaData = $_POST['codice'];
                        }

                        $stmt = $conn->prepare($sql_inserimentoRisposta);
                        $stmt->bind_param("issi", $idCompletamento, $testId, $rispostaData, $numQuesito);
                        $stmt->execute();
                        $stmt->close();

                        //PASSO 2.1 -> setto la data della risposta inserita se è la prima risposta
                        if ($primaRisposta){
                            $sql_aggiornaDataPrima = "UPDATE COMPLETAMENTO SET DataPrimaRisposta = NOW() WHERE NumeroProgressivo = $idCompletamento AND DataPrimaRisposta IS NULL";
                            $conn->query($sql_aggiornaDataPrima);
                            $conn->next_result();
                            $primaRisposta = false;
                        }

                        //PASSO 2.2 -> setto la data della risposta inserita come data dell'ultima risposta
                        $sql_aggiornaDataUltima = "UPDATE COMPLETAMENTO SET DataUltimaRisposta = NOW() WHERE NumeroProgressivo = $idCompletamento";
                        $conn->query($sql_aggiornaDataUltima);
                        $conn->next_result();

                        echo "idCompletamento: " . $idCompletamento . "<br>";
                        echo "testId: " . $testId . "<br>";
                        echo "rispostaData: " . $rispostaData . "<br>";
                        echo "numQuesito: " . $numQuesito . "<br>";

                        //PASSO 3: verifico l'esito
                        $sql_verificaRisposta = "CALL visualizzaEsitoRisposta(?, ?, ?, @esitoQ)";
                        $stmt = $conn->prepare($sql_verificaRisposta);
                        $stmt->bind_param("isi", $idCompletamento, $testId, $numQuesito);
                        $stmt->execute();
                        $stmt->close();

                        

                        // Ora esegui una query separata per recuperare il valore del parametro di output
                        $sql_esito = "SELECT @esitoQ AS esitoQ";
                        $stmt = $conn->prepare($sql_esito);
                        $stmt->execute();
                        $stmt->bind_result($esito);
                        $stmt->fetch();
                        $stmt->close();

                        echo "Esito: " . $esito . "<br>";
                        

                        if ($esito){
                            $testoVerifica = "Risposta corretta!";
                        } else {
                            $testoVerifica = "Risposta errata!";
                        }

                        echo "Verifica Risposta: " . $testoVerifica;

                        $esito = "";
                        $numDomanda = $_POST['numeroDomanda'];
                        mostraDatiTest($testId,$testoVerifica,$numDomanda);
                        creaGrafica($testId);
                        }
                    
                }

                

                function ottieniQuesiti($titoloTest, $testoVerifica, $numDomanda) {
                    include 'connessione.php';
                    
                    $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest('$titoloTest')";
                    $result_quesiti_test = $conn->query($sql_quesiti_test);
                    


                    $numeroQuesitiTest = $result_quesiti_test->num_rows;

                    $conn->next_result();
                    $num = 0;
                    if ($result_quesiti_test->num_rows > 0) {
                        while ($row = $result_quesiti_test->fetch_assoc()) {
                            $num++;
                            
                            echo "<br><p>Quesito nr." . $num . "</p>";                 
                            $numeroProgressivo = $row['NumeroProgressivo'];
                            $livelloDifficolta = $row['LivelloDifficolta'];
                            $descrizione = $row['Descrizione'];
                            $numeroRisposte = $row['NumeroRisposte'];
                            $dati = [$numeroProgressivo, $livelloDifficolta, $descrizione, $numeroRisposte];

                            $tipologiaQuesito = "";

                            $sql_quesitoRC = "SELECT * FROM QUESITORISPOSTACHIUSA WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                            $result_quesitoRC = $conn->query($sql_quesitoRC);
                            $conn->next_result();

                            $sql_quesitoCodice = "SELECT * FROM QUESITOCODICE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                            $result_quesitoCodice = $conn->query($sql_quesitoCodice);
                            $conn->next_result();

                            if ($result_quesitoRC->num_rows > 0) {
                                $tipologiaQuesito = "Risposta Chiusa";
                                echo "<p>Domanda:</p>" . $dati[2] . "<br>";
                                
                            } 
                            
                            if ($result_quesitoCodice->num_rows > 0){
                                $tipologiaQuesito = "Codice";
                                echo "<p>Domanda:\n" . $dati[2] . "</p><br>";
                            }

                            
                            // Da qui in poi è inserita la grafica delle risposte
                            if (!isset($contatore)){
                                $contatore = 0;
                            }
                            
                            if ($tipologiaQuesito == "Risposta Chiusa") {
                                $contatore++;
                                echo "<br><p>Seleziona la risposta corretta:</p>";
                                $sql_risposte = "SELECT * FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                                $result_risposte = $conn->query($sql_risposte);
                                $conn->next_result();
                                if ($result_risposte->num_rows > 0) {
                                    while ($row = $result_risposte->fetch_assoc()) {
                                        $risposta = $row['CampoTesto'];
                                        echo "<input type='radio' name='risposta' value='$risposta' data-quesito='$numeroProgressivo'>$risposta<br>";
                                    }
                                    echo "<form id='form_verifica' method='post' action='effettuaTest.php'>";
                                    echo "<input type='hidden' name='tipologiaQuesito' value='$tipologiaQuesito'>";
                                    echo "<input type='hidden' name='numeroQuesito' value='$numeroProgressivo'>";
                                    echo "<input type='hidden' name='numeroDomanda' value='" . $contatore . "'>";
                                    echo "<input type='hidden' name='titoloTest' value='" . $titoloTest . "'>";
                                    echo "<input type='hidden' name='rispostaData' id='risposta_selezionata' value=''>"; // Campo nascosto per la risposta selezionata
                                    echo "<button type='submit' name='verificaRisposta' class='btnVerifica' onclick='setRispostaSelezionata()'>Verifica Risposta</button>"; // Invoca la funzione JavaScript al clic
                                    if ($contatore == $numDomanda){
                                        echo "<br><label>" . $testoVerifica . "</label>";
                                    }                          
                                    echo "</form>";
                                    echo "
                                    <script>
                                        function setRispostaSelezionata() {
                                            var rispostaSelezionata = document.querySelector('input[name=\"risposta\"]:checked').value; // Ottieni il valore selezionato
                                            document.getElementById('risposta_selezionata').value = rispostaSelezionata; // Imposta il valore nell'input nascosto
                                        }
                                    </script>";
                                } 
                                

                        } else if ($tipologiaQuesito == "Codice") {
                                $contatore++;
                                
                                echo "<form method='post' action='effettuaTest.php'>";
                                echo "<p>Inserisci il codice:</p>";
                                echo "<textarea id='codice' name='codice' rows='10' cols='50'></textarea>";
                                echo "<input type='hidden' name='tipologiaQuesito' value='$tipologiaQuesito'>";
                                echo "<input type='hidden' name='numeroQuesito' value='$numeroProgressivo'>";
                                echo "<input type='hidden' name='numeroDomanda' value='" . $contatore . "'>";
                                echo "<input type='hidden' name='titoloTest' value='" . $titoloTest . "'><br>";
                                echo "<button type='submit' class='btnVerifica' name='verificaRisposta'>Verifica Risposta</button>";       

                                if ($contatore == $numDomanda){
                                    echo "<br><label>" . $testoVerifica . "</label>";
                                }
                                echo "</form>";

                                echo "<br><br>";
                                

                            }
                            
                            if ($contatore == $numeroQuesitiTest) { //num = numero quesiti del test
                                echo "<br><br>";
                                echo "<form method='post' action='effettuaTest.php'>";
                                echo "<input class='btn' type='submit' name='terminaTest' value='Termina Test'>";
                                echo "<input type='hidden' name='titoloTest' value='" . $titoloTest . "'>";
                                echo "</form>";
                            }
                        }
                    } else {
                        echo "Nessun quesito presente";
                    }
                }

                function mostraDatiTest($testId,$testoVerifica,$numDomanda) {
                    include 'connessione.php';

                    $sql_select_test = "SELECT * FROM TEST WHERE Titolo = '$testId'";
                    $result_select_test = $conn->query($sql_select_test);
                    $conn->next_result();
                    if ($result_select_test->num_rows > 0) {
                        $row = $result_select_test->fetch_assoc();
                        echo "<p>Titolo Test:\n" . $row['Titolo']. "</p><br>";

                        ottieniQuesiti($row['Titolo'], $testoVerifica, $numDomanda);
                    } else {
                        echo "<li class='test-item'>Nessun test trovato con l'ID specificato.</li>";
                    }
                }

                function creaGrafica($testId) {
                    echo "
                        <input type='hidden' id='titoloTest' name='titoloTest' value=" . $testId . ">
                        <br>
                    ";
                }
            ?>
        </ul>
    </div>
</body>
</html>
