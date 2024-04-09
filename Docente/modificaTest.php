
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
        
        .divQuesiti{
            background-color: #fcfcf0;
            margin: auto;
            width: 30%;
            height: 30%;
        }
        .creaBtn{
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
        
        .areaInserimento{
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
        .quesitoLabel{
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
        }
        .labelVerde{
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: green;
        }
        .labelRosso{
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: red;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifica Test</h2>
        <ul>
        <?php
            include '../connessione.php';
            include 'Test.php';

            if (!isset($_SESSION)){
                session_start();
            }

           
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                function creaGraficaQuesiti($titoloTest){
                    global $test;
                    $test = new Test();
                    $quesiti = $test->ottieniQuesiti($titoloTest);
                    
                    if (empty($quesiti)) {
                        echo "<li class='test-item'>Nessun quesito presente.</li>";
                    } else {
                        foreach ($quesiti as $quesito) {
                            echo "<span style=\"display: inline;\">";
                
                            // Accesso ai dati del quesito
                            foreach ($quesito as $chiave => $valore) {
                                echo "<label class='label'>" . $chiave . ": </label>" . $valore . "<br>";
                            }
                
                            // Verifica della tipologia del quesito
                            if ($quesito['Tipologia'] == "Risposta Chiusa") {
                                // Se è una risposta chiusa, ottieni e visualizza le soluzioni
                                $soluzioni = $test->ottieniRisposte($quesito['NumeroProgressivo'], $titoloTest);
                                echo "<label class='label'>Soluzioni:</label><br>";
                                if (empty($soluzioni)) {
                                    echo "<label class='label'>Nessuna soluzione presente.</label><br>";
                                } else {
                                    foreach ($soluzioni as $soluzione) {
                                        echo "<label class='label'>Campo Testo: </label>" . $soluzione['CampoTesto'] . "<br>";
                                    }
                                }
                            } else if ($quesito['Tipologia'] == "Codice") {
                                // Se è un quesito di tipo Codice, ottieni e visualizza le soluzioni
                                $soluzioni = $test->ottieniSoluzioni($quesito['NumeroProgressivo'], $titoloTest);
                                echo "<label class='label'>Soluzioni:</label><br>";
                                if (empty($soluzioni)) {
                                    echo "<label class='label'>Nessuna soluzione presente.</label><br>";
                                } else {
                                    $num = 1;
                                    foreach ($soluzioni as $soluzione) {
                                        echo "<label class='label'>" . $num . " - Testo Soluzione: </label>" . $soluzione['TestoSoluzione'] . "<br>";
                                        $num++;
                                    }
                                }
                            }
                
                            echo "</span><br>";
                        }
                    }
                }
                
                

                    
                      

                function mostraDatiTest(){
                    include '../connessione.php';
                    // Preleva il Titolo del test dalla query string
                    $testId = $_GET['id'];

                    // Esegue la query per selezionare il test dal database
                    $sql_select_test = "SELECT * FROM TEST WHERE Titolo = '$testId'";
                    $result_select_test = $conn->query($sql_select_test);
                    $conn->next_result();
                    // Verifica se il test è stato trovato
                    if ($result_select_test->num_rows > 0) {
                        $row = $result_select_test->fetch_assoc();
                        echo
                        "<span style=\"display: inline;\">
                            <label class='label' for='titolo' style=\"display: inline;\">Titolo Test: </label>"
                            . $row['Titolo'] . "<br><br>" . "
                            <label class='label' for='titolo' style=\"display: inline;\">Data Creazione: </label>"
                            . $row['DataCreazione'] . "<br><br>" . "
                            <label class='label' for='titolo' style=\"display: inline;\">Visualizza Risposte: </label>"
                            . $row['VisualizzaRisposte'] ."<br><br>" . "
                            <label class='label' for='titolo' style=\"display: inline;\">Email Docente: </label>"
                            . $row['EmailDocente'] . "<br><br>
                        </span>";
                        

                        creaGraficaQuesiti($row['Titolo']);
                    } else {
                        echo "<li class='test-item'>Nessun test trovato con l'ID specificato.</li>";
                    }
                }
                
                function creaGraficaValoriComuni() {
                    $testId = $_GET['id'];
                    echo "
                        <form id='modificaTestForm' action='modificaTest.php' method='post'>
                            <input type='hidden' id ='titoloTest' name='titoloTest' value=" . $testId . ">
                            <br>
                            <label for='visualizzaRisposte'>Visualizza Risposte:</label>
                            <input type='checkbox' id='visualizzaRisposteCB' name='visualizzaRisposte'>
                            <br>
                            <input type='hidden' name='action' value='crea'><br>
                            <button type='submit' class='btn'  id='modificaTestButton' value='modifica'>Salva</button>
                        </form>
                        <br>
                        <button id='inserisciQuesito' class='btn' onclick=\"window.location.href='inserisciQuesito.php?id=" . $testId . "'\">Aggiungi Quesito</button>
                        <button id='inserisciTabella' class='btn' onclick=\"window.location.href='inserisciTabella.php?id=" . $testId . "'\">Inserisci Tabella</button>
                        
                        <button id='tornaTest' class='btn' onclick='window.location.href=\"testDocenti.php\"'>Torna ai Test</button>
                        ";
                    
                    
                    
                }

                mostraDatiTest();

                creaGraficaValoriComuni();
        
                        
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
