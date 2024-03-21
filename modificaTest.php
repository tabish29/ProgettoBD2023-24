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
        .test-details {
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
        .form-container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifica Test</h2>
        <ul class="test-details">
        <?php
            include 'login.php';
            if (!isset($_SESSION)){
                session_start();
            }

           
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                echo "ATTENZIONE NON FUNZIONA SE IL TEST HA UN COMPLETAMENTO PER VIA DELLA FOREIGN KEY"; //tODO

                function mostraDatiTest(){
                    include 'login.php';
                    // Preleva il Titolo del test dalla query string
                    $testId = $_GET['id'];

                    // Esegue la query per selezionare il test dal database
                    $sql_select_test = "SELECT * FROM TEST WHERE Titolo = '$testId'";
                    $result_select_test = $conn->query($sql_select_test);

                    // Verifica se il test è stato trovato
                    if ($result_select_test->num_rows > 0) {
                        $row = $result_select_test->fetch_assoc();
                        // Visualizza i dettagli del test
                        echo "<li class='test-item'>Titolo: " . $row['Titolo'] . "</li>";
                        echo "<li class='test-item'>Data Creazione: " . $row['DataCreazione'] . "</li>";
                        echo "<li class='test-item'>Visualizza Risposte: " . $row['VisualizzaRisposte'] . "</li>";
                        echo "<li class='test-item'>Email: " . $row['EmailDocente'] . "</li>";
                    } else {
                        echo "<li class='test-item'>Nessun test trovato con l'ID specificato.</li>";
                    }
                }
                function creaGrafica() {
                    $testId = $_GET['id'];
                    echo "Test ahhh: " . $testId;
                    echo "
                    <form id='modificaTestForm' action='modificaTest.php' method='post'>
                                <input type='hidden' id ='vecchioTitolo' name='vecchioTitolo' value=" . $testId . ">
                                <label for='titolo'>Titolo:</label>
                                <input type='text' id='titolo' name='titolo' required>
                                <br>
                                <label for='visualizzaRisposte'>Visualizza Risposte:</label>
                                <input type='checkbox' id='visualizzaRisposteCB' name='visualizzaRisposte'>
                                <br>
                                <input type='hidden' name='action' value='crea'>
                                <button type='submit' id='modificaTestButton'>Modifica</button>
                                
                            </form>
                            ";
                        }

                        mostraDatiTest();

                        creaGrafica();
        
                        
                }

            

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                    $vecchioTitolo = $_POST['vecchioTitolo'];
                    $titolo = $_POST['titolo'];
                    $visualizza_risposte = isset($_POST['visualizzaRisposte']) ? '1' : '0';

                    // Query SQL per aggiornare il test nel database
                    $sql_update_test = "UPDATE TEST SET Titolo = '$titolo', VisualizzaRisposte = $visualizza_risposte WHERE Titolo = '$vecchioTitolo'";


                    // Esegue la query di aggiornamento
                    if ($conn->query($sql_update_test) === TRUE && mysqli_affected_rows($conn) > 0) {
                        echo "Test aggiornato con successo.";
                        echo '<a href="testDocenti.php">Torna ai Test</a>';
                    } else {
                        echo "Errore durante l'aggiornamento del test: " . $conn->error;
                        echo '<a href="testDocenti.php">Torna ai Test</a>';
                    }
                
            }
            
            

            
        ?>
        </ul>
        
        
    </div>
</body>
</html>
