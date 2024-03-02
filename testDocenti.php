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
            
            echo "Lista Test:";
                // Query per selezionare tutti i test
                $sql_all_tests = "CALL visualizzaTestDisponibili()";
                
                $result_all_tests = $conn->query($sql_all_tests);

                // Verifica se ci sono test 
                if ($result_all_tests->num_rows > 0) {
                    while ($row = $result_all_tests->fetch_assoc()) {
                        echo "<li class='test-item'>";
                        foreach ($row as $key => $value) {
                            echo ucfirst($key) . ": " . $value . "<br>";
                        }
                    echo "</li>";
                    }
                }
            
            
            ?>
        </ul>
    </div>
</body>
</html>
