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
    }
?>