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

        .messaggioConferma,
        .messaggioErrato {
            color: green;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
        }

        .messaggioErrato {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Elimina la riga dalla tabella</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label class="label">Scrivi il codice SQL per eliminare una riga:</label><br>
                <textarea id='codiceRiga' name='codiceRiga' rows='4' cols='100'></textarea><br>
                <input type="submit" value="Elimina Riga" class="inseriscibtn">
            </div>
        </form>
        <button id="modificaTest" class="inseriscibtn" onclick="window.location.href='../navBar/gestioneTabelle.php'">Back</button>
        <?php
            $mongoDBManager = connessioneMongoDB();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $codiceRiga = $_POST['codiceRiga'];


            // Validazione del formato SQL per il DELETE
            if (preg_match("/^DELETE FROM \s*([a-zA-Z0-9_àèìòùÀÈÌÒÙäëïöüÄËÏÖÜçÇ\-]+)\s*WHERE\s*(.+);$/i", $codiceRiga, $matches)) {
                $tableName = $matches[1];
                $whereCondition = $matches[2];

                // Esecuzione della query di selezione basata sulla condizione specificata nella DELETE
                $selectQuery = "SELECT * FROM $tableName WHERE $whereCondition";
                $result = $conn->query($selectQuery);

                if ($result) {
                    // Salva i dati selezionati nell'array
                    $selectedRows = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($selectedRows as $selectedRow) {
                        $testoRiga = implode(",", array_map('trim', array_values($selectedRow)));

                        $deleteFromRiga = "DELETE FROM Riga WHERE NomeTabella = ? AND Testo = ?";
                        $stmt = $conn->prepare($deleteFromRiga);
                        $stmt->bind_param("ss", $tableName, $testoRiga);
                        if ($stmt->execute()) {
                            $document = ['Tipologia Evento' => 'Eliminazione', 'Evento' => 'Eliminata riga dalla tabella:'.$tableName.'', 'Orario' => date('Y-m-d H:i:s')];
                            writeLog($mongoDBManager, $document); 
                        } else {
                            echo "<p class='messaggioErrato'>Errore nell'eliminare la riga dalla tabella Riga: " . $stmt->error . "</p>";
                        }
                        $stmt->close();
                    }
                    
                    // Esecuzione della query inserita dal docente
                    if ($conn->query($codiceRiga)) {
                        echo "<p class='messaggioConferma'>Eliminazione avvenuta con successo</p>";
                    } else {
                        echo "<p class='messaggioErrato'>errore nell'esecuzione della query di eliminazione </p>";
                    }
                } else {
                    echo "<p class='messaggioErrato'>Errore nell'eseguire la query di selezione </p>";
                }
            } else {
                echo "<p class='messaggioErrato'>Formato SQL non valido.</p>";
            }
        }
        ?>
    </div>
</body>

</html>