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
        
        .creaBtn{
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
        <?php
            include '../connessione.php';
            if (!isset($_SESSION)){
                session_start();
            }
            try{
                $email_login = $_SESSION['email'];

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
                        function creaGrafica() {
                            echo "
                            <h2 class='creaTest'>Crea Test</h2>
                            <form id='creaTestForm' action='creaTest.php' method='post' enctype='multipart/form-data'>
                                <label class='label' for='titolo'>Titolo:</label>
                                <input class='areaInserimento' type='text' id='titolo' name='titolo' required><br>                                
                                <label class='label' for='fotoLabel'>Foto:</label>
                                <input type='file' id='foto' name='foto'><br>
                                <label class='label'  for='visibilita'>Visibilità:</label>
                                <input type='checkbox' id='visibilitaCB' name='visibilita'><br>
                                <input  type='hidden' name='action' value='crea'>
                                <button type='submit' class='creaBtn' id='creaTestButton'>Crea</button><br>
                                <a href=\"testDocenti.php\" class=\"creaBtn\">Torna ai Test</a>
                            </form>
                            ";
                        }

                        creaGrafica();

                        
                }
            
            
            
            

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $_SESSION['email'] = $email_login;


                if (isset($_POST['action']) && $_POST['action'] == 'crea') {


                    $titolo = $_POST['titolo'];
                    $foto = "Immagini/default.png";
                    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
                        $nomeFileFoto = $_FILES['foto']['name'];
                        $foto = "Immagini/" . $nomeFileFoto;
                    }                 
                    $visibilita = isset($_POST['visibilita']) ? true : false; // Se il checkbox è stato selezionato, $visibilita sarà 1, altrimenti sarà 0

                    $data = date('Y-m-d H:i:s');// Data e ora correnti
                    
                    

                    $email_login = $_SESSION['email'];

                    $sql = "CALL CreazioneTest('$titolo', NOW(), '$foto', '$visibilita', '$email_login')";
                    //$sql = "CALL CreazioneTest('ciao', NOW(), null, 'false', 'docente@gmail.com')"; //ELIMINARE
                    //echo $sql;
                    $risultato = $conn->query($sql);
                    $conn->next_result(); //Se no entra in conflitto con la query di testDocenti
                    

                    // Verifica se ci sono errori nella query
                    if ($risultato === TRUE && mysqli_affected_rows($conn) > 0) {
                        echo "Test creato correttamente. <br>";
                        echo '<a href="testDocenti.php">Torna ai Test</a>';
                    } else {
                        echo "Errore durante la creazione del test o nessuna riga è stata inserita nel database.";
                        if ($conn->error){
                            echo "Errore: " . $conn->error;
                        }
                        echo '<a href="testDocenti.php">Torna ai Test</a>';
                    }
                    

                

                    }
                    else{
                        echo "Errore";
                    }
                }
            }
                catch (Exception $e) {
                    // Gestisci altre eccezioni
                    echo "Errore durante l'esecuzione dell'operazione': " . $e->getMessage();
                    echo '<br>';
                    echo '<a href="../">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
                } 



        ?>
    </div>
</body>