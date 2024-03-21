<?php
    if (!isset($_SESSION)){
        session_start();
    }
    include 'login.php';
    include 'testDocenti.php';
    try{
        $email_login = $_SESSION['email'];
        echo "Valore della variabile di sessione email in funzioniPerTest.php fuori dagli if: " . $_SESSION['email']; //ELIMINARE

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        echo "Valore della variabile di sessione email in funzioniPerTest.php nel GET: " . $_SESSION['email']; //ELIMINARE
   
        (isset($_GET['test']) ? $testId = $_GET['test'] : $testId = null); // l'ID del test selezionato
        $action = $_GET['action']; // l'azione richiesta (crea, modifica, cancella)
    
        switch ($action) {
            
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
        
    }
    }catch (Exception $e) {
            // Gestisci altre eccezioni
            echo "Errore durante l'esecuzione dell'operazione': " . $e->getMessage();
            echo '<br>';
            echo '<a href="index.html">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
        } 



?>