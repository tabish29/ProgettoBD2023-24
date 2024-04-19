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
            width: auto;
            height: auto;
            background-color: #f9acac;
            text-align: center;
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
        table {
            width: auto;
            min-width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            table-layout: fixed;
            background-color: whitesmoke;
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
    </style>
</head>
<html>

<?php
    // Inserire nome View e scommentare

    $ottieniViewQuery = "SELECT * FROM classifica_test_completati";
    /*
    $risultato = $conn->query($ottieniViewQuery);
    
    if ($risultato) {
        echo "<table>";
        echo "<tr>";
        while ($datiView = $risultato->fetch_field()) {
            echo "<th>" . htmlspecialchars($datiView->name) . "</th>";
        }
        echo "</tr>";

        while ($riga = $risultato->fetch_assoc()) {
            echo "<tr>";
            foreach ($riga as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    */
?>
<button class='button' onclick="window.location.href='../navBar/statistiche.php'">Indietro</button>
</html>


