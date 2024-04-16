<?php
include '../../connessione.php';
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
        <h2>Inserisci la nuova riga della tabella</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label class="label">Scrivi il codice SQL per inserire una riga:</label><br>
                <textarea id='codiceRiga' name='codiceRiga' rows='4' cols='100'></textarea><br>
                <input type="submit" value="Inserisci Riga" class="inseriscibtn">
            </div>
        </form>
        <button id="modificaTest" class="inseriscibtn" onclick="window.location.href='../navBar/gestioneTabelle.php'">Back</button>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $codiceRiga = $_POST['codiceRiga'];
            // Validazione del formato SQL per l'INSERT
            if (preg_match("/^INSERT INTO \s*(\w+)\s* \(([\w, ]+)\)\s*VALUES\s*\(([\w', ]+)\);$/i", $codiceRiga, $matches)) {
                $tableName = $matches[1];
                $testoRiga = $matches[3];

                // Esegui la query SQL
                if ($conn->query($codiceRiga)) {
                    echo "<p class='messaggioConferma'>Riga inserita con successo nella tabella $tableName.</p>";

                    // Preparazione per l'inserimento nella tabella Riga
                    $insertQuery = "INSERT INTO Riga (Testo,NomeTabella) VALUES (?, ?)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("ss", $testoRiga, $tableName);
                    if ($stmt->execute()) {
                        echo "<p class='messaggioConferma'>Dati riga salvati con successo.</p>";
                    } else {
                        echo "<p class='messaggioErrato'>Errore nel salvataggio dati riga.</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p class='messaggioErrato'>Errore nell'esecuzione della query</p>";
                }
            } else {
                echo "<p class='messaggioErrato'>Formato SQL non valido.</p>";
            }
        }
        ?>
    </div>
</body>

</html>