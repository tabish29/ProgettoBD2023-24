
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
            if (!isset($_SESSION)){
                session_start();
            }

           
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                function ottieniQuesiti($titoloTest){
                    include '../connessione.php';
                    
                    $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest('$titoloTest')";
                    $result_quesiti_test = $conn->query($sql_quesiti_test);
                    $conn->next_result();
                    $num = 1;
                    if ($result_quesiti_test->num_rows>0) {
                        while ($row = $result_quesiti_test->fetch_assoc()) {
                            echo "<div class=\"divQuesiti\"> <br><label class='quesitoLabel'>Quesito nr." . $num . "</label><br>"; // Attenzione non deve corrispondere al progressivo
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
                                echo "
                                <span style=\"display: inline;\">
                                    <label class='label' style=\"display: inline;\">Tipologia: </label>"
                                    . $tipologiaQuesito . "<br><br>" . "
                                    <label class='label'  style=\"display: inline;\">Livello Difficoltà: </label>"
                                    . $dati[1] . "<br><br>" . "
                                    <label class='label'  style=\"display: inline;\">Descrizione: </label>"
                                    . $dati[2]  ."<br><br>" . "
                                    <label class='label' style=\"display: inline;\">Numero Risposte: </label>"
                                    . $dati[3] . "<br><br>";


                                $sql_soluzioni = "SELECT CampoTesto, RispostaCorretta FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                                $result_soluzioni = $conn->query($sql_soluzioni);
                                $conn->next_result();
                                
                                echo "<label class='label'  style=\"display: inline;\">Soluzioni: </label><br>";
                                while ($soluzione = $result_soluzioni->fetch_assoc()) {
                                    if ($soluzione['RispostaCorretta'] == 1) {
                                        echo "<label class='labelVerde'> - " . $soluzione['CampoTesto'] . "</label><br>";
                                    } else {
                                        echo "<label class='labelRosso'> - " . $soluzione['CampoTesto'] . "</label><br>";
                                    }
                                }
                                echo "<br></span></div><br>";

                                

                            } 
                            
                            if ($result_quesitoCodice->num_rows>0){
                                $num++;
                                $tipologiaQuesito = "Codice";

                                echo " 
                                    <span style=\"display: inline;\">
                                    <label class='label'style=\"display: inline;\">Tipologia: </label>"
                                    . $tipologiaQuesito . "<br><br>" . "
                                    <label class='label' style=\"display: inline;\">Livello Difficoltà: </label>"
                                    . $dati[1] . "<br><br>" . "
                                    <label class='label'  style=\"display: inline;\">Descrizione: </label>"
                                    . $dati[2] ."<br><br>" . "
                                    <label class='label'  style=\"display: inline;\">Numero Risposte: </label>"
                                    . $dati[3] . "<br><br>";

                                

                                $sql_soluzioni = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                                $result_soluzioni = $conn->query($sql_soluzioni);
                                $conn->next_result();
                                echo "<label class='label'  style=\"display: inline;\">Soluzioni: </label><br>";
                            
                                while ($soluzione = $result_soluzioni->fetch_assoc()) {
                                    echo "- " . $soluzione['TestoSoluzione'] . "<br>";
                                }
                                echo "<br></span></div><br>";

                            }
                        }

                    } else {
                        echo "Nessun quesito presente";
                    
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
