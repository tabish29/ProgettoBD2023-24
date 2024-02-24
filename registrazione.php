<?php

$servername = "localhost"; // Il tuo server
$username = "root"; // Il tuo username
$password = "sk2wo9xm"; // La tua password (di solito è vuota di default in ambiente di sviluppo come XAMPP)
$dbname = "esql"; // Il nome del tuo database

// Creazione della connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
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
            $stmt = $conn->prepare("CALL RegistrazioneStudente(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssis", $email, $nome, $cognome, $recapito_telefonico, $anno_immatricolazione, $codice_alfanumerico);
        }

        // Se il ruolo è "docente", recupera i campi aggiuntivi specifici per i docenti
        if ($ruolo === "docente") {
            $nome_corso = $_POST['nome_corso'];
            $nome_dipartimento = $_POST['nome_dipartimento'];

            // Chiama la procedura di registrazione del docente
            $stmt = $conn->prepare("CALL RegistrazioneDocente(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $email, $nome, $cognome, $recapito_telefonico, $nome_dipartimento, $nome_corso);
        }


        // Esecuzione della procedura
        if ($stmt->execute()) {
            // Messaggio di conferma della registrazione
            echo "Registrazione avvenuta con successo!";
            echo '<br>';
            echo '<a href="index.html">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
        } else {
           // Messaggio di errore durante la registrazione
           echo "Errore durante l'esecuzione della stored procedure.";
        }

    }catch (Exception $e) {
        // Gestisci altre eccezioni
        echo "Errore durante la registrazione: " . $e->getMessage();
        echo '<br>';
        echo '<a href="index.html">Torna alla schermata principale</a>'; // Aggiungi un link per tornare alla schermata principale
    } finally {
        // Chiusura dello statement
        $stmt->close();
    }

}

// Chiusura della connessione
$conn->close();
?>
