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
            include 'navbar.php';
            include 'login.php';
            
            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: index.html");
                exit();
            }
            
            $email_login = $_SESSION['email'];
            $ruolo_login = $_SESSION['ruolo'];
            
           
            
            echo "\nLista Test:";
                // Query per selezionare tutti i test
                $sql_all_tests = "CALL visualizzaTestDisponibili()";
                
                $result_all_tests = $conn->query($sql_all_tests);

                // Verifica se ci sono test 
                if ($result_all_tests->num_rows > 0) {
                    echo "<form id='testForm' action='funzioniPerTest.php' method='get'>";
                    while ($row = $result_all_tests->fetch_assoc()) {
                        echo "<li class='test-item'>";
                        
                        echo "<input type='radio' name='test' value='" . $row['Titolo'] . "'>"; 
                        foreach ($row as $key => $value) {
                            echo ucfirst($key) . ": " . $value . "<br>";
                        }
                        echo "</li>";
                    }
                    echo "<input type='hidden' name='action' id='actionField'>";
                    echo "</form>";
                }
            
                echo "<button id='creaButton' type='button' onclick='submitForm(\"crea\")'>Crea Test</button>";
                echo "<button id='modificaButton' type='button' onclick='submitForm(\"modifica\")'>Modifica test</button>";
                echo "<button id='cancellaButton' type='button' onclick='submitForm(\"cancella\")'>Cancella</button>";

                echo "
                <script>
                    function submitForm(action) {
                        document.getElementById('actionField').value = action;
                        document.getElementById('testForm').submit();
                    }
                </script>
                ";
            ?>
        </ul>
    </div>
</body>
</html>
