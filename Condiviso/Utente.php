<?php
    include '../connessione.php';
    
    class Utente{

        function registrazioneStudente($email, $password, $nome, $cognome, $recapito_telefonico, $anno_immatricolazione, $codice_alfanumerico){
            $sql = "CALL RegistrazioneStudente('$email', '$password','$nome', '$cognome', '$recapito_telefonico', '$anno_immatricolazione', '$codice_alfanumerico')";

            if ($_SESSION['conn']->query($sql) === TRUE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        function registrazioneDocente($email, $password, $nome, $cognome, $recapito_telefonico, $nome_dipartimento, $nome_corso){
            $sql = "CALL RegistrazioneDocente('$email', '$password','$nome', '$cognome', '$recapito_telefonico', '$nome_dipartimento', '$nome_corso')";

            if ($_SESSION['conn']->query($sql) === TRUE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        function emailPresenteDocente($email_login, $password_login){
            $sql_check_email = "SELECT email FROM docente WHERE email = '$email_login' AND PasswordDocente = '$password_login'";
            $result_check_email = $_SESSION['conn']->query($sql_check_email);
            return $result_check_email;
        }

        function emailPresenteStudente($email_login, $password_login){
            $sql_check_email = "SELECT email FROM studente WHERE email = '$email_login' AND PasswordStudente = '$password_login'";
            $result_check_email = $_SESSION['conn']->query($sql_check_email);
            return $result_check_email;
        }
    }
?>