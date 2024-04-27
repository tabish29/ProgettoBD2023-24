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
          
            if (preg_match("/^INSERT INTO\s+([a-zA-Z0-9_àèìòùÀÈÌÒÙäëïöüÄËÏÖÜçÇ\-]+)\s*\(([a-zA-Z0-9_\s,àèìòùÀÈÌÒÙäëïöüÄËÏÖÜçÇ\-]+)\)\s*VALUES\s*\((.*)\);$/i", $codiceRiga, $matches)) {
                $tableName = $matches[1];
                if ($conn->query($codiceRiga)) {
                    $last_id = $conn->insert_id;
                    echo "<p class='messaggioConferma'>Riga inserita con successo. ID nuovo: $last_id</p>";
                    $query = "SELECT * FROM $tableName WHERE id = $last_id";
                    $result = $conn->query($query);
                    if ($result && $rowData = $result->fetch_assoc()) {
                        $testoRiga = implode(",", $rowData);
                        echo "<p class='messaggioConferma'>Dati della nuova riga: $testoRiga</p>";

                        //inserire il coidce per linseirmet nella tabella riga
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
                        echo "<p class='messaggioErrato'>Errore nel recuperare la nuova riga: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p class='messaggioErrato'>Errore nell'esecuzione della query: " . $conn->error . "</p>";
                }
            } else {
                echo "<p class='messaggioErrato'>Formato SQL non valido.</p>";
            }
        }
        ?>
    </div>
</body>

</html>