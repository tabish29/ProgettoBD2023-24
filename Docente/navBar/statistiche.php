<?php
include '../../connessione.php';

if (!isset($_SESSION)) {
    session_start();
}
include 'navbarDocente.php';
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
            width: auto;
            height: auto;
            background-color: #f9acac;
        }

        .container {
            width: auto;
            height: auto;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
        }

        button {
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

        .sceltaLabel {
            font-weight: bold;
            font-size: 40px;
            margin: 10px;
            margin-bottom: 40px;
            border-radius: 5px;
            height: auto;
            width: auto;

        }
        
    </style>
</head>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ottenere il valore selezionato dal menu a tendina
    $scelta = $_POST['scelta'];
    
    // Effettuare il reindirizzamento in base all'opzione selezionata
    switch ($scelta) {
        case 'studenti_completati':
            header('Location: ../Statistiche/studentiCompletati.php');
            exit;
        case 'studenti_corrette':
            header('Location: ../Statistiche/studentiCorrette.php');
            exit;
        case 'quesiti_ricevute':
            header('Location: ../Statistiche/quesitiRicevuti.php');
            exit;
        default:
            break;
    }
}
?>
<body>
    <div class="container">
        <form action="statistiche.php" method="post">
            <label for="sceltaLabel">Seleziona la statistica da visionare:</label><br>
            <select name="scelta" id="scelta">
                <option value="studenti_completati">Studenti: test completati</option>
                <option value="studenti_corrette">Studenti: risposte corrette</option>
                <option value="quesiti_ricevuti">Quesiti: risposte ricevute</option>
            </select>
            <button class='button' type="submit" value="Seleziona">Seleziona</button>
        </form>
    </div>

</body>

</html>


