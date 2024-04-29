<?php
    include '../../connessione.php';
    include '../../Condiviso/Test.php';
    if (!isset($_SESSION)) {
        session_start();
    }
    if ($_SESSION['ruolo'] != 'Docente') {
        echo "Accesso Negato";
        header('Location: ../../Accesso/Logout.php?message=Utente non autorizzato.');
        exit();
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
        .label {
            text-align: center;
            font-family: sans-serif;
            font-weight: bold;
            font-size: medium;
            color: black;
            display: block;
        }
        button{
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
       
        .quesitoLabel {
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
        }
        .labelVerde {
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: green;
        }
        .labelRosso {
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
            $mongoDBManager = connessioneMongoDB();
            
            function creaGraficaQuesiti($titoloTest)
            {
                global $test;
                $test = new Test();
                $quesiti = $test->ottieniQuesiti($titoloTest);

                if (empty($quesiti)) {
                    echo "<li class='test-item'>Nessun quesito presente.</li>";
                } else {
                    $QuesitoOggetto = new Quesito();
                    foreach ($quesiti as $quesito) {
                        //Vedo la tipologia del Quesito
                        $tipologia = $QuesitoOggetto->ottieniTipologiaQuesito($titoloTest, $quesito['NumeroProgressivo']);
                        echo "<div class='divQuesiti'>";
                        echo "<span style=\"display: inline;\">";
                        // Aggiungo un campo di input di tipo checkbox con il nome del quesito come valore
                        echo "<input type='radio' name='quesito_selezionato' value='" . $quesito['NumeroProgressivo'] . "'>";
                        // Accesso ai dati del quesito
                        foreach ($quesito as $chiave => $valore) {
                            if ($chiave == "TitoloTest") {
                                continue;
                            }
                            echo "<label class='label'>" . $chiave . ": </label>" . $valore . "<br>";
                        }
                        echo "<label class='label'>Tipologia: </label> " . $tipologia . "<br>";
                        // Verifica della tipologia del quesito
                        if ($tipologia == "Risposta Chiusa") {
                            // Se è una risposta chiusa, si ottengono e si visualizzano le soluzioni
                            $soluzioni = $test->ottieniRisposte($quesito['NumeroProgressivo'], $titoloTest);
                            echo "<label class='label'>Soluzioni:</label>";
                            if (empty($soluzioni)) {
                                echo "<label class='label'>Nessuna soluzione presente.</label><br>";
                            } else {
                                foreach ($soluzioni as $soluzione) {
                                    if ($soluzione['RispostaCorretta'] == 1) {
                                        echo "<label class='labelVerde'>" . $soluzione['CampoTesto'] . "</label><br>";
                                    } else {
                                        echo "<label class='labelRosso'>" . $soluzione['CampoTesto'] . "</label><br>";
                                    }
                                }
                            }
                        } else if ($tipologia == "Codice") {
                            // Se è un quesito di tipo Codice, si ottengono e si visualizzano le soluzioni
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
                        echo "</div>";
                        echo "</span><br>";
                    }
                }
            }

            function mostraDatiTest()
                {
                    // Preleva il Titolo del test dalla query string
                    $testId = $_GET['id'];
                    $test = new Test();
                    // Esegue la query per selezionare il test dal database
                    $sql_select_test = $test->ottieniTest($testId);
                    
                    // Verifica se il test è stato trovato
                    if ($sql_select_test!= null) {
                        $visualizzaRisposte = $sql_select_test['VisualizzaRisposte'] ? "Si" : "No";
                        
                        echo
                        "<span style=\"display: inline;\">
                            <label class='label' for='titolo' style=\"display: inline;\">Titolo Test: </label>"
                            . $sql_select_test['Titolo'] . "<br><br>" . "
                            <label class='label' for='titolo' style=\"display: inline;\">Data Creazione: </label>"
                            . $sql_select_test['DataCreazione'] . "<br><br>" . "
                            <label class='label' for='titolo' style=\"display: inline;\">Visualizza Risposte: </label>"
                            . $visualizzaRisposte . "<br><br>" . "
                            <label class='label' for='titolo' style=\"display: inline;\">Email Docente: </label>"
                            . $sql_select_test['EmailDocente'] . "<br><br>
                        </span>";

                        creaGraficaQuesiti($sql_select_test['Titolo']);
                    } else {
                        echo "<li class='test-item'>Nessun test trovato con l'ID specificato.</li>";
                    }
            }

            function creaGraficaValoriComuni()
                {
                    $testId = $_GET['id'];
                    echo "
                        <form id='modificaTestForm' action='modificaTest.php' method='post'>
                            <input type='hidden' id ='titoloTest' name='titoloTest' value=" . $testId . ">
                            <br>
                            <label class='label' for='visualizzaRisposte' style='display: inline-block;'>Visualizza Risposte:</label>
                            <input type='checkbox' id='visualizzaRisposteCB' name='visualizzaRisposte'>
                            <br>
                            <input type='hidden' name='action' value='crea'><br>
                            <button type='submit' class='button'  id='modificaTestButton' value='modifica'>Salva</button>
                        </form>
                        <br>
                        <button id='inserisciQuesito' class='button' onclick=\"window.location.href='inserisciQuesito.php?id=" . $testId . "'\">Aggiungi Quesito</button>
                        <button class='button' onclick='eliminaQuesito(\"". $testId . "\")'>Elimina Quesito</button>
                        <button id='tornaTest' class='button' onclick='window.location.href=\"../navBar/testDocenti.php\"'>Torna ai Test</button>
                        ";
            }

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                mostraDatiTest();
                creaGraficaValoriComuni();
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $titolo = $_POST['titoloTest'];
                $visualizza_risposte = isset($_POST['visualizzaRisposte']) ? '1' : '0';

                $test = new Test();

                // Query SQL per aggiornare il test nel database
                $sql_Aggiornamento = $test->aggiornaTest($titolo, $visualizza_risposte);

                // Esecuzione della query di aggiornamento
                if ($sql_Aggiornamento) {
                    $document = ['Tipologia Evento' => 'Aggiornamento', 'Evento' => 'Aggiornato Test: '.$titolo.'', 'Orario' => date('Y-m-d H:i:s')];
                    writeLog($mongoDBManager, $document); 
                    echo '<script>
                                window.alert("Test aggiornato con successo.");
                                window.location.href = "../navBar/testDocenti.php"; 
                            </script>';
                        exit(); 
                } else {
                    echo '<script>
                            window.alert("Errore durante l\'aggiornamento del test. Attenzione, forse non hai modificato nessun campo.");
                            window.location.href = "modificaTest.php?id=' . $titolo . '";
                        </script>';
                }
            }
            ?>
        </ul>
    </div>
    <script>
        function eliminaQuesito(idTest) {
            var quesitoSelezionato = document.querySelector('input[name="quesito_selezionato"]:checked');
            if (!quesitoSelezionato) {
                alert('Seleziona un quesito da eliminare.');
                return;
            }
            // Recupera l'id del quesito selezionato dall'utente
            var idQuesitoSelezionato = quesitoSelezionato.value;
            // Reindirizza alla pagina di eliminazione con l'id del test e del quesito selezionati
            window.location.href = 'eliminaQuesito.php?idTest=' + idTest + ';' + idQuesitoSelezionato;
        }

    </script>
</body>
</html>