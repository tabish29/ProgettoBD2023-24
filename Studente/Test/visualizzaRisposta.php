<?php
include '../../connessione.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
    // Redirect a una pagina di login se l'utente non è autenticato
    header("Location: index.html");
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
    </style>
</head>
<body>

<div class="container">
    <h2>Risposte al Test: <?php echo htmlspecialchars($idTest); ?></h2>
    <?php
    if ($idTest !== "ID del test non specificato.") {
        $queryQuesiti = "SELECT NumeroProgressivoQuesito, CampoTesto AS RispostaCorretta
                         FROM OPZIONERISPOSTA
                         WHERE TitoloTest = ? AND RispostaCorretta = TRUE";
        $stmtQuesiti = $conn->prepare($queryQuesiti);
        $stmtQuesiti->bind_param("s", $idTest);
        $stmtQuesiti->execute();
        $resultQuesiti = $stmtQuesiti->get_result();

        $numeroDomanda = 1;

        if ($resultQuesiti->num_rows > 0) {
            while ($rowQuesito = $resultQuesiti->fetch_assoc()) {
                $numeroProgressivoQuesito = $rowQuesito['NumeroProgressivoQuesito'];
                $queryRispostaData = "SELECT OpzioneScelta
                                      FROM RISPOSTAQUESITORISPOSTACHIUSA
                                      WHERE NumeroProgressivoQuesito = ?
                                      ORDER BY NumeroProgressivoCompletamento DESC
                                      LIMIT 1";
                $stmtRisposta = $conn->prepare($queryRispostaData);
                $stmtRisposta->bind_param("i", $numeroProgressivoQuesito);
                $stmtRisposta->execute();
                $resultRisposta = $stmtRisposta->get_result();

                if ($rowRisposta = $resultRisposta->fetch_assoc()) {
                    echo "<p><strong>Domanda n-" . $numeroDomanda ."</strong> <br>Risposta corretta: " . htmlspecialchars($rowQuesito['RispostaCorretta']) .
                        "<br>Risposta data: " . htmlspecialchars($rowRisposta['OpzioneScelta']) . "</p>";
                } else {
                    echo "<p>Domanda n-" . $numeroDomanda . "<br>Risposta corretta: " . htmlspecialchars($rowQuesito['RispostaCorretta']) .
                        "<br>Risposta data: Nessuna risposta fornita.</p>";
                }
                $numeroDomanda++;
                $stmtRisposta->close();
            }
        } else {
            echo "<p>Nessun quesito trovato per questo test.</p>";
        }
        $stmtQuesiti->close();
    }
    ?>
</div>

</body>
</html>
