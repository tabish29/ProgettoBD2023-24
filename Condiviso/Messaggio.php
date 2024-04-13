<?php
include '../../connessione.php';

    class Messaggio{

        function getMessaggiRicevutiDocente($email){
            $sql_all_messagesRicevuti = "SELECT * FROM ricezionedocente as RD, messaggio as M, inviostudente as S WHERE ((EmailDocenteDestinatario = '$email') AND (RD.TitoloTest = M.TitoloTest) AND (RD.TitoloTest = S.TitoloTest) AND (M.id = RD.Id))";
            $result_all_messagesRicevuti = $_SESSION['conn']->query($sql_all_messagesRicevuti);
            return $result_all_messagesRicevuti;
        }
        
        function getMessaggiInviatiDocente($email){
            $sql_all_messagesInviati = "SELECT * FROM messaggio as M, inviodocente as D WHERE (M.Id = D.Id) AND (D.emailDocenteMittente = '$email')";
            $result_all_messagesInviati = $_SESSION['conn']->query($sql_all_messagesInviati);
            return $result_all_messagesInviati;
        }
            
        function inserisciMessaggioDocente($titoloTest, $oggetto, $testo, $emailMittente){
            $sql = "CALL inserimentoMessaggioDocente('$titoloTest', '$oggetto', '$testo', NOW(), '$emailMittente')";
            if ($_SESSION['conn']->query($sql) === TRUE) {
                return TRUE;
            } else {
                return FALSE;
            }
        
        }

        function getMessaggiRicevutiStudente($email){
            $sql_all_messagesRicevuti = "CALL ricezioneMessaggiStudente('$email')";
            $result_all_messagesRicevuti = $_SESSION['conn']->query($sql_all_messagesRicevuti);
            $_SESSION['conn']->next_result();
            if ($result_all_messagesRicevuti->num_rows > 0) {
                return $result_all_messagesRicevuti;
            } else {
                return false;
            }
        }

        function getMessaggiInviatiStudente($email){
            $sql_all_messagesInviati = "SELECT * FROM messaggio as M, inviostudente as D WHERE (M.Id = D.Id) AND ('$email' = D.EmailStudenteMittente)";
            $result_all_messagesInviati = $_SESSION['conn']->query($sql_all_messagesInviati);
            $_SESSION['conn']->next_result();
            if ($result_all_messagesInviati->num_rows > 0) {
                return $result_all_messagesInviati;
            } else {
                return false;
            }
        }

        function inserisciMessaggioStudente($emailStudenteMittente, $emailDocenteDestinatario, $titoloTest, $oggetto, $testo){
            $sql = "CALL inserisciMessaggioStudente('$emailStudenteMittente', '$emailDocenteDestinatario', '$titoloTest', '$oggetto', '$testo')";
            $risultato = $_SESSION['conn']->query($sql);
            if ($risultato === TRUE) {
                return true;
            } else {
                return false;
            }
        }
        
    }
    
?>