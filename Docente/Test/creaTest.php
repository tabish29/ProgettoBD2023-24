<?php
if (!isset($_SESSION)){
    session_start();
}
include '../../connessione.php';
include '../../Condiviso/Test.php';
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
            background-color: #f9acac;
        }
        .container {
            text-align: center;
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9acac;
            border-radius: 5px;
        }

        .creaBtn {
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

        .areaInserimento {
            width: 30%;
            height: 30px;
            display: block;
            margin: auto;
        }

        .label {
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
        <?php
        try {
            $email_login = $_SESSION['email'];

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                function creaGrafica() {
                    echo "
                    <h2 class='creaTest'>Crea Test</h2>
                    <form id='creaTestForm' action='creaTest.php' method='post' enctype='multipart/form-data'>
                        <label class='label' for='titolo'>Inserisci il titolo del test:</label>
                        <input class='areaInserimento' type='text' id='titolo' name='titolo' required><br>                                
                        <label class='label' for='fotoLabel'>Seleziona la foto da associare al test:</label>
                        <input type='file' id='foto' name='foto'><br>
                        <input  type='hidden' name='action' value='crea'>
                        <button type='submit' class='creaBtn' id='creaTestButton'>Crea</button><br>
                        <a href=\"../navBar/testDocenti.php\" class=\"creaBtn\">Torna ai Test</a>
                    </form>
                    ";
                }

                creaGrafica();
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_SESSION['email'] = $email_login;

                if (isset($_POST['action']) && $_POST['action'] == 'crea') {
                    $titolo = $_POST['titolo'];
                    $foto = "../Immagini/default.png";
                    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
                        $nomeFileFoto = $_FILES['foto']['name'];
                        $foto = "../Immagini/" . $nomeFileFoto;
                    }                 
                    $data = date('Y-m-d H:i:s');// Data e ora correnti

                    $email_login = $_SESSION['email'];

                    $test = new Test();
                    $risultato = $test->creaTest($titolo, $foto);

                    if ($risultato === TRUE && mysqli_affected_rows($conn) > 0) {
                        echo '<script>
                                window.alert("Test creato correttamente!");
                                window.location.href = "../navBar/testDocenti.php"; 
                            </script>';
                        exit();                   
                    } else {
                        echo '<script>window.alert("Errore durante la creazione del test!");</script>';
                    }
                } else {
                    throw new Exception("Errore durante l'esecuzione dell'operazione");
                }
            }
        } catch (Exception $e) {
            echo '<script>window.alert("Errore durante la creazione del test!");
            window.location.href = "../navBar/testDocenti.php"; 
            </script>';
            
        } 
        ?>
    </div>
</body>
</html>
