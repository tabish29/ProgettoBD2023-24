
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
            text-align-last: center;
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
        .btn {
            width: auto;
            height: auto;
            border: 1px solid #222222;
            padding: 3px;
            margin: 0px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #7cfc00; 
            }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifica Test</h2>
        <ul>
            <?php
                include 'connessione.php';
                if (!isset($_SESSION)){
                    session_start();
                }

                

                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $testId = $_GET['id'];
                    ?>
                    
                    <form action = "inserisciTabella.php" method = "post">
                        <div class="form-group">
                            <label>Scrivi il codice SQL della Tabella:</label>
                            <textarea id='codiceTabella' name='codiceTabella' rows='20' cols='100'></textarea>
                            <input type="hidden" name="testId" value="<?php echo $testId; ?>"><br><br>
                            <input type="submit" value="Inserisci Tabella" class="btn">
                        </div>
                    </form>

                    <?php
                }

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $testId = $_POST['testId'];
                    $codiceTabella = $_POST['codiceTabella'];
                    echo "Codice Tabella: ".$codiceTabella;
                }
            ?>
        </ul>
    </div>
</body>
</html>
