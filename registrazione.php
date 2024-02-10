<?php
// Connessione al database
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
    $email = $_POST['email_reg'];
    $ruolo = $_POST['ruolo'];
    $recapito_telefonico = $_POST['recapito_telefonico'];

    // Campi aggiuntivi per studenti
    $codice_alfanumerico = "";
    $anno_immatricolazione = "";

    // Campi aggiuntivi per docenti
    $nome_corso = "";
    $nome_dipartimento = "";

    // Se il ruolo è "studente", recupera i campi aggiuntivi specifici per gli studenti
    if ($ruolo === "studente") {
        $codice_alfanumerico = $_POST['codice_alfanumerico'];
        $anno_immatricolazione = $_POST['anno_immatricolazione'];
    }

    // Se il ruolo è "docente", recupera i campi aggiuntivi specifici per i docenti
    if ($ruolo === "docente") {
        $nome_corso = $_POST['nome_corso'];
        $nome_dipartimento = $_POST['nome_dipartimento'];
    }

    // Query per verificare se l'email è già presente nella tabella corretta (docente o studente)
    $sql_check_email = "";
    if ($ruolo === "docente") {
        $sql_check_email = "SELECT * FROM docente WHERE email = '$email'";
    } else if ($ruolo === "studente") {
        $sql_check_email = "SELECT * FROM studente WHERE email = '$email'";
    }

    $result = $conn->query($sql_check_email);

    if ($result->num_rows > 0) {
        // Se l'email è già presente, mostra un messaggio di errore
        echo "L'email inserita esiste già. Si prega di inserire un'altra email.";
        echo "<br>";
        echo '<button onclick="window.history.back()">Riprova</button>';
    } else {
        // Se l'email non è presente, aggiungi il nuovo utente alla tabella corretta
        $sql_insert = "";
        if ($ruolo === "docente") {
            $sql_insert = "INSERT INTO docente (nome, cognome, email,RecapitoTelefonico,NomeDipartimento,NomeCorso) VALUES ('$nome', '$cognome', '$email', '$recapito_telefonico','$nome_dipartimento','$nome_corso')";
        } else if ($ruolo === "studente") {
            $sql_insert = "INSERT INTO studente (nome, cognome, email, RecapitoTelefonico,AnnoImmatricolazione,CodiceAlfaNumerico) VALUES ('$nome', '$cognome', '$email', '$recapito_telefonico','$anno_immatricolazione','$codice_alfanumerico')";
        }

        if ($conn->query($sql_insert) === TRUE) {
            // Messaggio di conferma della registrazione
            echo "Registrazione avvenuta con successo!";
        } else {
            echo "Errore durante la registrazione: " . $conn->error;
        }
    }
}

// Chiusura della connessione
$conn->close();
?>
