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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'crea') {
        // Il tuo codice per creare un test qui...

        // Ad esempio, puoi accedere ai valori dei campi di input del form così:
        $titolo = $_POST['titolo'];
        $foto = $_POST['foto'];
        $visibilita = $_POST['visibilita'];

        // Quindi puoi utilizzare questi valori per creare il tuo test...
    }
}



?>