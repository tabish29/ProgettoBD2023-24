<?php
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
            width: 100%;
            height: 100%;
            background-color: #f9acac;
        }

        .container {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            border-radius: 5px;

        }

        .test-list {
            list-style-type: none;
            padding: 0;

        }

        .testListH2 {
            text-align: center;
            margin-bottom: 20px;
            font: sans-serif;
            font-style: italic;
            font-size: medium;
        }


        .test-item {
            padding: 10px;
            width: 500px;
            height: auto;
            margin: auto;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .test-item p,
        .test-item label {
            text-align: left;
            /* Allinea il testo a sinistra */
            margin: auto;
            font-size: small;
            font-style: arial;


        }

        .test-item:hover {
            background-color: #9c9c9c;
            /* Cambia colore al passaggio del mouse */
        }

        .test-item:last-child {
            margin-bottom: 0;
            /* Rimuove il margine inferiore dall'ultimo elemento */
        }

        p,
        label {
            font:
                1rem 'Fira Sans',
                arial;
            font-size: 16px;
        }

        input {
            margin: 0.4rem;
        }

        .containerBtn {
            text-align: center;

        }

        .buttonEffettua {
            margin-top: 20px;
            background-color: greenyellow;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <ul class="test-list">
            <?php

            if (!isset($_SESSION)) {
                session_start();
            }

            include 'navbarStudente.php';
            include '../connessione.php';


            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: index.html");
                exit();
            }


            $email_login = $_SESSION['email'];
            $ruolo_login = $_SESSION['ruolo'];



            echo "<h2 class = 'testListH2'>Lista Test: </h2>";

            // Query per selezionare tutti i test
            $sql_all_tests = "CALL visualizzaTestDisponibili()";

            $result_all_tests = $conn->query($sql_all_tests);
            $conn->next_result(); //Se no entra in conflitto con la query di funzioniPerTest
            // Verifica se ci sono test 
            if ($result_all_tests->num_rows > 0) {
                echo "<form id='testForm'>";
                while ($row = $result_all_tests->fetch_assoc()) {
                    $titoloTest = $row['Titolo'];
                    $ottieniCompletamento = "SELECT Stato FROM COMPLETAMENTO WHERE TitoloTest = '$titoloTest' AND EmailStudente = '$email_login'";
                    $result_completamento = $conn->query($ottieniCompletamento);
                    $statoCompletamento = 'nessun completamento';
                    if ($result_completamento->num_rows > 0) {
                        $statoCompletamento = $result_completamento->fetch_assoc()['Stato'];
                    }
                    echo "<li class='test-item'>";

                    if ($statoCompletamento !== 'Concluso') {
                        echo "<input type='radio' name='test' value='" . $titoloTest . "'>";
                    }


                    echo "<p>Titolo del test: " . $titoloTest . "</p><br>";
                    echo "<p>Email Docente: " . $row['EmailDocente'] . "</p><br>";
                    echo "<p> Stato completamento: " . $statoCompletamento . "</p><br>";

                    if ($statoCompletamento === 'Concluso') {
                        $urlVisualizza = "visualizzaRisposta.php?idTest=" . urlencode($titoloTest);
                        echo "<a href='" . htmlspecialchars($urlVisualizza) . "' class='buttonEffettua'>Visualizza Risposta</a>";
                    }
                    echo "</li>";
                }
                echo "<input type='hidden' name='action' id='actionField'>";
                echo "</form>";
            }
            ?>
        </ul>
        <div class="containerBtn">
            <button class="buttonEffettua" onclick="openAction('effettua')">Effettua il Test</button>
        </div>

        <script>
            function openAction(action) {
                var selectedTestId = document.querySelector('input[name="test"]:checked');
                if (!selectedTestId) {
                    alert('Seleziona un test.');
                    return;
                }
                var testId = selectedTestId.value;
                if (action === 'effettua') {
                    window.location.href = 'effettuaTest.php?id=' + testId;
                }
            }
        </script>
    </div>

</body>

</html>