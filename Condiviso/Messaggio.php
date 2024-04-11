<?php
include '../connessione.php';

    class Messaggio{

        function getMessaggiRicevuti($email){
            $sql_all_messagesRicevuti = "SELECT * FROM ricezionedocente as RD, messaggio as M, inviostudente as S WHERE ((EmailDocenteDestinatario = '$email') AND (RD.TitoloTest = M.TitoloTest) AND (RD.TitoloTest = S.TitoloTest) AND (M.id = RD.Id))";
            $result_all_messagesRicevuti = $_SESSION['conn']->query($sql_all_messagesRicevuti);
            return $result_all_messagesRicevuti;
        }
        
        function getMessaggiInviati($email){
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
    }
    
?>