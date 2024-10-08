<?php
include 'navbarStudente.php';
include '../../connessione.php';
include '../../Condiviso/Test.php';
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['ruolo'] != 'Studente') {
    echo "Accesso Negato";
    header('Location: ../../Accesso/Logout.php');
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
            font-weight: bold;
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
            margin: auto;
            font-size: small;
            font: Arial;
        }

        .test-item:hover {
            background-color: #9c9c9c;
        }

        .test-item:last-child {
            margin-bottom: 0;
        }

        input {
            margin: 0.4rem;
        }

        .containerBtn {
            text-align: center;

        }

        .labelBold {
            font-weight: bold;
        }

        .test-item p,
        .test-item label {
            font-size: 15px;
        }

        .test-item {
        display: inline-block; 
        margin-left : 20px; 
        margin-right: 20px;
        margin-bottom: 20px;
        padding: 10px;
        width: auto; 
        height: auto;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        button{
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

    </style>
</head>

<body>
    <div class="container">
    <div class="containerBtn">
        <button class="button" onclick="openAction('effettua')">Effettua il Test</button>
    </div>
    <ul class="test-list">
            <?php

            if (isset($_SESSION['RispostaData'])){
                unset($_SESSION['RispostaData']);
            }

            $test = new Test();

            $email_login = $_SESSION['email'];

            echo "<h2 class = 'testListH2'>Lista Test: </h2>";

            $sql_test = $test->ottieniTuttiITest();
            
            if ($sql_test->num_rows > 0) {
                echo "<form id='testForm'>";
                while ($datiTest = $sql_test->fetch_assoc()) {
                    $titoloTest = $datiTest['Titolo'];
                    $statoCompletamento = $test->getStatoCompletamento($titoloTest, $_SESSION['email']);
                    if ($statoCompletamento === false){
                        $statoCompletamento = "nessun completamento";
                    }
                    
                    echo "<li class='test-item'>";

                    $visualizzaRisposte = $test->visualizzaRisposte($titoloTest);

                    if ($visualizzaRisposte == 0 && $statoCompletamento != "Concluso") {
                        echo "<input type='radio' name='test' value='" . $titoloTest . "'>";
                    }


                    echo "<p>Titolo del test: " . $titoloTest . "</p><br>";
                    echo "<p>Email Docente: " . $datiTest['EmailDocente'] . "</p><br>";
                    echo "<p> Stato completamento: " . $statoCompletamento . "</p><br>";

                    if ($visualizzaRisposte == 1) {
                        $urlVisualizza = "../Test/visualizzaRisposta.php?idTest=" . urlencode($titoloTest);
                        echo "<a href='" . htmlspecialchars($urlVisualizza) . "' class='button'>Visualizza Risposta</a>";
                    }
                    echo "</li>";
                }
                echo "<input type='hidden' name='action' id='actionField'>";
                echo "</form>";
            }
            ?>
        </ul>
        

        <script>
            function openAction(action) {
                var selectedTestId = document.querySelector('input[name="test"]:checked');
                if (!selectedTestId) {
                    alert('Seleziona un test.');
                    return;
                }
                var testId = selectedTestId.value;
                if (action === 'effettua') {
                    window.location.href = '../Test/effettuaTest.php?id=' + testId;
                }
            }
        </script>
    </div>

</body>

</html>