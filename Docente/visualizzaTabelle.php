<?php
if (!isset($_SESSION)) {
    session_start();
}

include 'navbarDocente.php';
include '../connessione.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
    // Redirect a una pagina di login se l'utente non è autenticato
    header("Location: ../");
    exit();
}



// Assicurati che l'ID del test sia passato come parametro nell'URL, per esempio: test.php?idTest=TitoloDelTest
if (isset($_GET['id'])) {
    $titoloTest = $_GET['id'];
} else {
    // Gestisci il caso in cui 'id' non sia presente nell'URL
    echo "ID del test non specificato.";
    exit;
}
echo "Il nome del test è: $titoloTest\n";

// Prima query per ottenere i nomi delle tabelle di esercizio legate al titolo del test
$queryCostituzione = "SELECT NomeTabella FROM COSTITUZIONE WHERE TitoloTest = ?";
$stmt = $conn->prepare($queryCostituzione);
$stmt->bind_param("s", $titoloTest);
$stmt->execute();
$resultCostituzione = $stmt->get_result();

$nomiTabella = [];
while ($row = $resultCostituzione->fetch_assoc()) {
    $nomiTabella[] = $row['NomeTabella'];
}
$stmt->close();

// Verifica se sono stati trovati nomi di tabella
if (count($nomiTabella) > 0) {
    // Seconda query per ottenere informazioni dalle tabelle di esercizio
    $placeholders = implode(',', array_fill(0, count($nomiTabella), '?')); // Crea una stringa di placeholders
    $queryTabellaEsercizio = "SELECT Nome, DataCreazione, num_righe, EmailDocente FROM TABELLADIESERCIZIO WHERE Nome IN ($placeholders)";
    $stmt = $conn->prepare($queryTabellaEsercizio);
    $stmt->bind_param(str_repeat('s', count($nomiTabella)), ...$nomiTabella); // Assegna dinamicamente i parametri alla query
    $stmt->execute();
    $resultTabellaEsercizio = $stmt->get_result();
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
                width: 100%;
                padding: 20px;
                background-color: #f9acac;
                border-radius: 5px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Informazioni sulle Tabelle di Esercizio per il Test: <?php echo htmlspecialchars($idTest); ?></h2>
            <ul>
                <?php
                while ($row = $resultTabellaEsercizio->fetch_assoc()) {
                    echo "<li>Nome: " . htmlspecialchars($row['Nome']) . ", Data Creazione: " . htmlspecialchars($row['DataCreazione']) . ", Numero righe: " . htmlspecialchars($row['num_righe']) . ", Email Docente: " . htmlspecialchars($row['EmailDocente']) . "</li>";
                }
                ?>
            </ul>
        </div>
    </body>

    </html>
<?php
    $stmt->close();
} else {
    echo "<p>Nessuna tabella di esercizio trovata per questo test.</p>";
}
?>