<?php
include '../connessione.php';

class Utente {

    function __construct() {
        $_SESSION['conn']->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
    }

    function registrazioneStudente($email, $password, $nome, $cognome, $recapito_telefonico, $anno_immatricolazione, $codice_alfanumerico) {
        try {
            $sql = "CALL RegistrazioneStudente(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("sssssis", $email, $password, $nome, $cognome, $recapito_telefonico, $anno_immatricolazione, $codice_alfanumerico);
            $stmt->execute();
            return TRUE;
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante la registrazione dello studente: " . $e->getMessage());
            return FALSE;
        }
    }

    function registrazioneDocente($email, $password, $nome, $cognome, $recapito_telefonico, $nome_dipartimento, $nome_corso) {
        try {
            $sql = "CALL RegistrazioneDocente(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("sssssss", $email, $password, $nome, $cognome, $recapito_telefonico, $nome_dipartimento, $nome_corso);
            $stmt->execute();
            return TRUE;
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante la registrazione del docente: " . $e->getMessage());
            return FALSE;
        }
    }

    function emailPresenteDocente($email_login, $password_login) {
        try {
            $sql_check_email = "SELECT email FROM docente WHERE email = ? AND PasswordDocente = ?";
            $stmt = $_SESSION['conn']->prepare($sql_check_email);
            $stmt->bind_param("ss", $email_login, $password_login);
            $stmt->execute();
            return $stmt->get_result();
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante il controllo dell'email del docente: " . $e->getMessage());
            return false;
        }
    }

    function emailPresenteStudente($email_login, $password_login) {
        try {
            $sql_check_email = "SELECT email FROM studente WHERE email = ? AND PasswordStudente = ?";
            $stmt = $_SESSION['conn']->prepare($sql_check_email);
            $stmt->bind_param("ss", $email_login, $password_login);
            $stmt->execute();
            return $stmt->get_result();
        } catch (mysqli_sql_exception $e) {
            error_log("Eccezione durante il controllo dell'email dello studente: " . $e->getMessage());
            return false;
        }
    }
}
?>