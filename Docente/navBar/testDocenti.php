<?php
include '../../connessione.php';

if (!isset($_SESSION)) {
    session_start();
}
include 'navbarDocente.php';
include '../../Condiviso/Test.php';
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
            /* Allinea il testo a sinistra */
            margin: auto;
            font-size: small;
            font: Arial;
        }
        .test-item:hover {
            background-color: #9c9c9c;
            /* Cambia colore al passaggio del mouse */
        }
        .test-item:last-child {
            margin-bottom: 0;
            /* Rimuove il margine inferiore dall'ultimo elemento */
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
        display: inline-block; /* Aggiunta di display inline-block */
        margin-left : 20px; /* Aggiunta di margine a sinistra per separare i test */
        margin-right: 20px; /* Aggiunta di margine a destra per separare i test */
        margin-bottom: 20px; /* Aggiunta di margine inferiore per separare i test */
        padding: 10px;
        width: auto; /* Modifica della larghezza del singolo test */
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
        <ul class="test-list">
            <div class="containerBtn">
                <button class="button" onclick="window.location.href='../Test/creaTest.php'">Crea nuovo Test</button>
                
            <?php
            
            function graficaConTestPresenti(){
                echo "<button class='button' onclick='openAction(\"modifica\")'>Modifica Test</button>";
                echo "<button class='button' onclick='openAction(\"cancella\")'>Cancella Test</button>";
                echo "<button class='button' onclick='openAction(\"visualizzaTabelle\")'>Visualizza Tabelle</button>";
                echo "</div>";
            }


            // Query per selezionare tutti i test
            $test = new Test();
            $result_all_tests = $test->ottieniTuttiITest();
            // Verifica se ci sono test 
            if ($result_all_tests->num_rows > 0) {
                graficaConTestPresenti();
                echo "<h2 class='testListH2'>Lista Test:</h2>";

                echo "<form id='testForm'>";
                while ($row = $result_all_tests->fetch_assoc()) {
                    echo "<li class='test-item'>";
                    echo "<input type='radio' name='test' value='" . $row['Titolo'] . "'>";

                    foreach ($row as $key => $value) {

                        if ($key === 'Foto') {
                            echo "<Label class='labelBold'>Foto:</label><br>";
                            echo "<img src='" . $value . "'  width=200px height=200px />";
                            //TODO: foto sono caricate in locale, andrebbero messe su db
                        } else {
                            echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p>";
                        }
                    }
                    echo "</li>";
                }
                echo "<input type='hidden' name='action' id='actionField'>";
                echo "</form>";
            } else {
                echo "</div>"; //Chiudo il div dei bottoni
                echo "<p class='testListH2'>Non sono presenti test.</label>";
                
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
                if (action === 'modifica') {
                    window.location.href = '../Test/modificaTest.php?id=' + testId;
                } else if (action === 'cancella') {
                    var confirmDelete = confirm('Sei sicuro di voler cancellare questo test?');
                    if (confirmDelete) {
                        window.location.href = '../Test/cancellaTest.php?id=' + testId;
                    }
                } else if (action === 'visualizzaTabelle') {
                    window.location.href = '../Test/visualizzaTabelle.php?id=' + testId;
                }
            }
        </script>
    </div>

</body>

</html>