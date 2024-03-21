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
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .test-list {
            list-style-type: none;
            padding: 0;
        }
        .test-item {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <ul class="test-list">
            <?php

                /*
                TODO:
                MOSTRARE IN UNA SEZIONE SEPRATA I TEST IN COMPLETAMENTO COSI DA NON RENDERLI SELEZIONABILI
                */
                if (!isset($_SESSION)){
                    session_start();
                }

                include 'navbar.php';
                include 'login.php';
                
                if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                    // Redirect a una pagina di login se l'utente non è autenticato
                    header("Location: index.html");
                    exit();
                }
                
                $email_login = $_SESSION['email'];
                $ruolo_login = $_SESSION['ruolo'];
                
                echo "Valore della variabile di sessione email in testDocenti.php: " . $_SESSION['email'];

                
                echo "\nLista Test:";
                    // Query per selezionare tutti i test
                $sql_all_tests = "CALL visualizzaTestDisponibili()";
                    
                $result_all_tests = $conn->query($sql_all_tests);
                $conn->next_result(); //Se no entra in conflitto con la query di funzioniPerTest
                // Verifica se ci sono test 
                if ($result_all_tests->num_rows > 0) {
                    echo "<form id='testForm'>";
                    while ($row = $result_all_tests->fetch_assoc()) {
                        echo "<li class='test-item'>";
                        echo "<input type='radio' name='test' value='" . $row['Titolo'] . "'>"; 
                        foreach ($row as $key => $value) {
                            if ($key === 'Foto') {
                                echo "<img src='data:image/png;base64," . base64_encode($value) . "' alt='Immagine del test'> <br>";
                            } else {
                                echo ucfirst($key) . ": " . $value . "<br>";
                            }
                        }
                        echo "</li>";
                    }
                    echo "<input type='hidden' name='action' id='actionField'>";
                    echo "</form>";
                }
            ?>
        </ul>
        <div class="button-container">
            <a href='creaTest.php' class='btn btn-primary'>Crea nuovo Test</a>
            <button class="action-button" onclick="openAction('modifica')">Modifica Test</button>
            <button class="action-button" onclick="openAction('cancella')">Cancella Test</button>
        </div>
                    
        <script>
            function openAction(action) {
                var selectedTestId = document.querySelector('input[name="test"]:checked');
                if (!selectedTestId) {
                    alert('Seleziona un test.');
                    return;
                }
                var testId = selectedTestId.value;
                if (action === 'modifica') {
                    window.location.href = 'modificaTest.php?id=' + testId;
                } else if (action === 'cancella') {
                    var confirmDelete = confirm('Sei sicuro di voler cancellare questo test?');
                    if (confirmDelete) {
                        window.location.href = 'cancellaTest.php?id=' + testId;
                    }
                }
            }
        </script>
    </div>
                         
</body>
</html>
