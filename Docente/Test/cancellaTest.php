<?php
    include '../../connessione.php';
    include '../../Condiviso/Test.php';
    if (!isset($_SESSION)){
        session_start();
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Cancella Test</h2>
        <ul>
        <?php
              
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $titoloTest = $_GET['id'];
                $test = new Test();

                $risultato = $test->cancellaTest($titoloTest);

                if ($risultato === TRUE) {
                    echo '<script>
                                window.alert("Test cancellato correttamente!");
                                window.location.href = "../navBar/testDocenti.php"; 
                            </script>';
                    exit(); 
                } else {
                    echo '<script>
                                window.alert("Errore nella cancellazione del test!");
                                window.location.href = "../navBar/testDocenti.php"; 
                            </script>';
                    exit();
                }
                        
            }
            
        ?>
        </ul>
        
        
    </div>
</body>
</html>
