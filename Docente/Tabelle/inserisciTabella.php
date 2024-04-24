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

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                try{
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

                } else {
                    echo "<br>Nome della tabella non trovato nel codice SQL fornito.\n";
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
                    $email = $_SESSION['email'];
                    $queryInserimentoTabella = "INSERT INTO TABELLADIESERCIZIO (Nome, DataCreazione, num_righe, EmailDocente) VALUES (?, NOW(), 0, '$email')"; //(da cambiare devo creare una varibaile che si prende l'email del docente)

                    // Prepara la query
                    $stmtInserimentoTabella = $conn->prepare($queryInserimentoTabella);

                    // Esegui il binding dei parametri e esegui la query
                    $stmtInserimentoTabella->bind_param("s", $nomeTabella);
                    if ($stmtInserimentoTabella->execute()) {
                        echo "<br><label class='messaggioConferma'>Inserimento in TABELLADIESERCIZIO avvenuto con successo.</label>";
                    } else {
                        echo "<br><label class='messaggioErrato'>Errore nell'inserimento in TABELLADIESERCIZIO</label>";
                    }
                    // Chiudi lo statement
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

                            // Controlla se lo statement è stato preparato correttamente
                            if ($stmt === false) {
                                echo "Errore nella preparazione della query";
                                continue; // Salta all'iterazione successiva del ciclo
                            }

                            // Esegui il binding dei parametri e la query
                            $stmt->bind_param("sss", $nomeTabella, $nomeAttributo, $tipo);
                            if (!$stmt->execute()) {
                                echo "<br>Errore nell'inserimento dell'attributo $nomeAttributo: " . $stmt->error . "<br>";
                            } 

                            // Chiudi lo statement
                            $stmt->close();
                        }
                    } else {
                        echo "Errore nell'esecuzione della query DESCRIBE";
                    }

                    // Assumendo che $conn sia la tua connessione al database e $codiceTabella sia la query SQL inserita dall'utente
                    if (strpos(strtoupper($codiceTabella), 'FOREIGN KEY') !== false) {
                        // La query contiene "FOREIGN KEY", quindi procedi con la verifica delle foreign key

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
                                // Qui inserisci i dettagli della foreign key in Vincolo di integrità
                                $queryInserimentoVincolo = "INSERT INTO VINCOLODIINTEGRITA (NomeTabellaUno, NomeAttributoUno, NomeTabellaDue, NomeAttributoDue) VALUES (?, ?, ?, ?)";
                                $stmtVincolo = $conn->prepare($queryInserimentoVincolo);
                                $stmtVincolo->bind_param("ssss", $rowFk['TABLE_NAME'], $rowFk['COLUMN_NAME'], $rowFk['REFERENCED_TABLE_NAME'], $rowFk['REFERENCED_COLUMN_NAME']);
                                $stmtVincolo->execute();

                                if (!$stmtVincolo->execute()) {
                                    echo "Errore nell'inserimento in VINCOLODIINTEGRITA: " . $stmtVincolo->error;
                                } 

                                $stmtVincolo->close();
                            }
                        } else {
                            echo "Nessuna foreign key trovata per la tabella $nomeTabella.";
                        }

                        $stmtFk->close();
                    } else {
                        echo "La query inserita non contiene foreign key.";
                    }
                } else {
                    echo "<br><label class='messaggioErrato'>Errore nell'esecuzione della query </label>";
                }
                } catch (Exception $e) {
                echo "<br><label>Errore nell'esecuzione della query " . $e->getMessage() . "</label>";
                }
            }
            ?>


        </ul>

    </div>
</body>

</html>