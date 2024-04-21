<?php
include '../../connessione.php';
include '../../Condiviso/Test.php';
include '../../Condiviso/Tabella.php';

if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['ruolo'] != 'Studente') {
    echo "Accesso Negato";
    header('Location: ../../Accesso/Logout.php?message=Utente non autorizzato.');
    exit();
}


$idTest = isset($_GET['idTest']) ? $_GET['idTest'] : "ID del test non specificato.";
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
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #FFE4E1; /* Sfondo rosa leggero */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #000;
        }

        p {
            font-size: 16px;
            color: #000;
            line-height: 1.5;
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

    </style>
</head>
<body>

<div class="container">
    <h2>Risposte al Test: <?php echo htmlspecialchars($idTest); ?></h2>
    <?php
    global $test;
    $test = new Test();

    global $quesito;
    $test = new Test();

    global $tabella;
    $tabella = new Tabella();

    $titoloTest = $idTest;
    
    $quesitiTest = $test->ottieniQuesitiPerTest($titoloTest);

    if (empty($quesitiTest)) {
        echo "<br><p class='classQuesito'>Nessun quesito presente</p>";
    } else {
        echo "<p>Tabelle:</p>";
        stampaTabella($titoloTest);
        mostraQuesito($quesitiTest,$titoloTest);                        
        
    }


    function mostraQuesito($arrayQuesiti,$titoloTest){
         
        global $test;
        global $quesito;
        global $tabella;

        $numeroDellaDomanda = 0;
        $idCompletamento = $test->trovaIdCompletamento($titoloTest, $_SESSION['email']);
        while ($numeroDellaDomanda < count($arrayQuesiti)) {

            $questoQuesito = $arrayQuesiti[$numeroDellaDomanda];
    
            $numeroProgressivo = $questoQuesito['NumeroProgressivo'];
            $descrizione = $questoQuesito['Descrizione'];
            $numeroRisposte = $questoQuesito['NumeroRisposte'];
    
            $quesitoOgg = new Quesito();
            $tipologiaQuesito = $quesitoOgg->ottieniTipologiaQuesito($titoloTest, $numeroProgressivo);
            ?>
    
                
                <br><p class='classQuesito'>Quesito nr. <?php echo ($numeroDellaDomanda+1) ?></p>
                <p>Domanda: <?php echo $descrizione ?></p>
                
                <?php
                // Gestione grafica delle risposte
                if ($tipologiaQuesito == "Risposta Chiusa") {
                    $soluzioni = $test->ottieniRisposte($numeroProgressivo, $titoloTest);
                    if (!empty($soluzioni)) {
                        $i = 1;
                        foreach ($soluzioni as $soluzione) {
                        $risposta = $soluzione['CampoTesto'];
                        
                        echo "<label>" . $i .": " .  $risposta . "</><br>";
                        
                        $i++;
                        }
                    } 
                    ?>
                    <p>Risposta data: <?php echo $quesitoOgg->ottieniRispostaDataRC($idCompletamento,$numeroProgressivo, $titoloTest) ?></p>
                    <?php
                } elseif ($tipologiaQuesito == "Codice") {
                    echo "<p>Risposta data:</p>";
                    echo "DA FARE";

                }
                $numeroDellaDomanda++;

        }
    }

    function stampaTabella($titoloTest){
        global $tabella;
        $tabella = new Tabella();
        $nomiTabella = $tabella->tabelleDelTest($titoloTest);
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

    
</div>

</body>
</html>
