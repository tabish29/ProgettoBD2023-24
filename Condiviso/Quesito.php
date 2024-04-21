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

    function ottieniRispostaCorrettaCasualeCodice($titoloTest, $numeroProgressivo) {
        $sql = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = ? AND TitoloTest = ?";
        $stmt = $_SESSION['conn']->prepare($sql);
        $stmt->bind_param("is", $numeroProgressivo, $titoloTest);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $risposte = [];

        foreach ($rows as $row) {
            $risposte[] = $row['TestoSoluzione'];
        }
    
        // Seleziona una risposta casuale se l'array non è vuoto
        if (!empty($risposte)) {
            $indexCasuale = array_rand($risposte);  
            return $risposte[$indexCasuale];  
        } else {
            return null;  
        }
    }

    function verificaRispostaCodice($titoloTest, $numeroProgressivo, $rispostaData) {
        try {
            $rispostaCorretta = $this->ottieniRispostaCorrettaCasualeCodice($titoloTest, $numeroProgressivo);
            if (!$rispostaCorretta) {
                throw new Exception("Variabile rispostaCorretta non esiste");
            }
            // Esegue la query della soluzione corretta
            $resultSoluzione = $_SESSION['conn']->query($rispostaCorretta);
            if (!$resultSoluzione) {
                throw new Exception("Errore nell'esecuzione della query della soluzione: " . $_SESSION['conn']->error);
            }
            $soluzioneResults = $resultSoluzione->fetch_all(MYSQLI_ASSOC);
    
            // Esegue la query della risposta data dall'utente
            $resultRispostaData = $_SESSION['conn']->query($rispostaData);
            if (!$resultRispostaData) {
                throw new Exception("Errore nell'esecuzione della query della risposta data: " . $_SESSION['conn']->error);
            }
            $rispostaDataResults = $resultRispostaData->fetch_all(MYSQLI_ASSOC);
    
          
            if ($soluzioneResults == $rispostaDataResults) {
                return 1;  
            } else {
                return 0;  
            }
        } catch (Exception $e) {
            // Gestisce eventuali errori di database
            throw new Exception("Eccezione nella verifica della risposta:<br> " . $e->getMessage());
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
    
    function inserimentoOpzioneRisposta($titoloTest, $numeroProgressivoQuesito, $campoTesto){
        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoOpzioneRisposta('$titoloTest',$numeroProgressivoQuesito, '$campoTesto',false)";
        $risultato = $_SESSION['conn']->query($sql_queryNuovaOpzioneOSoluzione);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            return false;
        } else {
            return $risultato;
        }
    }

    function inserimentoSoluzione($titoloTest, $numeroProgressivoQuesito, $testoSoluzione){
        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoSoluzione('$titoloTest',$numeroProgressivoQuesito, '$testoSoluzione')";
        $risultato = $_SESSION['conn']->query($sql_queryNuovaOpzioneOSoluzione);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            return false;
        } else {
            return $risultato;
        }

    }

    function ottieniCampoTesto($titoloTest, $domanda){
        $ottieniCampoTesto = "SELECT * FROM opzionerisposta WHERE TitoloTest = '$titoloTest' AND NumeroProgressivoQuesito = '$domanda'";
        $risultato = $_SESSION['conn']->query($ottieniCampoTesto);
        $_SESSION['conn']->next_result();
        if (!$risultato || $risultato->num_rows == 0) {
            return false;
        } else {
            return $risultato;
        }
    }

    function ottieniEsitoCodice($idCompletamento){
        $sql = "SELECT Esito FROM RISPOSTAQUESITOCODICE WHERE NumeroProgressivoCompletamento = $idCompletamento";
        $risultato = $_SESSION['conn']->query($sql);
        $_SESSION['conn']->next_result();
        return $risultato;
    }

    function ottieniRispostaDataRC($idCompletamento, $numeroProgressivo, $titoloTest){
        
        $sql = "SELECT OpzioneScelta FROM RISPOSTAQUESITORISPOSTACHIUSA WHERE NumeroProgressivoCompletamento = $idCompletamento AND NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $risultato = $_SESSION['conn']->query($sql);
        $_SESSION['conn']->next_result();
        $risultato = $risultato->fetch_assoc();
        return $risultato['OpzioneScelta'];
    }

    function ottieniRispostaCorrettaRC($numeroProgressivo, $titoloTest){
        $sql = "SELECT CampoTesto FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest' AND RispostaCorretta = 1";
        $risultato = $_SESSION['conn']->query($sql);
        $_SESSION['conn']->next_result();
        $risultato = $risultato->fetch_assoc();
        return $risultato['CampoTesto'];
    }

    function ottieniRispostaDataCodice($idCompletamento, $numeroProgressivo, $titoloTest){
        $sql = "SELECT Testo FROM RISPOSTAQUESITOCODICE WHERE NumeroProgressivoCompletamento = $idCompletamento AND NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $risultato = $_SESSION['conn']->query($sql);
        $_SESSION['conn']->next_result();
        $risultato = $risultato->fetch_assoc();
        return $risultato['Testo'];
    }

    function ottieniRispostaCorrettaCodice($numeroProgressivo, $titoloTest){
        $sql = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $risultato = $_SESSION['conn']->query($sql);
        $_SESSION['conn']->next_result();
        $risultato = $risultato->fetch_assoc();
        return $risultato['TestoSoluzione'];
    }

}
?>