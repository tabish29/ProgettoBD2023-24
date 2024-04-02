
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
            text-align: center;
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff8dc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .inseriscibtn{
            width: auto;
            height: auto;
            border: 1px solid #222222;
            padding: 3px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
            font-style: normal;
            color: #222222;
            background-color: #acf9ba; 
        }
        
        .areaInserimento{
            width: 40%;
            display: block;
            margin:auto;
        }
        .label{
            text-align: center;
            font: sans-serif;
            font-weight: bold;
            font-size: medium;
            padding: 1%;
            color: black;
            height: auto;
            width: auto;
            display: block;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inserisci Tabella</h2>
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
                            <label class="label">Scrivi il codice SQL della Tabella:</label><br>
                            <textarea id='codiceTabella' name='codiceTabella' rows='20' cols='100'></textarea>
                            <input type="hidden" name="testId" value="<?php echo $testId; ?>"><br><br>
                            <input type="submit" value="Inserisci Tabella" class="inseriscibtn">
                        </div>
                    </form>
                    <button id="modificaTest" class="inseriscibtn" onclick="window.location.href='modificaTest.php?id=<?php echo $_GET['id']; ?>'">Back</button>

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
