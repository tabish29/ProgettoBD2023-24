
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
            width: 100px;
            height: 30px;
            border: 1px solid #222222;
            padding: 3px;
            margin: 0px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #7cfc00; 
            }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifica Test</h2>
        <ul>
        <?php
            include 'connessione.php';
            if (!isset($_SESSION)){
                session_start();
            }

           
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                function ottieniQuesiti($titoloTest){
                    include 'connessione.php';
                    
                    $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest('$titoloTest')";
                    $result_quesiti_test = $conn->query($sql_quesiti_test);
                    $conn->next_result();
                    $num = 1;
                    if ($result_quesiti_test->num_rows>0) {
                        while ($row = $result_quesiti_test->fetch_assoc()) {
                            echo "Quesito nr." . $num . "<br>"; //ATtenzione non deve corrispondere al progressivo
                            $numeroProgressivo = $row['NumeroProgressivo'];
                            $livelloDifficolta = $row['LivelloDifficolta'];
                            $descrizione = $row['Descrizione'];
                            $numeroRisposte = $row['NumeroRisposte'];
                            $dati = [$numeroProgressivo, $livelloDifficolta,$descrizione,$numeroRisposte];

                            $tipologiaQuesito = "";

                            $sql_quesitoRC = "SELECT * FROM QUESITORISPOSTACHIUSA WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                            $result_quesitoRC = $conn->query($sql_quesitoRC);
                            $conn->next_result();

                            $sql_quesitoCodice = "SELECT * FROM QUESITOCODICE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                            $result_quesitoCodice = $conn->query($sql_quesitoCodice);
                            $conn->next_result();

                            if ($result_quesitoRC->num_rows>0) {
                                $num++;
                                $tipologiaQuesito = "Risposta Chiusa";
                                echo "<details>";
                                echo "<summary>Tipologia:</summary>";
                                echo "" . $tipologiaQuesito . "";
                                echo "</details><br>";
                                echo "<details>";
                                echo "<summary>Livello Difficoltà:</summary>";
                                echo "" . $dati[1] . "";
                                echo "</details><br>";
                                echo "<details>";
                                echo "<summary>Descrizione:</summary>";
                                echo "" . $dati[2] . "";
                                echo "</details><br>";
                                echo "<details>";
                                echo "<summary>Numero Risposte:</summary>";
                                echo "" . $dati[3] . "";
                                echo "</details><br>";

                                $sql_soluzioni = "SELECT CampoTesto FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                                $result_soluzioni = $conn->query($sql_soluzioni);
                                $conn->next_result();
                                if ($result_quesitoRC->num_rows>0) {
                                    $soluzioni = $result_soluzioni->fetch_assoc();

                                    echo "<details>";
                                    echo "<summary>Soluzioni:</summary>";
                                    echo "" . $soluzioni['CampoTesto'] . "";
                                    echo "</details><br>";
                                }

                                

                            } 
                            
                            if ($result_quesitoCodice->num_rows>0){
                                $num++;
                                $tipologiaQuesito = "Codice";
                                echo "<details>";
                                echo "<summary>Tipologia:</summary>";
                                echo "" . $tipologiaQuesito . "";
                                echo "</details><br>";
                                echo "<details>";
                                echo "<summary>Livello Difficoltà:</summary>";
                                echo "" . $dati[1] . "";
                                echo "</details><br>";
                                echo "<details>";
                                echo "<summary>Descrizione:</summary>";
                                echo "" . $dati[2] . "";
                                echo "</details><br>";
                                echo "<details>";
                                echo "<summary>Numero Risposte:</summary>";
                                echo "" . $dati[3] . "";
                                echo "</details><br>";

                                $sql_soluzioni = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                                $result_soluzioni = $conn->query($sql_soluzioni);
                                $conn->next_result();
                                if ($result_quesitoCodice->num_rows>0) {
                                    $soluzioni = $result_soluzioni->fetch_assoc();
                                    echo "<details>";
                                    echo "<summary>Soluzioni:</summary>";
                                    echo "" . $soluzioni['TestoSoluzione'] . "";
                                    echo "</details><br>";
                                }

                            }
                        }

                    } else {
                        echo "Nessun quesito presente";
                    
                    }
                }             

                function mostraDatiTest(){
                    include 'connessione.php';
                    // Preleva il Titolo del test dalla query string
                    $testId = $_GET['id'];

                    // Esegue la query per selezionare il test dal database
                    $sql_select_test = "SELECT * FROM TEST WHERE Titolo = '$testId'";
                    $result_select_test = $conn->query($sql_select_test);
                    $conn->next_result();
                    // Verifica se il test è stato trovato
                    if ($result_select_test->num_rows > 0) {
                        $row = $result_select_test->fetch_assoc();
                        // Visualizza i dettagli del test
                        /*
                        echo "<li class='test-item'>Titolo: " . $row['Titolo'] . "</li>";
                        echo "<li class='test-item'>Data Creazione: " . $row['DataCreazione'] . "</li>";
                        echo "<li class='test-item'>Visualizza Risposte: " . $row['VisualizzaRisposte'] . "</li>";
                        echo "<li class='test-item'>Email: " . $row['EmailDocente'] . "</li>";
                        echo "<li class='test-item'>Quesiti:</li>";*/
                        echo "<details>";
                        echo "<summary>Titolo Test:</summary>";
                        echo "" . $row['Titolo'] . "";
                        echo "</details><br>";
                        echo "<details>";
                        echo "<summary>Data Creazione:</summary>";
                        echo "" . $row['DataCreazione'] . "";
                        echo "</details><br>";
                        echo "<details>";
                        echo "<summary>Visualizza Risposte:</summary>";
                        echo "" . $row['VisualizzaRisposte'] . "";
                        echo "</details><br>";
                        echo "<details>";
                        echo "<summary>Email:</summary>";
                        echo "" . $row['EmailDocente'] . "";
                        echo "</details><br>";
                        

                        ottieniQuesiti($row['Titolo']);
                    } else {
                        echo "<li class='test-item'>Nessun test trovato con l'ID specificato.</li>";
                    }
                }
                
                function creaGrafica() {
                    $testId = $_GET['id'];
                    echo "
                        <form id='modificaTestForm' action='modificaTest.php' method='post'>
                            <input type='hidden' id ='titoloTest' name='titoloTest' value=" . $testId . ">
                            <br>
                            <label for='visualizzaRisposte'>Visualizza Risposte:</label>
                            <input type='checkbox' id='visualizzaRisposteCB' name='visualizzaRisposte'>
                            <br>
                            <input type='hidden' name='action' value='crea'>
                            <button type='submit' class='btn'  id='modificaTestButton' value='modifica'>Modifica</button>
                        </form>
                        <a href='inserisciQuesito.php?id=" . $testId . "' class='btn'>Aggiungi Quesito</a>                        
                        ";

                    
                    
                }

                mostraDatiTest();

                creaGrafica();
        
                        
        }
        


            

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $titolo = $_POST['titoloTest'];
                    $visualizza_risposte = isset($_POST['visualizzaRisposte']) ? '1' : '0';

                    // Query SQL per aggiornare il test nel database
                    $sql_update_test = "UPDATE TEST SET VisualizzaRisposte = $visualizza_risposte WHERE Titolo = '$titolo'";


                    // Esegue la query di aggiornamento
                    if ($conn->query($sql_update_test) === TRUE && mysqli_affected_rows($conn) > 0) {
                        echo "Test aggiornato con successo.";
                        echo '<a href="testDocenti.php">Torna ai Test</a>';
                    } else {
                        echo "Errore durante l'aggiornamento del test: " . $conn->error;
                        echo '<a href="testDocenti.php">Torna ai Test</a>';
                    }
                
            }
            
            

            
        ?>
        </ul>
        
        
    </div>
</body>
</html>
