<?php
include '../../connessione.php';
include '../../Condiviso/Test.php';
include '../../Condiviso/Tabella.php';

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
            width: auto;
            height: auto;
            background-color: #f9acac;
        }

        .container {
            width: auto;
            height: auto;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
            word-wrap: break-word;
        }

        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .test-details {
            list-style-type: none;
            padding: 0;
            width: auto;
            height: auto;
        }
        .test-form {
            width: auto;
            height: auto;
            padding: 2px;
            margin-bottom: 5px;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
            word-wrap: break-word;        
        }
        .test-item {
            width: auto;
            height: auto;
            padding: 2px;
            margin-bottom: 5px;
            margin-left: 300px;
            margin-right: 300px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            word-wrap: break-word;        
        }

        .test-item p,
        .test-item label {
            text-align: center;
            /* Allinea il testo a sinistra */
            margin: auto;
            font-size: medium;
            font: Arial;
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
        .btnScorrimentoAvanti{
            width: 100px;
            height: 40px;
            border: 1px solid #222222;
            padding: 3px;
            margin: 5px;
            margin-right: 100px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #7cfc00; 
            float: right;
        }
        .btnScorrimentoIndietro{
            width: 100px;
            height: 40px;
            border: 1px solid #222222;
            padding: 3px;
            margin: 5px;
            margin-left: 100px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #7cfc00; 
            float: left;
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
            width: 50%;
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
        table {
            width: auto;
            min-width: 40%;
            margin: 20px auto;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .labelNomeTabella{
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2 class='testH2'>Effettua il Test</h2>
        <ul class="test-details">
            <?php

                    global $test;
                    $test = new Test();    
                    $primaRisposta = true;
                    if (!isset($_SESSION['domandaAttuale'])) {
                        $_SESSION['domandaAttuale'] = 0;
                    }

                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        if (isset($_GET['id'])) {
                            $testId = $_GET['id'];
                            $_SESSION['titoloTest'] = $testId;
                            $_SESSION['domandaAttuale'] = 0; //TOgliere
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
                    global $test;
                    global $quesito;

                    if (isset($_POST['quesitoSuccessivo'])) {
                        $titoloTest = $_SESSION['titoloTest'];
                        $tipologiaQuesito = $_POST['tipologiaQuesito'];
                        $numeroProgressivo = $_POST['numeroQuesito'];
                        $idCompletamento = $test->trovaIdCompletamento($titoloTest, $_SESSION['email']);

                        // Salvataggio dei dati del quesito appena inserito
                        if ($tipologiaQuesito == "Risposta Chiusa") {
                            $rispostaData = "";
                            if (isset($_POST['risposta'])){
                                $rispostaData = $_POST['risposta'];
                            } 
                            $inserimento = $test -> inserisciRispostaQuesitoRispostaChiusa($idCompletamento,$titoloTest, $rispostaData, $numeroProgressivo);
                        } else if ($tipologiaQuesito == "Codice") {
                            $rispostaData = $_POST['codice'];
                            //Chiamare metodo da test che prende in input $rispostaData e restituisce $risultatoVerifica (boolean)
                            $risultatoVerifica = true;
                            $inserimento = $test -> inserisciRispostaQuesitoCodice($idCompletamento,$titoloTest, $rispostaData, $numeroProgressivo, $risultatoVerifica);
                            
                        }

                        if ($inserimento == false) {
                            echo "<script>
                                    window.alert('Errore nell'inserimento della risposta');
                                </script>";
                        }

                        $_SESSION['domandaAttuale'] = $_SESSION['domandaAttuale'] + 1;
                        mostraQuesito($_SESSION['arrayQuesiti'], $_SESSION['domandaAttuale']);
                    
                    } else if (isset($_POST['verificaRisposta'])) {
                        // Ottengo i dati
                        $numQuesito = $_POST['numeroQuesito'];
                        $tipologiaQuesito = $_POST['tipologiaQuesito'];
                        $rispostaData = $_POST['codice'];
                        ?>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {                                
                                var textarea = document.getElementById("codice");
                                textarea.value = "<?php echo $rispostaData; ?>";
                            });
                        </script>
                        <?php

                        $idCompletamento = $test->trovaIdCompletamento($_SESSION['titoloTest'], $_SESSION['email']);

                        //verifico l'esito della risposta Data
                        $quesitoOgg = new Quesito();
                        // TODO: chiamare metodo da realizzare (input: $rispostaData, output: $risultatoVerifica)
                        //$risultatoVerifica = $quesitoOgg->ottieniRispostaCorrettaCodice($testId, $numQuesito);
                        $risultatoVerifica = true;
                        $messaggio = "";
                        if ($risultatoVerifica == true){
                            $messaggio = "Risposta corretta";
                        } else {
                            $messaggio = "Risposta errata";
                        }
                        ?>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    var messaggioDiVerifica = document.getElementById("messaggioDiVerifica");
                                    messaggioDiVerifica.innerHTML = "<?php echo $messaggio; ?>";
                                });
                            </script>
                        <?php

                        $aggiuntaRisposta = $test->inserisciRispostaQuesitoCodice($idCompletamento, $_SESSION['titoloTest'], $rispostaData, $numQuesito, $risultatoVerifica);
                        if ($aggiuntaRisposta == 1) {
                            
                            $numDomanda = $_POST['numeroDomanda']; //Serve solo per la stampa delle domande
                            mostraDatiTest($_SESSION['titoloTest'], $numDomanda);
                            creaGrafica($_SESSION['titoloTest']);
                        }
                        else {
                            echo "<script>
                                    window.alert('Errore nell'inserimento della risposta');
                                </script>";
                        }
                        
                    } else if (isset($_POST['salvaTest'])) {
                        salvaDatiTest();
                    } else if (isset($_POST['quesitoPrecedente'])) {
                        $_SESSION['domandaAttuale'] = $_SESSION['domandaAttuale'] - 1;
                        mostraQuesito($_SESSION['arrayQuesiti'], $_SESSION['domandaAttuale']);
                    }
                }

            function mostraDatiTest($testId)
            {
                global $test;
                $testDetails = $test->ottieniTest($testId);
                $titoloTest = $testDetails['Titolo'];
                $_SESSION['arrayQuesiti'] = $test->ottieniQuesitiPerTest($titoloTest);
                
                // Verifica se ci sono quesiti
                if (empty($_SESSION['arrayQuesiti'])) {
                    echo "<br><p class='classQuesito'>Nessun quesito presente</p>";
                } else {
                    mostraQuesito($_SESSION['arrayQuesiti'], $_SESSION['domandaAttuale']);                        
                    
                }
                echo "</div>";
            }

            

            function creaGrafica($testId)
            {
                echo "<input type='hidden' id='titoloTest' name='titoloTest' value=" . $testId . ">";
                echo "<br>";
            }
            
            function salvaDatiTest(){
                global $test;
                
                $titoloTest = $_SESSION['titoloTest'];
                $tipologiaQuesito = $_POST['tipologiaQuesito'];
                $numeroProgressivo = $_POST['numeroQuesito'];
                $idCompletamento = $test->trovaIdCompletamento($titoloTest, $_SESSION['email']);

                if ($tipologiaQuesito == "Risposta Chiusa") {
                    $rispostaData = "";
                    if (isset($_POST['risposta'])){
                        $rispostaData = $_POST['risposta'];
                    } 
                    $inserimento = $test -> inserisciRispostaQuesitoRispostaChiusa($idCompletamento,$titoloTest, $rispostaData, $numeroProgressivo);
                } else if ($tipologiaQuesito == "Codice") {
                    $rispostaData = $_POST['codice'];
                    //Chiamare metodo da test che prende in input $rispostaData e restituisce $risultatoVerifica (boolean)
                    $risultatoVerifica = true;
                    $inserimento = $test -> inserisciRispostaQuesitoCodice($idCompletamento,$titoloTest, $rispostaData, $numeroProgressivo, $risultatoVerifica);
                            
                }

                if ($inserimento == false) {
                    echo "<script>
                        window.alert('Errore nell'inserimento della risposta');
                        </script>";
                }

                $_SESSION['titoloTest'] = "";
                $_SESSION['numeroDomande'] = "";
                $_SESSION['datiQuesito'] = array();
                
                echo "<script>alert('Test terminato con successo.');</script>";
                header("Location: ../navBar/testStudenti.php");
                exit();
                
            }

            function mostraQuesito($arrayQuesiti, $numeroDellaDomanda){
                global $test;
                if ($numeroDellaDomanda < count($arrayQuesiti)) {
                    $questoQuesito = $_SESSION['arrayQuesiti'][$numeroDellaDomanda];
            
                    $numeroProgressivo = $questoQuesito['NumeroProgressivo'];
                    $livelloDifficolta = $questoQuesito['LivelloDifficolta'];
                    $descrizione = $questoQuesito['Descrizione'];
                    $numeroRisposte = $questoQuesito['NumeroRisposte'];
            
                    $quesitoOgg = new Quesito();
                    $tipologiaQuesito = $quesitoOgg->ottieniTipologiaQuesito($_SESSION['titoloTest'], $numeroProgressivo);
                    ?>
            
                    <form id="testForm" class='test-form'method='post' action='effettuaTest.php?id=<?php $_SESSION['titoloTest']?>'>
                        <li class='test-item'>
                        <br><p class='classQuesito'>Quesito nr. <?php echo $numeroDellaDomanda ?></p>
                        <p>Domanda: <?php echo $descrizione ?></p>
                        <input type='hidden' name='numeroQuesito' value='<?php echo $numeroProgressivo?>'>
                        <input type='hidden' name='tipologiaQuesito' value='<?php echo $tipologiaQuesito?>'>
                        <input type='hidden' name='numeroDomanda' value='<?php echo $numeroDellaDomanda?>'>
                        <input type='hidden' name='titoloTest' value='<?php echo $_SESSION['titoloTest'] ?>'>
            
                        <?php
                        // Gestione grafica delle risposte
                        if ($tipologiaQuesito == "Risposta Chiusa") {
                            ?>
                            <br><p class='classInserimento'>Seleziona la risposta corretta:</p>
                            <?php
                            $soluzioni = $test->ottieniRisposte($numeroProgressivo, $_SESSION['titoloTest']);
                            if (!empty($soluzioni)) {
                                foreach ($soluzioni as $soluzione) {
                                    $risposta = $soluzione['CampoTesto'];
                                    ?>
                                    <input type='radio' name='risposta' value='<?php echo $risposta ?>' data-quesito='<?php echo $numeroProgressivo ?>'><?php echo $risposta ?><br>
                                    <?php
                                }
                            }
                        } elseif ($tipologiaQuesito == "Codice") {
                            ?>
                            <p class='classInserimento'>Inserisci il codice:</p>
                            <textarea class='areaCodice' id='codice' name='codice' rows='10' cols='50'></textarea>
                            <button type='submit' name='verificaRisposta' id='verificaRisposta' class='btnVerifica'>Verifica Risposta</button>
                            <label id='messaggioDiVerifica' class='labelVerifica'></label>
                            </li>
                            <?php   
                        }
            
                        if ($numeroDellaDomanda != count($arrayQuesiti)-1 && $numeroDellaDomanda != 0) {
                            ?>
                            <button type='submit' class='btnScorrimentoIndietro' name='quesitoPrecedente'>Indietro</button>    
                            <button type='submit' class='btnScorrimentoAvanti' name='quesitoSuccessivo'>Avanti</button>     
                            <?php
                        }
                        else if ($numeroDellaDomanda == 0){
                            ?>
                            <button type='submit' class='btnScorrimentoAvanti' name='quesitoSuccessivo'>Avanti</button>     
                            <?php
                        }

                        if ($numeroDellaDomanda == count($arrayQuesiti)-1) {
                            ?>
                            <button type='submit' class='btnScorrimentoIndietro' name='quesitoPrecedente'>Indietro</button>     
                            <button type='submit' class='btnSalva' name='salvaTest'>Salva Test</button>
                            <?php
                        }

                        stampaTabella($_SESSION['titoloTest'], $numeroProgressivo);
                        ?>
                    </form>
                    <?php
                }
            }
            

            function stampaTabella($titoloTest, $numeroQuesito){
                global $tabella;
                $tabella = new Tabella();
                $nomiTabella = $tabella->tabelleDelQuesito($titoloTest, $numeroQuesito);
                if (!empty($nomiTabella)) {
                    foreach ($nomiTabella as $nomeTabella) {
                        $datiTabella = $tabella->ottieniContenutoTabella($nomeTabella);
                        if ($datiTabella) {
                            echo "<br><br><br><label class='labelNomeTabella'> Tabella: " . $nomeTabella . "</label>";
                            echo "<table>";
                            echo "<tr>";
                            while ($campo = $datiTabella->fetch_field()) {
                                echo "<th>" . htmlspecialchars($campo->name) . "</th>";
                            }
                            echo "</tr>";
                
                            while ($row = $datiTabella->fetch_assoc()) {
                                echo "<tr>";
                                foreach ($row as $value) {
                                    echo "<td>" . htmlspecialchars($value) . "</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                    }
                } 
            }
        
        ?>
        </ul>
    </div>
  
</body>
</html>