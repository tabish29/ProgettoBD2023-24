<?php
include 'connessione.php';
if (!isset($_SESSION)){
    session_start();
}


// Verifica se è stata inviata una richiesta POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupero i dati inseriti nel form di registrazione
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST["email_reg"];
    $ruolo = $_POST['ruolo'];
    $recapito_telefonico = $_POST['recapito_telefonico'];

    // Campi aggiuntivi per studenti
    $codice_alfanumerico = "";
    $anno_immatricolazione = "";

    // Campi aggiuntivi per docenti
    $nome_corso = "";
    $nome_dipartimento = "";

    try {
        // Se il ruolo è "studente", recupera i campi aggiuntivi specifici per gli studenti
        if ($ruolo === "studente") {
            $codice_alfanumerico = $_POST['codice_alfanumerico'];
            $anno_immatricolazione = $_POST['anno_immatricolazione'];

            // Chiama la procedura di registrazione dello studente
            
            $sql = "CALL RegistrazioneStudente('$email', '$nome', '$cognome', '$recapito_telefonico', '$anno_immatricolazione', '$codice_alfanumerico')";
            if ($conn->query($sql) === TRUE) {
                echo "Registrazione avvenuta con successo!";
                echo '<br>';
                echo '<a href="index.html">Torna alla schermata principale</a>'; 
            }
            else{
                echo "Errore durante l'esecuzione della stored procedure.";
            }

        }

        // Se il ruolo è "docente", recupera i campi aggiuntivi specifici per i docenti
        if ($ruolo === "docente") {
            $nome_corso = $_POST['nome_corso'];
            $nome_dipartimento = $_POST['nome_dipartimento'];

            $sql = "CALL RegistrazioneDocente('$email', '$nome', '$cognome', '$recapito_telefonico', '$nome_dipartimento', '$nome_corso')";
            if ($conn->query($sql) === TRUE) {
                echo "Registrazione avvenuta con successo!";
                echo '<br>';
                echo '<a href="index.html">Torna alla schermata principale</a>'; 
            }
            else{
                echo "Errore durante l'esecuzione della stored procedure.";
            }
            // Chiama la procedura di registrazione del docente
            //$stmt = $conn->prepare("CALL RegistrazioneDocente(?, ?, ?, ?, ?, ?)");
            //$stmt->bind_param("ssssss", $email, $nome, $cognome, $recapito_telefonico, $nome_dipartimento, $nome_corso);
        }

    }catch (Exception $e) {
        // Gestisci altre eccezioni
        echo "Errore durante la registrazione: " . $e->getMessage();
        echo '<br>';
        echo '<a href="index.html">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
    } 

}

// Chiusura della connessione
//$conn->close();
?>
