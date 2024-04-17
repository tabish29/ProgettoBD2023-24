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
                    if (isset($_POST['quesitoSuccessivo'])) {
                        $testId = $_SESSION['titoloTest'];
                        $_SESSION['titoloTest'] = $testId;
                        $_SESSION['domandaAttuale'] = $_SESSION['domandaAttuale'] + 1;
                        //TODO: salvare i dati del quesito appena salvato
                        mostraQuesito($_SESSION['arrayQuesiti'], $_SESSION['domandaAttuale']);
                    } else if (isset($_POST['verificaRisposta'])) {
                        // Ottengo i dati
                        $testId = $_POST['titoloTest'];
                        $domanda = $_POST['verificaRisposta'];
                        echo "richiesta su domanda: $domanda<br>";
                        $numQuesito = $_POST['numeroQuesito' . ";" . $domanda];
                        $tipologiaQuesito = $_POST['tipologiaQuesito' . ";" . $domanda];
                        $rispostaData = $_POST['codice' . ";" . $domanda];

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

            function mostraDatiTest($testId, $esito, $numDomanda)
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
                $quesito = new Quesito();
                $numeroDomandePresenti = $_SESSION['numeroDomande'];
                echo "numero domande: " . $numeroDomandePresenti . "<br>";

                for ($i = 0; $i < $numeroDomandePresenti; $i++) {
                    $numeroQuesito = $_SESSION['datiQuesito'][$i][1];
                    echo "numero quesito: " . $numeroQuesito . "<br>";
                    echo "titolo test: " . $_SESSION['titoloTest'] . "<br>";
                    $tipologiaQuesito = $quesito->ottieniTipologiaQuesito($_SESSION['titoloTest'], $numeroQuesito);
                    echo "tipologia quesito: " . $tipologiaQuesito . "<br>";
                    $idCompletamento = $test->trovaIdCompletamento($_SESSION['titoloTest'], $_SESSION['email']);
                    /*
                    if ($tipologiaQuesito == "Risposta Chiusa") {
                        $rispostaData = $_POST['risposta' . ";" . $i];
                        $test->inserisciRispostaQuesitoRispostaChiusa($idCompletamento, $titoloTest, $rispostaData, $numeroQuesito);
                    } else if ($tipologiaQuesito == "Codice") {
                        $rispostaData = $_POST['codice' . ";" . $i]; // Sbagliato, ma non so come fare
                        $esito = $quesito->ottieniEsitoCodice($idCompletamento); //TODO: mettere metodo di Tab
                        $test->inserisciRispostaQuesitoCodice($idCompletamento, $titoloTest, $rispostaData, $numeroQuesito, $esito);
                    }*/
                }
                $_SESSION['titoloTest'] = "";
                $_SESSION['numeroDomande'] = "";
                $_SESSION['datiQuesito'] = array();
                /*
                echo "<script>alert('Test salvato con successo.');</script>";
                header("Location: ../navBar/testStudenti.php");
                exit();
                */
            }

            function mostraQuesito($arrayQuesiti, $numeroDellaDomanda){
                global $test;
                    echo "NUMERO DELLA DOMANDA: ". $numeroDellaDomanda . "<br>";
                    $questoQuesito = $_SESSION['arrayQuesiti'][$numeroDellaDomanda];

                        $numeroProgressivo = $questoQuesito['NumeroProgressivo'];
                        $livelloDifficolta = $questoQuesito['LivelloDifficolta'];
                        $descrizione = $questoQuesito['Descrizione'];
                        $numeroRisposte = $questoQuesito['NumeroRisposte'];


                        $quesitoOgg = new Quesito();
                        $tipologiaQuesito = $quesitoOgg->ottieniTipologiaQuesito($_SESSION['titoloTest'], $numeroProgressivo);

                        ?>
                        <form method='post' action='effettuaTest.php?id=<?php $_SESSION['titoloTest']?>'>
                        <br><p class='classQuesito'>Quesito nr. <?php echo $numeroDellaDomanda ?></p>
                        <p>Domanda: <?php echo $descrizione ?></p>
                        <input type='hidden' name='numeroQuesito; <?php echo $numeroDellaDomanda ?>' value='<?php echo $numeroProgressivo?>'>
                        <input type='hidden' name='tipologiaQuesito; <?php echo $numeroDellaDomanda ?>' value='<?php echo $tipologiaQuesito?>'>
                        <input type='hidden' name='numeroDomanda;' value='<?php echo $numeroDellaDomanda?>'>
                        <input type='hidden' name='titoloTest;' value='<?php echo $titoloTest ?>'>

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
                                    <input type='radio' name='risposta; <?php echo $numeroDellaDomanda ?>' value=' <?php echo $risposta ?>' data-quesito='<?php echo $numeroProgressivo ?>'><?php echo $risposta ?><br>
                                    <?php
                                }
                            }
                        } elseif ($tipologiaQuesito == "Codice") {
                            ?>

                            <p class='classInserimento'>Inserisci il codice:</p>
                            <textarea class='areaCodice' id='codice; <?php echo $numeroDellaDomanda ?>' name='codice; <?php echo $numeroDellaDomanda ?>' rows='10' cols='50'></textarea>
                            <button type='submit' class='btnVerifica' name='verificaRisposta' value=' <?php echo $numeroDellaDomanda ?>' >Verifica Risposta</button>
                            <label id='messaggioDiVerifica; <?php echo $numeroDellaDomanda ?> ' class='labelVerifica'>.</label>
                            
                            <?php   
                        }
                        ?>
                         
                            <button type='submit' class='btnSalva' name='quesitoSuccessivo'>Avanti</button>
                         </form>
                        <?php
                        // Visualizza il pulsante per salvare il test se si è all'ultimo quesito
                        if ($numeroDellaDomanda == count($_SESSION['arrayQuesiti'])) {
                            $_SESSION['numeroDomande'] = $numeroDellaDomanda;
                            echo "<br> HAI FINITO YUUU";

                            /*
                            ?>
                            <br><br>";
                            <form method='post' action='effettuaTest.php?id=".$_SESSION['titoloTest']." '>
                            <input class='btnSalva' type='submit' name='salvaTest' value='Salva Test'>
                            <input type='hidden' name='titoloTest' value='$titoloTest'>";
                            </form>

                            <?php
                            */

                            /*
                            for ($i = 0; $i < count($_SESSION['datiQuesito']); $i++) {
                                echo $_SESSION['datiQuesito'][$i][0] . " " . $_SESSION['datiQuesito'][$i][1] . "<br>";
                            }*/

                        }
                }
        ?>
        </ul>
    </div>
</body>
</html>




