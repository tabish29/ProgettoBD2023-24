<?php
include '../../connessione.php';
include '../../Condiviso/Tabella.php';
if (!isset($_SESSION)) {
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
            background-color: #f9acac;
            line-height: 1.2;
        }
        .container {
            text-align: center;
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9acac;
            border-radius: 5px;
        }
        .divTabella {
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
        .btn{
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
    </style>
</head>

<body>
    
    <?php

        $titoloTest = $_GET['id'];
    
        $tabella = new Tabella();

        $nomiTabella = $tabella->tabelleDelTest($titoloTest);

    // Verifica se sono stati trovati nomi di tabella
    if (count($nomiTabella) > 0) {
        
        ?>
        <body>
            <div class="container">
                <h2>Informazioni sulle Tabelle di Esercizio per il Test: <?php echo htmlspecialchars($titoloTest); ?></h2>
                <ul>
                    <?php
                    $dati = $tabella->ottieniDatiTabella($nomiTabella);
                    ?>
                    
                    <?php
                        while ($row = $dati->fetch_assoc()) {
                            echo "<div class=\"divTabella\">";
                            echo "<label class='label'>Nome Tabella: </label>";
                            echo "<p>".$row['Nome']."</p>";
                            echo "<label class='label'>Data Creazione: </label>";
                            echo "<p>".$row['DataCreazione']."</p>";
                            echo "<label class='label'>Numero Righe: </label>";
                            echo "<p>".$row['num_righe']."</p>";
                            echo "<label class='label'>Email Docente: </label>";
                            echo "<p>".$row['EmailDocente']."</p>";
                            echo "</div>";
                        }
                    ?>
                </ul>
                <div>
                    <button class='btn'onclick="window.location.href='../navBar/testDocenti.php'">Torna alla lista dei test</button>
                </div>
            </div>
        <?php
    } else {
        echo "<p>Nessuna tabella di esercizio trovata per questo test.</p>";
    }
    ?>
</body>
</html>