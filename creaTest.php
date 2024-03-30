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
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: large;
            margin-top: 10px;
            text-align: left;
        }
        .email-list {
            list-style-type: none;
            padding: 0;
        }
        .test-item {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .btn {
            width: auto;
            height: auto;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            include 'connessione.php';
            if (!isset($_SESSION)){
                session_start();
            }
            try{
                $email_login = $_SESSION['email'];

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
                        function creaGrafica() {
                            echo "
                            <form id='creaTestForm' action='creaTest.php' method='post'>
                                <label for='titolo'>Titolo:</label>
                                <input type='text' id='titolo' name='titolo' required><br>                                <label for='fotoLabel'>Foto:</label>
                                <input type='file'id='fotoF' name='foto'><br>
                                <label for='visibilita'>Visibilità:</label>
                                <input type='checkbox' id='visibilitaCB' name='visibilita'><br>
                                <input type='hidden' name='action' value='crea'>
                                <button type='submit' class='btn' id='creaTestButton'>Crea</button>
                            </form>
                            ";
                        }

                        creaGrafica();

                        
                }
            
            
            
            

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $_SESSION['email'] = $email_login;


                if (isset($_POST['action']) && $_POST['action'] == 'crea') {


                    $titolo = $_POST['titolo'];
                    $foto = $_POST['foto']; //TODO: Andrebbe messo _FILES['foto'] e sistemato l'html ma non so come fare per passare l'immagine
                    
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
                    echo '<a href="index.html">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
                } 



        ?>
    </div>
</body>