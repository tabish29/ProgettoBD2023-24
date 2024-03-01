<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage Docente</title>
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
        <?php include 'navbar.php' ?>
        <h2>HomePage docente</h2>
        <ul class="test-list">
        <?php
            
            $servername = "localhost"; // Il tuo server
            $username = "root"; // Il tuo username
            $password = "Alessia123!"; // La tua password (di solito è vuota di default in ambiente di sviluppo come XAMPP)
            $dbname = "esql"; // Il nome del tuo database

            // Creazione della connessione
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica della connessione
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

           
                
            ?>
        </ul>
    </div>
</body>
</html>
