<?php
    include 'GestoreTest.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['test'])) {
        $testId = $_POST['test']; // l'ID del test selezionato
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
        echo "La variabile 'test' non è stata ricevuta.";
    }
    
    
} else {
    echo "Metodo non valido.";
}



?>