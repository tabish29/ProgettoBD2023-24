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
        details {
            border-radius: 4px;
            padding: 0.5em 0.5em 0;
            }

        summary {
            font-weight: bold;
            margin: -0.5em -0.5em 0;
            padding: 0.5em;
            }

        details[open] {
            padding: 0.5em;
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
        
        
    </style>
</head>
<body>
    <div class="container">
        <ul class="test-list">
            <?php

                if (!isset($_SESSION)){
                    session_start();
                }

                include 'navbarStudente.php';
                include 'connessione.php';

                
             /*   if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                    // Redirect a una pagina di login se l'utente non è autenticato
                    header("Location: index.html");
                    exit();
                }
                */

                $email_login = $_SESSION['email'];
                $ruolo_login = $_SESSION['ruolo'];
                
                echo "Valore della variabile di sessione email in testStudenti.php: " . $_SESSION['email'];

                
                echo "\nLista Test:";
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
                        if ($result_completamento->num_rows > 0){
                            $statoCompletamento = $result_completamento->fetch_assoc()['Stato'];             
                        }
                        echo "<li class='test-item'>";
                        echo "<input type='radio' name='test' value='" . $titoloTest . "'>"; 

                        echo "<label for='test'>" . $titoloTest . "</label><br>";
                        echo "<label for ='test'>" . $row['EmailDocente'] . "</label><br>";
                        echo "<label for ='test'> Stato completamento: " . $statoCompletamento . "</label>";
                        echo "</li>";
                    }
                    echo "<input type='hidden' name='action' id='actionField'>";
                    echo "</form>";
                }
            ?>
        </ul>
        <div class="button-container">
            <button class="btn btn-primary" onclick="openAction('effettua')">Effettua il Test</button>
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
