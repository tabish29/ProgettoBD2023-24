
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

                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $testId = $_GET['id'];
                    mostraDatiTest($testId,'');
                    creaGrafica($testId);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $testId = $_POST['titoloTest'];
                    $testoVerifica = "risposta sbagliata/corretta"; //TODO: verificare la risposta
                    mostraDatiTest($testId,$testoVerifica);
                    creaGrafica($testId);
                    // Qui puoi inserire la logica per gestire i dati inviati tramite il form
                }

                function verificaRisposta() {
                    echo "
                        <script>
                            // Qui puoi inserire la logica JavaScript per la verifica della risposta
                        </script>
                    ";
                }

                function graficaRisposte($numeroProgressivo, $titoloTest, $tipologiaQuesito, $numeroQuesitiTest, $testoVerifica) {
                    include 'connessione.php';
                    verificaRisposta();
                    $contatore = 1;
                    
                    if ($tipologiaQuesito == "Risposta Chiusa") {
                        echo "<br><p>Seleziona la risposta corretta:</p>";
                        $sql_risposte = "SELECT * FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                        $result_risposte = $conn->query($sql_risposte);
                        $conn->next_result();
                        if ($result_risposte->num_rows > 0) {
                            while ($row = $result_risposte->fetch_assoc()) {
                                $risposta = $row['CampoTesto'];
                                echo "<input type='radio' name='risposta' value='$risposta' data-quesito='$numeroProgressivo'>$risposta<br>";
                            }
                            echo "<form method='post' action='effettuaTest.php'>";
                            echo "<input type='hidden' name='numeroQuesito' value='$numeroProgressivo'>";
                            echo "<input type='hidden' name='titoloTest' value='" . $titoloTest . "'>";
                            echo "<button type='submit' class='btnVerifica'>Verifica Risposta</button>";
                            echo "<br><label>" . $testoVerifica . "</label>";                            
                            echo "</form>";

                        } 
                        $contatore++;
                   } else if ($tipologiaQuesito == "Codice") {
                        echo "<p>Inserisci il codice:</p>";
                        echo "<textarea id='codice' name='codice' rows='10' cols='50'></textarea>";
                        echo "<form method='post' action='effettuaTest.php'>";
                        echo "<input type='hidden' name='numeroQuesito' value='$numeroProgressivo'>";
                        echo "<input type='hidden' name='titoloTest' value='" . $titoloTest . "'>";
                        echo "<button type='submit' class='btnVerifica'>Verifica Risposta</button>";
                        echo "<br><label>" . $testoVerifica . "</label>";
                        echo "</form>";

                        echo "<br><br>";
                        $contatore++;
                    }
                    
                    if ($contatore == $numeroQuesitiTest) {
                        echo "<br><br>";
                        echo "<input class='btn' type='submit' value='Termina Test'>"; 
                    }
                }

                function ottieniQuesiti($titoloTest, $testoVerifica) {
                    include 'connessione.php';
                    
                    $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest('$titoloTest')";
                    $result_quesiti_test = $conn->query($sql_quesiti_test);
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

                            graficaRisposte($numeroProgressivo, $titoloTest, $tipologiaQuesito, $num, $testoVerifica);
                        }
                    } else {
                        echo "Nessun quesito presente";
                    }
                }

                function mostraDatiTest($testId,$testoVerifica) {
                    include 'connessione.php';

                    $sql_select_test = "SELECT * FROM TEST WHERE Titolo = '$testId'";
                    $result_select_test = $conn->query($sql_select_test);
                    $conn->next_result();
                    if ($result_select_test->num_rows > 0) {
                        $row = $result_select_test->fetch_assoc();
                        echo "<p>Titolo Test:\n" . $row['Titolo']. "</p><br>";

                        ottieniQuesiti($row['Titolo'], $testoVerifica);
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
