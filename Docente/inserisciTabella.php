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

        .inseriscibtn {
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

        .areaInserimento {
            width: 40%;
            display: block;
            margin: auto;
        }

        .label {
            text-align: center;
            font: sans-serif;
            font-weight: bold;
            font-size: medium;
            padding: 1%;
            color: black;
            height: auto;
            width: auto;
            display: block;

        }

        .messaggioConferma {
            color: green;
            font-weight: bold;
            font-size: 20px;
        }

        .messaggioErrato {
            color: red;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Inserisci Tabella</h2>
        <ul>
            <?php
            include '../connessione.php';
            if (!isset($_SESSION)) {
                session_start();
            }

            ?>
            <form action="inserisciTabella.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
                <div class="form-group">
                    <label class="label">Scrivi il codice SQL della Tabella:</label><br>
                    <textarea id='codiceTabella' name='codiceTabella' rows='20' cols='100'></textarea>
                    <input type="hidden" name="testId" value="<?php echo $testId; ?>"><br><br>
                    <input type="submit" value="Inserisci Tabella" class="inseriscibtn">
                </div>

            </form>
            <button id="modificaTest" class="inseriscibtn" onclick="window.location.href='modificaTest.php?id=<?php echo $_GET['id']; ?>'">Back</button>

            <?php
            $testId = " ";

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $testId = $_GET['id'];
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $codiceTabella = $_POST['codiceTabella']; // Questo è il codice SQL inserito dall'utente
                // Divide la stringa in parti basandosi su "CREATE TABLE"
                $parti = explode("CREATE TABLE", $codiceTabella);

                // Controlla se abbiamo ottenuto almeno due parti
                if (count($parti) > 1) {
                    // Divide la seconda parte (dopo "CREATE TABLE") basandosi sugli spazi
                    $partiDopoCreateTable = explode(" ", trim($parti[1]));

                    // Il nome della tabella dovrebbe essere la prima parte dopo "CREATE TABLE"
                    $nomeTabella = $partiDopoCreateTable[0];

                    // Rimuove eventuali parentesi o caratteri speciali dal nome della tabella
                    $nomeTabella = preg_replace('/[^A-Za-z0-9_]/', '', $nomeTabella);

                    echo "Il nome della tabella è: $nomeTabella\n";
                } else {
                    echo "Nome della tabella non trovato nel codice SQL fornito.\n";
                }

                // Logica per eseguire il codice SQL

                if ($conn->multi_query($codiceTabella)) {
                    $messaggio = "Query eseguita con successo.";
                    echo "<br><label class = 'messaggioConferma'>Query eseguita con successo.</label>";

                    do {
                        if ($result = $conn->store_result()) {
                            while ($row = $result->fetch_assoc()) {
                                // Processa i tuoi risultati qui, se necessario
                            }
                            $result->free();
                        }
                    } while ($conn->next_result());
                    // Esegui la query per inserire nella tabella TABELLADIESERCIZIO
                    $queryInserimentoTabella = "INSERT INTO TABELLADIESERCIZIO (Nome, DataCreazione, num_righe, EmailDocente) VALUES (?, NOW(), 0, 'docente2@gmail.com')";

                    // Prepara la query
                    $stmtInserimentoTabella = $conn->prepare($queryInserimentoTabella);

                    // Esegui il binding dei parametri e esegui la query
                    $stmtInserimentoTabella->bind_param("s", $nomeTabella);
                    if ($stmtInserimentoTabella->execute()) {
                        echo "<br><label class='messaggioConferma'>Inserimento in TABELLADIESERCIZIO avvenuto con successo.</label>";
                    } else {
                        echo "<br><label class='messaggioErrato'>Errore nell'inserimento in TABELLADIESERCIZIO: " . $conn->error . "</label>";
                    }
                    // Chiudi lo statement
                    $stmtInserimentoTabella->close();

                    if (isset($_GET['id'])) {
                        $titoloTest = $_GET['id'];
                    } else {
                        // Gestisci il caso in cui 'id' non sia presente nell'URL
                        echo "ID del test non specificato.";
                        exit;
                    }
                    

                    // Query per inserire una riga in COSTITUZIONE
                    $queryInserimento = "INSERT INTO COSTITUZIONE (TitoloTest, NumeroProgressivoQuesito, NomeTabella) VALUES (?, ?, ?)";

                    // Prepara la query
                    $stmtInserimento = $conn->prepare($queryInserimento);

                    // Assumiamo che $numeroProgressivoQuesito sia già definito e valido.(secondo me da eliminrae dalla tabella costituzione)
                    // Sostituisci 1 con il numero progressivo quesito appropriato
                    $numeroProgressivoQuesito = 1;

                    // Esegue il binding dei parametri e esegue la query
                    $stmtInserimento->bind_param("sis", $titoloTest, $numeroProgressivoQuesito, $nomeTabella);
                    if ($stmtInserimento->execute()) {
                        echo "<br><label class='messaggioConferma'>Inserimento in COSTITUZIONE avvenuto con successo.</label>";
                    } else {
                        echo "<p>Il valore di testId è: " . $testId . "</p>";
                        echo "<br><label class='messaggioErrato'>Errore nell'inserimento in COSTITUZIONE: " . $conn->error . "</label>";
                    }

                    // Chiudi lo statement
                    $stmtInserimento->close();
                } else {
                    echo "<br><label class='messaggioErrato'>Errore nell'esecuzione della query: " . $conn->error . "</label>";
                }
            }
            ?>


        </ul>

    </div>
</body>

</html>