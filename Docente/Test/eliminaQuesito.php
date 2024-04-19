<?php
    include '../../connessione.php';
    include '../../Condiviso/Test.php';
    if (!isset($_SESSION)){
        session_start();
    }
    if ($_SESSION['ruolo'] != 'Docente') {
        echo "Accesso Negato";
        header('Location: ../../Accesso/Logout.php?message=Utente non autorizzato.');
        exit();
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
        <ul>
        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $campiSchermataPrecedente = explode(";",$_GET['idTest']);
                $titoloTest = $campiSchermataPrecedente[0];
                $numeroProgressivo = $campiSchermataPrecedente[1];
                $test = new Test();
                $risultato = $test->cancellaQuesito($titoloTest, $numeroProgressivo);

                if ($risultato === TRUE) {
                    echo '<script>
                                window.alert("Quesito cancellato correttamente!");
                                window.location.href = "modificaTest.php?id='.$titoloTest.'";
                            </script>';
                    exit(); 
                } else {
                    echo '<script>
                                window.alert("Errore nella cancellazione del quesito!");
                                window.location.href = "modificaTest.php?id='.$titoloTest.'";
                            </script>';
                    exit();
                }
        
                        
            }

         

            
        ?>
        </ul>
        
        
    </div>
</body>
</html>
