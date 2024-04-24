<?php
include '../../connessione.php';

class Messaggio {
    function __construct() {
        // Assicurati che gli errori SQL lancino eccezioni
        $_SESSION['conn']->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
    }

    function getMessaggiRicevutiDocente($email) {
        try {
            $sql_all_messagesRicevuti = "SELECT * FROM ricezionedocente as RD, messaggio as M, inviostudente as S WHERE ((EmailDocenteDestinatario = ?) AND (RD.TitoloTest = M.TitoloTest) AND (RD.TitoloTest = S.TitoloTest) AND (M.id = RD.Id) AND (S.Id = RD.Id))";
            $stmt = $_SESSION['conn']->prepare($sql_all_messagesRicevuti);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result();
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante la ricezione dei messaggi per il docente: " . $e->getMessage());
            return false;
        }
    }

    function getMessaggiInviatiDocente($email) {
        try {
            $sql_all_messagesInviati = "SELECT * FROM messaggio as M, inviodocente as D WHERE (M.Id = D.Id) AND (D.emailDocenteMittente = ?)";
            $stmt = $_SESSION['conn']->prepare($sql_all_messagesInviati);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result();
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante la ricezione dei messaggi inviati dal docente: " . $e->getMessage());
            return false;
        }
    }

    function inserisciMessaggioDocente($titoloTest, $oggetto, $testo, $emailMittente) {
        try {
            $sql = "CALL inserimentoMessaggioDocente(?, ?, ?, NOW(), ?)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("ssss", $titoloTest, $oggetto, $testo, $emailMittente);
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante l'inserimento del messaggio del docente: " . $e->getMessage());
            return false;
        }
    }

    function getMessaggiRicevutiStudente($email) {
        try {
            $sql_all_messagesRicevuti = "CALL ricezioneMessaggiStudente(?)";
            $stmt = $_SESSION['conn']->prepare($sql_all_messagesRicevuti);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0 ? $result : false;
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante la ricezione dei messaggi per lo studente: " . $e->getMessage());
            return false;
        }
    }

    function getMessaggiInviatiStudente($email) {
        try {
            $sql_all_messagesInviati = "SELECT * FROM messaggio as M, inviostudente as D WHERE (M.Id = D.Id) AND (D.EmailStudenteMittente = ?)";
            $stmt = $_SESSION['conn']->prepare($sql_all_messagesInviati);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0 ? $result : false;
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante la ricezione dei messaggi inviati dallo studente: " . $e->getMessage());
            return false;
        }
    }

    function inserisciMessaggioStudente($emailStudenteMittente, $emailDocenteDestinatario, $titoloTest, $oggetto, $testo) {
        try {
            $sql = "CALL inserisciMessaggioStudente(?, ?, ?, ?, ?)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("sssss", $emailStudenteMittente, $emailDocenteDestinatario, $titoloTest, $oggetto, $testo);
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante l'inserimento del messaggio dello studente: " . $e->getMessage());
            return false;
        }
    }
}
?>