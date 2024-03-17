<?php
    include 'GestoreTest.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   
        (isset($_GET['test']) ? $testId = $_GET['test'] : $testId = null); // l'ID del test selezionato
        $action = $_GET['action']; // l'azione richiesta (crea, modifica, cancella)
    
        switch ($action) {
            case 'crea':
                $gestoreTest = new GestoreTest();
                $gestoreTest->crea();
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
    
    
    
} else {
    echo "Metodo non valido.";
}



?>