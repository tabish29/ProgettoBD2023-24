<?php
    if (!isset($_SESSION)){
        session_start();
    }
    include 'GestoreTest.php';
    include 'login.php';
    include 'testDocenti.php';
    try{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   
        (isset($_GET['test']) ? $testId = $_GET['test'] : $testId = null); // l'ID del test selezionato
        $action = $_GET['action']; // l'azione richiesta (crea, modifica, cancella)
    
        switch ($action) {
            case 'crea':
                $gestoreTest = new GestoreTest();
                $gestoreTest->creaGrafica();
                break;
            case 'modifica':
                // Codice per modificare il test con ID $testId
                break;
            case 'cancella':
                // Codice per cancellare il test con ID $testId
                break;
            default:
                echo "Azione non valida.";
                break;
        }
    
    
    
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action']) && $_POST['action'] == 'crea') {

            $titolo = $_POST['titolo'];
            $foto = $_POST['foto']; //TODO: Andrebbe messo _FILES['foto'] e sistemato l'html ma non so come fare per passare l'immagine
            
            $visibilita = isset($_POST['visibilita']) ? 1 : 0; // Se il checkbox è stato selezionato, $visibilita sarà 1, altrimenti sarà 0
            
            // Connessione al database
            //$mysqli = new mysqli('localhost', 'username', 'password', 'database');

            $data = date('Y-m-d H:i:s');// Data e ora correnti
            
            
            // Controllo della connessione
            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            $email_login = $_SESSION['email'];
            $sql = "CALL CreazioneTest('$titolo', NOW(), '$foto', '$visibilita', '$email_login')";
            echo $sql;

            if ($conn->query($sql) === TRUE) {
                echo "Test creato correttamente. <br>";
                echo '<a href="testDocenti.php">Torna ai Test</a>';
            } else {
                echo "Errore durante la creazione del test: " . $conn->error;                
            }

            }
            else{
                echo "Errore";
            }
        }
    }
        catch (Exception $e) {
            // Gestisci altre eccezioni
            echo "Errore durante la registrazione: " . $e->getMessage();
            echo '<br>';
            echo '<a href="index.html">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
        } 



?>