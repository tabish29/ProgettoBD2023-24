<?php
    include '../../connessione.php';

class Quesito{

    function ottieniTipologiaQuesito($titoloTest, $numeroProgressivoQuesito){
        if ($this->verificaTipologiaRispostaChiusa($titoloTest, $numeroProgressivoQuesito)){
            return "Risposta Chiusa";
        } else if ($this->verificaTipologiaCodice($titoloTest, $numeroProgressivoQuesito)){
            return "Codice";
        }
    }
    
    function verificaTipologiaRispostaChiusa($titoloTest, $numeroProgressivo){
        $sql_verificaTipologia = "SELECT * FROM QUESITORISPOSTACHIUSA WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $result_verificaTipologia = $_SESSION['conn']->query($sql_verificaTipologia);
        $_SESSION['conn']->next_result();
        if ($result_verificaTipologia->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }

    function verificaTipologiaCodice($titoloTest, $numeroProgressivo){
        $sql_verificaTipologia = "SELECT * FROM QUESITOCODICE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $result_verificaTipologia = $_SESSION['conn']->query($sql_verificaTipologia);
        $_SESSION['conn']->next_result();
        if ($result_verificaTipologia->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }

    function ottieniRispostaCorrettaCodice($titoloTest, $numeroProgressivo){
        global $conn;
        $rispostaCorretta = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $rispostaCorretta = $conn -> query($rispostaCorretta);
        $rispostaCorretta = $rispostaCorretta -> fetch_assoc();
        $rispostaCorretta = $rispostaCorretta['TestoSoluzione'];
        echo "risposta corretta: " . $rispostaCorretta;
        return $rispostaCorretta;

    }

    function verificaRispostaCodice($testId, $numQuesito, $rispostaData, $rispostaCorretta) {
        global $conn;
            $soluzione = $conn -> prepare($rispostaCorretta);                
            $soluzione -> execute();
            $soluzione = $soluzione -> get_result();
    
            $rispostaDataSoluzione = $conn -> prepare($rispostaData);
            $rispostaDataSoluzione -> execute();
            $rispostaDataSoluzione = $rispostaDataSoluzione -> get_result();
            
            if($rispostaDataSoluzione == $soluzione) { 
                return 1;
            } else {
                return 0;
            }
        
    }

    function creaQuesitoRispostaChiusa($titoloTest, $livDifficolta, $descrizione){
        $sql_creaQuesitoQuery = "CALL CreazioneQuesitoRispostaChiusa('$titoloTest', '$livDifficolta', '$descrizione', @numeroProgressivoQuesito)";
        $risultato = $_SESSION['conn']->query($sql_creaQuesitoQuery);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            echo "<p>Errore nella creazione del quesito: " . $_SESSION['conn']->error . "</p>";
        } else {
            $sql_numeroProgressivo = "SELECT @NumeroProgressivoQuesito AS NumeroProgressivo";
            $result = $_SESSION['conn']->query($sql_numeroProgressivo);
            $row = $result->fetch_assoc();
            $numeroProgressivoQuesito = $row['NumeroProgressivo'];
            return $numeroProgressivoQuesito;
        }
    }

    function creaQuesitoCodice($titoloTest, $livDifficolta, $descrizione){
        $sql_creaQuesitoQuery = "CALL CreazioneQuesitoCodice('$titoloTest', '$livDifficolta', '$descrizione', @numeroProgressivoQuesito)";
        $risultato = $_SESSION['conn']->query($sql_creaQuesitoQuery);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            echo "<p>Errore nella creazione del quesito: " . $_SESSION['conn']->error . "</p>";
        } else {
            $sql_numeroProgressivo = "SELECT @NumeroProgressivoQuesito AS NumeroProgressivo";
            $result = $_SESSION['conn']->query($sql_numeroProgressivo);
            $row = $result->fetch_assoc();
            $numeroProgressivoQuesito = $row['NumeroProgressivo'];
            return $numeroProgressivoQuesito;
        }
    }

    function collegaTabella($numeroProgressivoQuesito, $titoloTest, $TabDaCollegare){
        $sql_creaCostituzioneQuery = "CALL CreazioneCostituzione('$numeroProgressivoQuesito', '$titoloTest', '$TabDaCollegare')";
        $risultato = $_SESSION['conn']->query($sql_creaCostituzioneQuery);
        $_SESSION['conn']->next_result();
        return $risultato;
    }

    function verificaPresenzaCollegamento($titoloTest, $numeroProgressivoQuesito){
        $sql_verificaPresenzaCollegamento = "CALL verificaPresenzaCollegamento('$titoloTest', '$numeroProgressivoQuesito', @risultato)";
        $_SESSION['conn']->query($sql_verificaPresenzaCollegamento);
        $_SESSION['conn']->next_result();
        $sql = "SELECT @risultato AS Risultato";
        $result = $_SESSION['conn']->query($sql);
        $row = $result->fetch_assoc();
        $risultato = $row['Risultato'];
        echo "Risultato: " . $risultato;
        $_SESSION['conn']->next_result();
        if ($risultato >= 1){
            return true;
        } else {
            return false;
        }

    }

    function setOpzioneRispostaCorretta($titoloTest, $numeroProgressivoQuesito, $rispostaSelezionata){
        $sql_inserisciRispostaCorretta = "CALL setOpzioneRispostaCorretta('$titoloTest', '$numeroProgressivoQuesito', '$rispostaSelezionata')";
        $risultato = $_SESSION['conn']->query($sql_inserisciRispostaCorretta);
        $_SESSION['conn']->next_result();
        return $risultato;
    }

}
?>