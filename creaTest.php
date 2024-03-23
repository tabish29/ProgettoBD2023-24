<?php
    include 'connessione.php';
    if (!isset($_SESSION)){
        session_start();
    }
    try{
        $email_login = $_SESSION['email'];
        echo "Valore della variabile di sessione email in funzioniPerTest.php fuori dagli if: " . $_SESSION['email']; //ELIMINARE

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        echo "Valore della variabile di sessione email in funzioniPerTest.php nel GET: " . $_SESSION['email']; //ELIMINARE
   
        
        echo "Valore della variabile di sessione email in funzioniPerTest.php prima di crea grafica: " . $_SESSION['email']; //ELIMINARE

                function creaGrafica() {
                    echo "
                    <form id='creaTestForm' action='creaTest.php' method='post'>
                        <label for='titolo'>Titolo:</label>
                        <input type='text' id='titolo' name='titolo' required>
                        <br>
                        <label for='fotoLabel'>Foto:</label>
                        <input type='file' id='fotoF' name='foto'>
                        <br>
                        <label for='visibilita'>Visibilità:</label>
                        <input type='checkbox' id='visibilitaCB' name='visibilita'>
                        <br>
                        <input type='hidden' name='action' value='crea'>
                        <button type='submit' id='creaTestButton'>Crea</button>
                    </form>
                    ";
                }

                creaGrafica();
                echo "Valore della variabile di sessione email in funzioniPerTest.php dopo di crea grafica: " . $_SESSION['email']; //ELIMINARE

                
        }
    
    
    
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $_SESSION['email'] = $email_login;
        echo "Valore della variabile di sessione email in funzioniPerTest.php nel POST: " . $_SESSION['email']; //ELIMINARE


        if (isset($_POST['action']) && $_POST['action'] == 'crea') {

            //include 'testDocenti.php';

            $titolo = $_POST['titolo'];
            $foto = $_POST['foto']; //TODO: Andrebbe messo _FILES['foto'] e sistemato l'html ma non so come fare per passare l'immagine
            
            $visibilita = isset($_POST['visibilita']) ? true : false; // Se il checkbox è stato selezionato, $visibilita sarà 1, altrimenti sarà 0
            
            // Connessione al database
            //$mysqli = new mysqli('localhost', 'username', 'password', 'database');

            $data = date('Y-m-d H:i:s');// Data e ora correnti
            
            
            // Controllo della connessione
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

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