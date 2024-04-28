<?php
include '../../connessione.php';

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
            <form action="inserisciTabella.php" method="post">
                <div class="form-group">
                    <label class="label">Scrivi il codice SQL della Tabella:</label><br>
                    <textarea id='codiceTabella' name='codiceTabella' rows='20' cols='100'></textarea><br>
                    <input type="submit" value="Inserisci Tabella" class="inseriscibtn">
                </div>
            </form>
            <button id="modificaTest" class="inseriscibtn" onclick="window.location.href='../navBar/gestioneTabelle.php'">Back</button>

            <?php
            $mongoDBManager = connessioneMongoDB();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                try {
                    $codiceTabella = $_POST['codiceTabella']; // codice SQL inserito dal docente

                    // Divide la stringa dopo "CREATE TABLE"
                    $parti = explode("CREATE TABLE", $codiceTabella);

                    // Controlla se abbiamo ottenuto almeno due parti
                    if (count($parti) > 1) {
                        // Divide la seconda parte (dopo "CREATE TABLE") basandosi sugli spazi
                        $partiDopoCreateTable = explode(" ", trim($parti[1]));

                        $nomeTabella = $partiDopoCreateTable[0];
                    } else {
                        echo "<br><label class='messaggioErrato'>Nome della tabella non trovato nel codice SQL fornito</label>";
                    }

                    if (strlen($nomeTabella) > 20) {
                        echo "<br><label class='messaggioErrato'>Errore: il nome della tabella supera il numero massimo di caratteri consentito(20)</label>";
                    } else {
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
                            $email = $_SESSION['email'];
                            $queryInserimentoTabella = "INSERT INTO TABELLADIESERCIZIO (Nome, DataCreazione, num_righe, EmailDocente) VALUES (?, NOW(), 0, '$email')";
                            $stmtInserimentoTabella = $conn->prepare($queryInserimentoTabella);

                            // Esegui il binding dei parametri e esegui la query
                            $stmtInserimentoTabella->bind_param("s", $nomeTabella);
                            if ($stmtInserimentoTabella->execute()) {
                                $document = ['Tipologia Evento' => 'Creazione', 'Evento' => 'Creata Tabella_Esercizio: ' . $nomeTabella . '', 'Orario' => date('Y-m-d H:i:s')];
                                writeLog($mongoDBManager, $document);
                                echo "<br><label class='messaggioConferma'>Inserimento in TABELLADIESERCIZIO avvenuto con successo.</label>";
                            } else {
                                echo "<br><label class='messaggioErrato'>Errore nell'inserimento in TABELLADIESERCIZIO</label>";
                            }

                            $stmtInserimentoTabella->close();

                            $query = "DESCRIBE " . $nomeTabella;
                            $result = $conn->query($query);

                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    $nomeAttributo = $row['Field'];
                                    $tipo = $row['Type'];


                                    // Prepara la query per inserire nella tabella ATTRIBUTO
                                    $insertQuery = "INSERT INTO ATTRIBUTO (NomeTabella, NomeAttributo, Tipo) VALUES (?, ?, ?)";
                                    $stmt = $conn->prepare($insertQuery);

                                    // Esegui il binding dei parametri e la query
                                    $stmt->bind_param("sss", $nomeTabella, $nomeAttributo, $tipo);
                                    if (!$stmt->execute()) {
                                        echo "<br><label class='messaggioErrato'>Errore nell'inserimento dell'attributo $nomeAttributo</label>";
                                    } else {
                                        $document = ['Tipologia Evento' => 'Creazione', 'Evento' => 'Creati Attributi della tabella: ' . $nomeTabella . '', 'Orario' => date('Y-m-d H:i:s')];
                                        writeLog($mongoDBManager, $document);
                                    }

                                    $stmt->close();
                                }
                            } else {
                                echo "<br><label class='messaggioErrato'>Errore nell'esecuzione della query DESCRIBE </label>";
                            }

                            //controllo se la query contiene "FOREIGN KEY"
                            if (strpos(strtoupper($codiceTabella), 'FOREIGN KEY') !== false) {

                                // Query per trovare le foreign key della tabella appena creata
                                $queryForeignKey = "SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                                            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                                            WHERE TABLE_SCHEMA = 'ESQL' AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL";

                                $stmtFk = $conn->prepare($queryForeignKey);
                                $stmtFk->bind_param("s", $nomeTabella);
                                $stmtFk->execute();
                                $resultFk = $stmtFk->get_result();


                                if ($resultFk->num_rows > 0) {
                                    while ($rowFk = $resultFk->fetch_assoc()) {
                                        // dettagli della foreign key in Vincolo di integrità
                                        $queryInserimentoVincolo = "INSERT INTO VINCOLODIINTEGRITA (NomeTabellaUno, NomeAttributoUno, NomeTabellaDue, NomeAttributoDue) VALUES (?, ?, ?, ?)";
                                        $stmtVincolo = $conn->prepare($queryInserimentoVincolo);
                                        $stmtVincolo->bind_param("ssss", $rowFk['TABLE_NAME'], $rowFk['COLUMN_NAME'], $rowFk['REFERENCED_TABLE_NAME'], $rowFk['REFERENCED_COLUMN_NAME']);

                                        if (!$stmtVincolo->execute()) {
                                            echo "<br><label class='messaggioErrato'>Errore nell'inserimento in VINCOLODIINTEGRITA: " . $stmtVincolo->error."</label>";
                                        } else {
                                            $document = [
                                                'Tipologia Evento' => 'Creazione',
                                                'Evento' => 'Creato vincolo di integrità tra la tabella referenziante: ' . $rowFk['TABLE_NAME'] . ' e la tabella referenziata: ' . $rowFk['REFERENCED_TABLE_NAME'],
                                                'Orario' => date('Y-m-d H:i:s')
                                            ];
                                            writeLog($mongoDBManager, $document);
                                        }

                                        $stmtVincolo->close();
                                    }
                                } else {
                                    echo "Nessuna foreign key trovata per la tabella $nomeTabella.";
                                }

                                $stmtFk->close();
                            }
                        } else {
                            echo "<br><label class='messaggioErrato'>Errore nell'esecuzione della query </label>";
                        }
                    }
                } catch (Exception $e) {
                    echo "<br><label class='messaggioErrato'>Errore nell'esecuzione della query </label>";
                }
            }
            ?>
        </ul>

    </div>
</body>

</html>