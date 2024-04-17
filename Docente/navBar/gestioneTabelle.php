<?php
if (!isset($_SESSION)) {
    session_start();
}
include 'navbarDocente.php';
include '../../connessione.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=100%, initial-scale=1.0">
    <style>
        body {
            height: 100%;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
        }

        .container {
            width: 100%;
            min-height: 100%;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
            word-wrap: break-word;
        }

        table {
            width: 80%;
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

        button[disabled] {
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <?php
        $email = $_SESSION['email'];
    ?>
    <div class="container">
        <button onclick="window.location.href='../Tabelle/inserisciTabella.php'">Aggiungi Tabella</button>
    </div>
    <div class="tableContainer">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="tableForm">
            <select id="tabellaSelect" name="nomeTabella" onchange="this.form.submit()">
                <option value="">Seleziona una tabella...</option>
                <?php
                $selectedTabella = isset($_POST['nomeTabella']) ? $_POST['nomeTabella'] : "";
                $query = "SELECT Nome FROM TABELLADIESERCIZIO WHERE EmailDocente = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $isSelected = ($row['Nome'] === $selectedTabella) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['Nome']) . "' $isSelected>" . htmlspecialchars($row['Nome']) . "</option>";
                    }
                } else {
                    echo "<p>Non hai creato tabelle.</p>";
                }
                $stmt->close();
                ?>
            </select>
            <button type="submit" name="delete" <?php echo empty($selectedTabella) ? 'disabled title="Selezionare una tabella da eliminare"' : ''; ?>>Elimina Tabella</button>
            <button type="button" onclick="location.href='../tabelle/aggiungiRiga.php?tabella=<?php echo urlencode($selectedTabella); ?>'" <?php echo empty($selectedTabella) ? 'disabled title="Selezionare una tabella per aggiungere una riga"' : ''; ?>>Aggiungi Riga</button>


            <?php
            if (isset($_POST['delete']) && !empty($_POST['nomeTabella'])) {
                $nomeTabella = $_POST['nomeTabella'];

                // Preparazione della query per eliminare la tabella
                $sqlDeleteTable = "DROP TABLE " . $nomeTabella; // Attenzione ai rischi di SQL injection se il nome della tabella non è controllato accuratamente
                if ($conn->query($sqlDeleteTable)) {
                    // Query eseguita con successo
                    echo "<p>Tabella eliminata con successo.</p>";
                    // Elimina anche l'informazione della tabella da TABELLADIESERCIZIO
                    $sqlDeleteInfo = "DELETE FROM TABELLADIESERCIZIO WHERE Nome = ?";
                    $stmt = $conn->prepare($sqlDeleteInfo);
                    $stmt->bind_param("s", $nomeTabella);
                    $stmt->execute();
                    $stmt->close();
                    // Reset della selezione dopo l'eliminazione
                    $selectedTabella = "";
                    echo "<p>Tabella eliminata con successo. Selezionare un'altra tabella.</p>";
                   
                } else {
                    echo "<p>Errore nell'eliminazione della tabella: " . $conn->error . "</p>";
                }
            }
            ?>
        </form>
    </div>

    <?php

    if (!empty($selectedTabella)) {
        $query = "SELECT * FROM " . $selectedTabella;
        $result = $conn->query($query);

        if ($result) {
            echo "<table>";
            echo "<tr>";
            while ($fieldinfo = $result->fetch_field()) {
                echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
            }
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Tabella non presente nel Database";
        }
    }

    ?>
</body>

</html>